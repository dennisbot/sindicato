<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendedor extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_vendedor');
        $this->load->model('publicacion/mdl_publicacion');
    }
    public function index()
    {
        $estado = uri_assoc('estado');
        $this->load->model('mdl_vendedor_table');
        $this->mdl_vendedor->default_limit = $this->config->item('results_per_page');
        $this->mdl_vendedor->order_by = uri_assoc('order_by');
        $this->mdl_vendedor->order = uri_assoc('order');
        // var_dump($this->mdl_vendedor);exit;
        $data= array();
        $data['table_headers'] = $this->mdl_vendedor_table->get_table_headers();
        $data['vendedors'] = $this->mdl_vendedor->get()->result();
        $results = $this->config->item('results_per_page_vendedor');

        if (isset($estado) ) {
            $data['vendedors'] = $this->mdl_vendedor
                            ->select('id,nombres,apellidos,dni,nickname,orden, fecha_nacimiento,estado,created_at')
                            ->where('estado',$estado)
                            ->paginate(array('where','select'),$results)
                            ->result();
        }
        else{
            $data['vendedors'] = $this->mdl_vendedor
                            ->select('id,nombres,apellidos,dni,nickname,orden, fecha_nacimiento,estado,created_at')
                            ->paginate(array('select'),$results)
                            ->result();
                            // var_dump($this->db->last_query());exit;
        }
        // var_dump($data['vendedors']);exit;
        /*
         * template
         */
        $this->template->add_css(public_url() . "vendedor/estilo.css");
        $this->template->write('header_title', 'Listado de Vendedores');
        $this->template->write('title', 'Listado de Vendedores');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }
    public function form()
    {
        $this->template->add_css(base_url() . "assets/chosen/chosen/chosen.css");
        $this->template->add_js(base_url() . "assets/chosen/chosen/chosen.jquery.min.js");
        $this->template->add_js(public_url() . "jquery/jquery.maskMoney.min.js");
        $javascript_inline = '
            $(function(){
                $(".chzn-select").chosen();
            });
            $(".numeroentero").maskMoney({
                    precision: 0,
                    defaultZero: false,
                    thousands:""
                });';
        $this->template->add_js($javascript_inline, 'embed');
        $id = uri_assoc('id');
        if ($this->mdl_vendedor->run_validation()) {

            $params = $this->mdl_vendedor->db_array();
            $params['created_at'] =  time();

            /* convertimos en mayúscula el nickname */
            $params['nickname'] = strtoupper($params['nickname']);

            $id = $this->mdl_vendedor->save($id,$params);
            redirect('vendedor/index');

        } else {
            $data = array( );
            if (isset($id)) {
                $vendedor = $this->mdl_vendedor->get_by_id($id);

                $fecha_nacimiento = $vendedor->fecha_nacimiento;
                if (strlen($fecha_nacimiento) > 0) {
                    $data['dia'] = $fecha_nacimiento[6].$fecha_nacimiento[7];
                    $data['mesf'] = $fecha_nacimiento[4].$fecha_nacimiento[5];
                    $data['anio'] = $fecha_nacimiento[0].$fecha_nacimiento[1].$fecha_nacimiento[2].$fecha_nacimiento[3];
                     // var_dump($data);
                }
            }
            $this->mdl_vendedor->prep_form($id);
            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "vendedor/validate_vendedor.js");
            $this->template->add_css(public_url() . "vendedor/estilo.css");
            $this->template->write('header_title', 'Administrar Vendedor');
            $this->template->write('title', 'Administrar Vendedor');
            $this->template->write_view('content', 'form',$data);
            $this->template->render();
        }
    }

    public function _post_handler()
    {
        if ($this->input->post('btn_add'))
            redirect('vendedor/form');
        if ($this->input->post('btn_cancel'))
            redirect('vendedor/index');
    }
    public function test()
    {
        //$res = $this->mdl_vendedor->getDeudasVendedor(79);
        // FB::log($this->mdl_vendedor->last_query(), "last query:\n");
        //var_dump($res);
        echo 'hola '. standardize_date('1377493200');
        echo 'hola 2'. date('d-m-Y'. '1377493200');
    }
    public function delete()
    {
        $id = uri_assoc('id');
        if ($id)
            $this->mdl_vendedor->delete($id);

        redirect('vendedor/index');
    }
    public function cambiar_estado($id,$estado,$estado_actual)
    {
        // var_dump($estado_actual);exit();

        $data['estado'] = $estado;
        $this->mdl_vendedor->save($id,$data);
        $result = $this->db->affected_rows();
        if ($estado_actual!="")
            redirect('vendedor/index/estado/'. $estado_actual);
        else
            redirect('vendedor/index');
    }
    public function ordenar()
    {
        if ($vendedores = $this->input->post('vendedores')) {
            $this->mdl_vendedor->update_orden($vendedores);
            $this->session->set_flashdata('custom_success', 'Las posiciones fueron actualizadas con éxito');
            redirect('vendedor/ordenar', 'refresh');
        }
        $this->template->add_css(base_jquery() . "css/ui/simple/jquery-ui-1.8.23.custom.css");
        $this->template->add_js(base_jquery() . "js/jquery-ui-1.10.1.custom.min.js");
        $this->template->add_js(public_url() . "multiselect/js/ui.multiselect.bot.js");
        $this->template->add_js(public_url() . "multiselect/js/locale/ui-multiselect-es.js");
        $this->template->add_css(public_url()  . "multiselect/css/ui.multiselect.css");
        $javascript_inline = "
            $('.multiselect').multiselect({language: 'es'});
        ";
        $this->template->add_js($javascript_inline, "embed");
        $css_inline = "/* multiselect styles */
            .multiselect {
                width: 380px !important;
                height: 440px !important;
            }
        ";
        $this->template->add_css($css_inline, "embed");

        $data['vendedores'] = $this->mdl_vendedor->getAllVendedoresCombo(false);
        $this->template->write('title', 'Orden de reparto a los Vendedores');
        $this->template->write_view('content', 'sort', $data);
        $this->template->render();
    }

    public function deudores()
    {
        // var_dump($data['vendedors']); exit();
        $this->load->model('mdl_vendedor_table');
        $this->mdl_vendedor->default_limit = $this->config->item('results_per_page');

        $this->mdl_vendedor->order_by = uri_assoc('order_by');
        $this->mdl_vendedor->order = uri_assoc('order');

        $data['deudores'] = "";

        if($this->input->post('btn_consulta')){
        	$fecha_inicio = strtotime(standardize_date($this->input->post('fecha_inicio')));
        	$id = $this->input->post('publicacion');

			//$fecha_fin = strtotime(standardize_date($this->input->post('fecha_fin')));
			$data['fecha_inicio'] = format_date_to_show($fecha_inicio);
			$data['deudores'] = $this->mdl_vendedor->deudores($fecha_inicio, $id);
			$data['publi'] = $id;

			//print_r($data['deudores']);

        }

        $data['publicaciones'] = $this->mdl_publicacion->select('id, nombre')->where('tipo_publicacion', 'periodico')->order_by('nombre', 'asc')->get()->result();


		//calendario
        $this->template->add_css(public_url() . "bootstrap/datepicker/css/datepicker.css");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/bootstrap-datepicker.js");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js");

        $javascript_inline = "

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
        $this->template->write('header_title', 'Listado de deudores');
        $this->template->write('title', 'Listado de deudores');
        $this->template->write_view('content', 'deudores', $data);
        $this->template->render();
    }


    public function pagos()
    {
        $this->load->model('remision/mdl_remision');
        $this->load->model('detalle_remision/mdl_detalle_remision');
        $this->load->model('deuda/mdl_deuda');
        $this->load->model('pago/mdl_pago');
        $this->load->model('detalle_pauta/mdl_detalle_pauta');
        $this->load->helper('fechas');

    	$data['pagos'] = array();
        if($this->input->post('btn_consulta')){
			$fecha_inicio = strtotime(standardize_date($this->input->post('fecha_inicio')));
			$data['fecha_inicio'] = format_date_to_show($fecha_inicio);
			$data['pagos'] = $this->mdl_vendedor->pagos_por_fecha($this->input->post('fecha_inicio'));
			//$suma = $this->mdl_ingreso->ingresos_monto_total($fecha_inicio, $fecha_fin);
			//$data['suma_monto'] = $suma->ganancia;
			//echo $fecha_inicio.' - '.$fecha_fin;
        }

		//calendario
        $this->template->add_css(public_url() . "bootstrap/datepicker/css/datepicker.css");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/bootstrap-datepicker.js");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js");

        $javascript_inline = "
            $('.calendari').datepicker({
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

        ";

        $this->template->add_js($javascript_inline, 'embed', false);
        $this->template->write('header_title', 'Pagos de vendedores por fecha');
        $this->template->write('title', 'Pagos de vendedores por fecha');
        $this->template->write_view('content', 'form_pagos', $data);
        $this->template->render();
    }

    function hola()
    {
        $vendedor_id = 4;
        $curdate_timestamp = strtotime('2014-05-16');
        var_dump("\$vendedor_id ", $vendedor_id);
        var_dump("\$curdate_timestamp ", $curdate_timestamp);
        // exit;
        $res = $this->mdl_vendedor->getPagados($vendedor_id, $curdate_timestamp);
        var_dump($this->db->last_query());
        var_dump($res);
        exit;
    	//echo format_date_to_show('1398126106');
    	$fecha = strtotime(standardize_date('15/04/2014'));
    	//echo format_date_to_show('1398056400');
    	echo '<br />';
    	echo time();
    	echo '<br />';
    	echo $fecha;
    }

    public function imprimir($vendedor_id, $curdate_timestamp)
    {
        // die(var_dump($this->filltest()));
        $data['curdate'] = $curdate_timestamp;
        $this->load->model('vendedor/mdl_vendedor');
        $data['registro_pagos'] = $this->mdl_vendedor->getPagados($vendedor_id, $curdate_timestamp);
        die(var_dump($data['registro_pagos']));
        $data['revistas_maniana'] = $this->mdl_vendedor->getDeudasVendedorRevistas($vendedor_id, $curdate_timestamp + 24*60*60);
        // die(var_dump($data['revistas_maniana']));
        // $data['registro_pagos'] = $this->filltest();

        $result = $this->db->query('select count(*) existe from orden where fecha=?', array($curdate_timestamp));
        $orden = 0;
        if ($result->row()->existe > 0) {
            $this->db->query('update orden set idorden = idorden + 1 where fecha=?', array($curdate_timestamp));
            $result = $this->db->query('select idorden from orden where fecha=?', array($curdate_timestamp));
            $orden = $result->row()->idorden;
        }
        else {
            $this->db->query('insert into orden values(?, ?)', array(1, $curdate_timestamp));
            $orden = 1;
        }
        $data['orden'] = $orden;
        $vendedor = $this->mdl_vendedor->get_by_id($vendedor_id);
        $data['vendedor'] = $vendedor;
        $this->load->helper('fechas');
        // die(var_dump($vendedor));
        // $pagados = $this->mdl_vendedor->getPagados($vendedor_id, $curdate_timestamp);
        // die(var_dump($pagados));
        $this->load->view('print_pagados', $data);
    }
    public function filltest()
    {
        return array (
             (object)array (
                  'estado_remision' => 'pendiente',
                  'dpid' => '46900',
                  'estado' => 'pagado',
                  'precio_vendedor' => '0.350',
                  'nickname' => 'ESTANISLAO',
                  'cantidad' => '70',
                  'monto_deuda' => '17.500',
                  'abonado' => '17.500',
                  'saldo' => '0.000',
                  'nombre' => 'OJO',
                  'shortname' => 'OJ',
                  'tipo_publicacion' => 'periodico',
                  'fecha' => '27/04/2014',
                  'fecha_ordenar' => '1398574800',
                  'cantidad_devolucion' => '20',
              ),
              (object)array (
                  'estado_remision' => 'pendiente',
                  'dpid' => '46972',
                  'estado' => 'pagado',
                  'precio_vendedor' => '0.325',
                  'nickname' => 'ESTANISLAO',
                  'cantidad' => '5',
                  'monto_deuda' => '0.975',
                  'abonado' => '0.975',
                  'saldo' => '0.000',
                  'nombre' => 'la revista mas larga del mundo #12',
                  'shortname' => '',
                  'tipo_publicacion' => 'revista',
                  'fecha' => '27/04/2014',
                  'fecha_ordenar' => '1398574800',
                  'cantidad_devolucion' => '2',
              ),
              (object)array (
                  'estado_remision' => 'pendiente',
                  'dpid' => '47044',
                  'estado' => 'pagado',
                  'precio_vendedor' => '0.350',
                  'nickname' => 'ESTANISLAO',
                  'cantidad' => '10',
                  'monto_deuda' => '1.750',
                  'abonado' => '1.750',
                  'saldo' => '0.000',
                  'nombre' => 'Correo',
                  'shortname' => 'CR',
                  'tipo_publicacion' => 'periodico',
                  'fecha' => '27/04/2014',
                  'fecha_ordenar' => '1398574800',
                  'cantidad_devolucion' => '5',
              ),
            );
        }
        public function ticket()
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
                .calendari .datepicker {
                    background-color: white;
                    cursor: pointer;
                }
            ";
            // el datepicker
            $this->template->add_css(public_url() . "bootstrap/datepicker/css/datepicker.css");
            $this->template->add_js(public_url() . "bootstrap/datepicker/js/bootstrap-datepicker.js");
            $this->template->add_js(public_url() . "bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js");

            $this->template->add_css(public_url() . 'chosen/chosen/chosen.css');
            $this->template->add_js(public_url() . 'chosen/chosen/chosen.jquery.min.js');
            $this->template->add_js(public_url() . 'js/mustache.min.js');
            /* bootbox */
            $this->template->add_js(bootstrap_js() . 'bootbox/bootbox.min.js');
            /* archivos personalizados para este controlador */
            $this->template->add_css(public_url() . 'cobranza/css/index.css');
            $this->template->add_js(public_url() . 'vendedor/js/ticket.js');

            $this->template->add_js(public_url() . 'jquery/jquery.maskMoney.min.js');
            $this->template->add_css($css_inline, "embed");
            $this->template->write('header_title', 'Ticket');
            $this->template->write('title', 'Ticket');
            $this->template->write_view('content', 'cobranza', $data);
            $this->template->render();
        }

}

?>