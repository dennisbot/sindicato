<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_detalle_remision_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'publicacion_id' => anchor('detalle_remision/index/order_by/publicacion_id/order/'.$order, 'publicacion_id'),
        'descripcion' => anchor('detalle_remision/index/order_by/descripcion/order/'.$order, 'descripcion'),
        'cantidad' => anchor('detalle_remision/index/order_by/cantidad/order/'.$order, 'cantidad'),
        'unidad_medida' => anchor('detalle_remision/index/order_by/unidad_medida/order/'.$order, 'unidad_medida'),
        'precio_unitario_guia' => anchor('detalle_remision/index/order_by/precio_unitario_guia/order/'.$order, 'precio_unitario_guia'),
        'importe' => anchor('detalle_remision/index/order_by/importe/order/'.$order, 'importe'),
        'cantidad_devolucion' => anchor('detalle_remision/index/order_by/cantidad_devolucion/order/'.$order, 'cantidad_devolucion'),
        'importe_neto' => anchor('detalle_remision/index/order_by/importe_neto/order/'.$order, 'importe_neto'),
        );


        return $headers;
    }

}

?>
