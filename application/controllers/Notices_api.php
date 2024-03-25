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

    public function avisosPorTecnico($tecnico_id) {
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
