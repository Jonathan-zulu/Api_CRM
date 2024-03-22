<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technicians_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargar el modelo y librerias
        $this->load->model('Technic_model');
        $this->load->library('form_validation');
        // Permitir acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Establecer el tipo de contenido a JSON
        header('Content-Type: application/json');
    }

    public function tecnicos_get() {
        // Obtener el código postal de la consulta
        $zipCode = $this->input->get('cp');

        if (!$zipCode) {
            // Enviar respuesta si no se proporciona el código postal
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(400) // HTTP Status Code: Bad Request
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se proporcionó el código postal']));
            return;
        }

        // Consultar el modelo para obtener datos
        $tecnicos = $this->Technic_model->getTechnicalsByZc($zipCode);

        if (!empty($tecnicos)) {
            // Si se encontraron técnicos, enviar los datos
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200) // HTTP Status Code: OK
                 ->set_output(json_encode($tecnicos));
        } else {
            // Si no se encontraron técnicos, enviar un mensaje de error
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404) // HTTP Status Code: Not Found
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se encontraron técnicos para el Id proporcionado']));
        }
    }

    public function tecnicos_getById() {
        // Obtener el Id de la consulta
        $technician_id = $this->input->get('id');

        if (!$technician_id) {
            // Enviar respuesta si no se proporciona el Id
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(400) // HTTP Status Code: Bad Request
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se proporcionó el id']));
            return;
        }

        // Consultar el modelo para obtener datos
        $tecnicos = $this->Technic_model->getTechnicalById($technician_id);

        if (!empty($tecnicos)) {
            // Si se encontraron técnicos, enviar los datos
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200) // HTTP Status Code: OK
                 ->set_output(json_encode($tecnicos));
        } else {
            // Si no se encontraron técnicos, enviar un mensaje de error
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404) // HTTP Status Code: Not Found
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se encontraron técnicos para el id proporcionado']));
        }
    }

    public function tecnicos_getInvoicesById() {
        
    }

    public function register() {
        if($this->input->server('REQUEST_METHOD') !== 'POST') {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(405)
                 ->set_output(json_encode(['error' => 'Method not allowed']));
            return;
        }

        $postData = json_decode($this->input->raw_input_stream, true);

        $this->form_validation->set_data($postData);
        $this->form_validation->set_rules('tecnico_nombre', 'Name', 'required');
        $this->form_validation->set_rules('tecnico_email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('tecnico_password', 'Password', 'required');
        $this->form_validation->set_rules('tecnico_telefono', 'Phone', 'required');
        $this->form_validation->set_rules('tecnico_CIF', 'CIF', 'required');
        $this->form_validation->set_rules('tecnico_provincia_id', 'Province ID', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(400)
                 ->set_output(json_encode(['error' => $this->form_validation->error_array()]));
                 return;
        }
        
        $insertId = $this->Technic_model->insert_technician($postData);

        if($insertId) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(201)
                 ->set_output(json_encode(['message' => 'Technician registered successfully', 'tecnico_id' => $insertId]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => "Could not register the technician"]));
        }
    }
}
