<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Province_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }


    public function get_provinces() {
        $this->db->select('provincia_id, provincia_cp, provincia_nombre, provincia_capital, provincia_ca, provincia_autonomia');
        $this->db->from('mahico_provincias');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }
}
