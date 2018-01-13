<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_descripcion_tipo_plantilla_table extends CI_Model
{
    public function get_table_headers()
    {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'iddescripcion' => anchor('descripcion_tipo_plantilla/index/order_by/iddescripcion/order/'.$order, 'iddescripcion'),
        'descripcion' => anchor('descripcion_tipo_plantilla/index/order_by/descripcion/order/'.$order, 'descripcion'),
        );


        return $headers;
    }
}

?>
