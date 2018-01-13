<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_pauta_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'remision_id' => anchor('pauta/index/order_by/remision_id/order/'.$order, 'remision_id'),
        'cantidad_pauta' => anchor('pauta/index/order_by/cantidad_pauta/order/'.$order, 'cantidad_pauta'),
        'fecha' => anchor('pauta/index/order_by/fecha/order/'.$order, 'fecha'),
        );


        return $headers;
    }

}

?>
