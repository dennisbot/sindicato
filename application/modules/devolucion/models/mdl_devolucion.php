<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_devolucion extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'devolucion';
        $this->primary_key = 'detalle_pauta_id';
        $this->date_created_field = "fecha_devolucion";
    }

    public function default_select()
    {
        $this->db->select('*');
    }

    public function default_order_by()
    {
        if ($this->order_by && $this->order) {
            $this->db->order_by($this->order_by, $this->order);
        }
        else {
            $this->db->order_by($this->primary_key);
        }
    }
    public function devolver($dpid, $db_array)
    {
        return $this->save($dpid, $db_array, false);
    }
    public function save($id, $db_array, $set_flashdata = false)
    {
        return parent::save($id, $db_array, $set_flashdata);
    }
    public function validation_rules() {
        return array(
            'cantidad_devolucion' => array(
                    'field' => 'cantidad_devolucion',
                    'label' => 'cantidad_devolucion',
                    'rules' => 'trim|xss_clean'
                    ),
            'fecha_devolucion' => array(
                    'field' => 'fecha',
                    'label' => 'fecha',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
}
?>