<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_egreso extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'egreso';
        $this->primary_key = 'egreso.id';
    }

    public function default_select() {
        $this->select("id, concepto, importe, fecha", false);
    }

    public function default_order_by() {
        if ($this->order_by && $this->order) {
            $this->order_by($this->order_by, $this->order);
        }
        else {

            $this->order_by($this->primary_key,'desc');

        }
    }
    public function save($id = null, $db_array = null, $set_flashdata = true)
    {
        if (!$db_array) {
            $db_array = $this->db_array();
            /* aquí pondremos la sesión del usuario que esta logueado */
            $db_array['operador_id'] = 1;
            $db_array['fecha'] = strtotime(to_standard_date($db_array['fecha']));
        }
        return parent::save($id, $db_array, $set_flashdata);
    }
    public function prep_form($id_egreso)
    {
        if (!$id_egreso) return;
        parent::prep_form($id_egreso);
        $fecha = $this->mdl_egreso->form_value('fecha');
        $this->mdl_egreso->set_form_value('fecha', date('d/m/Y', strtotime($fecha)));
    }
    public function validation_rules() {
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
                    ),
        );
    }
}
?>