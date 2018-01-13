<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comision_publicacion extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_comision_publicacion');
    }

    public function index()
    {
        $this->load->model('mdl_comision_publicacion_table');
        $this->mdl_comision_publicacion->default_limit = $this->config->item('results_per_page');

        $this->mdl_comision_publicacion->order_by = uri_assoc('order_by');
        $this->mdl_comision_publicacion->order = uri_assoc('order');

        $data = array(
            'comision_publicaciones' => $this->mdl_comision_publicacion->paginate()->result(),
            'table_headers' => $this->mdl_comision_publicacion_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Comision_publicacion');
        $this->template->write('title', 'Listado de Comision_publicacion');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form()
    {
        $id = uri_assoc('id');
        if ($this->mdl_comision_publicacion->run_validation()) {
            $id = $this->mdl_comision_publicacion->save($id);
            /*redirect('comision_publicacion/form/id/' . $id);*/
            redirect('comision_publicacion/index');

        } else {
            $this->mdl_comision_publicacion->prep_form($id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "comision_publicacion/validate_comision_publicacion.js");
            $this->template->write('header_title', 'Administrar Comision_publicacion');
            $this->template->write('title', 'Administrar Comision_publicacion');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler()
    {
        if ($this->input->post('btn_add'))
            redirect('comision_publicacion/form');
        if ($this->input->post('btn_cancel'))
            redirect('comision_publicacion/index');
    }

    public function delete()
    {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_comision_publicacion->delete($id);
        }
        redirect('comision_publicacion/index');
    }

    public function comision_by_publicacion_dia()
    {
        $publicacion_id = uri_assoc('publicacion');
        $dia = uri_assoc('dia');

        if( $publicacion_id != '' && $dia != '' ){
            echo $this->mdl_comision_publicacion->get_comision($publicacion_id, $dia);
        }
    }

}

?>