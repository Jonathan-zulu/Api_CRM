<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notices_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Notice_model');

        // Permitir acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Establecer el tipo de contenido a JSON
        header('Content-Type: application/json');
    }

    public function notices_getById() {
        $headers = $this->input->request_headers();
        $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;
    
        if (!$token) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(401) // Unauthorized
                 ->set_output(json_encode(['error' => 'Token no proporcionado o inválido']));
            return;
        }
    
        $this->load->library('jwthandler');
        $validation = $this->jwthandler->validateToken($token);
    
        if (!$validation['valid']) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(401) // Unauthorized
                 ->set_output(json_encode(['error' => 'Token no válido o expirado']));
            return;
        }
    
        // El token es válido, continúa con la lógica de la función
        $tecnico_id = $this->input->get('id');
    
        $notices = $this->Notice_model->getNoticesById($tecnico_id);
        if (!empty($notices)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode($notices));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'No se encontraron avisos para el técnico especificado']));
        }
    }
    
}
