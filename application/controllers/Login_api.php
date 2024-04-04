<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');

        // Configura los encabezados CORS para permitir el acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    }

    // Método para manejar las solicitudes de inicio de sesión
    public function index() {
        // Verifica si la solicitud es una solicitud OPTIONS y responde con los encabezados CORS apropiados
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            exit;
        }
    
        // Configura los encabezados CORS para permitir el acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    
        // Obtiene los datos POST enviados como JSON y los decodifica
        $postData = json_decode($this->input->raw_input_stream, true);
    
        // Verifica si se proporcionaron el nombre de usuario y la contraseña en los datos POST
        if (!isset($postData['tecnico_nombre']) || !isset($postData['tecnico_password'])) {
            // Si falta el nombre de usuario o la contraseña, devuelve un mensaje de error y establece el código de estado HTTP en 400 (Bad Request)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(400)
                 ->set_output(json_encode(['error' => 'Username and password are required']));
            return;
        }
    
        // Verifica las credenciales de inicio de sesión utilizando el modelo 'User_model'
        $user = $this->User_model->verify_user($postData['tecnico_nombre'], $postData['tecnico_password']);
    
        if ($user) {
            // Si las credenciales son válidas, genera un token JWT utilizando la biblioteca 'JwtHandler'
            $this->load->library('JwtHandler');
            $token = $this->jwthandler->generateToken(['tecnico_id' => $user['tecnico_id']]);
    
            // Devuelve un mensaje de éxito junto con el token JWT y el ID del usuario
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode([
                     'message' => 'Login successful', 
                     'accessToken' => $token, // Aquí devolvemos el token JWT
                     'user_id' => $user['tecnico_id']
                 ]));
        } else {
            // Si las credenciales son inválidas, devuelve un mensaje de error y establece el código de estado HTTP en 401 (Unauthorized)
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['error' => 'Invalid username or password']));
        }
    }    
}
