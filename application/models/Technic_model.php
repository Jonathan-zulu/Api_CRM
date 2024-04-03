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

    public function getVisibleOffices() {
        $this->db->select('
            o.oficio_id,
            o.oficio_descripcion
        ',);
        $this->db->where('oficio_visible', 'SI');
        $this->db->from('mahico_oficios o');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getAvailableTechnicians($zipCode, $jobId) {
        $sql = "SELECT * FROM mahico_tecnicos t 
                WHERE tecnico_bloqueado = 'NO' 
                AND t.tecnico_activo >= 0
                AND NOT ((t.tecnico_bloqueo_desde <= NOW() AND t.tecnico_bloqueo_desde <> '0000-00-00 00:00:00')
                AND (t.tecnico_bloqueo_hasta >= NOW() AND t.tecnico_bloqueo_hasta <> '0000-00-00 00:00:00'))
                AND ((t.tecnico_compra_avisos = 'SI' AND t.tecnico_saldo > 15) OR (t.tecnico_compra_avisos = 'NO'))
                AND tecnico_id IN (SELECT tecnico_id FROM mahico_tecnicos_oficios WHERE oficio_id IN (?))
                AND tecnico_id IN (SELECT tecnico_id FROM mahico_tecnicos_cps p, codigos_postales_geo g
                                   WHERE p.codigo_postal_id = g.codigo_postal_id AND g.codigo_postal_cp = ?)
                ORDER BY t.tecnico_prioridad DESC, t.tecnico_compra_avisos DESC, t.tecnico_plan_id";
        
        $query = $this->db->query($sql, array($zipCode, $jobId));
        
        return $query->result_array();
    }

    public function getTechnicalById($tecnico_id) {
        $this->db->select('
            mahico_tecnicos.tecnico_id,
            mahico_tecnicos.tecnico_nombre, 
            mahico_tecnicos.tecnico_bloqueado, 
            mahico_tecnicos.tecnico_bloqueo_desde, 
            mahico_tecnicos.tecnico_bloqueo_hasta,
            mahico_tecnicos.tecnico_dni,
            mahico_tecnicos.tecnico_CIF,
            mahico_tecnicos.tecnico_telefono,
            mahico_tecnicos.tecnico_precioxaviso,
            mahico_tecnicos.tecnico_precioxaviso_climaygas,
            mahico_tecnicos.tecnico_saldo,
            mahico_provincias.provincia_nombre,
            mahico_poblaciones.poblacion_nombre,
            mahico_oficios.oficio_descripcion',);
        $this->db->from('mahico_tecnicos');
        // JOIN con mahico_provincias para obtener el nombre de la provincia
        $this->db->join('mahico_provincias', 'mahico_tecnicos.tecnico_provincia_id = mahico_provincias.provincia_id', 'left');
        // JOIN con mahico_poblaciones para obtener el nombre de la población
        $this->db->join('mahico_poblaciones', 'mahico_tecnicos.tecnico_poblacion_id = mahico_poblaciones.poblacion_id', 'left');
        // JOIN con mahico_oficios para obtener el nombre del oficio
        $this->db->join('mahico_oficios', 'mahico_tecnicos.tecnico_oficio_id = mahico_oficios.oficio_id', 'left');
        $this->db->where('mahico_tecnicos.tecnico_id', $tecnico_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getInvoiceById($tecnico_id) {
        $this->db->select('
            factura_id,
            factura_empresa_id,
            factura_tecnico_id,
            factura_orden,
            factura_fecha,
            factura_concepto,
            factura_base_imponible,
            factura_tipo_iva,
            factura_total,
            factura_pdf,
            factura_excel,
            factura_cobrada,
            factura_documento_pago');
        $this->db->from('mahico_tecnicos');
        // JOIN con mahico_provincias para obtener el nombre de la provincia
        $this->db->join('mahico_provincias', 'mahico_tecnicos.tecnico_provincia_id = mahico_provincias.provincia_id', 'left');
        // JOIN con mahico_poblaciones para obtener el nombre de la población
        $this->db->join('mahico_poblaciones', 'mahico_tecnicos.tecnico_poblacion_id = mahico_poblaciones.poblacion_id', 'left');
        $this->db->where('mahico_tecnicos.tecnico_id', $tecnico_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function insert_technician($data) {
        $this->db->insert('mahico_tecnicos', $data);
        return $this->db->insert_id();
    }
}
