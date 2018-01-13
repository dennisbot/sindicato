<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comision_sindicato extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_comision_sindicato');
    }

    public function index() {
        $this->load->model('mdl_comision_sindicato_table');
        $this->mdl_comision_sindicato->default_limit = $this->config->item('results_per_page');

        $this->mdl_comision_sindicato->order_by = uri_assoc('order_by');
        $this->mdl_comision_sindicato->order = uri_assoc('order');

        $data = array(
            'comision_sindicatos' => $this->mdl_comision_sindicato->paginate()->result(),
            'table_headers' => $this->mdl_comision_sindicato_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Comision_sindicato');
        $this->template->write('title', 'Listado de Comision_sindicato');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form() {
        $proveedor_id = uri_assoc('proveedor_id');
        if ($this->mdl_comision_sindicato->run_validation()) {
            $proveedor_id = $this->mdl_comision_sindicato->save($proveedor_id);
            /*redirect('comision_sindicato/form/proveedor_id/' . $proveedor_id);*/
            redirect('comision_sindicato/index');

        } else {
            $this->mdl_comision_sindicato->prep_form($proveedor_id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "comision_sindicato/validate_comision_sindicato.js");
            $this->template->write('header_title', 'Administrar Comision_sindicato');
            $this->template->write('title', 'Administrar Comision_sindicato');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('comision_sindicato/form');
        if ($this->input->post('btn_cancel'))
            redirect('comision_sindicato/index');
    }

    public function delete() {
        $proveedor_id = uri_assoc('proveedor_id');
        if ($proveedor_id) {
            $this->mdl_comision_sindicato->delete($proveedor_id);
        }
        redirect('comision_sindicato/index');
    }

}

?>