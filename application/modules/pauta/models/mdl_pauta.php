<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_pauta extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'pauta';
        $this->primary_key = 'pauta.id';
    }

    public function default_select() {
        $this->select('pauta.*');
    }

    public function default_order_by() {
        if ($this->order_by && $this->order) {
            $this->order_by($this->order_by, $this->order);
        }
        else {
            $this->order_by($this->primary_key);
        }
    }
    public function existe_pauta_de_remision($remision_id = null)
    {
        $result = $this->select('id')
             ->where('remision_id', $remision_id)
             ->get()
             ->row();

        return !empty($result) ? $result->id : false;
    }
    public function editar_detalle_plantilla_pauta($cantidad, $publicacion_id, $vendedor_id, $descripcion_id)
    {
        ($publicacion_id and $vendedor_id and $descripcion_id) or die('debe establecer los valores de
            publicacion_id, vendedor_id y descripcion_id primero');
        /* sanitizamos los campos */
        $cantidad = $this->db->escape($cantidad);
        $publicacion_id = $this->db->escape($publicacion_id);
        $vendedor_id = $this->db->escape($vendedor_id);
        $descripcion_id = $this->db->escape($descripcion_id);

        $this->db->query(
                    sprintf('update plantilla_pauta
                        set cantidad=%s
                        where publicacion_id=%s and vendedor_id=%s and descripcion_id=%s',
                        $cantidad, $publicacion_id, $vendedor_id, $descripcion_id)
                );
    }
    public function existe_pauta($pauta_id = null)
    {
        $result = $this->select('id')
                ->where('id', $pauta_id)
                ->get()
                ->row();
        return !empty($result) ? $result->id : false;
    }
    public function generar_plantilla($proveedor_id, $keys_descripciones)
    {
        ($proveedor_id and $keys_descripciones) or die('Proveedor_id and keys_descripciones must have a value');
        $this->load->model('vendedor/mdl_vendedor');
        $vendedores = $this->mdl_vendedor->getAllVendedores();
        $publicaciones = $this->mdl_proveedor->getPublicacionesByProveedor($proveedor_id);
        $params = array();
        /* agregamos un valor por default */
        /*var_dump($keys_descripciones);
        var_dump($publicaciones);
        exit;*/
        $params['operador_id'] = 1;
        foreach ($publicaciones as $publicacion) {
            foreach ($vendedores as $vendedor) {
                $existe = $this->db->select('*')->where (
                                        array (
                                            'vendedor_id'       => $vendedor->id,
                                            'publicacion_id'    => $publicacion->id,
                                            'descripcion_id'    => $keys_descripciones[$publicacion->id],
                                        )
                                    )
                                    ->get('plantilla_pauta')->result();
                if (empty($existe)) {
                    /* si no devolvió datos, generamos la nueva entrada */
                    /* se va a generar el campo cantidad por defecto con 0 */
                    $params['vendedor_id'] = $vendedor->id;
                    $params['publicacion_id'] = $publicacion->id;
                    $params['descripcion_id'] = $keys_descripciones[$publicacion->id];
                    $this->db->insert('plantilla_pauta', $params);
                }
            }
        }
        return array(
                'vendedores' => $vendedores,
                'publicaciones' => $publicaciones
            );
    }
    public function acumulados_por_publicacion_dia($publicaciones, $keys_descripciones)
    {
        ($publicaciones and $keys_descripciones) or die('Necesita especificar publicaciones y keys_descripciones');
        for ($i = 0; $i < count($publicaciones); $i++) {
            $descripcion_id = $keys_descripciones[$publicaciones[$i]->id];
            $query_param = sprintf(
                "select sum(cantidad) total from plantilla_pauta where descripcion_id=%s and publicacion_id=%s",
                $descripcion_id,
                $this->db->escape($publicaciones[$i]->id)
            );
            $query = $this->db->query($query_param);
            $publicaciones[$i]->total_plantilla_dia = $query->row()->total;
        }
        return $publicaciones;
    }
    public function get_detalle_plantilla_pauta($params = array())
    {
        return $this->db->select('cantidad')
                        ->where($params)
                        ->get('plantilla_pauta')
                        ->row()->cantidad;
    }
    public function generar_detalles($pauta_id = '', $remision_id = '', $keys_descripciones)
    {
        if ($pauta_id == '' || $remision_id == '')
            throw new Exception("el campo pauta y proveedor no debe ser vacio", 1);

        /* recuperamos el descripcion_id de la plantilla a evaluar */
        $pauta = $this->mdl_pauta->get_by_id($pauta_id);
        /* ya no usamos el dia necesariamente, puede ser otro grupo */
        // $descripcion_id = $this->db->escape(date('N', $pauta->fecha));

        /*
        las publicaciones del detalle de la remision
        (de que publicaciones vamos a generar los detalles de la pauta)
        */
        $publicaciones = $this->mdl_detalle_remision->getPublicacionesByDetalleRemision($remision_id);
        $params = array();
        /* vamos a encontrar cuantos periodicos en total suman
        en la plantilla de cuanto por vendedor */
        for ($i = 0, $max = count($publicaciones); $i < $max; $i++) {
            $publicacion_id = $publicaciones[$i]->id;
            /* escapamos la cadena */
            // $publicacion_id = $this->db->escape($publicacion_id);
            $descripcion_id = $keys_descripciones === false ? -1 : $keys_descripciones[$publicacion_id];

            $query = $this->db->query("select ifnull(sum(cantidad), 0) cantidad_plantilla
                            from
                            plantilla_pauta
                            where
                            publicacion_id = $publicacion_id and descripcion_id = $descripcion_id");
            $publicaciones[$i]->cantidad_plantilla = $query->row()->cantidad_plantilla;

            if ($publicaciones[$i]->cantidad_plantilla != 0) {
                $k = $publicaciones[$i]->cantidad / $publicaciones[$i]->cantidad_plantilla;
            }
            else {
                $k = 0;
                $this->session->set_flashdata('custom_error',
                                'parece que no ha creado una
                                plantilla base para
                                estas publicaciones,
                                es por eso que verá que algunas
                                pautas se crearon en cero'
                            );
            }
            $publicaciones[$i]->k = $k;
            if ($k > 1) {
                /* significa que hay más periodicos que han llegado
                y superan al acumulado de la plantilla de los agremiados */
                $publicaciones[$i]->diff = $publicaciones[$i]->cantidad - $publicaciones[$i]->cantidad_plantilla;
            }
        }
        /* pasamos como parametro a pauta_id porque con ese id generaremos
           los detalles de la pauta */
        $params['pauta_id'] = $pauta_id;
        /* esto lo estableceremos luego cuando hagamos el login */
        $params['operador_id'] = 1;
        /* tambien se registrará los montos de deuda inicial */
        foreach ($publicaciones as $publicacion) {
            /* ahora le asignamos a cada vendedor su cuota */
            $params['detalle_remision_id'] = $publicacion->detalle_remision_id;
            $publicacion_id = $publicacion->id;
            $descripcion_id = $keys_descripciones === false ? -1 : $keys_descripciones[$publicacion_id];
            $vendedores = $this
                            ->db
                            ->query("
                                select vendedor_id, max(cantidad) cantidad
                                    from
                                    (
                                    select vendedor_id, ifnull(cantidad, 0) cantidad
                                    from plantilla_pauta pp
                                    left join
                                    vendedor v
                                    on pp.vendedor_id = v.id
                                    where
                                    publicacion_id = '$publicacion_id' and
                                    descripcion_id = $descripcion_id and
                                    estado='activo'
                                    union
                                    select id vendedor_id, 0 cantidad
                                    from vendedor where estado='activo'
                                    ) a
                                    group by vendedor_id
                            ")
                            ->result();

            /* si es que se tiene que aumentar proporcionalmente */
            if ($publicacion->k > 1) {
                $aumento = floor($publicacion->diff / count($vendedores));
            }
            /* generaremos los detalles según la plantilla y la fórmula */
            foreach ($vendedores as $vendedor) {
                $params['vendedor_id'] = $vendedor->vendedor_id;
                /* si la constante es menor que 1 significa que llegó menos periodico
                por lo tanto hay que repartirles proporcionalmente menos */
                $cant = 0;
                if ($publicacion->k <= 1) {
                    $cant = floor($vendedor->cantidad * $publicacion->k);
                }
                else {
                    /* si llego más periódicos (k > 1),
                    entonces tenemos que aumentarles a su cuota de manera proporcional */
                    /* elegimos si es 40% o 20% el incremento porque llegó más periodicos (k > 1) */
                    /*$rate = $vendedor->cantidad >= 50 ? 0.4 : 0.2;
                    $cant = $vendedor->cantidad + floor($rate * $vendedor->cantidad);*/
                    $cant = $vendedor->cantidad + $aumento;
                }
                if ($cant >= 10)
                    $params['cantidad'] = (int)($cant / 5) * 5;
                else
                    $params['cantidad'] = $cant;
                /* el campo estado se agregará por defecto a "pendiente" */
                $this->db->insert('detalle_pauta', $params);
            }
        }
    }
    public function save($id = null, $db_array = null, $set_flashdata = true)
    {
        if (!$db_array) {
            $db_array = $this->db_array();
            $params['remision_id'] = $db_array['remision_id'];
            /* convertimos el campo de fecha con formato dd/mm/yyyy
            al formato en segundos */
            $params['fecha'] = strtotime(to_standard_date($db_array['fecha']));
            $params['hora_llegada'] = join(' ', array(
                                                    $db_array['hour'],
                                                    $db_array['minute'],
                                                    $db_array['meridian']
                                                )
                                    );
            $params['medio_transporte'] = $db_array['medio_transporte'];
            /* por el momento estaremos seteando un valor arbitrario */
            $params['operador_id'] = 1;
        }
        return parent::save($id, $params, $set_flashdata);
    }
    public function _existe_remision($remision_id)
    {
        $this->db->select('count(*) existe');
        $this->db->where('id', $remision_id);
        $result = $this->db->get('remision');
        if ($result->num_rows() == 0) {
            $this
                ->form_validation
                ->set_message('_existe_remision',
                    'No existe la remisión ingresada.');
            return false;
        }
        return true;
    }
    public function _fecha_valida($fecha)
    {
        $times = substr_count($fecha, '/');
        if ($times != 2) {
            $this
                ->form_validation
                ->set_message('_fecha_valida', 'Ingrese una fecha correcta: %s.');
            return false;
        }
        list($day, $month, $year) = explode('/', $fecha);
        if (!checkdate($month, $day, $year)) {
            $this
                ->form_validation
                ->set_message('_fecha_valida', 'Ingrese una fecha correcta: %s.');
            return false;
        }
        return true;
    }
    public function _hora_valida($hora)
    {
        $ok = ('00' <= $hora and $hora <= '12');
        if (!$ok) {
            $this
                ->form_validation
                ->set_message('_hora_valida', 'Error en la Hora.');
            return false;
        }
        return true;
    }
    public function _minuto_valido($minuto)
    {
        $ok = ('00' <= $minuto and $minuto <= '59');
        if (!$ok) {
            $this
                ->form_validation
                ->set_message('_minuto_valido', 'Error en el Minuto.');
            return false;
        }
        return true;
    }
    public function _meridiano_valido($meridiano)
    {

        $ok = ($meridiano == 'AM' or $meridiano == 'PM');

        if (!$ok) {
            $this
                ->form_validation
                ->set_message('_meridiano_valido', 'Error en el Meridiano.');
            return false;
        }
        return true;
    }
    public function _medio_transporte_valido($medio)
    {
        $ok = ($medio == 'aéreo' or $medio == 'terrestre');
        if (!$ok) {
            $this
                ->form_validation
                ->set_message('_medio_transporte_valido',
                    'Elija un correcto medio de transporte.');
            return false;
        }
        return true;
    }
    public function get_proveedor_id($pauta_id)
    {
        $query = $this->select('proveedor.id as proveedor_id')
                 ->join('remision', 'remision.id=pauta.remision_id')
                 ->join('proveedor', 'proveedor.id=remision.proveedor_id')
                 ->where('pauta.id', $pauta_id)
                 ->get()
                 ->row();
        return !empty($query) ? $query->proveedor_id : false;
    }
    public function custom_validation()
    {
        return array(
                'remision_id' => array(
                        'field' => 'remision_id',
                        'label' => 'remision id',
                        'rules' => 'required|trim|xss_clean|callback__existe_remision',
                    ),
                'fecha' => array(
                        'field' => 'fecha',
                        'label' => 'Fecha',
                        'rules' => 'required|trim|xss_clean|callback__fecha_valida',
                    ),
                'hour' => array(
                        'field' => 'hour',
                        'label' => 'Hora',
                        'rules' => 'required|trim|xss_clean|callback__hora_valida',
                    ),
                'minute' => array(
                        'field' => 'minute',
                        'label' => 'Minuto',
                        'rules' => 'required|trim|xss_clean|callback__minuto_valido',
                    ),
                'meridian' => array(
                        'field' => 'meridian',
                        'label' => 'Meridiano',
                        'rules' => 'required|trim|xss_clean|callback__meridiano_valido',
                    ),

                'medio_transporte' => array(
                        'field' => 'medio_transporte',
                        'label' => 'Medio de transporte',
                        'rules' => 'required|trim|xss_clean|callback__medio_transporte_valido'
                    ),
            );
    }
    public function validation_rules() {
        return array(
            'cantidad_pauta' => array(
                    'field' => 'cantidad_pauta',
                    'label' => 'cantidad_pauta',
                    'rules' => 'trim|xss_clean'
                    ),
            'fecha' => array(
                    'field' => 'fecha',
                    'label' => 'fecha',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
}
?>