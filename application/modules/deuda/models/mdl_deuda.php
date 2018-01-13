<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_deuda extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'deuda';
        $this->primary_key = 'deuda.pauta_id';
    }

    public function default_select() {
        $this->db->select('deuda.*');
    }

    public function default_order_by() {
        if ($this->order_by && $this->order) {
            $this->db->order_by($this->order_by, $this->order);
        }
        else {
            $this->db->order_by($this->primary_key);
        }
    }
    public function validation_rules() {
        return array(
            'monto_deuda' => array(
                    'field' => 'monto_deuda',
                    'label' => 'monto_deuda',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
}
?>