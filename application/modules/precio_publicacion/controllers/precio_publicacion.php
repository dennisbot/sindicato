<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Precio_publicacion extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_precio_publicacion');
    }

    public function index()
    {
        $this->load->model('mdl_precio_publicacion_table');
        $this->mdl_precio_publicacion->default_limit = $this->config->item('results_per_page');

        $this->mdl_precio_publicacion->order_by = uri_assoc('order_by');
        $this->mdl_precio_publicacion->order = uri_assoc('order');

        $data = array(
            'precio_publicacions' => $this->mdl_precio_publicacion->paginate()->result(),
            'table_headers' => $this->mdl_precio_publicacion_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Precio_publicacion');
        $this->template->write('title', 'Listado de Precio_publicacion');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form()
    {
        $id = uri_assoc('id');
        if ($this->mdl_precio_publicacion->run_validation()) {
            $id = $this->mdl_precio_publicacion->save($id);
            /*redirect('precio_publicacion/form/id/' . $id);*/
            redirect('precio_publicacion/index');

        } else {
            $this->mdl_precio_publicacion->prep_form($id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "precio_publicacion/validate_precio_publicacion.js");
            $this->template->write('header_title', 'Administrar Precio_publicacion');
            $this->template->write('title', 'Administrar Precio_publicacion');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler()
    {
        if ($this->input->post('btn_add'))
            redirect('precio_publicacion/form');
        if ($this->input->post('btn_cancel'))
            redirect('precio_publicacion/index');
    }

    public function delete()
    {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_precio_publicacion->delete($id);
        }
        redirect('precio_publicacion/index');
    }

    public function precio_by_publicacion_dia()
    {
        $publicacion_id = uri_assoc('publicacion');
        $dia = uri_assoc('dia');

        if( $publicacion_id != '' && $dia != '' ){
            echo $this->mdl_precio_publicacion->get_precio($publicacion_id, $dia);
        }
    }

}

?>