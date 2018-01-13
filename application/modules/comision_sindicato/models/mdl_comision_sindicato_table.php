<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_comision_sindicato_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'proveedor_id' => anchor('comision_sindicato/index/order_by/proveedor_id/order/'.$order, 'proveedor_id'),
        'comision_sindicato' => anchor('comision_sindicato/index/order_by/comision_sindicato/order/'.$order, 'comision_sindicato'),
        );


        return $headers;
    }

}

?>
