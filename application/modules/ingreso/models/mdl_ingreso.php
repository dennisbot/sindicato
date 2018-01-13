<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_ingreso extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'ingreso';
        $this->primary_key = 'ingreso.id';
    }

    public function default_select()
    {
        $this->select("id, concepto, importe, fecha", false);
    }

    public function default_order_by()
    {
        if ($this->order_by && $this->order) {
            $this->order_by($this->order_by, $this->order);
        }
        else {
            $this->order_by($this->primary_key);
        }
    }
    public function save($id = null, $db_array = null, $set_flashdata = true)
    {
        if (!$db_array) {
            $db_array = $this->db_array();
            /* aqui pondremos la sesión del usuario que esta logueado */
            $db_array["operador_id"] = 1;
            $db_array['fecha'] = strtotime(to_standard_date($db_array['fecha']));
        }
        return parent::save($id, $db_array, $set_flashdata);
    }
    public function prep_form($id_ingreso)
    {
        if (!$id_ingreso) return;
        parent::prep_form($id_ingreso);
        $fecha = $this->mdl_ingreso->form_value('fecha');
        $this->mdl_ingreso->set_form_value('fecha', date('d/m/Y', $fecha));
    }

    //ingresos/ganancias por fecha
    function ingresos_por_fecha($fecha_inicio, $fecha_fin)
    {
		$query = $this->db->query("
        select fecha_recepcion, descripcion,
        round((precio_publicacion * comision/100), 3) as porcentaje,
        (cantidad - cantidad_devolucion) as cantidad_recibida,
        round((cantidad - cantidad_devolucion) * (precio_publicacion * comision/100), 3) as ganancia
        from
        remision
        inner join
        detalle_remision
        on
        remision.id = detalle_remision.remision_id
        where
        status = 'devuelto' and
        fecha_recepcion >= '".$fecha_inicio."' and fecha_recepcion <= '".$fecha_fin."'");
        return $query->result();
    }

    //ingresos/ganancias suma total
    function ingresos_monto_total($fecha_inicio, $fecha_fin) {
		$query = $this->db->query("
        select sum(round((cantidad - cantidad_devolucion) * (precio_publicacion * comision/100), 3)) as ganancia
        from remision inner join detalle_remision
        on remision.id = detalle_remision.remision_id
        where status = 'devuelto' and fecha_emision >= '".$fecha_inicio."' and fecha_emision <= '".$fecha_fin."'");
		return $query->row();
    }


    public function validation_rules()
    {
        return array(
            'concepto' => array(
                    'field' => 'concepto',
                    'label' => 'Concepto',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'importe' => array(
                    'field' => 'importe',
                    'label' => 'Importe',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'fecha' => array(
                    'field' => 'fecha',
                    'label' => 'Fecha',
                    'rules' => 'required|trim|xss_clean'
                    )
        );
    }
}
?>