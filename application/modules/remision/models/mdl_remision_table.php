<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_remision_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'id' => anchor('remision/index/order_by/id/order/'.$order, 'Código'),
        'nro_guia' => anchor('remision/index/order_by/nro_guia/order/'.$order, 'Nº de Guía'),
        'proveedor_id' => anchor('remision/index/order_by/proveedor_id/order/'.$order, 'Proveedor'),

        'razon_social' => anchor('remision/index/order_by/razon_social/order/'.$order, 'Razón Social'),
        // 'codigo' => anchor('remision/index/order_by/codigo/order/'.$order, 'codigo'),
        // 'ruc' => anchor('remision/index/order_by/ruc/order/'.$order, 'Ruc'),
        // 'tipo' => anchor('remision/index/order_by/tipo/order/'.$order, 'tipo'),
        // 'sector' => anchor('remision/index/order_by/sector/order/'.$order, 'Sector'),
        // 'observaciones' => anchor('remision/index/order_by/observaciones/order/'.$order, 'Observaciones'),
        'fecha_emision' => anchor('remision/index/order_by/fecha_emision/order/'.$order, 'Fecha de emision'),
        'fecha_recepcion' => anchor('remision/index/order_by/fecha_recepcion/order/'.$order, 'Fecha de recepción'),
        );


        return $headers;
    }

}

?>
