<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pauta extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('uri', 'fechas'));
        $this->_post_handler();
    }
    public function index()
    {
        $this->ver();
        /*$this->template->add_js(public_url() . 'jquery/fullcalendar/fullcalendar.js');
        $this->template->add_js(public_url() . 'jquery/fullcalendar/custom.js');
        $this->template->add_css(public_url() . 'jquery/fullcalendar/css/fullcalendar.css');
        $this->template->add_css(public_url() . 'jquery/fullcalendar/css/fullcalendar.print.css');
        $this->template->add_css(public_url() . 'jquery/fullcalendar/css/custom.css');
        $this->template->write_view("content", "index");
        $this->template->render();*/
    }
    public function generar()
    {
        if ($this->input->post('generar-pauta')) {


            $this->load->model(
                array(
                        'mdl_pauta',
                        /* para las publicaciones por detalle remision */
                        'detalle_remision/mdl_detalle_remision',
                        'vendedor/mdl_vendedor'
                )
            );
            $ok = $this->mdl_pauta->run_validation('custom_validation');

            $remision_id = $this->input->post('remision_id', true);
            $pauta_id = $this->mdl_pauta->save();

            /*
                para generar detalles necesitamos remision_id para
                recuperar los detalles de la remision y de esa manera
                identificar a que publicaciones corresponden los detalles
                de la pauta que vamos a generar
            */

            $keys_descripciones = $this->input->post('descripciones');

            $this->mdl_pauta->generar_detalles($pauta_id, $remision_id, $keys_descripciones);

            !$ok || redirect('pauta/listado/pauta_id/' . $pauta_id . '/remision_id/' . $remision_id);
        }
        // die(var_dump('sdfsdf'));

        $idproveedor = $this->input->post('idproveedor');
        // die(var_dump($data));
        $this->load->model(
                           array(
                                'proveedor/mdl_proveedor',
                                'detalle_remision/mdl_detalle_remision',
                                'descripcion_tipo_plantilla/mdl_descripcion_tipo_plantilla',
                           )
       );

        $data['descripciones'] = $this->mdl_descripcion_tipo_plantilla->getKeyPairDescripcion();


        $data["idproveedor"] = $idproveedor;

        $data["proveedor"] = $this->input->post('proveedor');

        $data['remision_id'] = $this->input->post('remision_id');

        /*$data['publicaciones'] = $this->mdl_proveedor->getPublicacionesByProveedor($idproveedor);
        var_dump($data['publicaciones']);*/
        $data['publicaciones'] = $this->mdl_detalle_remision->getPublicacionesByDetalleRemision($data['remision_id']);
        // die(var_dump($data['publicaciones']));
        $data['curdate'] = $this->input->post('curdate');

        $data['day_number'] = date('N');
        // die(var_dump($data['day_number']));
        /* para el timepicker */
        $this->template->add_css(public_url() . "bootstrap/timepicker/css/bootstrap-timepicker.min.css");
        $this->template->add_js(public_url() . "bootstrap/timepicker/js/bootstrap-timepicker.min.js");

        /* para el chosen */
        $this->template->add_css(public_url() . "chosen/chosen/chosen.css");
        $this->template->add_js(public_url() . "chosen/chosen/chosen.jquery.min.js");

        $javascript_inline = "
            $('.timepicker').timepicker();
            $('.chosen-select').chosen();
        ";
        $this->template->add_js($javascript_inline, "embed");
        $css_inline = '
            .input-append {
                display: block !important;
                padding: 0;
            }
            .timepicker {
                width: 180px;
            }
        ';
        $this->template->add_css($css_inline, "embed");

        $this->template->write_view('content', 'generar', $data);
        $this->template->render();
    }

    public function ver()
    {
        /* para el datepicker */
        $this->template->add_css(public_url() . "bootstrap/datepicker/css/datepicker.css");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/bootstrap-datepicker.js");
        $this->template->add_js(public_url() . "bootstrap/datepicker/js/locales/bootstrap-datepicker.es.js");
        /* para el timepicker */
        $this->template->add_css(public_url() . "bootstrap/timepicker/css/bootstrap-timepicker.min.css");
        $this->template->add_js(public_url() . "bootstrap/timepicker/js/bootstrap-timepicker.min.js");
        /* custom css */
        $this->template->add_css(public_url() . "pauta/css/ver.css");
        /* custom js */
        $this->template->add_js(public_url() . "pauta/js/ver.js");

        /* continuamos con la lógica de la aplicación */
        /* fecha del dia */
        $data["curdate"] = $this->input->post('fecha') ? : date("d/m/Y", time());

        /* luego de haber hecho post */
        $this->load->model('remision/mdl_remision');
        $fecha = $data["curdate"];
        $fecha = strtotime(to_standard_date($fecha));
        $data['report_date'] = report_date($fecha);
        $data["remisiones"] = $this->mdl_remision->listRemisionesByDate($fecha);
        // die(var_dump($data["remisiones"]));

        $this->template->write('header_title', 'Ver/Generar pautas:');
        $this->template->write('title', 'Ver/Generar pautas');
        $this->template->write_view('content', 'ver_generar_pautas', $data);
        $this->template->render();
    }

    public function listado()
    {
        $pauta_id = uri_assoc('pauta_id');
        $remision_id = uri_assoc('remision_id');

        if (!$remision_id or !$pauta_id ) {
            $this->session->set_flashdata('custom_error', 'Debe ingresar una pauta y una remisíón válidos.');
            redirect('pauta/ver');
        }

        $this->load->model(
                    array(
                        'vendedor/mdl_vendedor',
                        'pauta/mdl_pauta',
                        'detalle_pauta/mdl_detalle_pauta',
                        'remision/mdl_remision',
                        'detalle_remision/mdl_detalle_remision',
                        'devolucion/mdl_devolucion',
                    )
                );

        $existe_pauta = $this->mdl_pauta->existe_pauta($pauta_id);
        if (!$existe_pauta) {
            $this->session->set_flashdata('custom_error', 'La pauta no existe, ingrese una pauta válida.');
            redirect('pauta/ver');
        }
        $remision = $this->mdl_remision->existe_remision($remision_id);
        if (!$remision) {
            $this->session->set_flashdata('custom_error', 'La remisión no existe, ingrese una remisión válida.');
            redirect('pauta/ver');
        }
        $ok_remision_pauta = $this->mdl_remision->get_pauta_id(array('remision_id' => $remision_id));
        if ($ok_remision_pauta != $pauta_id) {
            $this->session->set_flashdata('custom_error', 'La pauta y la remisión no corresponden.');
            redirect('pauta/ver');
        }
        $pauta = $this->mdl_pauta->get_by_id($pauta_id);

        $today = date('d:m:Y', time());
        $fecha_pauta = date('d:m:Y', $pauta->fecha);

        $data['show_anular'] = $today == $fecha_pauta;
        /* esta linea se pondrá en "or" en lugar de "and" si se quiere probar exhaustivamente */
        // $data['cargar_edit_in_place'] = ($remision->status == 'pendiente' && $today == $fecha_pauta);
        $data['cargar_edit_in_place'] = true;
        /* fecha del listado */

        $fecha = $pauta->fecha;
        $data["medio_transporte"] = $pauta->medio_transporte;
        $data["hora_llegada"] = $pauta->hora_llegada;

        $data["proveedor"] = $remision->proveedor_id;
        // var_dump($remision_id);
        $publicaciones = $this->mdl_detalle_remision->getPublicacionesByDetalleRemisionDetalles($remision_id);
        // var_dump($publicaciones);exit;
        // var_dump($this->db->last_query());
        // exit;
        // var_dump($this->db->last_query());
        // var_dump($publicaciones);exit;
        /*
            le agregaremos una propiedad a la entidad publicacion
            la cual contendrá la cantidad de periodicos acumulados para la plantilla
            del dia elegido
        */
        $descripcion_id = date('N', $pauta->fecha);
        $data["publicaciones"] = $publicaciones;
        // var_dump($data["publicaciones"]);
        /* tenemos que pasar la pauta id tmb
         * en caso se tenga que anular la pauta */
        $data['pauta_id'] = $pauta_id;

        // var_dump($vendedores);
        $vendedores = $this->mdl_detalle_pauta->get_detalle_pauta($pauta_id, $remision_id);
        // $res = $this->db->last_query();
        /*var_dump($res);
        var_dump($vendedores);
        exit;*/
        /*var_dump($this->db->last_query());
        var_dump($vendedores);exit;*/
        // FB::log($this->db->last_query(), "last last:\n");
        // var_dump($vendedores);
        // var_dump(count($vendedores));
        // exit;
        $num_rows = count($vendedores);
        /* esto será usado para saber las devoluciones de la semana anterior*/
        $amount = floor($num_rows / 2) + $num_rows % 2;
        $data["amount"] = $amount;
        $data["vendedores"] = array_chunk($vendedores, $amount);

        $report_date = report_date($fecha);
        $data["report_date"] = $report_date;
        $data["date"] = date('d/m/Y', $fecha);
        /* si se quiere la vista para imprimir o para ver en pantalla */
        $print = uri_assoc('to_print');
        if ($print) {
            $this->imprimir_listado($data);
        }
        else {
            $this->mostrar_listado($data);
        }
    }
    private function imprimir_listado($data)
    {
        // die(var_dump($data));
        $this->template->set_template('print_pauta');
        $this->template->add_css(public_url() . "pauta/css/imprimir_listado.css");
        $this->template->add_js(public_url() . "jquery/jquery-1.9.1.js");
        $this->template->add_js(public_url() . "pauta/js/imprimir_listado.js");
        $this->template->write("header_title", "Pauta");
        $title =  '<strong>Pauta</strong> del Dia ' . $data["report_date"] . " -- (". $data["date"] .')<br>';
        $title .= '<strong>Hora de Llegada:</strong> ' . $data["hora_llegada"];
        $title .= ' <strong>Medio de transporte:</strong> ' . $data["medio_transporte"];
        $this->template->write('title',$title);
        $this->template->write_view("content", "print_listado", $data);
        $this->template->render();
    }
    private function mostrar_listado($data)
    {
         /* para el input numeral */
        $cur_url = current_url();
        $this->template->add_css(public_url() . "pauta/css/plantilla.css");
        $css_inline = "
            h1 {
                font-family: 'openSansLight';
                font-weight: 300;
                font-size: 22px;
            }
            h1 strong {
                font-weight: bold;
            }
            table tr td {
                vertical-align: middle !important;
            }
            table.vendedores tr td>div {
                height: 20px;
            }
            #caja-botones {
                float: right;
            }
            #imprimir {
                width: 100%;
                display: block;
            }
            #anular {
                display: block;
                margin-top: 20px;
            }
        ";
        $this->template->add_css($css_inline, "embed");
        $this->template->add_js(bootstrap_js() . 'bootbox/bootbox.min.js');
        $this->template->add_js(public_url() . 'jquery/jquery.editinplace.js');
        $this->template->add_js(public_url() . "jquery/jquery.maskMoney.min.js");
        $cargar_edit_in_place = $data['cargar_edit_in_place'] ? 1: 0;
        $javascript_inline = "
            var cargar_edit_in_place = $cargar_edit_in_place;
            /* listos para usar el boton de imprimir */
            $('#imprimir').on('hover', function() {
                \$this = $(this);
                if (!\$this.hasClass('disabled'))
                    \$this.addClass('btn-success');
            }).on('mouseout', function() {
                $(this).removeClass('btn-success');
            }).on('click', function() {
                window.location.href = '$cur_url/to_print/1';
            });
        ";
        $this->template->add_js($javascript_inline, "embed");
        $this->template->add_js(public_url() . 'pauta/js/listado.js');

        $this->template->write('header_title', 'Pauta');

        $title  =  '<strong>Pauta</strong> del Dia ' . $data["report_date"] . '<br>';
        $title .= '<strong>Hora de Llegada:</strong> ' . $data["hora_llegada"];
        $title .= ' <strong>Medio de transporte:</strong> ' . $data["medio_transporte"];
        $data['url_anular'] = base_url("pauta/anular_pauta");
        $this->template->write('title', $title);
        $this->template->write_view('content', 'listado', $data);
        $this->template->render();
    }
    public function form() {
        $remision_id = uri_assoc('remision_id');
        if ($this->mdl_pauta->run_validation()) {
            $remision_id = $this->mdl_pauta->save($remision_id);
            /*redirect('pauta/form/remision_id/' . $remision_id);*/
            redirect('pauta/index');

        } else {
            $this->mdl_pauta->prep_form($remision_id);
            $this->load->model('vendedor/mdl_vendedor');
            $this->load->model('publicacion/mdl_publicacion');
            $this->load->model('remision/mdl_remision');
            $data = array('vendedores' => $this->mdl_vendedor->get()->result(),
                          'publicaciones' => $this->mdl_publicacion->get()->result(),
                          'remisiones' =>$this->mdl_remision->get()->result());

            /*
             * template
            */
            $this->template->add_js(public_url() . "jquery/validate/jquery.validate.min.js");
            $this->template->add_js(public_url() . "pauta/validate_pauta.js");
            $this->template->write('header_title', 'Administrar Pauta');
            $this->template->write('title', 'Administrar Pauta');
            $this->template->write_view('content', 'form',$data);
            $this->template->render();
        }
    }
    public function plantilla()
    {
        $this->load->model(array(
                        'pauta/mdl_pauta',
                        'proveedor/mdl_proveedor',
                        'descripcion_tipo_plantilla/mdl_descripcion_tipo_plantilla',
                    )
        );
        $desc = $this->input->post('descripciones');
        // var_dump($desc);
        $data['proveedores'] = $this->mdl_proveedor->getAllProveedores();
        // die(var_dump($data));
        // die(var_dump($data));
        $proveedor_id = $this->input->post('proveedores', true);
        $data['proveedor'] = $proveedor_id;
        $data['descripciones'] = $this->mdl_descripcion_tipo_plantilla->getKeyPairDescripcion();
        // die(var_dump($data['descripciones']));
        // $res =
        // die(var_dump($res));
        /*  necesitamos agregar 1 a la descripcion_id */
        /*$keys_descripciones = $this->input->post('descripciones');
        var_dump($keys_descripciones);
        var_dump($proveedor_id);
        if ($keys_descripciones === false && $proveedor_id) {*/
        $t = $this->mdl_proveedor->getPublicacionesByProveedor($proveedor_id);
        $keys_descripciones = array();
        foreach ($t as $key => $value) {
            $keys_descripciones[$value->id] = isset($desc[$value->id]) ? $desc[$value->id] : 1;
        }

            // die(var_dump($keys_descripciones));
        // }
        // die(var_dump($keys_descripciones));
        // var_dump("\$keys_descripciones ", $keys_descripciones);
        $data['keys_descripciones'] = $keys_descripciones;
        // die(var_dump($data['keys_descripciones']));
        // die(var_dump($data));
        /* si es post */
        if ($proveedor_id and $keys_descripciones) {
            // die(var_dump($keys_descripciones));

            /* primero generamos la plantilla a cero con todos los proveedores */
            $collection = $this->mdl_pauta->generar_plantilla($proveedor_id, $keys_descripciones);

            /* recuperamos las publicaciones y los vendedores del param collection */
            $publicaciones = $collection['publicaciones'];
            // die(var_dump($publicaciones));

            /*
                le agregaremos una propiedad a la entidad publicacion
                la cual contendrá la cantidad de periodicos acumulados para la plantilla
                del keys_descripciones elegido
             */

            $publicaciones = $this->mdl_pauta->acumulados_por_publicacion_dia($publicaciones, $keys_descripciones);

            /* we'll need the headers in the view too */
            $data['publicaciones'] = $publicaciones;
            // die(var_dump($publicaciones));

            $vendedores = $collection['vendedores'];

            for ($k = 0, $max_pub = count($publicaciones); $k < $max_pub; $k++) {
                for ($i = 0, $max_vend = count($vendedores); $i < $max_vend; $i++) {
                    $cant = $this
                            ->mdl_pauta
                            ->get_detalle_plantilla_pauta (
                                    array (
                                        'vendedor_id'    => $vendedores[$i]->id,
                                        'publicacion_id' => $publicaciones[$k]->id,
                                        'descripcion_id' => $keys_descripciones[$publicaciones[$k]->id]
                                    )
                            );

                    $vendedores[$i]->publicacion[$publicaciones[$k]->nombre]["reparto"] = $cant;
                }
            }
            $num_rows = $max_vend;
            $amount = floor($num_rows / 2) + $num_rows % 2;
            $data["amount"] = $amount;
            $data["vendedores"] = array_chunk($vendedores, $amount);

        }
         /* para el chosen */
        $this->template->add_css(public_url() . "chosen/chosen/chosen.css");
        $this->template->add_js(public_url() . "chosen/chosen/chosen.jquery.min.js");

        $this->template->add_js(public_url() . 'jquery/jquery.editinplace.js');
        $this->template->add_js(public_url() . 'jquery/jquery.maskMoney.min.js');
        $this->template->add_css(public_url() . 'pauta/css/plantilla.css');
        $this->template->add_js(public_url() . 'pauta/js/plantilla.js');
        $this->template->write('header_title', 'Editar plantilla por Descripciones');
        $this->template->write('title', 'Editar plantilla por Descripciones');
        $this->template->write_view('content', 'pauta/plantilla/index', $data);
        $this->template->render();
    }
    public function editar_detalle_plantilla()
    {
        /* nos aseguramos que esta entrando por post */
        $this->input->post('ajax') or redirect();
        $element_id = $this->input->post('element_id');
        list($publicacion_id, $vendedor_id, $descripcion_id) = explode('-', $element_id);
        $this->load->model('mdl_pauta');
        $cantidad = $this->input->post('update_value', true);
        $this->mdl_pauta->editar_detalle_plantilla_pauta($cantidad, $publicacion_id, $vendedor_id, $descripcion_id);
        echo $cantidad;
    }
    public function editar_detalle_pauta()
    {
        /* nos aseguramos que esta entrando por post */
        $this->input->post('ajax') or redirect();
        $detalle_pauta_id = $this->input->post('detalle_pauta_id');
        $cantidad = $this->input->post('save_this');
        $this->load->model(
                           array(
                                'detalle_pauta/mdl_detalle_pauta'
                             )
                           );
        $edited = $this->mdl_detalle_pauta->editar_detalle_pauta($cantidad, $detalle_pauta_id);
        // FB::log($edited ? "editado" : "no se edito", "edited:\n");
        echo json_encode(
                        array(
                              'cantidad' => $cantidad,
                              'edited' => $edited
                      )
            );
    }

    public function ajax()
    {
        $this->load->view("ajax");
    }
    public function _post_handler() {
        if ($this->input->post('btn_add'))
            redirect('pauta/form');
        if ($this->input->post('btn_cancel'))
            redirect('pauta/index');
    }

    public function delete() {
        $remision_id = uri_assoc('remision_id');
        if ($remision_id) {
            $this->mdl_pauta->delete($remision_id);
        }
        redirect('pauta/index');
    }
    public function test()
    {
        $this->load->model('vendedor/mdl_vendedor');
        $this->load->view('errores');
    }
    public function existe_pauta_de_remision()
    {
        if (!$this->input->post("ajax")) {
            redirect();
        }
        $remision_id = $this->input->post('remision_id');
        $this->load->model('mdl_pauta');
        $res = $this->mdl_pauta->existe_pauta_de_remision($remision_id);
        echo json_encode(array('ok' => $res));
    }
    public function anular_pauta($pauta_id = null)
    {
        if (!isset($pauta_id)) {
            $this->session->set_flashdata('custom_error', 'Esta intentando acceder a una sección Privada');
            redirect();
        }
        $query = $this->db->query('select
                        dp.id,dp.vendedor_id, dp.pauta_id, p.monto_pago
                        from detalle_pauta dp
                        inner join pago p
                        on dp.id = p.detalle_pauta_id
                        where dp.pauta_id = ?', array($pauta_id));
        $result = $query->result();
        if (empty($result)) {
            $this->db->query('delete from pauta where id= ?', array($pauta_id));
            $this->db->query('delete from detalle_pauta where pauta_id = ?', array($pauta_id));
            $this->session->set_flashdata('custom_success', 'Se anuló con éxito la pauta');
            redirect('pauta/ver');
        }
        $this->session->set_flashdata('custom_error', 'Ya se tienen registrados
                                      pagos para esta pauta,
                                      no puede anular una pauta con cobranzas hechas');
        redirect('pauta/ver');
    }

}

?>