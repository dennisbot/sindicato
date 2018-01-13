<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ingreso extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_ingreso');
    }

    public function index() {
        $this->load->model('mdl_ingreso_table');
        $this->mdl_ingreso->default_limit = $this->config->item('results_per_page');
        $this->mdl_ingreso->order_by = uri_assoc('order_by');
        $this->mdl_ingreso->order = uri_assoc('order');
        $data = array(
            'ingresos' => $this->mdl_ingreso->paginate()->result(),
            'table_headers' => $this->mdl_ingreso_table->get_table_headers()
        );
        $this->template->write('header_title', 'Listado de Ingresos');
        $this->template->write('title', 'Listado de Ingresos');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }
    public function form()
    {
        // $javascript_inline = 'CKEDITOR.replace("concepto", {toolbar:"Miconfig"}); ';
        // $this->template->add_js('../assets/js/ckeditor/ckeditor.js', 'import', false, 'footer');
        // $this->template->add_js($javascript_inline, 'embed', false);
        $id = uri_assoc('id');
        if ($this->mdl_ingreso->run_validation()) {
            $this->mdl_ingreso->save($id, $db_array);
            redirect('ingreso/index');
        } else {
            $this->mdl_ingreso->prep_form($id);

            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . 'js/ckeditor/ckeditor.js');
            $this->template->add_js(public_url() . "jquery/jquery.maskMoney.min.js");

            $this->template->add_css(public_url().'bootstrap/datepicker/css/datepicker.css');
            $this->template->add_js(public_url().'bootstrap/datepicker/js/bootstrap-datepicker.js');
            $this->template->add_js(public_url().'bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js');

            $javascript_inline = "
                $('.datepicker').datepicker({
                    language: 'es',
                    minViewMode: 'days',
                    autoclose: 'true',
                    format: 'dd/mm/yyyy',
                    endDate: '0d'
                });
                $('.currency').maskMoney({
                    precision: 2,
                    defaultZero: false,
                    thousands:'',
                    decimal:'.'
                });
                CKEDITOR.replace('concepto', {toolbar:'Miconfig'});
            ";
            $this->template->add_js($javascript_inline,'embed');
            $css_inline = "
                input[name='fecha'] {
                    width: 176px;
                    background: #fff;
                    cursor: pointer;
                }
                input[name='importe'] {
                    text-align: left;
                }
                .controles {
                    width: 220px;
                    display: inline-block;
                    background-color: whitesmoke;
                }
            ";
            $this->template->add_css($css_inline, "embed");
            $this->template->write('header_title', 'Gestionar Ingresos');
            $this->template->write('title', 'Gestionar Ingresos');
            $this->template->write_view('content', 'form');
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('ingreso/form');
        if ($this->input->post('btn_cancel'))
            redirect('ingreso/index');
    }

    public function delete() {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_ingreso->delete($id);
        }
        redirect('ingreso/index');

    }

    public function balance_publicacion()
    {
        $this->load->model('detalle_remision/mdl_detalle_remision');
        $this->load->model('deuda/mdl_deuda');
        $this->load->model('pago/mdl_pago');
        $this->load->model('detalle_pauta/mdl_detalle_pauta');

        /* ingresos de los pagos de las remisiones (sindicato) */
        $remisiones_publicacion = $this->mdl_detalle_remision->get()->result();

        /* ingresos de los pagos de las pautas (vendedores) */
        $fecha_ini = uri_assoc('fecha_ini');
        $fecah_fin = uri_assoc('fecha_fin');
        $publicacion_id = uri_assoc('publicacion');
        if ($publicacion_id) {
            $pautas = $this->mdl_detalle_pauta->where('publicacion_id', $publicacion_id)->get()->result();
            for ($i=0; $i < count($pautas); $i++)
            {
                $pautas[$i]->deudas = $this->mdl_deuda->where('detalle_pauta_id', $pautas[$i]->id)->get()->result();
                /*for ($i=0; $i < count($pautas[$i]->deudas); $i++)
                {
                    $pauta[$i]->deudas[$j]->pagos = $this->mdl_pago->where('deuda_id', $deuda[$i]->id)->get()->result();
                }            */
            }
        }

        var_dump($pautas);


        $this->template->write('header_title', 'Balance General');
        $this->template->write('title', 'Balance General');
        $this->template->write_view('content', 'balance_publicacion');
        $this->template->render();
    }

    function form_ingresos()
    {
        $this->load->model('remision/mdl_remision');
        $this->load->model('detalle_remision/mdl_detalle_remision');
        $this->load->model('deuda/mdl_deuda');
        $this->load->model('pago/mdl_pago');
        $this->load->model('detalle_pauta/mdl_detalle_pauta');
        $this->load->helper('fechas');

    	$data['ingresos'] = array();
        if($this->input->post('btn_consulta')){
			$fecha_inicio = strtotime(standardize_date($this->input->post('fecha_inicio')));
			$fecha_fin = strtotime(standardize_date($this->input->post('fecha_fin')));
			$data['fecha_inicio'] = format_date_to_show($fecha_inicio);
			$data['fecha_fin'] = format_date_to_show($fecha_fin);
			$data['ingresos'] = $this->mdl_ingreso->ingresos_por_fecha($fecha_inicio, $fecha_fin);
			$suma = $this->mdl_ingreso->ingresos_monto_total($fecha_inicio, $fecha_fin);
			$data['suma_monto'] = $suma->ganancia;
			//echo $fecha_inicio.' - '.$fecha_fin;
        }

		//calendario
        $this->template->add_css(public_url() . "bootstrap/datepicker/css/datepicker.css");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/bootstrap-datepicker.js");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js");

        $javascript_inline = "
            //$('#alert').hide();
            //var startDate = new Date();
			//var curr_date = startDate.getDate();
			//var curr_month = startDate.getMonth() + 1;
			//var curr_year = startDate.getFullYear();
			//startDate = (curr_date < 10 ? '0' : '') + curr_date + '/' + (curr_month < 10 ? '0' : '') + curr_month + '/'  + curr_year;

            $('.calendari, .calendarf').datepicker({
            	setDate: new Date(),
                language: 'es',
                autoclose: 'true',
                startDate: '',
                endDate: '',
                format: 'dd/mm/yyyy',
                todayBtn: 'linked',
                todayHighlight: 'true'
            });
				var startDate = new Date('01/01/2012');
				var FromEndDate = new Date();
				var ToEndDate = new Date();
				ToEndDate.setDate(ToEndDate.getDate() + 365);

				$('.calendari').datepicker({
				    weekStart: 1,
				    startDate: '01/01/2012',
				    endDate: FromEndDate,
				    autoclose: true
				})
				    .on('changeDate', function(selected){
				        startDate = new Date(selected.date.valueOf());
				        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
				        $('.calendarf').datepicker('setStartDate', startDate);
				    });
				$('.calendarf')
				    .datepicker({
				        weekStart: 1,
				        startDate: startDate,
				        endDate: ToEndDate,
				        autoclose: true
				    })
				    .on('changeDate', function(selected){
				        FromEndDate = new Date(selected.date.valueOf());
				        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
				        $('.calendari').datepicker('setEndDate', FromEndDate);
				    });
        ";

        $this->template->add_js($javascript_inline, 'embed', false);
        $this->template->write('header_title', 'Ingresos por fecha');
        $this->template->write('title', 'Ingresos por fecha');
        $this->template->write_view('content', 'form_ingreso', $data);
        $this->template->render();

    }

}

?>