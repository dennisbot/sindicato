<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_pago_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'deuda_id' => anchor('pago/index/order_by/deuda_id/order/'.$order, 'deuda_id'),
        'monto_pago' => anchor('pago/index/order_by/monto_pago/order/'.$order, 'monto_pago'),
        'fecha' => anchor('pago/index/order_by/fecha/order/'.$order, 'fecha'),
        );


        return $headers;
    }

}

?>
