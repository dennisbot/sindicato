<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Proveedor extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_proveedor');
        $this->load->model('comision_publicacion/mdl_comision_publicacion');
    }

    public function index() {
        $this->load->model('mdl_proveedor_table');
        $this->mdl_proveedor->default_limit = $this->config->item('results_per_page');

        $this->mdl_proveedor->order_by = uri_assoc('order_by');
        $this->mdl_proveedor->order = uri_assoc('order');

        $data = array(
            'proveedors' => $this->mdl_proveedor->paginate()->result(),
            'table_headers' => $this->mdl_proveedor_table->get_table_headers()
        );

        /*
         * assets
         */
        /*
        $this->template->add_css('nombre_archivo.css', 'link', false);
        $this->template->add_js('nombre_archivo.js', 'import', false);
        $javascript_inline = '
            $(".clase").accion({
              //operaciones
            })
        ';
        $this->template->add_js($javascript_inline, 'embed', false);
        */

        /*
         * template
         */
        $this->template->write('header_title', 'Listado de Proveedor');
        $this->template->write('title', 'Listado de Proveedor');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form() {
        $id = uri_assoc('id');
        if ($this->mdl_proveedor->run_validation()) {
            $id = $this->mdl_proveedor->save($id);
            /*redirect('proveedor/form/id/' . $id);*/
            redirect('proveedor/index');

        } else {
            $this->mdl_proveedor->prep_form($id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/jquery.maskMoney.min.js");
            $javascript_inline = "              
                $('.currency').maskMoney({
                    precision: 0,
                    defaultZero: false,
                    thousands: ''
                });
         
            ";
            $this->template->add_js($javascript_inline, 'embed', false);
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "proveedor/validate_proveedor.js");
            $this->template->write('header_title', 'Administrar Proveedor');
            $this->template->write('title', 'Administrar Proveedor');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('proveedor/form');
        if ($this->input->post('btn_cancel'))
            redirect('proveedor/index');
    }

    public function delete() {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_proveedor->delete($id);
        }
        redirect('proveedor/index');
    }
    public function getPublicacionesByProveedor($proveedor_id)
    {
        
        /* nos aseguramos que solo estan consultado por ajax */
        // ($this->input->post('ajax')) or redirect();
        $p = $this->mdl_proveedor->getPublicacionesByProveedor($proveedor_id);
        $publicaciones = array();
        foreach ($p as $publicacion) {
            $publicaciones[$publicacion->id] = $publicacion->nombre;
        }
        echo json_encode(array('publicaciones' => $publicaciones));
    }

}

?>