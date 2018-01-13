<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_devolucion_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'vendedor_id' => anchor('devolucion/index/order_by/vendedor_id/order/'.$order, 'vendedor_id'),
        'cantidad_devolucion' => anchor('devolucion/index/order_by/cantidad_devolucion/order/'.$order, 'cantidad_devolucion'),
        'fecha' => anchor('devolucion/index/order_by/fecha/order/'.$order, 'fecha'),
        );


        return $headers;
    }

}

?>
