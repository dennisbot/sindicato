<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_descuento_publicacion extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'descuento_publicacion';
        $this->primary_key = 'descuento_publicacion.dia_descuento_id';
    }

    public function default_select() {
        $this->db->select('descuento_publicacion.*');
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
            'porcentaje_descuento' => array(
                    'field' => 'porcentaje_descuento',
                    'label' => 'porcentaje_descuento',
                    'rules' => 'trim|xss_clean'
                    ),
            'precio_publico' => array(
                    'field' => 'precio_publico',
                    'label' => 'precio_publico',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
}
?>