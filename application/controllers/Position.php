<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Position extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['view_path'] = 'pages/position/list';
        $data['page_title'] = "Position List - Sporglo Admin Panel";
        $data['scripts'] = ['assets/js/pages/position/list.js','assets/js/pages/position/new.js'];
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
                ['label' => 'Sport Name', 'key' => 'sport_id', 'validations' => 'required'],
                ['label' => 'Position Name', 'key' => 'position_name', 'validations' => 'required'],
                ['label' => 'Position Type', 'key' => 'position_type', 'validations' => 'required'],
        

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

         

            
            // Check if product already exists
            // $sports = $this->Sports_model->get_product_by_name($data['sport_name']);
            // if (!empty($sports)) {
            //     $this->sendHTTPResponse(409, [
            //         'status' => 409,
            //         'error' => 'Conflict',
            //         'message' => 'Product with similar name already exists.'
            //     ]);
            //     return;
            // }

            // Save product to the database
            $createdPosition = $this->Position_model->add_position($data);
            if ($createdPosition) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' =>  $createdPosition['position_name'] . " created successfully.",
                    'data' => $createdPosition
                ]);
            } else {
                throw new Exception('Failed to create new position.');
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
                ['label' => 'Sport Name', 'key' => 'sport_id', 'validations' => 'required'],
                ['label' => 'Position Name', 'key' => 'position_name', 'validations' => 'required'],
                ['label' => 'Position Type', 'key' => 'position_type', 'validations' => 'required'],
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
            $sport = $this->Position_model->get_position_by_id($id);
            if (empty($sport)) {
                $this->sendHTTPResponse(404, [
                    'status' => 'error',
                    'code' => 404,
                    'error' => 'Position details not found with provided ID',
                    'message' => 'Position details not found with provided ID'
                ]);
                return;
            }

            // Save updated data to the database
            $updatedPosition = $this->Position_model->update_position($id, $data);
            if ($updatedPosition) {
                $this->sendHTTPResponse(201, [
                    'status' => 201,
                    'message' =>  $updatedPosition['position_name'] . " Updated Successfully.",
                    'type' => 'update',
                    'data' => $updatedPosition,
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

        $total_position = $this->Position_model->get_position('total', $limit, $currentPage, $filters, $search);
        $position = $this->Position_model->get_position('list', $limit, $currentPage, $filters, $search);

        $response = [
            'pagination' => [
                'total_records' => $total_position,
                'total_pages' => generatePages($total_position, $limit),
                'current_page' => $currentPage,
                'limit' => $limit
            ],
            'position' => $position,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }


    public function details($positionID)
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
        if (!isset($positionID)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid Position ID, Please provide Sport id to fetch details.'
                ]));
        }

        $position = $this->Position_model->get_position_by_id($positionID);

        // Check if product data exists
        if (empty($position)) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'position details not found.'
                ]));
        }

        // Successful response with product data
        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Position details retrieved successfully',
                'data' => $position
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
                ->set_output(json_encode(['message' => 'Invalid Position ID.']));
            return;
        }

        // Attempt to delete the Request
        $position = $this->Position_model->get_position_by_id($id);
        $result = $this->Position_model->delete_position_by_id($id);
        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200) // 200 OK status code
                ->set_output(json_encode(['status' => true, 'message' => " $position[position_name] deleted successfully."]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500) // 500 Internal Server Error status code
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete position.']));
        }
    }
}
