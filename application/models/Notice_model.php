<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getNoticesById($tecnico_id) {
        $this->db->select('
            a.aviso_id,
            a.aviso_fecha, 
            a.aviso_observaciones_tecnico, 
            a.aviso_oficio_id, 
            o.oficio_descripcion, 
            a.aviso_ref, 
            a.aviso_telefono, 
            a.aviso_descripcion, 
            a.aviso_poblacion_id, 
            p.poblacion_nombre, 
            a.aviso_provincia_id, 
            pr.provincia_nombre, 
            a.aviso_estado_id, 
            e.estado_descripcion'
        );
        $this->db->from('mahico_avisos a');
        $this->db->join('mahico_oficios o', 'a.aviso_oficio_id = o.oficio_id', 'left');
        $this->db->join('mahico_poblaciones p', 'a.aviso_poblacion_id = p.poblacion_id', 'left');
        $this->db->join('mahico_provincias pr', 'a.aviso_provincia_id = pr.provincia_id', 'left');
        $this->db->join('mahico_estados e', 'a.aviso_estado_id = e.estado_id', 'left');
        $this->db->where('a.aviso_tecnico_id', $tecnico_id);
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
