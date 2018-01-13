<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Descuento_publicacion extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_descuento_publicacion');
    }

    public function index() {
        $this->load->model('mdl_descuento_publicacion_table');
        $this->mdl_descuento_publicacion->default_limit = $this->config->item('results_per_page');

        $this->mdl_descuento_publicacion->order_by = uri_assoc('order_by');
        $this->mdl_descuento_publicacion->order = uri_assoc('order');

        $data = array(
            'descuento_publicacions' => $this->mdl_descuento_publicacion->paginate()->result(),
            'table_headers' => $this->mdl_descuento_publicacion_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Descuento_publicacion');
        $this->template->write('title', 'Listado de Descuento_publicacion');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form() {
        $dia_descuento_id = uri_assoc('dia_descuento_id');
        if ($this->mdl_descuento_publicacion->run_validation()) {
            $dia_descuento_id = $this->mdl_descuento_publicacion->save($dia_descuento_id);
            /*redirect('descuento_publicacion/form/dia_descuento_id/' . $dia_descuento_id);*/
            redirect('descuento_publicacion/index');

        } else {
            $this->mdl_descuento_publicacion->prep_form($dia_descuento_id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "descuento_publicacion/validate_descuento_publicacion.js");
            $this->template->write('header_title', 'Administrar Descuento_publicacion');
            $this->template->write('title', 'Administrar Descuento_publicacion');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('descuento_publicacion/form');
        if ($this->input->post('btn_cancel'))
            redirect('descuento_publicacion/index');
    }

    public function delete() {
        $dia_descuento_id = uri_assoc('dia_descuento_id');
        if ($dia_descuento_id) {
            $this->mdl_descuento_publicacion->delete($dia_descuento_id);
        }
        redirect('descuento_publicacion/index');
    }

}

?>