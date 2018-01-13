<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
* clase para acceder de manera asincrona a servicios de vendedor
*/
class Async_vendedor extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_vendedor');
    }
    public function getAllVendedores()
    {
        if (!$this->input->post('ajax')) {
            $this->session->set_flashdata('custom_error', 'Usted no tiene permiso para acceder a esta sección');
            redirect();
        }
        echo json_encode(array('result' => $this->mdl_vendedor->getVendedoresNoSuplentesCombo()));
    }
    public function getDeudasVendedor()
    {
        // exit('dsafsdf');
        if (!$this->input->post('ajax')) {
            $this->session->set_flashdata('custom_error', 'Usted no tiene permiso para acceder a esta sección');
            redirect();
        }
        $vendedor_id = $this->input->post('vendedor_id');
        echo json_encode(array('result' => $this->mdl_vendedor->getDeudasVendedor($vendedor_id)));
        FB::log($this->db->last_query(), "last query:\n");
    }
}