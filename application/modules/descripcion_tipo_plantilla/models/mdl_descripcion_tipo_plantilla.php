<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_descripcion_tipo_plantilla extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'descripcion_tipo_plantilla';
        $this->primary_key = 'descripcion_tipo_plantilla.iddescripcion';
    }

    public function default_select()
    {
        $this->db->select('descripcion_tipo_plantilla.*');
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
    public function getKeyPairDescripcion()
    {
        $coleccion = $this->mdl_descripcion_tipo_plantilla->get()->result();
        $arr = array();
        foreach ($coleccion as $key => $value) {
            $arr[$value->iddescripcion] = $value->descripcion;
        }
        return $arr;
    }
    public function validation_rules()
    {
        return array(
            'descripcion' => array(
                    'field' => 'descripcion',
                    'label' => 'descripcion',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
}
?>