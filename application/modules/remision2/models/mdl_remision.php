<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_remision extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'remision';
        $this->primary_key = 'remision.id';
    }

    public function default_select() {
        $this->db->select('id,proveedor_id,razon_social,fecha_emision,fecha_recepcion');
    }

    public function default_order_by() {
        if ($this->order_by && $this->order) {
            $this->db->order_by($this->order_by, $this->order);
        }
        else {
            $this->db->order_by($this->primary_key);
        }
    }
    public function save($id = null, $db_array = null, $set_flashdata = true)
    {
        if (!$db_array) {
            $db_array = $this->db_array();
            $db_array['fecha_emision'] = strtotime(to_standard_date($db_array['fecha_emision']));
            $db_array['fecha_recepcion'] = strtotime(to_standard_date($db_array['fecha_recepcion']));
            if ($set_flashdata) {
                if ($id) {
                    $this->session->set_flashdata('alert_success', 'Remisión actualizada correctamente.');
                }
                else {
                    $this->session->set_flashdata('alert_success', 'Remisión creada correctamente.');
                }
            }
        }
        return parent::save($id, $db_array, false);
    }
    public function get_pauta_id($proveedor_id, $fecha_unixtime)
    {
        $query = $this->select('id')->where(
                    array(
                        'proveedor_id'      => $proveedor_id,
                        'fecha_recepcion'  => $fecha_unixtime
                    )
                )->get();
        return $query->num_rows() > 0 ? $query->row()->id : false;
    }
    public function listRemisionesByDate($datetime = null)
    {
        if ($datetime != null) {
            $remisiones = $this->select('remision.id remision_id, razon_social, p.nombre, p.id proveedor_id')
                             ->join('proveedor p', 'p.id = remision.proveedor_id')
                             ->where('fecha_recepcion', $datetime)
                             ->get()
                             ->result();
             // FB::log($this->last_query(), "last:\n");
            $r = array();
            foreach ($remisiones as $remision) {
                $r[] = array(
                            "remision_id" => $remision->remision_id,
                            "proveedor_id" => $remision->proveedor_id,
                            "razon_social" => $remision->razon_social,
                            "nombre" => $remision->nombre
                        );
            }
            return $r;
        }
        return array();
    }
    public function valida_consulta()
    {
		return array(
            'proveedor_id' => array(
                    'field' => 'proveedor_id',
                    'label' => 'Proveedor',
                    'rules' => 'trim|xss_clean|required'
                    ),
            'razon_social' => array(
                    'field' => 'razon_social',
                    'label' => 'razon_social',
                    'rules' => 'trim|xss_clean'
                    )
		);
    }
    public function validation_rules()
    {
        return array(
            'nro_guia' => array(
                    'field' => 'nro_guia',
                    'label' => 'N&uacute;mero de gu&iacute;a',
                    'rules' => 'trim|xss_clean|required'
                    ),
            'razon_social' => array(
                    'field' => 'razon_social',
                    'label' => 'razon_social',
                    'rules' => 'trim|xss_clean'
                    ),
            'codigo' => array(
                    'field' => 'codigo',
                    'label' => 'codigo',
                    'rules' => 'trim|xss_clean'
                    ),
            'ruc' => array(
                    'field' => 'ruc',
                    'label' => 'ruc',
                    'rules' => 'trim|xss_clean'
                    ),
            'tipo' => array(
                    'field' => 'tipo',
                    'label' => 'tipo',
                    'rules' => 'trim|xss_clean'
                    ),
            'sector' => array(
                    'field' => 'sector',
                    'label' => 'sector',
                    'rules' => 'trim|xss_clean'
                    ),
            'observaciones' => array(
                    'field' => 'observaciones',
                    'label' => 'observaciones',
                    'rules' => 'trim|xss_clean'
                    ),
            'fecha_emision' => array(
                    'field' => 'fecha_emision',
                    'label' => 'fecha_emision',
                    'rules' => 'trim|xss_clean'
                    ),
            'fecha_recepcion' => array(
                    'field' => 'fecha_recepcion',
                    'label' => 'fecha_recepcion',
                    'rules' => 'trim|xss_clean'
                    ),
            'proveedor_id' => array(
                    'field' => 'proveedor_id',
                    'label' => 'Proveedor',
                    'rules' => 'trim|xss_clean|required'
                    )
        );
    }
}
?>