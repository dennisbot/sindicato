<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
* clase para llamadas asincronas que
* harán uso de la entidad proveedores
*/
class Async_proveedor extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
    }
    public function getAllProveedores()
    {
        if (!$this->input->post('ajax')) {
            $this->session->set_flashdata('custom_error', 'Usted no tiene permiso para acceder a esta sección');
            redirect();
        }
        $this->load->model('mdl_proveedor');
        echo json_encode(array('result' => $this->mdl_proveedor->getAllProveedores()));
    }
}