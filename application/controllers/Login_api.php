<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            exit;
        }
    
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    
        $postData = json_decode($this->input->raw_input_stream, true);
    
        if (!isset($postData['tecnico_nombre']) || !isset($postData['tecnico_password'])) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(400)
                 ->set_output(json_encode(['error' => 'Username and password are required']));
            return;
        }
    
        $user = $this->User_model->verify_user($postData['tecnico_nombre'], $postData['tecnico_password']);
    
        if ($user) {
            $this->load->library('JwtHandler');
            $token = $this->jwthandler->generateToken(['tecnico_id' => $user['tecnico_id']]);
    
            // Incluyendo el token en la respuesta
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode([
                     'message' => 'Login successful', 
                     'accessToken' => $token, // Aquí devolvemos el token
                     'user_id' => $user['tecnico_id']
                 ]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['error' => 'Invalid username or password']));
        }
    }    
}
