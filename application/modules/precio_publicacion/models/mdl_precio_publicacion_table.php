<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_precio_publicacion_table extends CI_Model
{
    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'id' => anchor('precio_publicacion/index/order_by/id/order/'.$order, 'id'),
        'precio' => anchor('precio_publicacion/index/order_by/precio/order/'.$order, 'precio'),
        'fecha' => anchor('precio_publicacion/index/order_by/fecha/order/'.$order, 'fecha'),
        'publicacion_id' => anchor('precio_publicacion/index/order_by/publicacion_id/order/'.$order, 'publicacion_id'),
        'operador_id' => anchor('precio_publicacion/index/order_by/operador_id/order/'.$order, 'operador_id'),
        );


        return $headers;
    }
}

?>
