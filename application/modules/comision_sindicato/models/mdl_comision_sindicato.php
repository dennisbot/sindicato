<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_comision_sindicato extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'comision_sindicato';
        $this->primary_key = 'comision_sindicato.proveedor_id';
    }

    public function default_select() {
        $this->db->select('comision_sindicato.*');
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
            'comision_sindicato' => array(
                    'field' => 'comision_sindicato',
                    'label' => 'comision_sindicato',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
}
?>