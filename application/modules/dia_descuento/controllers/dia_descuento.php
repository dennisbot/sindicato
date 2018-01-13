<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dia_descuento extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_dia_descuento');
    }

    public function index() {
        $this->load->model('mdl_dia_descuento_table');
        $this->mdl_dia_descuento->default_limit = $this->config->item('results_per_page');

        $this->mdl_dia_descuento->order_by = uri_assoc('order_by');
        $this->mdl_dia_descuento->order = uri_assoc('order');

        $data = array(
            'dia_descuentos' => $this->mdl_dia_descuento->paginate()->result(),
            'table_headers' => $this->mdl_dia_descuento_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Dia_descuento');
        $this->template->write('title', 'Listado de Dia_descuento');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form() {
        $id = uri_assoc('id');
        if ($this->mdl_dia_descuento->run_validation()) {
            $id = $this->mdl_dia_descuento->save($id);
            /*redirect('dia_descuento/form/id/' . $id);*/
            redirect('dia_descuento/index');

        } else {
            $this->mdl_dia_descuento->prep_form($id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "dia_descuento/validate_dia_descuento.js");
            $this->template->write('header_title', 'Administrar Dia_descuento');
            $this->template->write('title', 'Administrar Dia_descuento');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('dia_descuento/form');
        if ($this->input->post('btn_cancel'))
            redirect('dia_descuento/index');
    }

    public function delete() {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_dia_descuento->delete($id);
        }
        redirect('dia_descuento/index');
    }

}

?>