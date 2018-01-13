<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_comision_publicacion_table extends CI_Model
{
    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'id' => anchor('comision_publicacion/index/order_by/id/order/'.$order, 'id'),
        'comision' => anchor('comision_publicacion/index/order_by/comision/order/'.$order, 'comision'),
        'fecha' => anchor('comision_publicacion/index/order_by/fecha/order/'.$order, 'fecha'),
        'publicacion_id' => anchor('comision_publicacion/index/order_by/publicacion_id/order/'.$order, 'publicacion_id'),
        'operador_id' => anchor('comision_publicacion/index/order_by/operador_id/order/'.$order, 'operador_id'),
        );


        return $headers;
    }
}

?>
