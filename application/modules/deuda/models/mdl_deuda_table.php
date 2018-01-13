<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_deuda_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'pauta_id' => anchor('deuda/index/order_by/pauta_id/order/'.$order, 'pauta_id'),
        'monto_deuda' => anchor('deuda/index/order_by/monto_deuda/order/'.$order, 'monto_deuda'),
        );


        return $headers;
    }

}

?>
