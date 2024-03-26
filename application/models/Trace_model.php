<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trace_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getTracesById($tecnico_id) {
        $this->db->select('
            t.traza_id,
            t.traza_tecnico_id,
            t.traza_fecha,
            a.aviso_ref,
            t.traza_saldo_anterior,
            t.traza_saldo_actual,
            t.traza_precio_aviso,
            t.traza_gastos_gestion,
            e.estado_descripcion,
            t.traza_tpv_id',);
        $this->db->from('mahico_trazas t');
        $this->db->join('mahico_avisos a', 't.traza_aviso_id = a.aviso_id', 'left');
        $this->db->join('mahico_estados e', 't.traza_estado_id = e.estado_id', 'left');
        $this->db->where('t.traza_tecnico_id', $tecnico_id);

        $query = $this->db->get();
        return $query->result_array();
    }
}
