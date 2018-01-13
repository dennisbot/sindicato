<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pago extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_pago');
    }

    public function index() {
        $this->load->model('mdl_pago_table');
        $this->mdl_pago->default_limit = $this->config->item('results_per_page');

        $this->mdl_pago->order_by = uri_assoc('order_by');
        $this->mdl_pago->order = uri_assoc('order');

        $data = array(
            'pagos' => $this->mdl_pago->paginate()->result(),
            'table_headers' => $this->mdl_pago_table->get_table_headers()
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
        $this->template->write('header_title', 'Listado de Pago');
        $this->template->write('title', 'Listado de Pago');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form() {
        $deuda_id = uri_assoc('deuda_id');
        if ($this->mdl_pago->run_validation()) {
            $deuda_id = $this->mdl_pago->save($deuda_id);
            /*redirect('pago/form/deuda_id/' . $deuda_id);*/
            redirect('pago/index');

        } else {
            $this->mdl_pago->prep_form($deuda_id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "pago/validate_pago.js");
            $this->template->write('header_title', 'Administrar Pago');
            $this->template->write('title', 'Administrar Pago');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('pago/form');
        if ($this->input->post('btn_cancel'))
            redirect('pago/index');
    }

    public function delete() {
        $deuda_id = uri_assoc('deuda_id');
        if ($deuda_id) {
            $this->mdl_pago->delete($deuda_id);
        }
        redirect('pago/index');
    }

    public function edicion() {
        $data = array();
        $this->load->helper('form');
        $css_inline = "
            .subitems {
                margin: 5px;
            }
            .td-cantidad-dev {
              max-width:16px;
              padding:0;
            }

            .td-cantidad-dev input {
                box-sizing:border-box;
                width:100%;
                height:36px;
                text-align:right;
                margin:5.5px auto;
            }
            table.table th, table.table td {
                text-align: right;
                padding: 0 6px;
                vertical-align: middle;
            }
            .chzn-container, .chzn-drop {
                width: 100% !important;
                box-sizing: border-box;
            }
            .chzn-container b {
                margin-top: 10px;
            }
            .chzn-search input {
                width: 96% !important;
                font-weight: bolder;
                text-transform: uppercase;
            }
            .chzn-single {
                height: 40px !important;
            }
            .chzn-single > span {
                line-height: 40px !important;
            }
            #caja-publicacion {
                font-weight: bolder;
            }
        ";
        $fecha_inicio = strtotime(standardize_date($this->input->post('fecha_inicio')));
        $data['fecha_inicio'] = format_date_to_show($fecha_inicio);
		//calendario
        $this->template->add_css(public_url() . "bootstrap/datepicker/css/datepicker.css");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/bootstrap-datepicker.js");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js");

        $javascript_inline = "

        ";

        $this->template->add_js($javascript_inline, 'embed', false);
        
        $this->template->add_css(public_url() . 'chosen/chosen/chosen.css');
        $this->template->add_js(public_url() . 'chosen/chosen/chosen.jquery.min.js');
        $this->template->add_js(public_url() . 'js/mustache.min.js');
        /* archivos personalizados para este controlador */
        $this->template->add_css(public_url() . 'cobranza/css/index.css');
        $this->template->add_js(public_url() . 'pago/js/pago_edicion.js');

        $this->template->add_js(public_url() . 'jquery/jquery.maskMoney.min.js');
        $this->template->add_css($css_inline, "embed");
        $this->template->write('header_title', 'Edici&oacute;n de pagos');
        $this->template->write('title', 'Edici&oacute;n de pagos');
        $this->template->write_view('content', 'edicion_pagos', $data);
        $this->template->render();
    }

    
}

?>