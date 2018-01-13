<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Descripcion_tipo_plantilla extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_descripcion_tipo_plantilla');
    }

    public function index()
    {
        $this->load->model('mdl_descripcion_tipo_plantilla_table');
        $this->mdl_descripcion_tipo_plantilla->default_limit = $this->config->item('results_per_page');

        $this->mdl_descripcion_tipo_plantilla->order_by = uri_assoc('order_by');
        $this->mdl_descripcion_tipo_plantilla->order = uri_assoc('order');

        $data = array(
            'descripcion_tipo_plantillas' => $this->mdl_descripcion_tipo_plantilla->paginate()->result(),
            'table_headers' => $this->mdl_descripcion_tipo_plantilla_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Descripcion de tipo de Plantilla');
        $this->template->write('title', 'Listado de Descripcion de tipo de Plantilla');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form()
    {
        $iddescripcion = uri_assoc('iddescripcion');
        if ($this->mdl_descripcion_tipo_plantilla->run_validation()) {
            $iddescripcion = $this->mdl_descripcion_tipo_plantilla->save($iddescripcion);
            /*redirect('descripcion_tipo_plantilla/form/iddescripcion/' . $iddescripcion);*/
            redirect('descripcion_tipo_plantilla/index');

        } else {
            $this->mdl_descripcion_tipo_plantilla->prep_form($iddescripcion);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "descripcion_tipo_plantilla/validate_descripcion_tipo_plantilla.js");
            $this->template->write('header_title', 'Administrar Descripción de tipo de Plantilla');
            $this->template->write('title', 'Administrar Descripción de tipo de Plantilla');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler()
    {
        if ($this->input->post('btn_add'))
            redirect('descripcion_tipo_plantilla/form');
        if ($this->input->post('btn_cancel'))
            redirect('descripcion_tipo_plantilla/index');
    }

    public function delete()
    {
        $iddescripcion = uri_assoc('iddescripcion');
        if ($iddescripcion) {
            $this->mdl_descripcion_tipo_plantilla->delete($iddescripcion);
        }
        redirect('descripcion_tipo_plantilla/index');
    }

}

?>