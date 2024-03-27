<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traces_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Trace_model');

        // Permitir acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Establecer el tipo de contenido a JSON
        header('Content-Type: application/json');
    }

    public function traces_getById() {
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
    
        
        $tecnico_id = $this->input->get('id');
    
        $traces = $this->Trace_model->getTracesById($tecnico_id);
        if (!empty($traces)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode($traces));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'No se encontraron trazas para el técnico especificado']));
        }
    }
    
}
