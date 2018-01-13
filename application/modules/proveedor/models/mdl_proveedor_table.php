<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_proveedor_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'nombre' => anchor('proveedor/index/order_by/nombre/order/'.$order, 'Nombre'),
        'direccion' => anchor('proveedor/index/order_by/direccion/order/'.$order, 'Direcci&oacute;n'),
        'telefonos' => anchor('proveedor/index/order_by/telefonos/order/'.$order, 'Tel&eacute;fonos'),
        'ruc' => anchor('proveedor/index/order_by/ruc/order/'.$order, 'RUC'),
        'ciudad' => anchor('proveedor/index/order_by/ciudad/order/'.$order, 'Ciudad'),
        );
        return $headers;
    }

}

?>
