<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Remision2 extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->_post_handler();
        $this->load->model(
                        array(
                            'remision2/mdl_remision',
                            'detalle_remision/mdl_detalle_remision',
                            'proveedor/mdl_proveedor'
                        )
        );
    }

    public function index() {
        $this->load->model('mdl_remision_table');
        $this->load->model('proveedor/mdl_proveedor');

        $this->mdl_remision->default_limit = $this->config->item('results_per_page');

        $this->mdl_remision->order_by = uri_assoc('order_by');
        $this->mdl_remision->order = uri_assoc('order');

        $data = array(
            'remisions' => $this->mdl_remision->paginate()->result(),
            'table_headers' => $this->mdl_remision_table->get_table_headers(),
            'proveedores' => $this->mdl_proveedor->select('id, nombre')->get()->result()
        );
        $this->template->write('header_title', 'Listado de Remision');
        $this->template->write('title', 'Listado de Remision');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form()
    {
        // var_dump($_POST);
        // exit;
        $this->load->library('lib_remision');
        $remision_id = uri_assoc('id');

        if ($this->mdl_remision->run_validation()) {
            /* override de my_model en mdl_remision para "save" (por eso no uso aca db_array
            para parsear las fechas, lo hago en mdl_remision, revisar el mdl)
            para tratar siempre de mantener limpio el codigo
            */
            $remision_id = $this->mdl_remision->save($remision_id);
            $this->load->library('lib_remision');
            $detalles = $this->input->post('detalle');
            // var_dump($detalles);
            $extra = array(
                        'remision_id' => $remision_id,
                    );
            $detalles = $this->lib_remision->formatDetalleRemision($detalles, $extra);

            // var_dump($detalles); exit();

            /* si la remision no es nueva y ahora hemos eliminado detalles
            entonces tenemos que borrarlo tmb de la base de datos */
            $this->mdl_detalle_remision->deleteDetalles($detalles, $remision_id);
            $this->mdl_detalle_remision->guardarDetalles($detalles);

            redirect('remision2/index');

        } else {
            $this->load->model('proveedor/mdl_proveedor');
            $this->mdl_remision->prep_form($remision_id);
            //calendario
            $this->template->add_js(public_url() . "jquery/jquery.maskMoney.min.js");
            $this->template->add_css(public_url(). 'bootstrap/datepicker/css/datepicker.css');
            $this->template->add_js(public_url(). 'bootstrap/datepicker/js/bootstrap-datepicker.js');
            $this->template->add_js(public_url(). 'bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js');

            $this->template->add_js(base_js() . "ckeditor/ckeditor.js", 'import', false, 'footer');

            /* para que el remision_id sea usando en javascript */
            $remision_id = $remision_id == null ? -1 : $remision_id;

            $javascript_inline = "
                var ID = $remision_id;
	             $('.datepicker').datepicker({
                    language: 'es',
                    minViewMode: 'days',
                    autoclose: 'true',
                    format: 'dd/mm/yyyy',
                    endDate: '0d'
                });
                $('.numeroentero, #cantidad').maskMoney({
                    precision: 0,
                    defaultZero: false,
                    thousands:'',
                    decimal:'.'
                });
                $('#precio-unitario-guia, #precio-unitario-calculado, #importe').maskMoney({
                    precision: 3,
                    defaultZero: true,
                    thousands: '',
                    decimal: '.'
                })
	            CKEDITOR.replace('observaciones', {toolbar:'Miconfig'});
	        ";

            //proveedores
            $data['proveedores'] = $this->mdl_proveedor->getAllProveedores();

            $detalles = $this->input->post('detalle');
            $data['detalles'] = $this->lib_remision->formatDetalleRemision($detalles, array(), true);
            /* si no hay datos de post, entonces puede que exista detalles de la remision
            (si es que existe la remisión tmb) */
            if (empty($data['detalles']) && $remision_id != -1) {
                $data['detalles'] = $this->mdl_detalle_remision->getDetalles($remision_id);
            }
            /*
             * template
            */
        	$this->template->add_js($javascript_inline, 'embed', false);
            $this->template->add_css(public_url() . "remision2/css/remision.css");
            $this->template->add_js(public_url() . "remision2/js/remision.js");
            //$this->template->add_js(public_url() . "jquery/jquery.numberformatter-1.2.3.min.js");
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "remision/validate_remision.js");
            $this->template->write('header_title', 'Administrar Remision');
            $this->template->write('title', 'Administrar Remision');
            $this->template->write_view('content', 'form', $data);
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('remision2/form');
        if ($this->input->post('btn_cancel'))
            redirect('remision2/index');
    }

    public function delete() {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_remision->delete($id);
        }
        redirect('remision/index');
    }


    public function devolucion() {

    	$proveedor_id = uri_assoc('proveedor_id');

    	$data['remisiones'] = '';
        if($this->input->post('btn_consulta')){
        	if ($this->input->post('proveedor_id') != 0)  {

		    	$fecha_emision = strtotime(standardize_date($this->input->post('fecha_remision')));
		        $this->load->model('remision/mdl_remision');
		        //$this->load->model('detalle_remision/mdl_detalle_remision');

		        $remisiones = $this->mdl_remision->select('nombre, nro_guia, razon_social,
		        				fecha_emision, remision_id, publicacion_id, cantidad,
		        				unidad_medida, precio_unitario_guia, importe, cantidad_devolucion, importe_neto')

		        			   ->join('detalle_remision', 'remision.id = detalle_remision.remision_id')
		        			   ->join('publicacion', 'publicacion.id = detalle_remision.publicacion_id')
		        			   ->where(array('remision.proveedor_id' => $this->input->post('proveedor_id'), 'fecha_emision' => $fecha_emision))
		        			   ->get()->result();

				$data['remisiones'] = $remisiones;
				//print_r($remisiones);
        	}
        	else{
        		$this->session->set_flashdata('custom_error', 'Por favor seleccione un proveedor.');
        		$data['remisiones'] = '';
        	}
        }

        if($this->input->post('btn_submit')){
        	$publicacion = $this->input->post('publicacion');
        	$cantidad_devolucion = $this->input->post('cantidad_devolucion');
        	$importe_neto = $this->input->post('importe_neto');
        	$resultado = false;
			foreach ($publicacion as $key => $fila) {
	        	$params_detalle_remision = array('cantidad_devolucion' => $cantidad_devolucion[$key], 'importe_neto' => $importe_neto[$key]);
	        	$this->db->where('publicacion_id', $publicacion[$key]);
				$detalle_remision_id = $this->db->update('detalle_remision', $params_detalle_remision);
				if($detalle_remision_id)
					$resultado = true;
	        	//$detalle_remision_id = $this->mdl_detalle_remision->save($publicacion[$key], $params_detalle_remision, false);
	        	//echo $cantidad_devolucion[$key];
	        }
	        if($resultado) {
	        	$this->session->set_flashdata('alert_success', 'Se hizo la devoluci&oacute;n de publicaciones correctamente.');
	        	redirect('remision/index');
	        }
        	else
        		echo 'No pudimos modificar el detalles de la remisi&oacute;n';
        }


        /*if ($this->mdl_remision->run_validation()) {

        	$cantidades = $this->input->post('cantidad');
        	$precios = $this->input->post('precio');
        	$importes = $this->input->post('importe');
        	$publicacion = $this->input->post('publicacion');

        	$remision_id = $this->mdl_remision->save($proveedor_id);

            if($remision_id)
            {
            	//guardamos el detalle de remision
            	$this->load->model('detalle_remision/mdl_detalle_remision');
                foreach ($publicacion as $key => $fila) {
	        		$params_detalle_remision = array('remision_id' => $remision_id, 'publicacion_id' => $fila, 'cantidad' => $cantidades[$key], 'unidad_medida' => 'unid.', 'precio_unitario' => $precios[$key], 'importe' => $importes[$key]);
	        		$detalle_remision_id = $this->mdl_detalle_remision->save('', $params_detalle_remision, false);
	        		//print_r($params_detalle_remision);
	        	}
            }
           redirect('remision/index');

        }*/
        //else {
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

    public function get_publicaciones_proveedor($proveedor_id,$fecha) {

       // dd-mm-yyyy
        // fecha en yyyy/mm/dd
    // var_dump($fecha); exit();
        $aux = explode("-", $fecha);
        $fecha = $aux[2].'/'.$aux[1].'/'.$aux[0];
        //convertir la fecha al nombre de día(lunes,martes,etc) para obtener el precio exacto.
        // $this->load->helper('fechas_helper');
        $nombre_dia = ObtenerNombreDia($fecha);
        $this->load->model('publicacion/mdl_publicacion');
        $this->db->query("SET lc_time_names = 'es_UY'");
        $publicaciones = $this->db->query("select publicacion_id,publicacion_nombre, dia_mes_fecha_actual,precio_publico, fecha,comision, porcentaje_descuento_dia_normal, porcentaje_descuento_especial,fecha_especial_nombre,GREATEST(porcentaje_descuento_dia_normal,IFNULL(porcentaje_descuento_especial, 0)) as porcentaje_final , precio_publico *comision/100 as comision_final, precio_publico - (precio_publico*GREATEST( porcentaje_descuento_dia_normal,IFNULL(porcentaje_descuento_especial, 0))/100)-precio_publico *comision/100 as costo_unitario_final  from ( select TFinal.*,TFechas.porcentaje_descuento as porcentaje_descuento_especial,fecha_especial_nombre  from ( select Tprecio.*, TDescuento.porcentaje_descuento as porcentaje_descuento_dia_normal from (select C.*, comision_proveedor.comision from (select A.*,B.precio as precio_publico,B.fecha from (select publicacion.id as publicacion_id,nombre as publicacion_nombre, concat('{ \"dia\": ',day('".$fecha."'),', \"mes\": ',month('".$fecha."'),' }')as dia_mes_fecha_actual ,proveedor_id from publicacion  where proveedor_id='".$proveedor_id."')A inner join (select * from precio_publicacion where INSTR(fecha, dayname('".$fecha."'))>0)B  on A.publicacion_id = B.publicacion_id)C, comision_proveedor where comision_proveedor.proveedor_id = C.proveedor_id  and INSTR(comision_proveedor.fecha,C.fecha)>0  )TPrecio inner join (select publicacion_id,nombre,porcentaje_descuento from descuento_publicacion  inner join dia_descuento  on dia_descuento.id=dia_descuento_id where  INSTR(dia_descuento.fecha, dayname('".$fecha."')) )TDescuento on TDescuento.publicacion_id = TPrecio.publicacion_id )TFinal left join (select publicacion_id,porcentaje_descuento,fecha as fecha_especial,nombre as fecha_especial_nombre from descuento_publicacion  inner join dia_descuento  on dia_descuento.id=dia_descuento_id where  fecha =  concat('{ \"dia\": ', day('".$fecha."'),', \"mes\": ',month('".$fecha."'),' }'))TFechas on TFechas.fecha_especial =TFinal.dia_mes_fecha_actual and  TFinal.publicacion_id=TFechas.publicacion_id )T ")->result();
		echo json_encode($publicaciones);

    }
    public function get_publicaciones_proveedor_edicion($idremision)
    {

        $this->load->model('detalle_remision/mdl_detalle_remision');
        $result = $this->mdl_detalle_remision->select('*')->where('remision_id','2')->get()->result();
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

        $remisiones = $this->mdl_remision->select('nombre, nro_guia, razon_social,
        				fecha_emision, remision_id, publicacion_id, cantidad, observaciones, sector,
        				unidad_medida, precio_unitario, importe, cantidad_devolucion, importe_neto')
        			   ->join('detalle_remision', 'remision.id = detalle_remision.remision_id')
        			   ->join('publicacion', 'publicacion.id = detalle_remision.publicacion_id')
        			   ->where(array('remision_id' => $remision_id))
        			   ->get()->result();

		$data['remisiones'] = $remisiones;

        $this->template->add_css(public_url() . "remision/css/remision.css");
        $this->template->add_js(public_url() . "remision/js/remision.js");
        $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
        $this->template->add_js(public_url() . "remision/validate_remision.js");
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
           $publicacion = $publicaciones = $this->db->query("select  precio_publico, fecha,comision, porcentaje_descuento_dia_normal, porcentaje_descuento_especial,fecha_especial_nombre,GREATEST(porcentaje_descuento_dia_normal,IFNULL(porcentaje_descuento_especial, 0)) as porcentaje_final , precio_publico *comision/100 as comision_final, precio_publico - (precio_publico*GREATEST( porcentaje_descuento_dia_normal,IFNULL(porcentaje_descuento_especial, 0))/100)-precio_publico *comision/100 as costo_unitario_final  from ( select TFinal.*,TFechas.porcentaje_descuento as porcentaje_descuento_especial,fecha_especial_nombre  from ( select Tprecio.*, TDescuento.porcentaje_descuento as porcentaje_descuento_dia_normal from (select C.*, comision_proveedor.comision from (select A.*,B.precio as precio_publico,B.fecha from (select publicacion.id as publicacion_id,nombre as publicacion_nombre, concat('{ \"dia\": ',day('".$fecha."'),', \"mes\": ',month('".$fecha."'),' }')as dia_mes_fecha_actual ,proveedor_id from publicacion  where id='".$publicacion_id."')A inner join (select * from precio_publicacion where INSTR(fecha, dayname('".$fecha."'))>0)B  on A.publicacion_id = B.publicacion_id)C, comision_proveedor where comision_proveedor.proveedor_id = C.proveedor_id  and INSTR(comision_proveedor.fecha,C.fecha)>0  )TPrecio inner join (select publicacion_id,nombre,porcentaje_descuento from descuento_publicacion  inner join dia_descuento  on dia_descuento.id=dia_descuento_id where  INSTR(dia_descuento.fecha, dayname('".$fecha."')) )TDescuento on TDescuento.publicacion_id = TPrecio.publicacion_id )TFinal left join (select publicacion_id,porcentaje_descuento,fecha as fecha_especial,nombre as fecha_especial_nombre from descuento_publicacion  inner join dia_descuento  on dia_descuento.id=dia_descuento_id where  fecha =  concat('{ \"dia\": ', day('".$fecha."'),', \"mes\": ',month('".$fecha."'),' }'))TFechas on TFechas.fecha_especial =TFinal.dia_mes_fecha_actual and  TFinal.publicacion_id=TFechas.publicacion_id )T ")->row();
           echo json_encode($publicacion);

    }

}

?>