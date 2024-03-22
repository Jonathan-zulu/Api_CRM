<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provinces_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Province_model');
    }

    public function index() {
        $provinces = $this->Province_model->get_provincias();

        if (!empty($provinces)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(200) // HTTP Status Code: OK
                 ->set_output(json_encode($provinces));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_status_header(404) // HTTP Status Code: Not Found
                 ->set_output(json_encode(['message' => 'No se encontraron provincias']));
        }
    }
}
