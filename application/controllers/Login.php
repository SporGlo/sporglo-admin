<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    private $secret_key;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');

        $this->secret_key = SECRET_KEY; // Define secret key for token generation
        $this->load->model('Auth_model'); // Ensure Auth_model is loaded
    }

    function index($type = null)
    {
        $data['usertype'] = $type;
        $data['script'] = ['assets/js/pages/auth/login.js'];
        $this->load->view('pages/login', $data);
    }

    function validate()
    {
        if (strtolower($this->input->method()) !== 'post') {
            $this->output
                ->set_status_header(405)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'type' => 'method',
                    'message' => 'Method Not Allowed. Please use POST.'
                ]));
            return;
        }

        // Validate User Data
        $this->form_validation->set_rules('email', 'Email', 'required|trim|htmlspecialchars');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|htmlspecialchars');

        if ($this->form_validation->run() == FALSE) {
            $errors = array(
                'email' => form_error('email'),
                'password' => form_error('password')
            );
            $errors = array_filter($errors);
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'type' => 'validation',
                    'errors' => $errors
                ]));
            return;
        }

        $input = $this->input->post();
        $email = $this->security->xss_clean($input['email']);
        $password = $this->security->xss_clean($input['password']);

        $user = $this->Auth_model->verify_user($email, $password);

        if ($user) {


            if (isset($user['is_2fa_enabled']) && $user['is_2fa_enabled'] == '1') {
                $response = [
                    'status' => true,
                    'message' => 'User Authenticated Successfully',
                    'two_step_enabled' => true,
                    'user' => $user['id']
                ];
            } else {
                $payload = json_encode([
                    'userid' => $user['id'],
                    'admin_type' => $user['admin_type'],
                    'email' => $user['email'],
                    'username' => "{$user['first_name']} {$user['last_name']}",
                    'timestamp' => time(),
                ]);

                $token = hash_hmac('sha256', $payload, $this->secret_key);
                $auth_token = base64_encode($payload) . '.' . $token;
                $expiry = time() + (3 * 3600);

                $cookie_domain = $_SERVER['HTTP_HOST'];
                if ($cookie_domain == 'test.sporglo.com') {
                    $cookie_domain = 'test.sporglo.com';
                } else if ($cookie_domain == 'sporglo.com') {
                    $cookie_domain = '.sporglo.com';
                }

                $cookie = [
                    'name' => 'token_auth',
                    'value' => $auth_token,
                    'expire' => $expiry,
                    'secure' => TRUE,
                    'httponly' => False,
                    'domain' => $cookie_domain
                ];
                $this->input->set_cookie($cookie);

                $this->Auth_model->create_token($user['id'], $auth_token, 'auth', $expiry);

                $response = [
                    'status' => true,
                    'message' => 'User Authenticated Successfully',
                    'two_step_enabled' => false,
                    // 'url' => 'http://localhost/panel/' // redirect to home page 
                    'url' => ADMIN_PANEL_URL,
                ];
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'Invalid username or password'
            ];
        }

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function logout()
    {
        // Get the token from the Authorization header
        $headers = $this->input->get_request_header('Authorization');

        if (!$headers) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'No authorization token found.'
                ]));
        }

        // Extract the token from the "Bearer <token>" format
        $token = str_replace('Bearer ', '', $headers);


        // Start a transaction
        $this->db->trans_start();

        try {
            // Delete the token from the xx_crm_authtokens table
            $this->db->delete('auth_tokens', ['token' => $token]);

            // Clear the cookies
            // delete_cookie('token_auth'); // Adjust if you use a different cookie name
            $cookie = [
                'name' => 'token_auth',
                'value' => '',
                'expire' => time() - 3600, // Set expiry in the past
                'path' => '/',
                'domain' => 'test.sporglo.com',
                'secure' => false,
                'httponly' => false,
            ];
            set_cookie($cookie);
            // Commit the transaction
            $this->db->trans_complete();

            // Check if transaction was successful
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Failed to delete token from the database.');
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => 'User logged out successfully.'
                ]));
        } catch (Exception $e) {
            // Rollback transaction in case of an error
            $this->db->trans_rollback();

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Error logging out: ' . $e->getMessage()
                ]));
        }
    }
}
