<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_remision extends MY_Model
{

	public function __construct()
    {
		parent::__construct();
		$this->table = 'remision';
		$this->primary_key = 'remision.id';
	}

    public function default_select() {
        $this->select('id, nro_guia, proveedor_id, razon_social, fecha_emision, fecha_recepcion, fecha_vencimiento');
    }

    public function default_order_by()
    {
        if ($this->order_by && $this->order) {
            $this->order_by($this->order_by, $this->order);
        }
        else {
            $this->order_by('fecha_emision', 'desc');
        }
    }
    public function existe_remision($remision_id)
    {
        $query = $this->select('id, proveedor_id, status')
                      ->where('id', $remision_id)
                      ->get();
        return $this->num_rows() > 0 ? $this->row() : false;
    }
    public function existeRemisionSemanaAnterior($remision_id, $weekTimes)
    {
        $remisionActual = $this->get_by_id($remision_id);
        $query = $this->select('id, proveedor_id, status')
                      ->where('fecha_recepcion', $remisionActual->fecha_recepcion - $weekTimes * 7 * 24 * 60 * 60)
                      ->where('son_periodicos', true)
                      ->where('proveedor_id', $remisionActual->proveedor_id)
                      ->get();
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        }
        return 0;
    }
    public function get_pauta_id($where = null)
    {
        ($where) or die('no hay el parametro where');
        $query = $this
                ->select('pauta.id')
                ->join('pauta', 'pauta.remision_id=remision.id')
                ->where(
                    $where
                )->get();
        return $query->num_rows() > 0 ? $query->row()->id : false;
    }
    public function save($id = null, $db_array = null, $set_flashdata = true)
    {

        if (!$db_array) {
            $db_array = $this->db_array();
            $db_array['fecha_vencimiento'] = strtotime(to_standard_date($db_array['fecha_vencimiento']));
            $db_array['fecha_emision'] = strtotime(to_standard_date($db_array['fecha_emision']));
            $db_array['fecha_recepcion'] = strtotime(to_standard_date($db_array['fecha_recepcion']));
            unset($db_array['existen_detalles_remision']);
        }
        return parent::save($id, $db_array, $set_flashdata);
    }

    public function listRemisionesByDate($datetime = null)
    {
        if ($datetime != null) {
            $remisiones = $this->select('remision.id remision_id, nro_guia, razon_social, p.nombre, p.id proveedor_id')
                             ->join('proveedor p', 'p.id = remision.proveedor_id')
                             ->where('fecha_recepcion', $datetime)
                             ->where('status', 'pendiente')
                             ->get()
                             ->result();
             // FB::log($this->last_query(), "last:\n");
            $r = array();
            foreach ($remisiones as $remision) {
                $r[] = array("remision_id" => $remision->remision_id,
                			"nro_guia" => $remision->nro_guia,
                            "proveedor_id" => $remision->proveedor_id,
                            "razon_social" => $remision->razon_social,
                            "nombre" => $remision->nombre
				);
			}
			return $r;
		}
		return array();
	}

	//funcion para mostrar la cantidad de devoluciones por proveedor
	function mostrar_cantidad_devolucion($remision_id, $publicacion_id)
    {
		$query = $this->db->query("select sum(devolucion.cantidad_devolucion) as total_a_cobrar
			from remision inner join detalle_remision on remision.id = detalle_remision.remision_id
			inner join publicacion on detalle_remision.publicacion_id = publicacion.id
			inner join detalle_pauta on detalle_remision.id = detalle_pauta.detalle_remision_id
			inner join vendedor on detalle_pauta.vendedor_id = vendedor.id
			inner join devolucion on  detalle_pauta.id = devolucion.detalle_pauta_id
			where remision_id = '".$remision_id."' and  publicacion_id = '".$publicacion_id."'");
		$results = $query->row();
		if ($results)
		return $results->total_a_cobrar;
		else
		return 0;
	}

	//funcion para saber si una remision ya esta en pautas
	function tiene_pauta($remision_id)
    {
		$query = $this->db->query("select remision.id
			from remision where remision.id in(select remision_id from pauta)
			and remision.id = '".$remision_id."'");
		$results = $query->row();
		if (count($results) > 0)
			return true;
		else
			return false;

	}

	//funcion para saber si una remision ya esta en pautas
	function tiene_devolucion($remision_id)
    {
		$query = $this->db->query("select remision.id
			from remision inner join detalle_remision on remision.id = detalle_remision.remision_id
			inner join publicacion on detalle_remision.publicacion_id = publicacion.id
			inner join detalle_pauta on detalle_remision.id = detalle_pauta.detalle_remision_id
			inner join devolucion on detalle_pauta.id = devolucion.detalle_pauta_id
			where remision_id = '".$remision_id."'");
		$results = $query->row();
		if (count($results) > 0)
			return true;
		else
			return false;

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
                    'rules' => 'trim|xss_clean|required|callback_nroguiacheck'
                    ),
            'razon_social' => array(
                    'field' => 'razon_social',
                    'label' => 'Razón social',
                    'rules' => 'trim|xss_clean|required'
                    ),
            'codigo' => array(
                    'field' => 'codigo',
                    'label' => 'codigo',
                    'rules' => 'trim|xss_clean'
                    ),
            'ruc' => array(
                    'field' => 'ruc',
                    'label' => 'Ruc',
                    'rules' => 'trim|xss_clean'
                    ),
            'tipo' => array(
                    'field' => 'tipo',
                    'label' => 'Tipo',
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
            'fecha_vencimiento' => array(
                    'field' => 'fecha_vencimiento',
                    'label' => 'Fecha vencimiento',
                    'rules' => 'trim|xss_clean|required'
                    ),
            'fecha_emision' => array(
                    'field' => 'fecha_emision',
                    'label' => 'Fecha emisión',
                    'rules' => 'trim|xss_clean|required'
                    ),
            'fecha_recepcion' => array(
                    'field' => 'fecha_recepcion',
                    'label' => 'Fecha recepción',
                    'rules' => 'trim|xss_clean|required'
                    ),
            'existen_detalles_remision' => array(
                    'field' => 'existen_detalles_remision',
                    'label' => 'existen_detalles_remision ',
                    'rules' => 'trim|xss_clean|callback_check_detalles_remision'
                    ),
            'proveedor_id' => array(
                    'field' => 'proveedor_id',
                    'label' => 'Proveedor',
                    'rules' => 'trim|xss_clean|required'
                    ),
            'son_periodicos' => array(
                    'field' => 'son_periodicos',
                    'label' => 'Periódicos o Revistas',
                    'rules' => 'trim|xss_clean|required'
                    ),
            'total' => array(
                    'field' => 'total',
                    'label' => 'total',
                    'rules' => 'trim|xss_clean|callback_check_precios_guia'
                    )
            );
	}
    public function nroguiacheck($nroguia)
    {
        $id = uri_assoc('id');
    	if ($id) {
            $result = $this->select('id')
            ->where(
                    array(
                          'nro_guia' => $nroguia,
                          'id !=' => $id
                  )
            )
            ->get();
        }
        else{
        $result = $this->select('id')
                      ->where('nro_guia', $nroguia)->get();
        }

        if ($result->num_rows() == 0 ) {

           return true;
        }
        else
        {
            $this->form_validation->set_message('nroguiacheck',  'El número de guía ya existe.');
            return false;
        }

    }
    public function check_detalles_remision($val)
    {
    	if ($val == false) {
			$this->form_validation->set_message('check_detalles_remision',  'Ingrese al menos una cantidad mayor a "0" para las publicaciones.');
            return false;
        }
    	return true;
    }

    //check precio guia
    public function check_precios_guia($val)
    {
    	if ($val == '0.000') {
			$this->form_validation->set_message('check_precios_guia',  'Ingrese todos los precios de gu&iacute;a y la cantidad mayor a "0".');
            return false;
        }
    	return true;
    }
}
