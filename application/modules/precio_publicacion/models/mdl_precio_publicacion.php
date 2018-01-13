<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_precio_publicacion extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'precio_publicacion';
        $this->primary_key = 'precio_publicacion.id';
    }

    public function default_select()
    {
        $this->db->select('precio_publicacion.*');
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
            'precio' => array(
                    'field' => 'precio',
                    'label' => 'precio',
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

    public function editar_precio_publicacion($updated_value, $publicacion_id, $precio_id)
    {
        ($publicacion_id and $precio_id) or die('debe establecer los valores de publicacion_id, precio_id primero');
        $this->db->query('
            update `precio_publicacion`
            set precio=\''  . $updated_value . '\'
            where `publicacion_id`=' . $publicacion_id . ' and `id`=' . $precio_id . ';
        ');
    }

    public function get_precio($publicacion_id, $dia)
    {
        $precio = $this->mdl_precio_publicacion
                        ->select('precio')
                        ->where(
                            array(
                                'publicacion_id' => $publicacion_id,
                                'dia' => $dia
                                )
                            )
                        ->get()
                        ->row();
        if ($precio)
        return $precio->precio;
    }
}
?>