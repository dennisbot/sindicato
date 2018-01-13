<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_publicacion_table extends CI_Model
{
    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'nombre' => anchor('publicacion/index/order_by/nombre/order/'.$order, 'Nombre'),
        'tipo_publicacion' => anchor('publicacion/index/order_by/tipo_publicacion/order/'.$order, 'Tipo'),
        // 'img' => anchor('publicacion/index/order_by/img/order/'.$order, 'Imagen'),
        'fecha_aniversario' => anchor('publicacion/index/order_by/fecha_aniversario/order/'.$order, 'Aniversario'),
        'proveedor_id' => anchor('publicacion/index/order_by/proveedor_id/order/'.$order, 'Proveedor'),
        );
        return $headers;
    }
}

?>
