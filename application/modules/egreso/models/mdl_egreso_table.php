<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_egreso_table extends CI_Model {

    public function get_table_headers() {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'concepto' => anchor('egreso/index/order_by/concepto/order/'.$order, 'Concepto'),
        'importe' => anchor('egreso/index/order_by/importe/order/'.$order, 'Importe (S./)'),
        'fecha' => anchor('egreso/index/order_by/fecha/order/'.$order, 'Fecha'),
        );

        return $headers;
    }

}

?>
