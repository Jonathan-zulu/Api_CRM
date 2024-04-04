<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technicians_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargar el modelo y librerías necesarias
        $this->load->model('Technic_model'); // Carga el modelo 'Technic_model' al inicializar el controlador
        $this->load->library('form_validation'); // Carga la biblioteca 'form_validation' para validar formularios

        // Configurar encabezados CORS para permitir acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Establecer el tipo de contenido a JSON
        header('Content-Type: application/json');
    }

    // Método para obtener los técnicos por código postal
    public function tecnicos_get() {
        // Obtener el código postal de la consulta
        $zipCode = $this->input->get('cp');

        // Verificar si se proporcionó el código postal
        if (!$zipCode) {
            // Enviar respuesta de error si no se proporciona el código postal
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(400) // Código de estado HTTP: Solicitud incorrecta
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se proporcionó el código postal']));
            return;
        }

        // Consultar el modelo para obtener los técnicos por código postal
        $tecnicos = $this->Technic_model->getTechnicalsByZc($zipCode);

        // Verificar si se encontraron técnicos
        if (!empty($tecnicos)) {
            // Si se encontraron técnicos, enviar los datos en formato JSON con el código de estado HTTP 200 (OK)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode($tecnicos));
        } else {
            // Si no se encontraron técnicos, enviar un mensaje de error con el código de estado HTTP 404 (Not Found)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se encontraron técnicos para el código postal proporcionado']));
        }
    }

    // Método para obtener un técnico por su ID
    public function tecnicos_getById() {
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
        $technician_id = $this->input->get('id');
        
        // Verificar si se proporcionó el ID del técnico
        if (!$technician_id) {
            // Si no se proporciona el ID del técnico, enviar un mensaje de error con el código de estado HTTP 400 (Bad Request)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(400)
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se proporcionó el ID del técnico']));
            return;
        }
    
        // Consultar el modelo para obtener los datos del técnico por su ID
        $tecnicos = $this->Technic_model->getTechnicalById($technician_id);
        
        // Verificar si se encontraron datos del técnico
        if (!empty($tecnicos)) {
            // Si se encontraron datos del técnico, enviar los datos en formato JSON con el código de estado HTTP 200 (OK)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode($tecnicos));
        } else {
            // Si no se encontraron datos del técnico, enviar un mensaje de error con el código de estado HTTP 404 (Not Found)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se encontraron técnicos para el ID proporcionado']));
        }
    }

    // Método para obtener los oficios visibles
    public function getOffices() {
        // Obtener los oficios visibles consultando el modelo
        $tecnicos = $this->Technic_model->getVisibleOffices();

        // Verificar si se encontraron oficios visibles
        if (!empty($tecnicos)) {
            // Si se encontraron oficios visibles, enviar los datos en formato JSON con el código de estado HTTP 200 (OK)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200)
                 ->set_output(json_encode($tecnicos));
        } else {
            // Si no se encontraron oficios visibles, enviar un mensaje de error con el código de estado HTTP 404 (Not Found)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404)
                 ->set_output(json_encode(['status' => FALSE, 'message' => 'No se encontraron oficios visibles']));
        }
    }

    // Método para obtener técnicos disponibles
    public function getTechniciansAvailable() {
        // Verificar si la solicitud es de tipo OPTIONS
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            exit;
        }
    
        // Configurar encabezados CORS
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        // Verificar si el método de la solicitud es POST
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            // Si el método de la solicitud no es POST, enviar un mensaje de error con el código de estado HTTP 405 (Method Not Allowed)
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(['error' => 'Method not allowed']));
            return;
        }
    
        // Decodificar los datos de entrada JSON
        $postData = json_decode($this->input->raw_input_stream, true);
    
        // Verificar si se proporcionaron el código postal y el ID del oficio
        if (!isset($postData['codigo_postal']) || !isset($postData['oficio_id'])) {
            // Si no se proporcionan los datos necesarios, enviar un mensaje de error con el código de estado HTTP 400 (Bad Request)
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Faltan datos necesarios para la consulta.']));
            return;
        }
    
        // Consultar el modelo para obtener técnicos disponibles
        $availableTechnicians = $this->Technic_model->getAvailableTechnicians($postData['codigo_postal'], $postData['oficio_id']);
    
        // Verificar si se encontraron técnicos disponibles
        if ($availableTechnicians) {
            // Si se encontraron técnicos disponibles, enviar los datos en formato JSON con el código de estado HTTP 200 (OK)
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($availableTechnicians));
        } else {
            // Si no se encontraron técnicos disponibles, enviar un mensaje de error con el código de estado HTTP 404 (Not Found)
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode(['message' => 'No se encontraron técnicos disponibles.']));
        }
    }

    // Método para registrar un técnico
    public function register() {
        // Verificar si la solicitud es de tipo OPTIONS
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            exit;
        }

        // Configurar encabezados CORS
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        // Verificar si el método de la solicitud es POST
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            // Si el método de la solicitud no es POST, enviar un mensaje de error con el código de estado HTTP 405 (Method Not Allowed)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(405)
                 ->set_output(json_encode(['error' => 'Method not allowed']));
            return;
        }

        // Decodificar los datos de entrada JSON
        $postData = json_decode($this->input->raw_input_stream, true);

        // Configurar reglas de validación para los datos del técnico
        $this->form_validation->set_data($postData);
        $this->form_validation->set_rules('tecnico_nombre', 'Name', 'required');
        $this->form_validation->set_rules('tecnico_email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('tecnico_password', 'Password', 'required');
        $this->form_validation->set_rules('tecnico_telefono', 'Phone', 'required');
        $this->form_validation->set_rules('tecnico_CIF', 'CIF', 'required');
        $this->form_validation->set_rules('tecnico_provincia_id', 'Province ID', 'required|integer');

        // Verificar si la validación de formularios es exitosa
        if ($this->form_validation->run() === FALSE) {
            // Si la validación de formularios falla, enviar un mensaje de error con el código de estado HTTP 400 (Bad Request)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(400)
                 ->set_output(json_encode(['error' => $this->form_validation->error_array()]));
            return;
        }

        // Agregar la fecha de alta del técnico
        $postData['tecnico_fecha_alta'] = date('Y-m-d H:i:s');

        // Insertar el técnico utilizando el modelo
        $insertId = $this->Technic_model->insert_technician($postData);

        // Verificar si el técnico se registró con éxito
        if ($insertId) {
            // Si el técnico se registró con éxito, enviar un mensaje de éxito con el código de estado HTTP 201 (Created)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(201)
                 ->set_output(json_encode(['message' => 'Técnico registrado exitosamente', 'tecnico_id' => $insertId]));
        } else {
            // Si el técnico no se registró con éxito, enviar un mensaje de error con el código de estado HTTP 500 (Internal Server Error)
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'No se pudo registrar el técnico']));
        }
    }
}
