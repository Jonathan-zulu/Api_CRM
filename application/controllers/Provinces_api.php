<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provinces_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Province_model'); // Carga el modelo 'Province_model' al inicializar el controlador
        $this->load->library('form_validation'); // Carga la biblioteca 'form_validation' para validación de formularios

        // Configura los encabezados CORS para permitir el acceso desde cualquier origen
        header('Access-Control-Allow-Origin: *');
        // Establece el tipo de contenido a JSON
        header('Content-Type: application/json');
    }

    // Método para obtener todas las provincias
    public function index() {
        // Obtiene todas las provincias utilizando el método 'get_provinces' del modelo 'Province_model'
        $provinces = $this->Province_model->get_provinces();

        // Verifica si se encontraron provincias
        if (!empty($provinces)) {
            // Si se encontraron provincias, devuelve las provincias en formato JSON y establece el código de estado HTTP en 200 (OK)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200) // HTTP Status Code: OK
                 ->set_output(json_encode($provinces));
        } else {
            // Si no se encontraron provincias, devuelve un mensaje de error y establece el código de estado HTTP en 404 (Not Found)
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404) // HTTP Status Code: Not Found
                 ->set_output(json_encode(['message' => 'No se encontraron provincias']));
        }
    }
}
