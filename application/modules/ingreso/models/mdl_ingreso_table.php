<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_ingreso_table extends CI_Model
{
    public function get_table_headers()
    {

        $order = (uri_assoc('order')) == 'asc' ? 'desc' : 'asc';

        $headers = array(
        'concepto' => anchor('ingreso/index/order_by/concepto/order/'.$order, 'concepto'),
        'importe' => anchor('ingreso/index/order_by/importe/order/'.$order, 'importe (S./)'),
        'fecha' => anchor('ingreso/index/order_by/fecha/order/'.$order, 'fecha'),
        );


        return $headers;
    }
}

?>
