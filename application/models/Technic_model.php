<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technic_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getTechnicalsByZc($zipCode) {
        $this->db->select('tecnico_nombre, tecnico_telefono');
        $this->db->from('mahico_tecnicos');
        $this->db->where('tecnico_codigo_postal', $zipCode);
        $query = $this->db->get();
        
        return $query->result_array();
    }
}
