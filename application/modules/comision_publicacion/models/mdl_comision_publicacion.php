<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_comision_publicacion extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'comision_publicacion';
        $this->primary_key = 'comision_publicacion.id';
    }

    public function default_select()
    {
        $this->db->select('comision_publicacion.*');
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

    public function validation_rules()
    {
        return array(
            'comision' => array(
                    'field' => 'comision',
                    'label' => 'comision',
                    'rules' => 'trim|xss_clean'
                    ),
            'fecha' => array(
                    'field' => 'fecha',
                    'label' => 'fecha',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'publicacion_id' => array(
                    'field' => 'publicacion_id',
                    'label' => 'publicacion_id',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'operador_id' => array(
                    'field' => 'operador_id',
                    'label' => 'operador_id',
                    'rules' => 'required|trim|xss_clean'
                    )
        );
    }

    public function editar_comision_publicacion($updated_value, $publicacion_id, $comision_id)
    {
        ($publicacion_id and $comision_id) or die('debe establecer los valores de publicacion_id, comision_id primero');
        $this->db->query('
            update `comision_publicacion`
            set comision=\''  . $updated_value . '\'
            where `publicacion_id`=' . $publicacion_id . ' and `id`=' . $comision_id . ';
        ');
    }

    public function get_comision($publicacion_id, $dia)
    {
        $comision = $this->mdl_comision_publicacion
                        ->select('comision')
                        ->where(
                            array(
                                'publicacion_id' => $publicacion_id,
                                'dia' => $dia
                                )
                            )
                        ->get()
                        ->row();
        if ($comision)
        return $comision->comision;
    }

}
?>