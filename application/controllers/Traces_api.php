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
