<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notices_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Notice_model'); // Carga el modelo 'Notice_model' al inicializar el controlador

        // Configura los encabezados CORS para permitir el acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Establece el tipo de contenido a JSON
        header('Content-Type: application/json');
    }

    // Método para obtener avisos por ID de técnico
    public function notices_getById() {
        $headers = $this->input->request_headers(); // Obtiene los encabezados de la solicitud HTTP
        $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null; // Obtiene el token de autorización de los encabezados
        
        // Verifica si el token está presente
        if (!$token) {
            // Si no hay token, devuelve un mensaje de error y establece el código de estado HTTP en 401 (Unauthorized)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(401) // Unauthorized
                 ->set_output(json_encode(['error' => 'Token no proporcionado o inválido']));
            return;
        }
    
        // Carga la biblioteca 'JwtHandler' para manejar el token JWT
        $this->load->library('JwtHandler');
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
    
        // El token es válido, continúa con la lógica de la función
        // Obtiene el ID del técnico de los parámetros de la solicitud
        $tecnico_id = $this->input->get('id');
    
        // Obtiene los avisos del modelo 'Notice_model' utilizando el ID del técnico
        $notices = $this->Notice_model->getNoticesById($tecnico_id);
        
        // Verifica si se encontraron avisos
        if (!empty($notices)) {
            // Si se encontraron avisos, devuelve los avisos en formato JSON y establece el código de estado HTTP en 200 (OK)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode($notices));
        } else {
            // Si no se encontraron avisos, devuelve un mensaje de error y establece el código de estado HTTP en 404 (Not Found)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'No se encontraron avisos para el técnico especificado']));
        }
    }
    
}
