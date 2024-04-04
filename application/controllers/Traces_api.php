<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traces_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargar el modelo necesario
        $this->load->model('Trace_model');

        // Configurar encabezados CORS para permitir acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Establecer el tipo de contenido a JSON
        header('Content-Type: application/json');
    }

    // Método para obtener trazas por ID de técnico
    public function traces_getById() {
        // Obtener el token de autorización de los encabezados de la solicitud
        $headers = $this->input->request_headers();
        $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;
    
        // Verificar si se proporcionó un token de autorización
        if (!$token) {
            // Si no se proporciona un token, enviar un mensaje de error con el código de estado HTTP 401 (Unauthorized)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(401)
                 ->set_output(json_encode(['error' => 'Token no proporcionado o inválido']));
            return;
        }
    
        // Validar el token utilizando la biblioteca JwtHandler
        $this->load->library('JwtHandler');
        $validation = $this->jwthandler->validateToken($token);
    
        // Verificar si el token es válido
        if (!$validation['valid']) {
            // Si el token no es válido, enviar un mensaje de error con el código de estado HTTP 401 (Unauthorized)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(401)
                 ->set_output(json_encode(['error' => 'Token no válido o expirado']));
            return;
        }
    
        // Obtener el ID del técnico de la consulta
        $tecnico_id = $this->input->get('id');
    
        // Consultar el modelo para obtener las trazas por ID de técnico
        $traces = $this->Trace_model->getTracesById($tecnico_id);
        
        // Verificar si se encontraron trazas
        if (!empty($traces)) {
            // Si se encontraron trazas, enviar los datos en formato JSON con el código de estado HTTP 200 (OK)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode($traces));
        } else {
            // Si no se encontraron trazas, enviar un mensaje de error con el código de estado HTTP 404 (Not Found)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'No se encontraron trazas para el técnico especificado']));
        }
    }
    
}
