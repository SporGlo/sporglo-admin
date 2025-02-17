<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sports extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['view_path'] = 'pages/sports/list';
        $data['page_title'] = "Sports List - Sporglo Admin Panel";
        $data['scripts'] = ['assets/js/pages/sports/list.js'];
        $this->load->view('layout', $data);
    }

    public function add()
    {
        $data['view_path'] = 'pages/sports/new';
        $data['page_title'] = "New Sport - Sporglo Admin Panel";
        $data['scripts'] = ['assets/js/pages/sports/new.js'];

        $this->load->view('layout', $data);
    }


    public function new()
    {
        // Check if the authentication is valid
        $isAuthorized = $this->isAuthorized();
        if (!$isAuthorized['status']) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Unauthorized access.']))
                ->_display();
            exit;
        }

        try {
            // Check if the request method is POST
            if (strtolower($this->input->method()) !== 'post') {
                $this->sendHTTPResponse(405, [
                    'status' => 405,
                    'error' => 'Method Not Allowed',
                    'message' => 'The requested HTTP method is not allowed for this endpoint.'
                ]);
                return;
            }

            //  Set validation rules 
            $validation_rules = [
                ['label' => 'Sport Type', 'key' => 'type', 'validations' => 'required'],
                ['label' => 'Sport Name', 'key' => 'sport_name', 'validations' => 'required'],
                ['label' => 'Sport Order', 'key' => 'sport_order', 'validations' => 'required'],
                ['label' => 'History', 'key' => 'history', 'validations' => 'required'],
                ['label' => 'Description', 'key' => 'description', 'validations' => 'required'],
                // ['label' => 'Cover Image', 'key' => 'cover_image', 'validations' => 'required'],
                // ['label' => 'Category Image', 'key' => 'category_image', 'validations' => 'required'],

            ];
            foreach ($validation_rules as $rule) {
                $this->form_validation->set_rules($rule['key'], $rule['label'], $rule['validations']);
            }

            if ($this->form_validation->run() == FALSE) {
                $errors = $this->form_validation->error_array();
                $this->sendHTTPResponse(422, [
                    'status' => 422,
                    'error' => 'Unprocessable Entity',
                    'validation_errors' => $errors
                ]);
                return;
            }

            // Retrieve and sanitize POST data
            $data = $this->input->post();
            $data = array_map([$this->security, 'xss_clean'], $data);

            // File upload configuration
            $this->load->library('upload');
            $upload_config = [
                'upload_path' => FCPATH . 'assets/img/',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size' => 50000, // 2MB
                'file_name' => ''
            ];

            // Upload cover_image
            $cover_image_path = '';
            if (!empty($_FILES['cover_image']['name'])) {
                $upload_config['upload_path'] .= 'cover_image/';
                $upload_config['file_name'] = 'cover_' . time();
                $this->upload->initialize($upload_config);

                if (!$this->upload->do_upload('cover_image')) {
                    $this->sendHTTPResponse(422, [
                        'status' => 422,
                        'error' => 'File Upload Error',
                        'message' => $this->upload->display_errors()
                    ]);
                    return;
                } else {
                    $upload_data = $this->upload->data();
                    // $cover_image_path = 'assets/img/cover_image/' . $upload_data['file_name'];
                    $cover_image_path = $upload_data['file_name'];
                }
            }

            // Upload category_image
            $category_image_path = '';
            if (!empty($_FILES['category_image']['name'])) {
                $upload_config['upload_path'] = FCPATH . 'assets/img/category_image/';
                $upload_config['file_name'] = 'category_' . time();
                $this->upload->initialize($upload_config);

                if (!$this->upload->do_upload('category_image')) {
                    $this->sendHTTPResponse(422, [
                        'status' => 422,
                        'error' => 'File Upload Error',
                        'message' => $this->upload->display_errors()
                    ]);
                    return;
                } else {
                    $upload_data = $this->upload->data();
                    // $category_image_path = 'assets/img/category_image/' . $upload_data['file_name'];
                    $category_image_path = $upload_data['file_name'];
                }
            }

            // Add uploaded file paths to data
            $data['cover_image'] = $cover_image_path;
            $data['category_image'] = $category_image_path;

            // Check if product already exists
            $sports = $this->Sports_model->get_sport_by_name($data['sport_name']);
            if (!empty($sports)) {
                $this->sendHTTPResponse(409, [
                    'status' => 409,
                    'error' => 'Conflict',
                    'message' => 'Sport with similar name already exists.'
                ]);
                return;
            }

            // Save product to the database
            $createdSports = $this->Sports_model->add_sport($data);
            if ($createdSports) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' =>  $createdSports['sport_name'] . " created successfully.",
                    'data' => $createdSports
                ]);
            } else {
                throw new Exception('Failed to create new sport.');
            }
        } catch (Exception $e) {
            $this->sendHTTPResponse(500, [
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
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
                ['label' => 'Sport Type', 'key' => 'type', 'validations' => 'required'],
                ['label' => 'Sport Name', 'key' => 'sport_name', 'validations' => 'required'],
                ['label' => 'Sport Order', 'key' => 'sport_order', 'validations' => 'required'],
                ['label' => 'History', 'key' => 'history', 'validations' => 'required'],
                ['label' => 'Description', 'key' => 'description', 'validations' => 'required'],
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
            $sport = $this->Sports_model->get_sport_by_id($id);
            if (empty($sport)) {
                $this->sendHTTPResponse(404, [
                    'status' => 'error',
                    'code' => 404,
                    'error' => 'Sports details not found with provided ID',
                    'message' => 'Sports details not found with provided ID'
                ]);
                return;
            }

            // Check if a new image is uploaded

            // Load the upload library
            $this->load->library('upload');

            // Handle cover_image upload
            if (!empty($_FILES['cover_image']['name'])) {
                $config['upload_path'] = FCPATH . 'assets/img/cover_image'; // Absolute server path
                $config['allowed_types'] = 'jpg|jpeg|png|gif'; // Allowed file types
                $config['max_size'] = 2048; // Max size in KB (2MB)
                $config['file_name'] = 'cover_' . time(); // Set custom file name

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('cover_image')) {
                    $this->sendHTTPResponse(422, [
                        'status' => 422,
                        'error' => 'File Upload Error',
                        'message' => $this->upload->display_errors('', '')
                    ]);
                    return;
                }

                // File upload succeeded
                $upload_data = $this->upload->data();
                $data['cover_image'] = $upload_data['file_name'];

                // Delete the old image
                if (!empty($sport['cover_image']) && file_exists(FCPATH . $sport['cover_image'])) {
                    unlink(FCPATH . $sport['cover_image']);
                }
            }

            // Handle category_image upload
            if (!empty($_FILES['category_image']['name'])) {
                $config['upload_path'] = FCPATH . 'assets/img/category_image'; // Absolute server path
                $config['allowed_types'] = 'jpg|jpeg|png|gif'; // Allowed file types
                $config['max_size'] = 2048; // Max size in KB (2MB)
                $config['file_name'] = 'category_' . time(); // Set custom file name

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('category_image')) {
                    $this->sendHTTPResponse(422, [
                        'status' => 422,
                        'error' => 'File Upload Error',
                        'message' => $this->upload->display_errors('', '')
                    ]);
                    return;
                }

                // File upload succeeded
                $upload_data = $this->upload->data();
                $data['category_image'] = $upload_data['file_name'];

                // Delete the old image
                if (!empty($sport['category_image']) && file_exists(FCPATH . $sport['category_image'])) {
                    unlink(FCPATH . $sport['category_image']);
                }
            }


            // Save updated data to the database
            $updatedSport = $this->Sports_model->update_sport($id, $data);
            if ($updatedSport) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' =>  $updatedSport['sport_name'] . " Updated Successfully.",
                    'type' => 'update',
                    'data' => $updatedSport,
                ]);
            } else {
                throw new Exception('Failed to update Sport details.');
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

        $total_sports = $this->Sports_model->get_sports('total', $limit, $currentPage, $filters, $search);
        $sports = $this->Sports_model->get_sports('list', $limit, $currentPage, $filters, $search);

        $response = [
            'pagination' => [
                'total_records' => $total_sports,
                'total_pages' => generatePages($total_sports, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'sports' => $sports,
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

        $sport = $this->Sports_model->get_sport_by_id($sportID);

        // Check if product data exists
        if (empty($sport)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Sport details not found.'
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
                'data' => $sport
            ]));
    }


    function delete($id)
    {        // Check if the authentication is valid
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
        $sport = $this->Sports_model->get_sport_by_id($id);
        $result = $this->Sports_model->delete_sport_by_id($id);
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => " $sport[sport_name] deleted successfully."]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete sport.']));
        }
    }
}
