<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_dia_descuento_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'id' => anchor('dia_descuento/index/order_by/id/order/'.$order, 'id'),
        'nombre' => anchor('dia_descuento/index/order_by/nombre/order/'.$order, 'nombre'),
        'fecha' => anchor('dia_descuento/index/order_by/fecha/order/'.$order, 'fecha'),
        );


        return $headers;
    }

}

?>
