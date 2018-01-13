<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cobranza extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
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
            .td-cantidad-dev.saldo>input[type='text'] {
                background-color: white;
                cursor: pointer;
            }
        ";
        $this->template->add_css(public_url() . 'chosen/chosen/chosen.css');
        $this->template->add_js(public_url() . 'chosen/chosen/chosen.jquery.min.js');
        $this->template->add_js(public_url() . 'js/mustache.min.js');
        /* bootbox */
        $this->template->add_js(bootstrap_js() . 'bootbox/bootbox.min.js');
        /* archivos personalizados para este controlador */
        $this->template->add_css(public_url() . 'cobranza/css/index.css');
        $this->template->add_js(public_url() . 'cobranza/js/cobranza.js');

        $this->template->add_js(public_url() . 'jquery/jquery.maskMoney.min.js');
        $this->template->add_css($css_inline, "embed");
        $this->template->write('header_title', 'Cobranzas');
        $this->template->write('title', 'Cobranzas');
        $this->template->write_view('content', 'cobranza', $data);
        $this->template->render();
    }
}