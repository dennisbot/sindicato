<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Publicacion extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_publicacion');
        $this->load->model('dia_descuento/mdl_dia_descuento');
        $this->load->model('descuento_publicacion/mdl_descuento_publicacion');
        $this->load->model('precio_publicacion/mdl_precio_publicacion');
        $this->load->model('comision_publicacion/mdl_comision_publicacion');
    }

    public function index()
    {
        $idproveedor = uri_assoc('proveedor');
        $this->load->model('mdl_publicacion_table');
        $this->load->model('proveedor/mdl_proveedor');
        $this->mdl_publicacion->default_limit = $this->config->item('results_per_page');
        $this->mdl_publicacion->order_by = uri_assoc('order_by');
        $this->mdl_publicacion->order = uri_assoc('order');

        //chosen para las publicaciones por proveedor
        $this->template->add_css(base_url() . "assets/chosen/chosen/chosen.css");
        $this->template->add_js(base_url() . "assets/chosen/chosen/chosen.jquery.min.js");
        $javascript_inline = '
                var BASE_URL ="'.base_url().'";
                $(function(){
                    $(".chzn-select").chosen();
                });';
        $this->template->add_js($javascript_inline, 'embed');
        $data = array();

        $data['table_headers'] = $this->mdl_publicacion_table->get_table_headers();
        $data['proveedores'] = $this->mdl_proveedor->get()->result();

        if (isset($idproveedor) == false) {
            $data['publicacions'] = $this->mdl_publicacion->paginate(array('select'), $this->config->item('results_per_page_publicacion'))->result();
        }
        else
        {
            // $data['publicacions'] = $this->mdl_publicacion->where('proveedor_id',$idproveedor)->paginate(array('where'))->result();
            $data['publicacions'] = $this->mdl_publicacion->where('proveedor_id',$idproveedor)->get()->result();
            $data['proveedor_actual'] = $idproveedor;
        }
        /*
         * template
         */
        $this->template->add_js(public_url() . "publicacion/publicacion_index.js",'import');
        $this->template->add_css(public_url() . "publicacion/estilo.css");
        $this->template->write('header_title', 'Listado de publicaciones');
        $this->template->write('title', 'Listado de publicaciones');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form()
    {
        $this->load->model('proveedor/mdl_proveedor');
        $data['proveedores'] =  $this->mdl_proveedor->get()->result();
        $id = uri_assoc('id');
        if ($this->mdl_publicacion->run_validation()) {

            $params = $this->mdl_publicacion->db_array();

            $dia = $this->input->post('aniversario_dia');
            $mes = $this->input->post('aniversario_mes');

            $params['fecha_aniversario'] = $dia . '-' . $mes;
            $params['tipo_publicacion'] = $this->input->post('tipo');
            $id = $this->mdl_publicacion->save($id, $params);


            redirect('publicacion/index');

        } else {
            if (isset($id)) {
                $publicacion = $this->mdl_publicacion->get_by_id($id);
                $data['idproveedor'] =  $publicacion->proveedor_id;
                $fecha = explode('-',$publicacion->fecha_aniversario);
                $data['aniversario_dia'] =  $fecha[0];
                $data['aniversario_mes'] =  $fecha[1];
                $data['tipo_publicacion'] =  $publicacion->tipo_publicacion;
            }
            $this->mdl_publicacion->prep_form($id);
            /*
             * template
            */
            $this->template->add_css(base_css().'datepicker.css','import', false);
            $this->template->add_js(base_js().'bootstrap-datepicker.js','import', false, 'footer');

            $this->template->add_css(public_url() . "chosen/chosen/chosen.css");
            $this->template->add_js(public_url() . "chosen/chosen/chosen.jquery.min.js");
            $this->template->add_js(public_url() . "bootstrap/fileUpload/file_upload.js");
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "publicacion/validate_publicacion.js");
            $this->template->add_css(public_url() . "publicacion/estilo.css");

            $javascript_inline = '
            $(function(){
                $(".chzn-select").chosen();
            });
            ';
            $this->template->add_js($javascript_inline, 'embed');

            $this->template->write('header_title', 'Administrar publicaci&oacute;n');
            $this->template->write('title', 'Administrar publicaci&oacute;n');
            $this->template->write_view('content', 'form',$data);
            $this->template->render();
        }
    }

    public function descuentos()
    {
    	$data['publicaciones'] = $this->mdl_publicacion->get()->result();
        $data['descuentos_publicaciones'] = $this->mdl_descuento_publicacion->get()->result();

    	/* obtenemos columnas de dia_descuento */
    	$data['dia_descuentos'] = $this->mdl_dia_descuento->get()->result();
    	$aniversario_exists = false;
    	$data['columnas'] = array();
    	$i=0;
        foreach ($data['dia_descuentos'] as $dia_descuento)
        {
            $data['columnas'][$i] = new stdClass();
            $data['columnas'][$i]->nombre           = $dia_descuento->nombre;
            $data['columnas'][$i]->dia_descuento_id = $dia_descuento->id;

            foreach ($data['descuentos_publicaciones'] as $descuento_publicacion)
            {
                if ( $dia_descuento->id == $descuento_publicacion->dia_descuento_id )
                {
                    $data['columnas'][$i]->publicaciones .= $descuento_publicacion->publicacion_id . ",";
                }
            }

            /* si no hay publicaciones setear $data['columnas'][$i]->publicaciones con null */
            if (isset($data['columnas'][$i]->publicaciones)){
              $data['columnas'][$i]->publicaciones = substr($data['columnas'][$i]->publicaciones, 0, -1);
            }
            else{
                $data['columnas'][$i]->publicaciones = null;
            }

            $data['columnas'][$i]->tipo_fecha = $dia_descuento->tipo_fecha;
            $i++;
    	}

		/* objeto de descuentos por publicacion */
		for ($i=0; $i < count($data['publicaciones']); $i++) {
			$descuentos_por_publicacion = $this->mdl_descuento_publicacion->where('publicacion_id', $data['publicaciones'][$i]->id)->get()->result();
			if ( count($descuentos_por_publicacion) > 0 )
			{
				for ($j=0; $j < count($descuentos_por_publicacion); $j++)
				{
					if ( $data['publicaciones'][$i]->id == $descuentos_por_publicacion[$j]->publicacion_id)
					{
                        $data['publicaciones'][$i]->descuentos[$j] = new stdClass();
						$data['publicaciones'][$i]->descuentos[$j]->porcentaje = $descuentos_por_publicacion[$j]->porcentaje_descuento;

						$dia_descuento = $this
                            ->mdl_dia_descuento
                            ->select('id, nombre, fecha, tipo_fecha')
                            ->where('id', $descuentos_por_publicacion[$j]->dia_descuento_id)
                            ->get()->row();
						$data['publicaciones'][$i]->descuentos[$j]->dia_descuento_id = $dia_descuento->id;
						$data['publicaciones'][$i]->descuentos[$j]->fecha = $dia_descuento->fecha;
						$data['publicaciones'][$i]->descuentos[$j]->tipo_fecha = $dia_descuento->tipo_fecha;
					}
				}
			}
		}

    	/*
         * template
        */
    	$this->template->write('header_title', 'Descuentos por Publicaci&oacute;n');
        $this->template->write('title', 'Descuentos por Publicaci&oacute;n (%)');
        $this->template->write_view('content', 'descuentos', $data);
        $this->template->render();
    }

    public function nuevo_descuento()
    {
    	/*if ($_POST){
    		var_dump($_POST);
    		exit;
    	}*/

    	if( $this->mdl_publicacion->run_validation("validation_rules_descuento") )
    	{
    		/* DIA_DESCUENTO */
    		$db_array_dia_descuento['tipo_fecha'] = $this->input->post('tipo_fecha');
    		$db_array_dia_descuento['nombre'] = $this->input->post('dia_descuento_nombre');

    		/* armar formato json de fecha */
    		if( $this->input->post('tipo_fecha') == "dia")
    		{
    			$dia_array = '"';
    			foreach ($this->input->post('dia') as $value) {
    				if (trim($value) == '0') continue;
    				$dia_array .= $value . ',';
    			}
    			$dia_array = substr($dia_array, 0, -1) . '"';
    			$fecha_json = '{ "dia": ' . $dia_array . '}';
    		}
    		elseif( $this->input->post('tipo_fecha') == "aniversario")
    		{
    			$aniversario_dia_array = $this->input->post('aniversario_dia');
    			$aniversario_mes_array = $this->input->post('aniversario_mes');
				$fecha_json = '{ "dia": ' . $aniversario_dia_array[0] . ', "mes": ' . $aniversario_mes_array[0] . ' }';
    		}
    		elseif( $this->input->post('tipo_fecha') == "feriado")
    		{
    			$feriado_dia_array = $this->input->post('feriado_dia');
    			$feriado_mes_array = $this->input->post('feriado_mes');
    			$fecha_json = '{ "dia": ' . $feriado_dia_array[0] . ', "mes": ' . $feriado_mes_array[0] . ' }';
    		}

    		$db_array_dia_descuento['fecha'] = $fecha_json;
    		$dia_descuento_id = $this->mdl_dia_descuento->save(null, $db_array_dia_descuento, false);

			/* DESCUENTO_PUBLICACION */
			$db_array_descuento_publicacion['porcentaje_descuento'] = $this->input->post('porcentaje_descuento');
        	$db_array_descuento_publicacion['publicacion_id'] = $this->input->post('publicacion_id');
        	$db_array_descuento_publicacion['dia_descuento_id'] = $dia_descuento_id;

        	if ( $db_array_descuento_publicacion['publicacion_id'] == "todas")
        	{
        		$publicaciones = $this->mdl_publicacion->select('id')->get()->result();
        		foreach ( $publicaciones as $publicacion)
        		{
        			$db_array_descuento_publicacion['publicacion_id'] = $publicacion->id;
        			$this->mdl_descuento_publicacion->save(null, $db_array_descuento_publicacion);
        			$this->session->set_flashdata('alert_success', 'Descuentos agregados exitosamente a todas las publicaciones!');
        		}
        	}
        	else
        	{
        		$this->mdl_descuento_publicacion->save(null, $db_array_descuento_publicacion);
        		$this->session->set_flashdata('alert_success', 'Descuento agregado exitosamente!');
        	}

			redirect('publicacion/nuevo_descuento');
    	}

        $data['publicaciones_list'] = $this->mdl_publicacion->order_by('nombre')->get()->result();
        /* objeto de descuentos por publicacion */
        for ($i=0; $i < count($data['publicaciones_list']); $i++) {
            $descuentos_por_publicacion = $this->mdl_descuento_publicacion->where('publicacion_id', $data['publicaciones_list'][$i]->id)->get()->result();
            if ( count($descuentos_por_publicacion) > 0 )
            {
                for ($j=0; $j < count($descuentos_por_publicacion); $j++)
                {
                    if ( $data['publicaciones_list'][$i]->id == $descuentos_por_publicacion[$j]->publicacion_id)
                    {
                        $data['publicaciones_list'][$i]->descuentos[$j] = new stdClass();
                        $data['publicaciones_list'][$i]->descuentos[$j]->porcentaje = $descuentos_por_publicacion[$j]->porcentaje_descuento;

                        $dia_descuento = $this->mdl_dia_descuento->select('id, nombre, fecha, tipo_fecha')->where('id', $descuentos_por_publicacion[$j]->dia_descuento_id)->get()->row();
                        $data['publicaciones_list'][$i]->descuentos[$j]->dia_descuento_id = $dia_descuento->id;
                        $data['publicaciones_list'][$i]->descuentos[$j]->fecha = $dia_descuento->fecha;
                        $data['publicaciones_list'][$i]->descuentos[$j]->tipo_fecha = $dia_descuento->tipo_fecha;
                    }
                }
            }
            /* si no hay descuentos para la publicacion actual */
            else
            {
                $data['publicaciones_list'][$i]->descuentos = array();
            }
        }

		/* chosen: publicacion */
        $publicaciones = $data['publicaciones_list'];
        $publicaciones_arr = array('' => '');
        foreach ($publicaciones as $key => $publicacion) {
            $publicaciones_arr[$publicacion->id] = $publicacion->nombre;
        }
        $publicaciones_arr['todas'] = 'Todas las Publicaciones';
        $data['publicaciones'] = $publicaciones_arr;
        $data['chosen_publicaciones'] =  $this->input->post('publicacion_id');

        /* tipo fecha */
        $tipos_fecha_arr = array(
        	'0' => '',
        	'dia' => 'descuento por dias',
        	'aniversario' => 'descuento por aniversario',
        	'feriado' => 'descuento por feriado'
        	);
        $data['tipos_fecha'] = $tipos_fecha_arr;
        $data['chosen_tipos_fecha'] =  $this->input->post('tipo_fecha');

        /* dia */
        $dias_arr = array(
        	'0' => '',
        	'todos' => 'todos los dias',
        	'lunes' => 'lunes',
        	'martes' => 'martes',
        	'miercoles' => 'miercoles',
        	'jueves' => 'jueves',
        	'viernes' => 'viernes',
        	'sabado' => 'sabado',
        	'domingo' => 'domingo'
        	);
        $data['dias'] = $dias_arr;
        $data['chosen_dias'] =  $this->input->post('dia');

        /* aniversario_dia y feriado_dia */
        $dias_arr = array(
        	'0' => '',
        	'1' => '1',
        	'2' => '2',
        	'3' => '3',
        	'4' => '4',
        	'5' => '5',
        	'6' => '6',
        	'7' => '7',
        	'8' => '8',
        	'9' => '9',
        	'10' => '10',
        	'11' => '11',
        	'12' => '12',
        	'13' => '13',
        	'14' => '14',
        	'15' => '15',
        	'16' => '16',
        	'17' => '17',
        	'18' => '18',
        	'19' => '19',
        	'20' => '20',
        	'21' => '21',
        	'22' => '22',
        	'23' => '23',
        	'24' => '24',
        	'25' => '25',
        	'26' => '26',
        	'27' => '27',
        	'28' => '28',
        	'29' => '29',
			'30' => '30',
			'31' => '31',
        	);
        $data['fecha_dias'] = $dias_arr;
        $data['chosen_aniversario_dias'] =  $this->input->post('aniversario_dia');
        $data['chosen_feriado_dias'] =  $this->input->post('feriado_dia');

        /* aniversario_mes y feriado_mes */
        $meses_arr = array(
        	'0' => '',
        	'1' => 'enero',
        	'2' => 'febrero',
        	'3' => 'marzo',
        	'4' => 'abril',
        	'5' => 'mayo',
        	'6' => 'junio',
        	'7' => 'julio',
        	'8' => 'agosto',
        	'9' => 'setiembre',
        	'10' => 'octubre',
        	'11' => 'noviembre',
        	'12' => 'diciembre',
        	);
        $data['fecha_meses'] = $meses_arr;
        $data['chosen_aniversario_meses'] =  $this->input->post('aniversario_mes');
        $data['chosen_feriado_meses'] =  $this->input->post('feriado_mes');

    	/* chosen */
		$this->template->add_css(base_url() . "assets/chosen/chosen/chosen.css");
		$this->template->add_js(base_url() . "assets/chosen/chosen/chosen.jquery.min.js");

		$style_inline = '
            #dia, #aniversario, #feriado{
                display: none;
            }
        ';
        $this->template->add_css($style_inline, 'embed');

        $javascript_inline = '
			$(function(){
				$(".chzn-select").chosen();

				$("#tipo_fecha").chosen().change(function(event){
					$("#dia").hide();
					$("#aniversario").hide();
					$("#feriado").hide();

					if( $(event.target).val() == "dia")
						$("#dia").show("slow");
					if( $(event.target).val() == "aniversario")
						$("#aniversario").show("slow");
					if( $(event.target).val() == "feriado")
						$("#feriado").show("slow");
				});

			});
		';
        $this->template->add_js($javascript_inline, 'embed');

    	/*
         * template
        */
        $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
        $this->template->add_js(public_url() . "publicacion/validate_nuevo_descuento.js");
        $this->template->write('header_title', 'Nuevo Descuento');
        $this->template->write('title', 'Nuevo Descuento');
        $this->template->write_view('content', 'nuevo_descuento', $data);
        $this->template->render();
    }

    public function precios_old()
    {
        /* objeto de precios por publicacion */
        $data['publicaciones'] = $this->mdl_publicacion->get()->result();
        $dias = array();
        for ($i=0; $i < count($data['publicaciones']); $i++) {
            $precios_fecha_publicacion = $this->mdl_precio_publicacion
                                                ->select('precio, fecha')
                                                ->where(
                                                    array('publicacion_id' => $data['publicaciones'][$i]->id)
                                                    )
                                                ->get()
                                                ->result();
            foreach ($precios_fecha_publicacion as $precio_fecha_publicacion)
            {
                $dias_temp = json_decode($precio_fecha_publicacion->fecha);
                $dias_temp = explode(',', $dias_temp->dia);
                foreach ($dias_temp as $dia_temp) {
                    $dia_precio = array(
                        'dia' => $dia_temp,
                        'precio' => $precio_fecha_publicacion->precio
                    );
                    array_push($dias, $dia_precio);
                }
            }
            $data['publicaciones'][$i]->precios = $dias;
            $dias = array();
        }
        $data['columnas'] = array(
            '0' => 'lunes',
            '1' => 'martes',
            '2' => 'miercoles',
            '3' => 'jueves',
            '4' => 'viernes',
            '5' => 'sabado',
            '6' => 'domingo'
        );

        /*
         * template
        */
        $this->template->add_css(public_url() . "datatable/jquery.dataTables.css");
        $this->template->add_js(public_url() . "datatable/jquery.dataTables.js");
        $javascript_inline = '
            $(function(){
                $("#precios").dataTable();
            });
        ';
        $this->template->add_js($javascript_inline, 'embed');

        $this->template->write('header_title', 'Precios por Publicaci&oacute;n');
        $this->template->write('title', 'Precio por Publicaci&oacute;n (S/)');
        $this->template->write_view('content', 'precios', $data);
        $this->template->render();
    }

    public function precios()
    {
        /* objeto de precios por publicacion */
        $data['publicaciones'] = $this->mdl_publicacion->get()->result();
        $dias = array();
        for ($i=0; $i < count($data['publicaciones']); $i++) {
            $precios_publicacion = $this->mdl_precio_publicacion
                                                ->select('precio, dia')
                                                ->where(
                                                    array('publicacion_id' => $data['publicaciones'][$i]->id)
                                                    )
                                                ->get()
                                                ->result();

            foreach ($precios_publicacion as $precio_publicacion)
            {
                $dia_precio = array(
                    'dia' => $precio_publicacion->dia,
                    'precio' => $precio_publicacion->precio
                );
                array_push($dias, $dia_precio);
            }
            $data['publicaciones'][$i]->precios = $dias;
            $dias = array();
        }

        $data['columnas'] = array(
            '0' => 'lunes',
            '1' => 'martes',
            '2' => 'miercoles',
            '3' => 'jueves',
            '4' => 'viernes',
            '5' => 'sabado',
            '6' => 'domingo'
        );

        /*
         * template
        */
        $this->template->write('header_title', 'Precios por Publicaci&oacute;n');
        $this->template->write('title', 'Precio por Publicaci&oacute;n (S/)');
        $this->template->write_view('content', 'precios', $data);
        $this->template->render();
    }

    public function nuevo_precio()
    {
        /*if ($_POST){
            var_dump($_POST);
            exit;
        }*/

        if( $this->mdl_publicacion->run_validation("validation_rules_precio") )
        {
            /* PRECIO */
            $db_array_precio_publicacion = $this->mdl_publicacion->db_array();

            $dias_array = '"';
            if (in_array("todos", $this->input->post('dia')))
            {
                $dias_array = array('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo');
            }
            else
            {
                $dias_array = $this->input->post('dia');
            }

            /* guardar precio para todas las publicaciones? */
            $success = '';
            $header_success = '';
            $body_success = '';
            if ( $db_array_precio_publicacion['publicacion_id'] == "todas")
            {
                $add = false;
                $publicaciones = $this->mdl_publicacion->select('id, nombre')->get()->result();
                foreach ( $publicaciones as $publicacion)
                {
                    $db_array_precio_publicacion['publicacion_id'] = $publicacion->id;
                    $header_success = '<br/>Publicacion: ' . $publicacion->nombre;
                    foreach ($dias_array as $dia)
                    {
                        $db_array_precio_publicacion['dia'] = $dia;
                        $existe_precio = $this->mdl_precio_publicacion
                                        ->select('id')
                                        ->where(
                                            array(
                                                'publicacion_id' => $db_array_precio_publicacion['publicacion_id'],
                                                'dia' => $db_array_precio_publicacion['dia']
                                                )
                                            )
                                        ->get()
                                        ->row();
                        if( count($existe_precio) == 0 )
                        {
                            $this->mdl_precio_publicacion->save(null, $db_array_precio_publicacion);
                            $body_success = '<br/>Se agrego el precio para el d&iacute;a ' . $db_array_precio_publicacion['dia'];
                            $add = true;
                        }
                    }
                    if($add)
                    {
                        $success .= $header_success . $body_success;
                        $add = false;
                    }
                }
            }
            else
            {
                $publicacion = $this->mdl_publicacion->select('id, nombre')->where('id', $this->input->post('publicacion_id'))->get()->row();
                $db_array_precio_publicacion['publicacion_id'] = $publicacion->id;
                $header_success = 'Publicacion: ' . $publicacion->nombre;

                foreach ($dias_array as $dia)
                {
                    $db_array_precio_publicacion['dia'] = $dia;
                    $existe_precio = $this->mdl_precio_publicacion
                                            ->select('id')
                                            ->where(
                                                array(
                                                    'publicacion_id' => $db_array_precio_publicacion['publicacion_id'],
                                                    'dia' => $db_array_precio_publicacion['dia']
                                                    )
                                                )
                                            ->get()
                                            ->row();
                    if( count($existe_precio) == 0 )
                    {
                        $this->mdl_precio_publicacion->save(null, $db_array_precio_publicacion);
                        $body_success = '<br/>Se agrego el precio para el d&iacute;a ' . $db_array_precio_publicacion['dia'];
                    }
                }
            }
            $this->session->set_flashdata('alert_success', $success);
            redirect('publicacion/nuevo_precio/publicacion/' . $publicacion->id);
        }

        $data['publicaciones_list'] = $this->mdl_publicacion->order_by('nombre')->get()->result();
        for ($i=0; $i < count($data['publicaciones_list']); $i++)
        {
            $data['publicaciones_list'][$i]->precios = $this->mdl_precio_publicacion->where('publicacion_id', $data['publicaciones_list'][$i]->id)->get()->result();
        }

        /* chosen: publicacion */
        $publicaciones = $data['publicaciones_list'];
        $publicaciones_arr = array('' => '');
        foreach ($publicaciones as $key => $publicacion) {
            $publicaciones_arr[$publicacion->id] = $publicacion->nombre;
        }
        $publicaciones_arr['todas'] = 'Todas las Publicaciones';
        $data['publicaciones'] = $publicaciones_arr;
        $data['chosen_publicaciones'] =  $this->input->post('publicacion_id');

        /* dia */
        $dias_arr = array(
            '0' => '',
            'todos' => 'todos los dias',
            'lunes' => 'lunes',
            'martes' => 'martes',
            'miercoles' => 'miercoles',
            'jueves' => 'jueves',
            'viernes' => 'viernes',
            'sabado' => 'sabado',
            'domingo' => 'domingo'
            );
        $data['dias'] = $dias_arr;
        $data['chosen_dias'] =  $this->input->post('dia');

        /* chosen */
        $this->template->add_css(base_url() . "assets/chosen/chosen/chosen.css");
        $this->template->add_js(base_url() . "assets/chosen/chosen/chosen.jquery.min.js");

        /* editinplace */
        $this->template->add_js(public_url() . 'jquery/jquery.editinplace.js');

        $javascript_inline = '
            $(function(){
                $(".chzn-select").chosen();
                $("#myTab a[href=#' . uri_assoc('publicacion') . ']").tab("show");
            });
        ';
        $this->template->add_js($javascript_inline, 'embed');
        $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
        $this->template->add_js(public_url() . "precio_publicacion/js/validate_precio_publicacion.js");

        /*
         * template
        */
        $this->template->write('header_title', 'Modificar Precios');
        $this->template->write('title', 'Modificar Precios');
        $this->template->write_view('content', 'nuevo_precio', $data);
        $this->template->render();
    }

    public function nuevo_precio_old()
    {
        /*if ($_POST){
            var_dump($_POST);
            exit;
        }*/

        if( $this->mdl_publicacion->run_validation("validation_rules_precio") )
        {
            /* PRECIO */
            $db_array_precio_publicacion = $this->mdl_publicacion->db_array();

            /* armar formato json de fecha */
            $dia_array = '"';
            foreach ($this->input->post('dia') as $value) {
                if (trim($value) == '0') continue;
                $dia_array .= $value . ',';
            }
            $dia_array = substr($dia_array, 0, -1) . '"';
            if (stripos($dia_array, "todos") !== false)
            {
                $fecha_json = '{ "dia": "lunes,martes,miercoles,jueves,viernes,sabado,domingo"}';
            }
            else
            {
                $fecha_json = '{ "dia": ' . $dia_array . '}';
            }
            $db_array_precio_publicacion['fecha'] = $fecha_json;

            if ( $db_array_precio_publicacion['publicacion_id'] == "todas")
            {
                $publicaciones = $this->mdl_publicacion->select('id')->get()->result();
                foreach ( $publicaciones as $publicacion)
                {
                    $db_array_precio_publicacion['publicacion_id'] = $publicacion->id;
                    $this->mdl_precio_publicacion->save(null, $db_array_precio_publicacion);
                    $this->session->set_flashdata('alert_success', 'Precios agregados exitosamente a todas las publicaciones!');
                }
            }
            else
            {
                $this->mdl_precio_publicacion->save(null, $db_array_precio_publicacion);
                $this->session->set_flashdata('alert_success', 'Precio agregado exitosamente!');
            }
            redirect('publicacion/nuevo_precio');
        }

        $data['publicaciones_list'] = $this->mdl_publicacion->order_by('nombre')->get()->result();
        for ($i=0; $i < count($data['publicaciones_list']); $i++) {
            $data['publicaciones_list'][$i]->precios = $this->mdl_precio_publicacion->where('publicacion_id', $data['publicaciones_list'][$i]->id)->get()->result();
        }

        /* chosen: publicacion */
        $publicaciones = $data['publicaciones_list'];
        $publicaciones_arr = array('' => '');
        foreach ($publicaciones as $key => $publicacion) {
            $publicaciones_arr[$publicacion->id] = $publicacion->nombre;
        }
        $publicaciones_arr['todas'] = 'Todas las Publicaciones';
        $data['publicaciones'] = $publicaciones_arr;
        $data['chosen_publicaciones'] =  $this->input->post('publicacion_id');

        /* dia */
        $dias_arr = array(
            '0' => '',
            'todos' => 'todos los dias',
            'lunes' => 'lunes',
            'martes' => 'martes',
            'miercoles' => 'miercoles',
            'jueves' => 'jueves',
            'viernes' => 'viernes',
            'sabado' => 'sabado',
            'domingo' => 'domingo'
            );
        $data['dias'] = $dias_arr;
        $data['chosen_dias'] =  $this->input->post('dia');

        /* chosen */
        $this->template->add_css(base_url() . "assets/chosen/chosen/chosen.css");
        $this->template->add_js(base_url() . "assets/chosen/chosen/chosen.jquery.min.js");

        /* editinplace */
        $this->template->add_js(public_url() . 'jquery/jquery.editinplace.js');

        $javascript_inline = '
            $(function(){
                $(".chzn-select").chosen();
            });
        ';
        $this->template->add_js($javascript_inline, 'embed');
        $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
        $this->template->add_js(public_url() . "precio_publicacion/js/validate_precio_publicacion.js");

        /*
         * template
        */
        $this->template->write('header_title', 'Modificar Precios');
        $this->template->write('title', 'Modificar Precios');
        $this->template->write_view('content', 'nuevo_precio', $data);
        $this->template->render();
    }

    public function eliminar_precio()
    {
         $id = uri_assoc('id');
        if ($id) {
            $this->mdl_precio_publicacion->delete($id);
        }
        redirect('publicacion/nuevo_precio');
    }

    public function eliminar_descuento()
    {
         $id = uri_assoc('id');
        if ($id) {
            $this->mdl_descuento_publicacion->delete($id);
        }
        redirect('publicacion/nuevo_descuento');
    }

    public function editar_precio()
    {
        /* getting values */
        $this->input->post('ajax') or redirect();
        $element_id = $this->input->post('element_id');
        $updated_value = $this->input->post('update_value', true);

        /* explode values */
        list($publicacion_id, $precio_id) = explode('-', $element_id);

        /* updating data*/
        $updated_value = $string = str_replace(' ', '', $updated_value);
        $this->mdl_precio_publicacion->editar_precio_publicacion($updated_value, $publicacion_id, $precio_id);
        echo $updated_value;
    }

    public function _post_handler()
    {
        if ($this->input->post('btn_add'))
            redirect('publicacion/form');
        if ($this->input->post('btn_cancel'))
            redirect('publicacion/index');
    }
    public function delete() {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_publicacion->delete($id);


        }
        redirect('publicacion/index');
    }
    public function get_ids()
    {
        $result = $this->mdl_publicacion->get()->result();
        echo json_encode($result);
    }

    public function comisiones()
    {
        /* objeto de comisiones por publicacion */
        $data['publicaciones'] = $this->mdl_publicacion->get()->result();
        $dias = array();
        for ($i=0; $i < count($data['publicaciones']); $i++) {
            $comisiones_publicacion = $this->mdl_comision_publicacion
                                                ->select('comision, dia')
                                                ->where(
                                                    array('publicacion_id' => $data['publicaciones'][$i]->id)
                                                    )
                                                ->get()
                                                ->result();
            foreach ($comisiones_publicacion as $comision_publicacion)
            {
                $dia_comision = array(
                    'dia' => $comision_publicacion->dia,
                    'comision' => $comision_publicacion->comision
                );
                array_push($dias, $dia_comision);
            }
            $data['publicaciones'][$i]->comisiones = $dias;
            $dias = array();
        }
        $data['columnas'] = array(
            '0' => 'lunes',
            '1' => 'martes',
            '2' => 'miercoles',
            '3' => 'jueves',
            '4' => 'viernes',
            '5' => 'sabado',
            '6' => 'domingo'
        );

        $this->template->write('header_title', 'Comisiones por Publicaci&oacute;n');
        $this->template->write('title', 'Comisiones por Publicaci&oacute;n (%)');
        $this->template->write_view('content', 'comisiones', $data);
        $this->template->render();
    }

    public function nueva_comision()
    {
        /*if ($_POST){
            var_dump($_POST);
            exit;
        }*/

        if( $this->mdl_publicacion->run_validation("validation_rules_comision") )
        {
            /* COMISION */
            $db_array_comision_publicacion = $this->mdl_publicacion->db_array();

            $dias_array = '"';
            if (in_array("todos", $this->input->post('dia')))
            {
                $dias_array = array('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo');
            }
            else
            {
                $dias_array = $this->input->post('dia');
            }

            /* guardar comision para todas las publicaciones? */
            $success = '';
            $header_success = '';
            $body_success = '';
            if ( $db_array_comision_publicacion['publicacion_id'] == "todas")
            {
                $add = false;
                $publicaciones = $this->mdl_publicacion->select('id, nombre')->get()->result();
                foreach ( $publicaciones as $publicacion)
                {
                    $db_array_comision_publicacion['publicacion_id'] = $publicacion->id;
                    $header_success = 'Publicacion: ' . $publicacion->nombre;
                    foreach ($dias_array as $dia)
                    {
                        $db_array_comision_publicacion['dia'] = $dia;
                        $existe_comision = $this->mdl_comision_publicacion
                                        ->select('id')
                                        ->where(
                                            array(
                                                'publicacion_id' => $db_array_comision_publicacion['publicacion_id'],
                                                'dia' => $db_array_comision_publicacion['dia']
                                                )
                                            )
                                        ->get()
                                        ->row();
                        if( count($existe_precio) == 0 )
                        {
                            $this->mdl_precio_publicacion->save(null, $db_array_precio_publicacion);
                            $body_success = '<br/>Se agrego el precio para el d&iacute;a ' . $db_array_precio_publicacion['dia'];
                            $add = true;
                        }
                    }
                    if($add)
                    {
                        $success .= $header_success . $body_success;
                        $add = false;
                    }
                }
            }
            else
            {
                $publicacion = $this->mdl_publicacion->select('id, nombre')->where('id', $this->input->post('publicacion_id'))->get()->row();
                $db_array_comision_publicacion['publicacion_id'] = $publicacion->id;
                $header_success = 'Publicacion: ' . $publicacion->nombre;

                foreach ($dias_array as $dia)
                {
                    $db_array_comision_publicacion['dia'] = $dia;
                    $existe_comision = $this->mdl_comision_publicacion
                                            ->select('id')
                                            ->where(
                                                array(
                                                    'publicacion_id' => $db_array_comision_publicacion['publicacion_id'],
                                                    'dia' => $db_array_comision_publicacion['dia']
                                                    )
                                                )
                                            ->get()
                                            ->row();
                    if( count($existe_comision) == 0 )
                    {
                        $this->mdl_comision_publicacion->save(null, $db_array_comision_publicacion);
                        $body_success = '<br/>Se agrego el comision para el d&iacute;a ' . $db_array_comision_publicacion['dia'];
                    }
                }
            }
            $this->session->set_flashdata('alert_success', $success);
            redirect('publicacion/nueva_comision/publicacion/' . $publicacion->id);
        }

        $data['publicaciones_list'] = $this->mdl_publicacion->order_by('nombre')->get()->result();
        for ($i=0; $i < count($data['publicaciones_list']); $i++) {
            $data['publicaciones_list'][$i]->comisiones = $this->mdl_comision_publicacion->where('publicacion_id', $data['publicaciones_list'][$i]->id)->get()->result();
        }

        /* chosen: publicacion */
        $publicaciones = $data['publicaciones_list'];
        $publicaciones_arr = array('' => '');
        foreach ($publicaciones as $key => $publicacion) {
            $publicaciones_arr[$publicacion->id] = $publicacion->nombre;
        }
        $publicaciones_arr['todas'] = 'Todos las publicaciones';
        $data['publicaciones'] = $publicaciones_arr;
        $data['chosen_publicaciones'] =  $this->input->post('publicacion_id');

        /* dia */
        $dias_arr = array(
            '0' => '',
            'todos' => 'todos los dias',
            'lunes' => 'lunes',
            'martes' => 'martes',
            'miercoles' => 'miercoles',
            'jueves' => 'jueves',
            'viernes' => 'viernes',
            'sabado' => 'sabado',
            'domingo' => 'domingo'
            );
        $data['dias'] = $dias_arr;
        $data['chosen_dias'] =  $this->input->post('dia');

        /* chosen */
        $this->template->add_css(base_url() . "assets/chosen/chosen/chosen.css");
        $this->template->add_js(base_url() . "assets/chosen/chosen/chosen.jquery.min.js");

        /* editinplace */
        $this->template->add_js(public_url() . 'jquery/jquery.editinplace.js');

        $javascript_inline = '
            $(function(){
                $(".chzn-select").chosen();
                $("#myTab a[href=#' . uri_assoc('publicacion') . ']").tab("show");
            });
        ';
        $this->template->add_js($javascript_inline, 'embed');

        /*
         * template
        */
        $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
        $this->template->add_js(public_url() . "comision_publicacion/js/validate_comision_publicacion.js");
        $this->template->write('header_title', 'Modificar Comisiones');
        $this->template->write('title', 'Modificar Comisiones');
        $this->template->write_view('content', 'nueva_comision', $data);
        $this->template->render();
    }

    public function eliminar_comision()
    {
         $id = uri_assoc('id');
        if ($id) {
            $this->mdl_comision_publicacion->delete($id);
        }
        redirect('publicacion/nueva_comision');
    }

    public function editar_comision()
    {
        /* getting values */
        $this->input->post('ajax') or redirect();
        $element_id = $this->input->post('element_id');
        $updated_value = $this->input->post('update_value', true);

        /* explode values */
        list($publicacion_id, $comision_id) = explode('-', $element_id);

        /* updating data*/
        $updated_value = $string = str_replace(' ', '', $updated_value);
        $this->mdl_comision_publicacion->editar_comision_publicacion($updated_value, $publicacion_id, $comision_id);
        echo $updated_value;
    }

    public function editar_comision2()
    {
        $this->db->query('
            INSERT INTO `comision_publicacion` (`comision`, `fecha`, `publicacion_id`, `operador_id`) VALUES
            (\'1.00\', \'' . var_dump($_POST) . '\', 9, 0);
        ');
        var_dump($_POST);
        exit;

        /* getting values */
        $this->input->post('ajax') or redirect();
        $element_id = $this->input->post('element_id');
        $updated_value = $this->input->post('update_value', true);

        /* explode values */
        list($publicacion_id, $precio_id) = explode('-', $element_id);

        /* updating data*/
        $updated_value = $string = str_replace(' ', '', $updated_value);
        $this->mdl_precio_publicacion->editar_fecha_precio_publicacion($updated_value, $publicacion_id, $precio_id);
        echo $updated_value;
    }
}

?>