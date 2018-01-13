<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Deuda extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_deuda');
    }

    public function index() {
        $this->load->model('mdl_deuda_table');
        $this->mdl_deuda->default_limit = $this->config->item('results_per_page');

        $this->mdl_deuda->order_by = uri_assoc('order_by');
        $this->mdl_deuda->order = uri_assoc('order');

        $data = array(
            'deudas' => $this->mdl_deuda->paginate()->result(),
            'table_headers' => $this->mdl_deuda_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Deuda');
        $this->template->write('title', 'Listado de Deuda');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form() {
        $pauta_id = uri_assoc('pauta_id');
        if ($this->mdl_deuda->run_validation()) {
            $pauta_id = $this->mdl_deuda->save($pauta_id);
            /*redirect('deuda/form/pauta_id/' . $pauta_id);*/
            redirect('deuda/index');

        } else {
            $this->mdl_deuda->prep_form($pauta_id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "deuda/validate_deuda.js");
            $this->template->write('header_title', 'Administrar Deuda');
            $this->template->write('title', 'Administrar Deuda');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('deuda/form');
        if ($this->input->post('btn_cancel'))
            redirect('deuda/index');
    }

    public function delete() {
        $pauta_id = uri_assoc('pauta_id');
        if ($pauta_id) {
            $this->mdl_deuda->delete($pauta_id);
        }
        redirect('deuda/index');
    }

}

?>