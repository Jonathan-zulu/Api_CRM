<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technicians_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargar el modelo
        $this->load->model('Technic_model');
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
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se encontraron técnicos para el código postal proporcionado']));
        }
    }
}
