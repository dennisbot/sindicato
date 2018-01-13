<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Devolucion extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_devolucion');
        $this->load->model('vendedor/mdl_vendedor');
    }

    public function index() {
        $this->load->model('mdl_devolucion_table');
        $this->mdl_devolucion->default_limit = $this->config->item('results_per_page');

        $this->mdl_devolucion->order_by = uri_assoc('order_by');
        $this->mdl_devolucion->order = uri_assoc('order');

        $data = array(
            'devolucions' => $this->mdl_devolucion->paginate()->result(),
            'table_headers' => $this->mdl_devolucion_table->get_table_headers()
        );
        $this->template->write('header_title', 'Listado de Devolucion');
        $this->template->write('title', 'Listado de Devolucion');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form() {
        $vendedor_id = uri_assoc('vendedor_id');
        if ($this->mdl_devolucion->run_validation()) {
            $vendedor_id = $this->mdl_devolucion->save($vendedor_id);
            redirect('devolucion/index');

        } else {
            $this->mdl_devolucion->prep_form($vendedor_id);
            $data['vendedores'] = $this->mdl_vendedor->select('id, nombres, apellidos, nickname')->get()->result();
            print_r($data['vendedores']);

            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "devolucion/validate_devolucion.js");
            $this->template->write('header_title', 'Administrar Devolucion');
            $this->template->write('title', 'Administrar Devolucion');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('devolucion/form');
        if ($this->input->post('btn_cancel'))
            redirect('devolucion/index');
    }

    public function delete() {
        $vendedor_id = uri_assoc('vendedor_id');
        if ($vendedor_id) {
            $this->mdl_devolucion->delete($vendedor_id);
        }
        redirect('devolucion/index');
    }

}

?>