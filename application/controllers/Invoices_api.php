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

    // Método para obtener facturas por ID de técnico
    public function invoices_getById() {
        $headers = $this->input->request_headers();
        $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;
    
        // Verifica si el token está presente
        if (!$token) {
            // Si no hay token, devuelve un mensaje de error y establece el código de estado HTTP en 401 (Unauthorized)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(401) // Unauthorized
                 ->set_output(json_encode(['error' => 'Token no proporcionado o inválido']));
            return;
        }
    
        // Carga la biblioteca 'jwthandler' para manejar el token JWT
        $this->load->library('jwthandler');
        // Valida el token JWT
        $validation = $this->jwthandler->validateToken($token);
    
        // Verifica si la validación del token fue exitosa
        if (!$validation['valid']) {
            // Si el token no es válido o ha expirado, devuelve un mensaje de error y establece el código de estado HTTP en 401 (Unauthorized)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(401) // Unauthorized
                 ->set_output(json_encode(['error' => 'Token no válido o expirado']));
            return;
        }
    
        $tecnico_id = $this->input->get('id');
        // Obtiene las facturas del modelo 'Invoice_model' utilizando el ID del técnico
        $invoices = $this->Invoice_model->getInvoicesById($tecnico_id);
        // Verifica si se encontraron facturas
        if (!empty($invoices)) {
            // Si se encontraron facturas, devuelve las facturas en formato JSON y establece el código de estado HTTP en 200 (OK)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode($invoices));
        } else {
            // Si no se encontraron facturas, devuelve un mensaje de error y establece el código de estado HTTP en 404 (Not Found)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'No se encontraron facturas para el técnico especificado']));
        }
    }
    
}
