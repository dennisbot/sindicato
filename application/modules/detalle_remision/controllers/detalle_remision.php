<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Detalle_remision extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_detalle_remision');
    }

    public function index() {
        $this->load->model('mdl_detalle_remision_table');
        $this->mdl_detalle_remision->default_limit = $this->config->item('results_per_page');

        $this->mdl_detalle_remision->order_by = uri_assoc('order_by');
        $this->mdl_detalle_remision->order = uri_assoc('order');

        $data = array(
            'detalle_remisions' => $this->mdl_detalle_remision->paginate()->result(),
            'table_headers' => $this->mdl_detalle_remision_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Detalle_remision');
        $this->template->write('title', 'Listado de Detalle_remision');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form() {
        $publicacion_id = uri_assoc('publicacion_id');
        if ($this->mdl_detalle_remision->run_validation()) {
            $publicacion_id = $this->mdl_detalle_remision->save($publicacion_id);
            /*redirect('detalle_remision/form/publicacion_id/' . $publicacion_id);*/
            redirect('detalle_remision/index');

        } else {
            $this->mdl_detalle_remision->prep_form($publicacion_id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "detalle_remision/validate_detalle_remision.js");
            $this->template->write('header_title', 'Administrar Detalle_remision');
            $this->template->write('title', 'Administrar Detalle_remision');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('detalle_remision/form');
        if ($this->input->post('btn_cancel'))
            redirect('detalle_remision/index');
    }

    public function delete() {
        $publicacion_id = uri_assoc('publicacion_id');
        if ($publicacion_id) {
            $this->mdl_detalle_remision->delete($publicacion_id);
        }
        redirect('detalle_remision/index');
    }

}

?>