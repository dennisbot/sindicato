<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_pago extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'pago';
        $this->primary_key = 'pago.deuda_id';
        $this->date_created_field ='pago.fecha_pago';
    }

    public function default_select() {
        $this->db->select('pago.*');
    }

    public function default_order_by() {
        if ($this->order_by && $this->order) {
            $this->db->order_by($this->order_by, $this->order);
        }
        else {
            $this->db->order_by($this->primary_key);
        }
    }
    public function cobrar($dpid, $monto_pago)
    {
        $params['detalle_pauta_id'] = $dpid;
        $params['monto_pago'] = $monto_pago;
        $params['operador_id'] = 1;
        return $this->save(null, $params, false);
    }
    public function validation_rules() {
        return array(
            'monto_pago' => array(
                    'field' => 'monto_pago',
                    'label' => 'monto_pago',
                    'rules' => 'trim|xss_clean'
                    ),
            'fecha' => array(
                    'field' => 'fecha',
                    'label' => 'fecha',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
}
?>