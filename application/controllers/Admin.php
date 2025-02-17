<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['view_path'] = 'pages/admin/list';
        $data['page_title'] = "Admin List - Sporglo Admin Panel";
        $data['scripts'] = ['assets/js/pages/admin/list.js'];
        $this->load->view('layout', $data);
    }

    public function add()
    {
        $loggedInUser = $this->isUserAuthenticated();
        $name = ($loggedInUser->first_name ?? '') . ' ' . ($loggedInUser->last_name ?? '');
        $data['full_name'] = $name;
        $data['view_path'] = 'pages/admin/new';
        $data['page_title'] = "New Admin - Sporglo Admin Panel";
        $data['scripts'] = ['assets/js/pages/admin/new.js'];

        $this->load->view('layout', $data);
    }


    function new()
    {
        // $isAuthorized = $this->isAuthorized();
        $isAuthorized = ['status' => true, 'userid' => 1, 'role' => 'admin'];
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Unauthorized access. You do not have permission to perform this action.']))
                ->_display();
            exit;
        };

        try {
            // Check if the request method is POST
            if (strtolower($this->input->method()) !== 'post') {
                $this->sendHTTPResponse(405, [
                    'status' => 405,
                    'error' => 'Method Not Allowed',
                    'message' => 'The requested HTTP method is not allowed for this endpoint. Please check the API documentation for allowed methods.'
                ]);
                return;
            }

            // Set validation rules
            $validation_rules = [
                ['label' => 'First Name', 'key' => 'first_name', 'validations' => 'required'],
                ['label' => 'Last Name', 'key' => 'last_name', 'validations' => 'required'],
                ['label' => 'Password', 'key' => 'password', 'validations' => 'required'],
                ['label' => 'Email Address', 'key' => 'email', 'validations' => 'required|valid_email'],
                ['label' => 'Admin Type', 'key' => 'admin_type', 'validations' => 'required'],
                ['label' => 'City', 'key' => 'city', 'validations' => 'required'],
                ['label' => 'Country', 'key' => 'country', 'validations' => 'required'],

            ];
            foreach ($validation_rules as $rule)
                $this->form_validation->set_rules($rule['key'], $rule['label'], $rule['validations']);

            // Run validation
            if ($this->form_validation->run() == FALSE) {
                // Validation failed, prepare response with errors
                $errors = $this->form_validation->error_array();

                $this->sendHTTPResponse(422, [
                    'status' => 422,
                    'error' => 'Unprocessable Entity',
                    'message' => 'The submitted data failed validation.',
                    'validation_errors' => $errors
                ]);
                return;
            }

            // Retrieve POST data and sanitize it
            $data = $this->input->post();
            $data = array_map([$this->security, 'xss_clean'], $data);

            if ($data['password'] !== $data['confirm_password']) {
                $this->sendHTTPResponse(400, [
                    'status' => 400,
                    'error' => 'Password and Confirm Password not matched',
                    'message' => 'Password and Confirm Password not matched',
                ]);
                return;
            }
            // Check data is already created with given filter
            $user = $this->Admin_model->get_admin_by_email($data['email']);
            if (!empty($user)) {
                $this->sendHTTPResponse(409, [
                    'status' => 'error',
                    'code' => 409,
                    'error' => 'User with this email already exists.',
                    'message' => 'User with this email already exists.'
                ]);
                return;
            }


            // Save Data to the product table
            $createdAdmin = $this->Admin_model->add_admin($data, $isAuthorized['userid']);
            // $createdUser = $this->User_model->add_user($data, $isAuthorized['userid']);

            if ($createdAdmin) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' => 'New Admin Created Successfully',
                    'type' => 'insert',
                    'data' => $createdAdmin,
                ]);
            } else {
                throw new Exception('Failed to create new Admin account.');
            }
        } catch (Exception $e) {
            // Catch any unexpected errors and respond with a standardized error
            $this->sendHTTPResponse(500, [
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => 'An unexpected error occurred on the server.',
                'details' => $e->getMessage()
            ]);
        }
    }


    function update($id)
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Unauthorized access. You do not have permission to perform this action.']))
                ->_display();
            exit;
        };

        try {
            // Check if the request method is POST
            if (strtolower($this->input->method()) !== 'post') {
                $this->sendHTTPResponse(405, [
                    'status' => 405,
                    'error' => 'Method Not Allowed',
                    'message' => 'The requested HTTP method is not allowed for this endpoint. Please check the API documentation for allowed methods.'
                ]);
                return;
            }

            // Set validation rules
            $validation_rules = [
                ['label' => 'First Name', 'key' => 'first_name', 'validations' => 'required'],
                ['label' => 'Last Name', 'key' => 'last_name', 'validations' => 'required'],
                ['label' => 'Email Address', 'key' => 'email', 'validations' => 'required|valid_email'],
                ['label' => 'Type', 'key' => 'admin_type', 'validations' => 'required'],
                ['label' => 'City', 'key' => 'city', 'validations' => 'required'],
                ['label' => 'Country', 'key' => 'country', 'validations' => 'required'],

            ];
            foreach ($validation_rules as $rule) {
                $this->form_validation->set_rules($rule['key'], $rule['label'], $rule['validations']);
            }

            // Run validation
            if ($this->form_validation->run() == FALSE) {
                $errors = $this->form_validation->error_array();
                $this->sendHTTPResponse(422, [
                    'status' => 422,
                    'error' => 'Unprocessable Entity',
                    'message' => 'The submitted data failed validation.',
                    'validation_errors' => $errors
                ]);
                return;
            }

            // Retrieve POST data and sanitize it
            $data = $this->input->post();
            $data = array_map([$this->security, 'xss_clean'], $data);

            // Check if the product exists
            $sport = $this->Admin_model->get_admin_by_id($id);
            if (empty($sport)) {
                $this->sendHTTPResponse(404, [
                    'status' => 'error',
                    'code' => 404,
                    'error' => 'Admin details not found with provided ID',
                    'message' => 'Admin details not found with provided ID'
                ]);
                return;
            }


            // Save updated data to the database
            $updatedAdmin = $this->Admin_model->update_admin_details($id, $data);
            if ($updatedAdmin) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' =>  $updatedAdmin['admin_id'] . " Updated Successfully.",
                    'type' => 'update',
                    'data' => $updatedAdmin,
                ]);
            } else {
                throw new Exception('Failed to update Admin details.');
            }
        } catch (Exception $e) {
            $this->sendHTTPResponse(500, [
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => 'An unexpected error occurred on the server.',
                'details' => $e->getMessage()
            ]);
        }
    }

    function list()
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Unauthorized access. You do not have permission to perform this action.']))
                ->_display();
            exit;
        };

        // Get the raw input data from the request
        $input = $this->input->raw_input_stream;

        // Decode the JSON data
        $data = json_decode($input, true); // Decode as associative array

        // Check if data is received
        if (!$data) {
            // Handle the error if no data is received
            $this->output
                ->set_status_header(400) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Invalid JSON input']))
                ->_display();
            exit;
        }

        // Access the parameters
        $limit = isset($data['limit']) ? $data['limit'] : null;
        $currentPage = isset($data['currentPage']) ? $data['currentPage'] : null;
        $filters = isset($data['filters']) ? $data['filters'] : [];
        $search = isset($data['search']) ? $data['search'] : [];

        $total_admin = $this->Admin_model->get_admin('total', $limit, $currentPage, $filters, $search);
        $admins = $this->Admin_model->get_admin('list', $limit, $currentPage, $filters, $search);

        $response = [
            'pagination' => [
                'total_records' => $total_admin,
                'total_pages' => generatePages($total_admin, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'admins' => $admins,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }


    public function details($sportID)
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Unauthorized access. You do not have permission to perform this action.']))
                ->_display();
            exit;
        };

        // Validate input and check if `productUUID` is provided
        if (!isset($sportID)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid Sport ID, Please provide Sport id to fetch details.'
                ]));
        }

        $admin = $this->Admin_model->get_admin_by_id($sportID);

        // Check if product data exists
        if (empty($admin)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'admin details not found.'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Sport details retrieved successfully',
                'data' => $admin
            ]));
    }


    function delete($id)
    {        // Check if the authentication is valid
        // $isAuthorized = $this->isAuthorized();
        $isAuthorized = $this->isAuthorized();
        
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401) // Set HTTP response status to 400 Bad Request
                ->set_content_type('application/json')
                ->set_output(json_encode(['message' => 'Unauthorized access. You do not have permission to perform this action.']))
                ->_display();
            exit;
        };

        // Check if the user is admin or not
        if (isset($isAuthorized['role']) && $isAuthorized['role'] !== 'admin') {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(403) // 403 Forbidden status code
                ->set_output(json_encode(['message' => 'You do not have permission to perform this action.']));
            return;
        }

        // Validate the Request ID
        if (empty($id) || !is_numeric($id)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400) // 400 Bad Request status code
                ->set_output(json_encode(['message' => 'Invalid Sport ID.']));
            return;
        }

        // Attempt to delete the Request
        $admin = $this->Admin_model->get_admin_by_id($id);
        $result = $this->Admin_model->delete_admin_by_id($id);
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => " $admin[admin_id] deleted successfully."]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete sport.']));
        }
    }
}
