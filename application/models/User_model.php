<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Método para verificar las credenciales del técnico
    public function verify_user($username, $password) {
        $this->db->where('tecnico_nombre', $username);
        $this->db->where('tecnico_password', $password);
        $query = $this->db->get('mahico_tecnicos');

        if ($query->num_rows() === 1) {
            return $query->row_array(); // Devuelve el técnico si las credenciales son correctas
        } else {
            return false; // Credenciales incorrectas o técnico no encontrado
        }
    }
}
