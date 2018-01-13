<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_proveedor extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'proveedor';
        $this->primary_key = 'proveedor.id';
    }

    public function default_select() {
        $this->db->select('proveedor.*');
    }

    public function default_order_by() {
        if ($this->order_by && $this->order) {
            $this->db->order_by($this->order_by, $this->order);
        }
        else {
            $this->db->order_by($this->primary_key);
        }
    }
    public function existe_proveedor($proveedor_id)
    {
        $result = $this
                    ->select('id')
                    ->where('id', $proveedor_id)
                    ->get()
                    ->row();
        return !empty($result) ? $result->id : false;
    }
    public function getAllProveedores()
    {
        $proveedores = $this->mdl_proveedor
                                ->select('id, nombre')
                                ->get()
                                ->result();
        $p = array('0' => 'Seleccione un Proveedor ...');
        foreach ($proveedores as $proveedor) {
           $p[$proveedor->id] = $proveedor->nombre;
        }
        return $p;
    }
    public function getPublicacionesByProveedor($idProveedor, $tipoPublicacion = 'periodico')
    {
        $p = $this->mdl_proveedor
                            ->select('pu.id, pu.nombre, pu.shortname')
                            ->join('publicacion pu', 'pu.proveedor_id=proveedor.id')
                            ->where(
                                array(
                                    'proveedor.id' => $idProveedor,
                                    'tipo_publicacion' => $tipoPublicacion
                                )
                            )
                            ->order_by('pu.orden')
                            ->get()
                            ->result();
        return $p;
    }
    public function validation_rules() {
        return array(
            'nombre' => array(
                    'field' => 'nombre',
                    'label' => 'Nombre',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'direccion' => array(
                    'field' => 'direccion',
                    'label' => 'Direccion',
                    'rules' => 'trim|xss_clean'
                    ),
            'telefonos' => array(
                    'field' => 'telefonos',
                    'label' => 'Telefonos',
                    'rules' => 'valid_number|trim|xss_clean'
                    ),
            'ruc' => array(
                    'field' => 'ruc',
                    'label' => 'Ruc',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'ciudad' => array(
                    'field' => 'ciudad',
                    'label' => 'Ciudad',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
}
?>