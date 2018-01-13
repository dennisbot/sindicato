<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_dia_descuento extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'dia_descuento';
        $this->primary_key = 'dia_descuento.id';
    }

    public function default_select() {
        $this->db->select('dia_descuento.*');
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
            'nombre' => array(
                    'field' => 'nombre',
                    'label' => 'nombre',
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