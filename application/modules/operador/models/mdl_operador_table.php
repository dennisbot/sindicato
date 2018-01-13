<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_operador_table extends CI_Model
{
    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'nombre_usuario' => anchor('operador/index/order_by/nombre_usuario/order/'.$order, 'Nombre de Usuario'),
        'email' => anchor('operador/index/order_by/email/order/'.$order, 'Correo Electrónico'),
        );


        return $headers;
    }
}

?>
