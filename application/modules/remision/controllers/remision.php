<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Remision extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        // $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_remision');
        $this->load->model('detalle_remision/mdl_detalle_remision');
        $this->load->model('proveedor/mdl_proveedor');
    }

    public function index() {
        $this->load->model('mdl_remision_table');
        $this->load->model('proveedor/mdl_proveedor');

        $this->mdl_remision->default_limit = $this->config->item('results_per_page');

        $this->mdl_remision->order_by = uri_assoc('order_by');
        $this->mdl_remision->order = uri_assoc('order');

        $data['proveedores'] = $this->mdl_proveedor->select('id, nombre')->get()->result();
		$data['table_headers'] = $this->mdl_remision_table->get_table_headers();

        /*$data = array(
            'remisions' => $this->mdl_remision->paginate()->result(),
        );*/
        $estado = uri_assoc('estado');
        //print_r($this->mdl_remision->paginate()->result());

        if (isset($estado))
        {
        	switch ($estado) {
        		case 'pendiente':
					$data['remisions'] = $this->mdl_remision
                                ->select('id, nro_guia, razon_social,
                                         codigo, ruc, tipo, fecha_emision,
                                         fecha_recepcion, fecha_pago,
                                         proveedor_id, status')
                                ->where('status', 'pendiente')
                                ->paginate(array('select', 'where'))->result();
        		break;
        		case 'pagado':
					$data['remisions'] = $this->mdl_remision
                                ->select('id, nro_guia,
                                         razon_social, codigo,
                                         ruc, tipo, fecha_emision,
                                         fecha_recepcion, fecha_pago,
                                         proveedor_id, status')
                                ->where('status', 'pagado')
                                ->paginate(array('select', 'where'))->result();
        		break;
        		case 'anulado':
					$data['remisions'] = $this->mdl_remision
                                ->select('id, nro_guia, razon_social,
                                         codigo, ruc, tipo, fecha_emision,
                                         fecha_recepcion, fecha_pago,
                                         proveedor_id, status')
                                ->where('status', 'anulado')
                                ->paginate(array('select', 'where'))->result();
                     break;
                case 'todos':

                    $data['remisions'] = $this->mdl_remision
                                ->select('id, nro_guia, razon_social,
                                         codigo, ruc, tipo, fecha_emision,
                                         fecha_recepcion, fecha_pago,
                                         proveedor_id, status')
                                ->paginate()->result();
        		break;
        	}
        }

        else{
            //$data['remisions'] = $this->mdl_remision->paginate()->result();
            $data['remisions'] = $this->mdl_remision->select('id, nro_guia, razon_social, codigo, ruc, tipo, fecha_emision, fecha_recepcion, fecha_pago, proveedor_id, status')->paginate()->result();
        }
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

        $this->template->add_js(bootstrap_js() . 'bootbox/bootbox.min.js');
        $this->template->add_js(public_url(). 'remision/js/index.js');
        $this->template->write('header_title', 'Listado de remisi&oacute;n');
        $this->template->write('title', 'Listado de remisi&oacute;n');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form()
    {
        $remision_id = uri_assoc('id');

        $ganancia_total = 0;
        $cantidad_total = 0;

        $this->load->library('lib_remision');
        if ($this->mdl_remision->run_validation()) {
            /* override de my_model en mdl_remision para "save" (por eso no uso aca db_array
            para parsear las fechas, lo hago en mdl_remision, revisar el mdl)
            para tratar siempre de mantener limpio el codigo
            */
        	$db_array_remision = $this->mdl_remision->db_array();

        	$db_array_remision['fecha_emision'] = strtotime(standardize_date($db_array_remision['fecha_emision']));
        	$db_array_remision['fecha_recepcion'] = strtotime(standardize_date($db_array_remision['fecha_recepcion']));
        	$db_array_remision['fecha_vencimiento'] = strtotime(standardize_date($db_array_remision['fecha_vencimiento']));

        	unset($db_array_remision['total']);
        	unset($db_array_remision['existen_detalles_remision']);

            $remision_id = $this->mdl_remision->save($remision_id, $db_array_remision);

            //guardar los detalles_remision//
            $detalles = $this->input->post('detalle');
            $extra = array(
                        'remision_id' => $remision_id,
            );
            $detalles = $this->lib_remision->formatDetalleRemision1($detalles, $extra);
            //print_r($detalles);exit;
            /* si la remision no es nueva y ahora hemos eliminado detalles
            entonces tenemos que borrarlo tmb de la base de datos */
            //sacar los atributos demás

            $detalles = $this->limpiar_detalles_remision($detalles);

            $this->mdl_detalle_remision->deleteDetalles($detalles, $remision_id);

            $this->mdl_detalle_remision->guardarDetalles($detalles);

            redirect('remision/index');

        } else {

            $this->load->model('proveedor/mdl_proveedor');
            $this->mdl_remision->prep_form($remision_id);

            /* para que el remision_id sea usando en javascript */
            $remision_id = $remision_id == null ? -1 : $remision_id;

            //proveedores
            $this->load->model('proveedor/mdl_proveedor');
            $data['proveedores'] = $this->mdl_proveedor->select('id, nombre')->get()->result();

            $detalles = $this->input->post('detalle');
            //echo 'deta '.$detalles;
            $data['detalles'] = $this->lib_remision->formatDetalleRemision1($detalles, array(), true);
            $data['total'] = $this->input->post('total');
            $data['proveedor_id'] = $this->input->post('proveedor_id');
            $data['existen_detalles_remision'] = $_SERVER['REQUEST_METHOD'] == 'POST';

            $ganancia_total = $this->input->post('ganancia_total_sindicato');
            $cantidad_total = $this->input->post('cantidad_total');

            $data['fecha_vencimiento'] = $this->input->post('fecha_vencimiento');
            $data['fecha_emision_date'] = $this->input->post('fecha_emision');
            $data['fecha_recepcion'] = $this->input->post('fecha_recepcion');
            /* si no hay datos de post, entonces puede que exista detalles de la remision
            (si es que existe la remision tmb) */
            if (empty($data['detalles']) && $remision_id != -1) {

                $remision = $this->mdl_remision->get_by_id($remision_id);

                $data['detalles_remision'] = $this->get_detalles_remision($remision_id);
                //print_r($data['detalles_remision']);exit;
                $now = time();

                $data['fecha_vencimiento'] = date( "d/m/Y",$remision->fecha_vencimiento);
                $data['fecha_emision_date'] = date( "d/m/Y",$remision->fecha_emision);
                $data['fecha_recepcion'] = date( "d/m/Y",$remision->fecha_recepcion);
                $data['proveedor_id'] = $remision->proveedor_id;
                $data['detalles'] = $this->mdl_detalle_remision->getDetalles($remision_id);
                //var_dump( $data['detalles']);exit;
                $data['detalles'] = $this->datos_adicionales($remision->proveedor_id,$remision->fecha_recepcion, $data['detalles']);


                foreach ($data['detalles'] as $detalle) {
                	$ganancia_total += $detalle->ganancia_sindicato;
                    $cantidad_total += $detalle->cantidad;
                }

            }

            $data['ganancia_total_sindicato'] =  $ganancia_total;
            $data['cantidad_total_sindicato'] = $cantidad_total ;

            //calendario
            $this->template->add_js(public_url() . "jquery/jquery.maskMoney.min.js");
            $this->template->add_css(public_url(). 'bootstrap/datepicker/css/datepicker.css');
            $this->template->add_js(public_url(). 'bootstrap/datepicker/js/bootstrap-datepicker.js');
            $this->template->add_js(public_url(). 'bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js');

            $this->template->add_js(base_js() . "ckeditor/ckeditor.js", 'import', false, 'footer');
            /* luego usamos el datepicker */
            $javascript_inline = "
                var ID = $remision_id;
                 $('.datepicker').datepicker({
                    language: 'es',
                    minViewMode: 'days',
                    autoclose: 'true',
                    format: 'dd/mm/yyyy',
                    endDate: '+2d'
                });
                $('.datepickerv').datepicker({
                    language: 'es',
                    minViewMode: 'days',
                    autoclose: 'true',
                    format: 'dd/mm/yyyy',
                    endDate: '+20d'
                });
                $('.numeroentero, #cantidad').maskMoney({
                    precision: 0,
                    defaultZero: true,
                    thousands:'',
                    decimal:'.'
                });
                $('.digits').maskMoney({
                    precision: 0,
                    defaultZero: false,
                    allowZero: false,
                    thousands: '',
                    decimal: ''
                });
                $('.currency').maskMoney({
                    precision: 3,
                    defaultZero: true,
                    allowZero: true,
                    thousands: '',
                    decimal: '.'
                });
            $('.currency_cantidad').maskMoney({
                    precision: 0,
                    defaultZero: true,
                    allowZero: true,
                    thousands: '',
                    decimal: '.'
                });
            	//CKEDITOR.replace('observaciones', {toolbar:'Miconfig'});
            ";
            /*
             * template
            */

            $this->template->add_js($javascript_inline, 'embed', false);
            $this->template->add_css(public_url() . "remision/css/remision.css");
            $this->template->add_js(public_url() . "remision/js/remision.js");
            $this->template->add_js(public_url() . "remision/js/jquery.ba-outside-events.min.js");
            //$this->template->add_js(public_url() . "jquery/jquery.numberformatter-1.2.3.min.js");
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->write('header_title', 'Administrar remisi&oacute;n');
            $this->template->write('title', 'Administrar remisi&oacute;n');
            $this->template->write_view('content', 'form', $data);
            $this->template->render();
        }
    }


    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('remision/form');
        if ($this->input->post('btn_cancel'))
            redirect('remision/index');
    }

    public function delete() {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_remision->delete($id);
        }
        redirect('remision/index');
    }

    //formulario de busqueda de remisiones por fecha
    public function devolucion() {

    	$proveedor_id = uri_assoc('proveedor_id');

    	$data['remisiones'] = array();
        if($this->input->post('btn_consulta')){
        	if ($this->input->post('proveedor_id') != 0)  {

		    	$fecha_emision = strtotime(standardize_date($this->input->post('fecha_remision')));
		        $this->load->model('remision/mdl_remision');
		        //$this->load->model('detalle_remision/mdl_detalle_remision');

		        $data['nro_remisiones'] = $this->mdl_remision->select('id, nro_guia, razon_social, status')
		        			   ->where(array('proveedor_id' => $this->input->post('proveedor_id'), 'fecha_emision' => $fecha_emision, 'status' => 'pendiente'))
		        			   ->get()->result();

		        $remisiones = $this->mdl_remision->select('remision.id, nombre, nro_guia, razon_social,
		        				fecha_emision, remision_id, publicacion_id, cantidad, status,
		        				unidad_medida, precio_unitario_calculado, sector, precio_unitario_guia, importe, cantidad_devolucion, importe_neto, observaciones')

		        			   ->join('detalle_remision', 'remision.id = detalle_remision.remision_id')
		        			   ->join('publicacion', 'publicacion.id = detalle_remision.publicacion_id')
		        			   ->where(array('remision.proveedor_id' => $this->input->post('proveedor_id'), 'fecha_emision' => $fecha_emision))
		        			   ->get()->result();

				$data['remisiones'] = $remisiones;
				//print_r($remisiones);
        	}
        	else{
        		$this->session->set_flashdata('custom_error', 'Por favor seleccione un proveedor.');
        		$data['remisiones'] = array();
        	}
        }

		$this->mdl_remision->prep_form($proveedor_id);

		//calendario
        $this->template->add_css(public_url() . "bootstrap/datepicker/css/datepicker.css");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/bootstrap-datepicker.js");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js");

            $javascript_inline = "
            var startDate = new Date();
			var curr_date = startDate.getDate();
			var curr_month = startDate.getMonth() + 1;
			var curr_year = startDate.getFullYear();
			startDate = (curr_date < 10 ? '0' : '') + curr_date + '/' + (curr_month < 10 ? '0' : '') + curr_month + '/'  + curr_year;
            $('.calendar').datepicker({
            	setDate: new Date(),
                language: 'es',
                autoclose: 'true',
                startDate: '',
                endDate: '0',
                format: 'dd/mm/yyyy',
                todayBtn: 'linked',
                todayHighlight: 'true'
            });
            //$('#dpStartDate').data({date: startDate}).datepicker('update').children('input').val(startDate);
        ";

            //proveedores
            $data['proveedores'] = $this->mdl_proveedor->select('id, nombre')->get()->result();
            $data['proveedor_id'] = ($this->input->post('proveedor_id') != '')?$this->input->post('proveedor_id'):'0';

            //print_r($data['remisiones']);

            /*
             * template
            */
            $this->template->add_js($javascript_inline, 'embed', false);
            $this->template->add_css(public_url() . "remision/css/remision.css");
            $this->template->add_js(public_url() . "remision/js/remision.js");
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "remision/validate_remision.js");
            $this->template->write('header_title', 'Administrar Devoluci&oacute;n');
            $this->template->write('title', 'Administrar Devoluci&oacute;n');
            $this->template->write_view('content', 'form_devolucion_remision', $data);
            $this->template->render();
       // }
    }

    public function devolver() {

    	//$proveedor_id = uri_assoc('proveedor_id');
    	$remision_id = uri_assoc('remision_id');

    	$data['remisiones'] = array();
    	//validar que la remision tenga devoluciones
    	$tiene = $this->mdl_remision->tiene_devolucion($remision_id);
        if ($remision_id != '')  {

	        $this->load->model('remision/mdl_remision');
	        //$this->load->model('detalle_remision/mdl_detalle_remision');

	        $remisiones = $this->mdl_remision->select('remision.id, nombre, nro_guia, razon_social,
	        				fecha_emision, remision_id, publicacion_id, cantidad, precio_vendedor, precio_publicacion, comision,
	        				unidad_medida, precio_unitario_calculado, sector, precio_unitario_guia, importe, cantidad_devolucion, importe_neto, observaciones')
	        			   ->join('detalle_remision', 'remision.id = detalle_remision.remision_id')
	        			   ->join('publicacion', 'publicacion.id = detalle_remision.publicacion_id')
	        			   ->where(array('remision.id' => $remision_id))
	        			   ->get()->result();

			$data['remisiones'] = $remisiones;

			if($this->input->post('btn_submit')){

	        	$publicacion = $this->input->post('publicacion');
	        	$cantidad_devolucion = $this->input->post('cantidad_devolucion');
	        	$importe_neto = $this->input->post('importe_neto');
	        	//$remision_id = $this->input->post('remision_id');
	        	$resultado = false;
				foreach ($publicacion as $key => $fila) {
		        	$params_detalle_remision = array('cantidad_devolucion' => $cantidad_devolucion[$key], 'importe_neto' => $importe_neto[$key]);
		        	$this->db->where(array('publicacion_id' => $publicacion[$key], 'remision_id' => $remision_id));
					$detalle_remision_id = $this->db->update('detalle_remision', $params_detalle_remision);

					if($detalle_remision_id)
						$resultado = true;
		        	//$detalle_remision_id = $this->mdl_detalle_remision->save($publicacion[$key], $params_detalle_remision, false);
		        	//echo $cantidad_devolucion[$key];
		        }
		        if($resultado){
		        	$remision_update = $this->mdl_remision->save($remision_id, array('status' => 'devuelto'), false);
		        	$this->session->set_flashdata('alert_success', 'Se hizo la devoluci&oacute;n de publicaciones correctamente.');
		        	redirect('remision/index');
		        }
	        	else
	        		echo 'No pudimos modificar el detalles de la remisi&oacute;n';
        	}
			//print_r($remisiones);
        }
        else{
        	redirect('remision/index');
        }
            $this->mdl_remision->prep_form($remision_id);

            //calendario
            $this->template->add_css(public_url() . "bootstrap/datepicker/css/datepicker.css");
            $this->template->add_js(public_url() . "bootstrap/datepicker/js/bootstrap-datepicker.js");
            $this->template->add_js(public_url() . "bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js");

            $javascript_inline = "
            var startDate = new Date();
			var curr_date = startDate.getDate();
			var curr_month = startDate.getMonth() + 1;
			var curr_year = startDate.getFullYear();
			startDate = (curr_date < 10 ? '0' : '') + curr_date + '/' + (curr_month < 10 ? '0' : '') + curr_month + '/'  + curr_year;
            $('.calendar').datepicker({
            	setDate: new Date(),
                language: 'es',
                autoclose: 'true',
                startDate: '',
                endDate: '0',
                format: 'dd/mm/yyyy',
                todayBtn: 'linked',
                todayHighlight: 'true'
            });
            //$('#dpStartDate').data({date: startDate}).datepicker('update').children('input').val(startDate);
        ";

            //proveedores
            $data['proveedores'] = $this->mdl_proveedor->select('id, nombre')->get()->result();
            $data['proveedor_id'] = ($this->input->post('proveedor_id') != '')?$this->input->post('proveedor_id'):'0';

            //print_r($data['remisiones']);

            /*
             * template
            */
            $this->template->add_js($javascript_inline, 'embed', false);
            $this->template->add_css(public_url() . "remision/css/remision.css");
            $this->template->add_js(public_url() . "remision/js/remision.js");
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "remision/validate_remision.js");
            $this->template->write('header_title', 'Administrar Devoluci&oacute;n');
            $this->template->write('title', 'Realizar devoluciones');
            $this->template->write_view('content', 'devolucion_remision', $data);
            $this->template->render();
       // }
    }

    public function get_remisiones($proveedor_id) {
        $this->load->model('remision/mdl_remision');
        //$this->load->model('detalle_remision/mdl_detalle_remision');

        $remisiones = $this->mdl_remision->select('nombre, nro_guia, razon_social,
        				fecha_emision, remision_id, publicacion_id, cantidad,
        				unidad_medida, precio_unitario_guia, importe')
        			   ->join('detalle_remision', 'remision.id = detalle_remision.remision_id')
        			   ->join('publicacion', 'publicacion.id = detalle_remision.publicacion_id')
        			   ->where(array('remision.proveedor_id' => $proveedor_id))
        			   ->get()->result();
    	//print_r();
		echo json_encode($remisiones);
    }
    public function get_publicaciones_proveedor($proveedor_id, $fecha) {

       	//LA FECHA TIENE QUE ESTAR EN : dd-mm-yyyy
       	//fecha en yyyy/mm/dd

        $aux = explode("-", $fecha);
        $fecha = $aux[2].'/'.$aux[1].'/'.$aux[0];
        //convertir la fecha al nombre de día(lunes,martes,etc) para obtener el precio exacto.
        // $this->load->helper('fechas_helper');
        $nombre_dia = ObtenerNombreDia($fecha);

        $this->load->model('publicacion/mdl_publicacion');
        $this->db->query("SET lc_time_names = 'es_UY'");
        $consulta = $this->ConsultaPublicacionProveedor($fecha,$proveedor_id,null);
        // FB::log($consulta, "consulta consulta:\n");
        $publicaciones = $this->db->query($consulta)->result();
        // FB::log($publicaciones, "las publicaciones:\n");
        // FB::log($this->db->last_query(), "last query:\n");
		echo json_encode($publicaciones);

    }
     public function get_publicaciones_proveedor_nj($proveedor_id,$fecha) {

        //LA FECHA TIENE QUE ESTAR EN : dd-mm-yyyy
        //fecha en yyyy/mm/dd
        $aux = explode("-", $fecha);
        $fecha = $aux[2].'/'.$aux[1].'/'.$aux[0];
        //convertir la fecha al nombre de día(lunes,martes,etc) para obtener el precio exacto.
        // $this->load->helper('fechas_helper');
        $nombre_dia = ObtenerNombreDia($fecha);
        $this->load->model('publicacion/mdl_publicacion');
        $this->db->query("SET lc_time_names = 'es_UY'");
        $consulta = $this->ConsultaPublicacionProveedor($fecha,$proveedor_id,null);
        $publicaciones = $this->db->query($consulta)->result();
        return $publicaciones;
    }
    public function ConsultaPublicacionProveedor($fecha,$proveedor_id=null,$publicacion_id =null)
    {
        if ($publicacion_id!=null) {
            $provedor_publicacion =" id = '".$publicacion_id."'";
        }
        else
        {
            $provedor_publicacion =" proveedor_id = '".$proveedor_id."'";
        }
        $consulta = "select publicacion_id, publicacion_nombre, dia_mes_fecha_actual,precio_publico, fecha,comision, porcentaje_descuento_dia_normal, porcentaje_descuento_especial, fecha_especial_nombre,GREATEST(porcentaje_descuento_dia_normal,IFNULL(porcentaje_descuento_especial, 0)) as porcentaje_final , precio_publico *comision/100 as comision_final, precio_publico - (precio_publico*GREATEST( porcentaje_descuento_dia_normal,IFNULL(porcentaje_descuento_especial, 0))/100)-precio_publico *comision/100 as costo_unitario_final from  ( select TFinal.*,TFechas.porcentaje_descuento as porcentaje_descuento_especial,fecha_especial_nombre from ( select Tprecio.*, TDescuento.porcentaje_descuento as porcentaje_descuento_dia_normal from (select C.*, comision_publicacion.comision from (select A.*,B.precio as precio_publico,B.dia as fecha from  ( select publicacion.id as publicacion_id,nombre as publicacion_nombre, concat('{ \"dia\": ',day('".$fecha."'),', \"mes\": ',month('".$fecha."'),' }')as dia_mes_fecha_actual ,proveedor_id from publicacion where   ".$provedor_publicacion.")A inner join (select * from precio_publicacion where dia =  replace(replace(dayname('".$fecha."'),'é','e'),'á','a'))B on A.publicacion_id = B.publicacion_id)C, comision_publicacion where comision_publicacion.publicacion_id = C.publicacion_id and comision_publicacion.dia = fecha )TPrecio inner join (select publicacion_id,nombre,porcentaje_descuento from descuento_publicacion inner join dia_descuento on dia_descuento.id=dia_descuento_id where  instr(dia_descuento.fecha, replace(replace(dayname('".$fecha."'),'é','e'),'á','a'))>0 )TDescuento on TDescuento.publicacion_id = TPrecio.publicacion_id )TFinal left join  (select publicacion_id,porcentaje_descuento,fecha as fecha_especial,tipo_fecha as fecha_especial_nombre from descuento_publicacion inner join dia_descuento on dia_descuento.id=dia_descuento_id where fecha = concat('{ \"dia\": ', day('".$fecha."'), ', \"mes\": ',month('".$fecha."'),' }'))TFechas on TFechas.fecha_especial =TFinal.dia_mes_fecha_actual and TFinal.publicacion_id=TFechas.publicacion_id )T ";
        return $consulta;
    }
    public function get_publicaciones_proveedor_edicion($idremision)
    {
        $this->db->query("SET lc_time_names = 'es_PE'");
        $this->load->model('detalle_remision/mdl_detalle_remision');
        $result = $this->mdl_detalle_remision->select('*')->where('remision_id',$idremision)->get()->result();
        echo json_encode($result);
    }
    public function datos_publicacion() {
    	// $this->load->helper('date');
    	$fecha_emision = strtotime(standardize_date($this->input->post('fecha_remision')));
        $this->load->model('remision/mdl_remision');
        //$this->load->model('detalle_remision/mdl_detalle_remision');

        $remisiones = $this->mdl_remision->select('nombre, nro_guia, razon_social,
        				fecha_emision, remision_id, publicacion_id, cantidad,
        				unidad_medida, precio_unitario_guia, importe')
        			   ->join('detalle_remision', 'remision.id = detalle_remision.remision_id')
        			   ->join('publicacion', 'publicacion.id = detalle_remision.publicacion_id')
        			   ->where(array('remision.proveedor_id' => $this->input->post('proveedor_id'), 'fecha_emision' => $fecha_emision))
        			   ->get()->result();
    	//print_r($remisiones);
		//echo json_encode($remisiones);
		return $remisiones;
    }

    public function get_detalles_remision($id)
    {
        $this->load->model('detalle_remision/mdl_detalle_remision');
        return $this->mdl_detalle_remision->select('*')->where('remision_id',$id)->get()->result();
    }
    public function ver($remision_id) {
    	$remision_id = uri_assoc('remision_id');

        $remisiones = $this->mdl_remision->select('nombre, nro_guia, razon_social, status,
        				fecha_emision, remision_id, publicacion_id, cantidad, observaciones, sector,
        				unidad_medida, precio_unitario_guia, importe, cantidad_devolucion, importe_neto')
        			   ->join('detalle_remision', 'remision.id = detalle_remision.remision_id')
        			   ->join('publicacion', 'publicacion.id = detalle_remision.publicacion_id')
        			   ->where(array('remision_id' => $remision_id))
        			   ->get()->result();

		$data['remisiones'] = $remisiones;

        $this->template->add_css(public_url() . "remision/css/remision.css");
        //$this->template->add_js(public_url() . "remision/js/remision.js");
        $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
        //$this->template->add_js(public_url() . "remision/validate_remision.js");
        $this->template->write('header_title', 'Detalle Remisi&oacute;n');
        $this->template->write('title', 'Detalle Remisi&oacute;n');
        $this->template->write_view('content', 'detalle_remision', $data);
        $this->template->render();


    }
    public function get_comisiones_detalles_remision ($publicacion_id,$fecha)
    {

		$aux = explode("-", $fecha);
        $fecha = $aux[2].'/'.$aux[1].'/'.$aux[0];
        //convertir la fecha al nombre de día(lunes,martes,etc) para obtener el precio exacto.
        // $this->load->helper('fechas_helper');
        $nombre_dia = ObtenerNombreDia($fecha);
		$this->load->model('publicacion/mdl_publicacion');
        $this->db->query("SET lc_time_names = 'es_UY'");
        $consulta = $this->ConsultaPublicacionProveedor($fecha,null,$publicacion_id);
        // FB::log($consulta, "consulta info:\n");
        $publicacion = $this->db->query($consulta)->row();
        // FB::log($this->db->last_query(), "last query:\n");
        echo json_encode($publicacion);
    }

    public function  datos_adicionales($proveedor_id,$fecha,$detalles)
    {
        //$fecha esta en timestamp , se debe convertir a dd-mm-yyyy
        $now = time();
        $fecha = format_date_to_show($fecha, $now);
        $aux = explode(" ", $fecha);
        $mes = $aux[1];
        switch ($mes) {
            case 'Ene':
               $mes = '01';
                break;
            case 'Feb':
               $mes = '02';
                break;
             case 'Mar':
               $mes = '03';
                break;
             case 'Abr':
               $mes = '04';
                break;
             case 'May':
               $mes = '05';
                break;
            case 'Jun':
               $mes = '06';
                break;
            case 'Jul':
               $mes = '07';
                break;
             case 'Ago':
               $mes = '08';
                break;
             case 'Set':
               $mes = '09';
                break;
             case 'Oct':
               $mes = '10';
                break;
            case 'Nov':
               $mes = '11';
                break;
             case 'Dic':
               $mes = '12';
                break;

            default:
                 $mes = '01';
                break;
        }
        $fecha = $aux[0].'-'.$mes.'-'.$aux[2];
        $results = $this->get_publicaciones_proveedor_nj($proveedor_id,$fecha);
        $collection = $detalles;
        $i=0;

        foreach ( $detalles as $detalle ) {

           foreach ($results as $result) {

               if ($detalle->publicacion_id == $result->publicacion_id) {
                 $descuento_aplicado = $result->porcentaje_descuento_dia_normal;
       // convertir de json a texto plano

         $fecha = strtotime($fecha);
         $fecha = day_to_spanish($fecha);
       if($result->porcentaje_descuento_especial > $descuento_aplicado){
			$descuento_aplicado = $result->porcentaje_descuento_especial;
        	$fecha = $result->fecha_especial_nombre;
       }

                $precio_vendedor = $result->costo_unitario_final + ($result->comision/100) * $result->precio_publico;
                $collection[$i]->{'publicacion_id'} = $result->publicacion_id;
                $collection[$i]->{'precioPublico'} = $result->precio_publico;
                $collection[$i]->{'porcentajeFinal'} = $result->porcentaje_final;
                $collection[$i]->{'comision'} = $result->comision;
                $collection[$i]->{'nombrePublicacion'} = $result->publicacion_nombre;
                $collection[$i]->{'descuentoAplicado'} = $descuento_aplicado;
                $collection[$i]->{'fecha'} = $fecha;
                //$collection[$i]->{'precio_vendedor'} = $precio_vendedor;
                $collection[$i]->{'ganancia_sindicato'} = $detalle->cantidad * ($precio_vendedor - $result->costo_unitario_final);
                $i++;
                break;
               }
           }

        }
            return $collection;
    }

	public function limpiar_detalles_remision($detalles)
	{
	    //seleccionar solo los datos a guardar
	    $collection = array();

	    foreach ($detalles as $detalle) {
	    	$objeto['id'] =$detalle['id'];
	       	$objeto['publicacion_id']  = $detalle['publicacion_id'];
	       	$objeto['cantidad'] = $detalle['cantidad'];
	       	$objeto['precio_unitario_calculado'] = $detalle['precio_unitario_calculado'] ;
	       	$objeto['precio_unitario_guia'] = $detalle['precio_unitario_guia'];
	       	$objeto['importe']= $detalle['importe'];
	       	$objeto['remision_id']= $detalle['remision_id'];
	       	$objeto['precio_publicacion']= $detalle['precioPublico'];
	        $objeto['porcentaje_dscto']= $detalle['descuentoAplicado'];
	        $objeto['comision']= $detalle['comision'];
	        $objeto['descripcion']= $detalle['nombrePublicacion'];
	        $objeto['precio_vendedor']= $detalle['precio_vendedor']; // unitario calculado mas el porcentaje de comision del preico de la publicacion
	       	array_push($collection, $objeto);
	    }
	    return $collection;
	}

	function cambiar_estado_ajax(){
        $remision_id = $this->input->post("remision_id", true);
        $estado_cambio = $this->input->post("estado", true);
        $estado_remision = $this->mdl_remision
                                ->select('status')
                                ->where(
                                    array('id' => $remision_id)
                                )->get()
                                ->row();
		if($estado_remision->status == 'devuelto' && ($estado_cambio == 'Pagado' || $estado_cambio == 'Pendiente' || $estado_cambio == 'Anulado')){
	        $estado_remision_id = $this->mdl_remision->save($remision_id, array('status' => $estado_cambio), false);
	        if($estado_remision_id)
	        	echo json_encode(array('estado' => 'ok'));
		}
		if($estado_remision->status != 'devuelto' && ($estado_cambio == 'Pendiente' || $estado_cambio == 'Anulado')){
	        $estado_remision_id = $this->mdl_remision->save($remision_id, array('status' => $estado_cambio), false);
	        if($estado_remision_id)
	        	echo json_encode(array('estado' => 'ok'));
		}
		if($estado_remision->status != 'devuelto' && $estado_cambio == 'Pagado')
			echo json_encode(array('estado' => 'A&uacute;n no se hizo la devoluci&oacute;n de publicaciones para cambiar a este estado.'));
	}

    public function devoluciones() {
        $this->load->model('mdl_remision_table');
        $this->load->model('proveedor/mdl_proveedor');

        $this->mdl_remision->default_limit = $this->config->item('results_per_page');

        $this->mdl_remision->order_by = uri_assoc('order_by');
        $this->mdl_remision->order = uri_assoc('order');

        $data['proveedores'] = $this->mdl_proveedor->select('id, nombre')->get()->result();
		$data['table_headers'] = $this->mdl_remision_table->get_table_headers();

		$data['remisions'] = $this->mdl_remision
        		->select('id, nro_guia, razon_social, codigo, ruc, tipo, fecha_emision,
                		fecha_recepcion, fecha_pago, proveedor_id, status')
				->where('status', 'devuelto')
                ->paginate(array('select', 'where'))->result();
		
        /*
         * template
         */

        $this->template->add_js(bootstrap_js() . 'bootbox/bootbox.min.js');
        $this->template->add_js(public_url(). 'remision/js/index.js');
        $this->template->write('header_title', 'Listado de remisiones devueltas');
        $this->template->write('title', 'Listado de remisiones devueltas');
        $this->template->write_view('content', 'remisiones_devueltas', $data);
        $this->template->render();
    }





}

?>