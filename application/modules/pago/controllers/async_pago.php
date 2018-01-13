<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
* clase para manejar los pagos de manera asincrona
*/
class Async_pago extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('vendedor/mdl_vendedor');
    }
    public function cobrar()
    {
        $this->input->post('ajax') or die('Usted no tiene permiso para ingresar a esta sección privada.');
        $dpid = $this->input->post('dpid');
        $abonado = $this->input->post('abonado');
        $monto_pago = $this->input->post('monto_pago');
        $total = $this->input->post('total');

        ($dpid &&
         $monto_pago != null &&
         $total != null &&
         $abonado != null
         ) or die('detalle pauta id, abonado, total y monto pago son requeridos');
        $this->load->model('pago/mdl_pago');
        $inserted_id = $this->mdl_pago->cobrar($dpid, $monto_pago);
        $cancelado = false;
        /*FB::log($total, "\$total:\n");
        FB::log($monto_pago, "\$monto_pago:\n");
        FB::log($abonado, "\$abonado:\n");*/
        if ($total - ($monto_pago + $abonado) == 0) {
            /* significa que esta cancelando */
            $this->load->model('detalle_pauta/mdl_detalle_pauta');
            $this->mdl_detalle_pauta->registrarComoPagado($dpid);
            $cancelado = true;
        }
        // FB::log($cancelado, "\$cancelado:\n");
        echo json_encode(
                         array(
                               'result' => $inserted_id,
                               'cancelado' => $cancelado,
                           )
                     );
    }

    public function get_pagos_edicion()
    {
        if (!$this->input->post('ajax')) {
            $this->session->set_flashdata('custom_error', 'Usted no tiene permiso para acceder a esta sección');
            redirect();
        }
        $vendedor_id = $this->input->post('vendedor_id');
        $fecha = $this->input->post('fecha_i');
        echo json_encode(array('result' => $this->mdl_vendedor->getPagosVendedor($vendedor_id, $fecha)));
        // FB::log($this->db->last_query(), "last query:\n");
    }

}