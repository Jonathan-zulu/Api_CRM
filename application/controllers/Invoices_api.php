<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Invoice_model');

        // Permitir acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Establecer el tipo de contenido a JSON
        header('Content-Type: application/json');
    }

    public function invoices_getById() {
        $tecnico_id = $this->input->get('id');

        $invoices = $this->Notice_model->getInvoicesById($tecnico_id);
        if (!empty($invoices)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode($invoices));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'No se encontraron avisos para el técnico especificado']));
        }
    }
}
