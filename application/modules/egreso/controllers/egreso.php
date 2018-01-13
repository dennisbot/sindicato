<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Egreso extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->language('mcb', 'spanish');
        $this->load->helper(array('uri', 'icon'));
        $this->_post_handler();
        $this->load->model('mdl_egreso');
    }

    public function index() {
        $this->load->model('mdl_egreso_table');
        $this->mdl_egreso->default_limit = $this->config->item('results_per_page');

        $this->mdl_egreso->order_by = uri_assoc('order_by');
        $this->mdl_egreso->order = uri_assoc('order');

        $data = array(
            'egresos' => $this->mdl_egreso->paginate()->result(),
            'table_headers' => $this->mdl_egreso_table->get_table_headers()
        );
             /*
         * template
         */
        $this->template->write('header_title', 'Listado de Egresos');
        $this->template->write('title', 'Listado de Egresos');
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function form()
    {
        // $javascript_inline = 'CKEDITOR.replace("concepto", {toolbar:"Miconfig"}); ';

        // $this->template->add_js('../assets/js/ckeditor/ckeditor.js', 'import', false, 'footer');
        // $this->template->add_js($javascript_inline, 'embed', false);
        $id = uri_assoc('id');
        if ($this->mdl_egreso->run_validation()) {
            $this->mdl_egreso->save($id, $db_array);
            redirect('egreso/index');
        } else {
            $data['fecha'] = date('d/m/Y',time());
            if (isset($id)) {
            $data['fecha']  = date('d/m/Y',$this->mdl_egreso->get_by_id($id)->fecha) ;
            }
            $this->mdl_egreso->prep_form($id);

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
            $this->template->write('header_title', 'Gestionar Egresos');
            $this->template->write('title', 'Gestionar Egresos');
            $this->template->write_view('content', 'form',$data);
            $this->template->render();
        }
    }

    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('egreso/form');
        if ($this->input->post('btn_cancel'))
            redirect('egreso/index');
    }

    public function delete() {
        $id = uri_assoc('id');
        if ($id) {
            $this->mdl_egreso->delete($id);
        }
        redirect('egreso/index');

    }

    public function balance_publicacion()
    {
        $this->load->model('detalle_remision/mdl_detalle_remision');
        $this->load->model('deuda/mdl_deuda');
        $this->load->model('pago/mdl_pago');
        $this->load->model('detalle_pauta/mdl_detalle_pauta');

        /* egresos de los pagos de las remisiones (sindicato) */
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

    public function prueba()
    {
        $this->load->helper('date_helper');
        $post_date = '1079621429';
        $now = time();
        // numero a date
        echo format_date_to_show($post_date, $now);
        //date a numero
        echo strtotime(standardize_date('18 Mar 2004'));
    }
    public function obtener_egresos()
    {
   
    //         select remision_id,descripcion,precio_unitario_guia,cantidad,cantidad_devolucion,cantidad_total,nombre_dia,
    // precio_publico,porcentaje_comision,(porcentaje_comision /100)* precio_publico as ganancia_unitaria,(porcentaje_comision /100)* precio_publico *cantidad_total
    // as ganancia_total from
    // (
    // select  C.*,porcentaje_comision from(select A.*,B.nombre_dia,B.precio_publico from(select remision.id as remision_id, proveedor_id,detalle_remision.id as detalle_remision_id,publicacion_id,descripcion,
    // precio_unitario_guia,cantidad,cantidad_devolucion,(cantidad -cantidad_devolucion)as cantidad_total
    // from remision  inner join detalle_remision
    // on  detalle_remision.remision_id =remision.id where fecha_emision = '1363582800')A inner
    // join (select publicacion_id,nombre as nombre_dia,precio as precio_publico from precio_publicacion inner join dias on dias.id=dias_id where dias.nombre ='lunes')
    //  B on B.publicacion_id=A.publicacion_id)C
    // ,

    // (select proveedor_id,dia_descuento.nombre,comision_sindicato as porcentaje_comision from comision_sindicato
    // inner join dia_descuento on dia_descuento.id=comision_sindicato.dia_descuento_id) D where C.proveedor_id=D.proveedor_id and D.nombre =C.nombre_dia
    // )Tabla_ganancia

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


    }

}

?>