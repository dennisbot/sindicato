<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_publicacion extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'publicacion';
        $this->primary_key = 'id';
    }

    public function default_select()
    {
        $this->select('publicacion.*');
    }

    public function default_order_by()
    {
        if ($this->order_by && $this->order) {
            $this->order_by($this->order_by, $this->order);
        }
        else {
            $this->order_by($this->primary_key, 'desc');
        }
    }
    public function getAllPublicaciones($tipo)
    {
        $result = $this
                    ->select('id, nombre')
                    ->where('tipo_publicacion', $tipo)
                    ->get()->result();
        $periodicos = array();
        foreach ($result as $periodico) {
            $periodicos[$periodico->id] = $periodico->nombre;
        }
        return $periodicos;
    }
    public function save($id, $db_array, $set_flashdata = false)
    {

        return parent::save($id, $db_array, $set_flashdata);
    }
    public function validation_rules()
    {
        return array(
            'nombre' => array(
                    'field' => 'nombre',
                    'label' => 'Nombre',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'img' => array(
                    'field' => 'img',
                    'label' => 'img',
                    'rules' => 'trim|xss_clean'
                    ),
            'fecha_aniversario' => array(
                    'field' => 'fecha_aniversario',
                    'label' => 'Fecha aniversario',
                    'rules' => 'trim|xss_clean'
                    ),
            'proveedor_id' => array(
                    'field' => 'proveedor_id',
                    'label' => 'Proveedor',
                    'rules' => 'required|trim|xss_clean'
                    )
        );
    }

    public function validation_rules_descuento()
    {
        $return_array['publicacion_id'] = array(
                        'field' => 'publicacion_id',
                        'label' => 'Publicaci&oacute;n',
                        'rules' => 'required|trim|xss_clean'
                        );
        if ($this->input->post('tipo_fecha') == '0')
        {
            unset($_POST['tipo_fecha']);
            $return_array['tipo_fecha'] = array(
                        'field' => 'tipo_fecha',
                        'label' => 'Tipo de descuento',
                        'rules' => 'required|trim|xss_clean'
                        );
        }
        else
        {
            if( $this->input->post('tipo_fecha') == "dia")
            {
                unset($_POST['dia'][0]);
                if ( count($_POST['dia']) == 0 )
                {
                    unset($_POST['dia']);
                    $return_array['dia'] = array(
                        'field' => 'dia',
                        'label' => 'Dia',
                        'rules' => 'required|trim|xss_clean'
                        );
                }
            }
            elseif( $this->input->post('tipo_fecha') == "aniversario")
            {
                if ( $_POST['aniversario_dia'][0] == 0 )
                {
                    unset($_POST['aniversario_dia']);
                    $return_array['aniversario_dia'] = array(
                        'field' => 'aniversario_dia',
                        'label' => 'Dia del Aniversario',
                        'rules' => 'required|trim|xss_clean'
                        );
                }
                if ( $_POST['aniversario_mes'][0] == 0 )
                {
                    unset($_POST['aniversario_mes']);
                    $return_array['aniversario_mes'] = array(
                        'field' => 'aniversario_mes',
                        'label' => 'Mes del Aniversario',
                        'rules' => 'required|trim|xss_clean'
                        );
                }
            }
            elseif( $this->input->post('tipo_fecha') == "feriado")
            {
                if ( $_POST['feriado_dia'][0] == 0 )
                {
                    unset($_POST['feriado_dia']);
                    $return_array['feriado_dia'] = array(
                        'field' => 'feriado_dia',
                        'label' => 'Dia del Feriado',
                        'rules' => 'required|trim|xss_clean'
                        );
                }
                if ( $_POST['feriado_mes'][0] == 0 )
                {
                    unset($_POST['feriado_mes']);
                    $return_array['feriado_mes'] = array(
                        'field' => 'feriado_mes',
                        'label' => 'Mes del Feriado',
                        'rules' => 'required|trim|xss_clean'
                        );
                }
            }
        }

        $return_array['porcentaje_descuento'] = array(
                        'field' => 'porcentaje_descuento',
                        'label' => 'Porcentaje',
                        'rules' => 'required|trim|xss_clean'
                        );

        return $return_array;
    }

    public function validation_rules_precio()
    {
        $return_array['publicacion_id'] = array(
                        'field' => 'publicacion_id',
                        'label' => 'Publicacion',
                        'rules' => 'required|trim|xss_clean'
                        );
        if ( isset($_POST['dia']) )
        {
            unset($_POST['dia'][0]);
            if ( count($_POST['dia']) == 0 )
            {
                unset($_POST['dia']);
                $return_array['dia'] = array(
                    'field' => 'dia',
                    'label' => 'Dia',
                    'rules' => 'required|trim|xss_clean'
                    );
            }
        }

		$return_array['precio'] = array(
			            'field' => 'precio',
			            'label' => 'Precio',
			            'rules' => 'required|xss_clean'
			            );
		return $return_array;
	}

    public function validation_rules_comision()
    {
        $return_array['publicacion_id'] = array(
                        'field' => 'publicacion_id',
                        'label' => 'Publicacion',
                        'rules' => 'required|trim|xss_clean'
                        );
        if ( isset($_POST['dia']) )
        {
            unset($_POST['dia'][0]);
            if ( count($_POST['dia']) == 0 )
            {
                unset($_POST['dia']);
                $return_array['dia'] = array(
                    'field' => 'dia',
                    'label' => 'Dia',
                    'rules' => 'required|trim|xss_clean'
                    );
            }
        }

        $return_array['comision'] = array(
                        'field' => 'comision',
                        'label' => 'Comision',
                        'rules' => 'required|xss_clean'
                        );
        return $return_array;
    }

}
?>