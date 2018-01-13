<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Operador extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_operador');
    }

    public function index()
    {
        $this->load->model('mdl_operador_table');
        $this->mdl_operador->default_limit = $this->config->item('results_per_page');
        $this->mdl_operador->order_by = uri_assoc('order_by');
        $this->mdl_operador->order = uri_assoc('order');

        $data = array(
            'operador' => $this->mdl_operador->paginate()->result(),
            'table_headers' => $this->mdl_operador_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Operador');
        $this->template->write('title', 'Listado de Operador');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form()
    {
        $id = uri_assoc('id');
        if ($this->mdl_operador->run_validation()) {
            $id = $this->mdl_operador->save($id);
            /*redirect('operador/form/id/' . $id);*/
            redirect('operador/index');

        } else {
            $this->mdl_operador->prep_form($id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "operador/validate_operador.js");
            $this->template->write('header_title', 'Administrar Operador');
            $this->template->write('title', 'Administrar Operador');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }


    public function login()
    {
        if ($this->mdl_operador->run_validation('validar_login')) {
            $params = array(
                'email' => $this->input->post('email'),
                'clave' => $this->input->post('clave')
            );
            $this->mdl_operador->login($params);
            // si no hubo errores en el login entonces redirecciona al dashboard inicio
            redirect('/');
        }
        $css_inline = "
            .row, .row-fluid {
                margin: 0 0 20px;
            }
            footer, hr {
                position: absolute;
                bottom: 0;
            }
        ";
        $this->template->set_template('administrador_login');
        $this->template->add_css($css_inline, "embed");
        $this->template->write('title', 'Ingresar');
        $this->template->write_view('content', 'vista_login');
        $this->template->render();
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('custom_success', 'Se cerr&oacute; su sesi&oacute;n con &eacute;xito');
        redirect("operador/login");
    }


    public function _post_handler()
    {
        if ($this->input->post('btn_add'))
            redirect('operador/form');
        if ($this->input->post('btn_cancel'))
            redirect('operador/index');
    }

    public function delete()
    {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_operador->delete($id);
        }
        redirect('operador/index');
    }

}

?>