<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Método para obtener las facturas asociadas a un técnico por su ID
    public function getInvoicesById($tecnico_id) {
        $this->db->select('
            f.factura_id,
            f.factura_empresa_id, 
            f.factura_tecnico_id, 
            f.factura_orden, 
            f.factura_fecha, 
            f.factura_concepto, 
            f.factura_base_imponible, 
            f.factura_tipo_iva, 
            f.factura_iva, 
            f.factura_total, 
            f.factura_pdf, 
            f.factura_excel, 
            f.factura_cobrada, 
            f.factura_documento_pago,
            f.factura_preliquidacion'
        );
        $this->db->from('mahico_facturas f');
        $this->db->where('f.factura_tecnico_id', $tecnico_id);
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
