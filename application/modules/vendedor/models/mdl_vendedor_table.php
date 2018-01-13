<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_vendedor_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'nombres' => anchor('vendedor/index/order_by/nombres/order/'.$order, 'Nombres'),
        'apellidos' => anchor('vendedor/index/order_by/apellidos/order/'.$order, 'Apellidos'),
        'nickname' => anchor('vendedor/index/order_by/nickname/order/'.$order, 'Nickname'),
        'orden' => anchor('vendedor/index/order_by/orden/order/'.$order, 'Orden'),
        'fecha_nacimiento' => anchor('vendedor/index/order_by/fecha_nacimiento/order/'.$order, 'Fecha de Nacimiento'),
        'fecha_ingreso' => anchor('vendedor/index/order_by/created_at/order/'.$order, 'Fecha de Ingreso'),
         );


        return $headers;
    }
       public function get_table_headers_deudores() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'nombres' => anchor('vendedor/deudores/order_by/nombres/order/'.$order, 'Nombres'),
        'apellidos' => anchor('vendedor/deudores/order_by/apellidos/order/'.$order, 'Apellidos'),
        'nickname' => anchor('vendedor/deudores/order_by/nickname/order/'.$order, 'Nickname'),
        'fecha' => anchor('vendedor/deudores/order_by/fecha_nacimiento/order/'.$order, 'Fecha'),
        'monto_deuda' => anchor('vendedor/deudores/order_by/estado/order/'.$order, 'Deuda'),
        'pago_total' => anchor('vendedor/deudores/order_by/estado/order/'.$order, 'Total Pagado'),
        'saldo_final' => anchor('vendedor/deudores/order_by/estado/order/'.$order, 'Saldo'),
        'nombre' => anchor('vendedor/deudores/order_by/estado/order/'.$order, 'Publicacion'),

        );


        return $headers;
    }

}

?>
