<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_descuento_publicacion_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'dia_descuento_id' => anchor('descuento_publicacion/index/order_by/dia_descuento_id/order/'.$order, 'dia_descuento_id'),
        'porcentaje_descuento' => anchor('descuento_publicacion/index/order_by/porcentaje_descuento/order/'.$order, 'porcentaje_descuento'),
        'precio_publico' => anchor('descuento_publicacion/index/order_by/precio_publico/order/'.$order, 'precio_publico'),
        );


        return $headers;
    }

}

?>
