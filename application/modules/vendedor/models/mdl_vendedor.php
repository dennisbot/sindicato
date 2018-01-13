<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_vendedor extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'vendedor';
        $this->primary_key = 'id';
    }

    public function default_select()
    {
        $this->select('*');
    }

    public function default_order_by()
    {
        if ($this->order_by && $this->order) {
            $this->order_by($this->order_by,$this->order);
        }
        else
            $this->order_by('orden','asc');

    }

    public function getAllVendedores()
    {
        $v = $this->mdl_vendedor
            ->select('id, nickname')
            ->where('estado', 'activo')
            ->order_by('orden', 'asc')
            ->get()
            ->result();
        return $v;
    }

    public function getAllVendedoresCombo($indexed_key = true)
    {
        $result = $this->getAllVendedores();
        $combo = array();
        foreach ($result as $vendedor) {
            if ($indexed_key) {
                $combo[$vendedor->id] = $vendedor->nickname;
            }
            else {
                $combo[$vendedor->nickname] = $vendedor->nickname;
            }
        }
        return $combo;
    }
    public function getVendedoresNoSuplentes()
    {
        $v = $this->mdl_vendedor
            ->select('id, orden, nickname')
            ->where('estado', 'activo')
            ->order_by('orden', 'asc')
            ->get()
            ->result();
        return $v;
    }

    public function getVendedoresNoSuplentesCombo($indexed_key = true)
    {
        $result = $this->getVendedoresNoSuplentes();
        $combo = array();
        foreach ($result as $vendedor) {
            if ($indexed_key) {
                $combo[$vendedor->id] = $vendedor->orden  . " - " . $vendedor->nickname;
            }
            else {
                $combo[$vendedor->nickname] =  $vendedor->orden  . " - " . $vendedor->nickname;
            }
        }
        return $combo;
    }
    public function getDeudasVendedor($vendedor_id)
    {
        ($vendedor_id) or die('no existe vendedor_id');
        return $this->select("
                 re.status estado_remision,
                 dp.id dpid,
                 dp.estado estado,
                 dr.precio_vendedor,
                 nickname,
                 dp.cantidad,
                 round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3) monto_deuda,
                 sum(ifnull(p.monto_pago, 0)) abonado,
                 (round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3)
                  -sum(ifnull(p.monto_pago, 0))) saldo,
                 pu.nombre,
                 from_unixtime(fecha, '%d/%m/%Y') fecha,
                 fecha fecha_ordenar,
                 ifnull(dev.cantidad_devolucion, 0) cantidad_devolucion
             ", false)
             ->join('detalle_pauta dp', 'vendedor.id = dp.vendedor_id')
             ->join('devolucion dev', 'dev.detalle_pauta_id = dp.id', 'left')
             ->join('pauta pa', 'dp.pauta_id = pa.id')
             ->join('detalle_remision dr', 'dr.id = dp.detalle_remision_id')
             ->join('remision re', 're.id = dr.remision_id')
             ->join('publicacion pu', 'pu.id = dr.publicacion_id')
             ->join('pago p', 'p.detalle_pauta_id = dp.id', 'left')
             ->where(
                     array(
                           'dp.estado !=' => 'pagado',
                           'vendedor.id' => $vendedor_id,
                           // 'pa.fecha !=' => strtotime(date('Y-m-d')),
                           're.status !=' => 'anulado',
                       )
              )
             ->group_by('dp.id')
             ->having('saldo != 0')
             ->order_by('fecha_ordenar', 'desc')
             ->order_by('pu.proveedor_id', 'asc')
             ->order_by('pu.orden', 'asc')
             // ->order_by('pu.proveedor_id', 'asc')
             // ->order_by('pu.tipo_publicacion', 'desc')
             ->get()
             ->result();
    }

    public function getPagosVendedor($vendedor_id, $fecha)
    {
        ($vendedor_id) or die('no existe vendedor_id');
        $fecha = strtotime(standardize_date($fecha));
        $fecha1 = $fecha + 86400;
		$query = $this->db->query("
			SELECT dp.id dpid, dp.estado estado, dr.precio_vendedor, nickname, dp.cantidad,
			round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3) monto_deuda, monto_pago,
			sum(ifnull(p.monto_pago, 0)) abonado, (round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3)
			 -sum(ifnull(p.monto_pago, 0))) saldo, pu.nombre, from_unixtime(fecha, '%d/%m/%Y') fecha,
			fecha fecha_ordenar, ifnull(dev.cantidad_devolucion, 0) cantidad_devolucion, p.fecha_pago
			from
			devolucion dev
			join detalle_pauta dp on dev.detalle_pauta_id = dp.id
			join vendedor on vendedor.id = dp.vendedor_id
			JOIN `pauta` pa ON `dp`.`pauta_id` = `pa`.`id`
			JOIN `detalle_remision` dr ON `dr`.`id` = `dp`.`detalle_remision_id`
			JOIN `remision` re ON `re`.`id` = `dr`.`remision_id`
			JOIN `publicacion` pu ON `pu`.`id` = `dr`.`publicacion_id`
			left JOIN `pago` p ON `p`.`detalle_pauta_id` = `dp`.`id`
			-- WHERE `dp`.`estado` =  'devuelto'
			WHERE `vendedor`.`id` =  '".$vendedor_id."'
			and p.fecha_pago >= '".$fecha."' and p.fecha_pago <= '".$fecha1."'
			-- and p.fecha_pago = '1398126106'
			GROUP BY `dp`.`id`");
        return $query->result();
	}
    public function getDeudasVendedorRevistas($vendedor_id, $fecha_unixtimestamp)
    {
        ($vendedor_id) or die('no existe vendedor_id');
        return $this->select("
                 re.status estado_remision,
                 dp.id dpid,
                 dp.estado estado,
                 dr.precio_vendedor,
                 nickname,
                 dp.cantidad,
                 round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3) monto_deuda,
                 sum(ifnull(p.monto_pago, 0)) abonado,
                 (round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3)
                  -sum(ifnull(p.monto_pago, 0))) saldo,
                 pu.nombre,
                 from_unixtime(fecha, '%d/%m/%Y') fecha,
                 fecha fecha_ordenar,
                 ifnull(dev.cantidad_devolucion, 0) cantidad_devolucion
             ", false)
             ->join('detalle_pauta dp', 'vendedor.id = dp.vendedor_id')
             ->join('devolucion dev', 'dev.detalle_pauta_id = dp.id', 'left')
             ->join('pauta pa', 'dp.pauta_id = pa.id')
             ->join('detalle_remision dr', 'dr.id = dp.detalle_remision_id')
             ->join('remision re', 're.id = dr.remision_id')
             ->join('publicacion pu', 'pu.id = dr.publicacion_id')
             ->join('pago p', 'p.detalle_pauta_id = dp.id', 'left')
             ->where(
                     array(
                           'dp.estado !=' => 'pagado',
                           'vendedor.id' => $vendedor_id,
                           // 'pa.fecha !=' => strtotime(date('Y-m-d')),
                           're.status !=' => 'anulado',
                           're.son_periodicos' => 0,
                           're.fecha_vencimiento' => $fecha_unixtimestamp,
                       )
              )
             ->group_by('dp.id')
             ->having('saldo != 0')
             ->order_by('fecha_ordenar', 'desc')
             ->order_by('pu.proveedor_id', 'asc')
             ->order_by('pu.orden', 'asc')
             // ->order_by('pu.proveedor_id', 'asc')
             // ->order_by('pu.tipo_publicacion', 'desc')
             ->get()
             ->result();
    }
    public function update_orden($vendedores = array())
    {
        foreach ($vendedores as $pos => $nickname) {
            $this->db->query('update vendedor set orden=? where nickname=?', array($pos + 1, $nickname));
        }
    }
    public function validation_rules()
    {
        return array(
            'nombres' => array(
                    'field' => 'nombres',
                    'label' => 'Nombres',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'apellidos' => array(
                    'field' => 'apellidos',
                    'label' => 'Apellidos',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'nickname' => array(
                    'field' => 'nickname',
                    'label' => 'Nickname',
                    'rules' => 'required|trim|xss_clean|callback_nicknamecheck'
                    ),
            'telefono' => array(
                    'field' => 'telefono',
                    'label' => 'Telefono',
                    'rules' => 'trim|xss_clean'
                    ),
            'dni' => array(
                    'field' => 'dni',
                    'label' => 'DNI',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'direccion_casa' => array(
                    'field' => 'direccion_casa',
                    'label' => 'direccion_casa',
                    'rules' => 'trim|xss_clean'
                    ),
            'direccion_tienda' => array(
                    'field' => 'direccion_tienda',
                    'label' => 'direccion_tienda',
                    'rules' => 'trim|xss_clean'
                    ),
            'fecha_nacimiento' => array(
                    'field' => 'fecha_nacimiento',
                    'label' => 'fecha_nacimiento',
                    'rules' => 'trim|xss_clean'
                    ),
            'email' => array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'trim|valid_email|xss_clean'
                    ),
            'created_at' => array(
                    'field' => 'created_at',
                    'label' => 'created_at',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
    public function deudores($fecha, $id)
    {
    	if($id != '')
    		$where = array('dp.estado !=' => 'pagado', 'pa.fecha' => $fecha, 'pu.id' => $id);
    	else
    		$where = array('dp.estado !=' => 'pagado', 'pa.fecha' => $fecha);
        return $this->select("
                 re.status estado_remision,
                 dp.id dpid,
                 dp.estado estado,
                 dr.precio_vendedor,
                 nickname,
                 dp.cantidad,
                 round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3) monto_deuda,
                 sum(ifnull(p.monto_pago, 0)) abonado,
                 (round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3)
                  -sum(ifnull(p.monto_pago, 0))) saldo,
                 pu.nombre,
                 from_unixtime(fecha, '%d/%m/%Y') fecha,
                 fecha fecha_ordenar,
                 ifnull(dev.cantidad_devolucion, 0) cantidad_devolucion
             ", false)
             ->join('detalle_pauta dp', 'vendedor.id = dp.vendedor_id')
             ->join('devolucion dev', 'dev.detalle_pauta_id = dp.id', 'left')
             ->join('pauta pa', 'dp.pauta_id = pa.id')
             ->join('detalle_remision dr', 'dr.id = dp.detalle_remision_id')
             ->join('remision re', 're.id = dr.remision_id')
             ->join('publicacion pu', 'pu.id = dr.publicacion_id')
             ->join('pago p', 'p.detalle_pauta_id = dp.id', 'left')
             ->where($where)
             //->group_by('nickname')
             ->group_by('dp.id')
             //->group_by('pu.nombre')
             ->having('saldo != 0')
             ->order_by('fecha_ordenar', 'desc')
             ->order_by('pu.proveedor_id', 'asc')
             ->order_by('pu.orden', 'asc')
             // ->order_by('pu.proveedor_id', 'asc')
             // ->order_by('pu.tipo_publicacion', 'desc')
             ->get()
             ->result();


        /*$result = $this->db->query(
        'select
         nombres,
         apellidos,
         nickname,
         fecha,
         monto_deuda,pago_total,saldo_final,publicacion.nombre
         from (select nombres,apellidos,nickname,telefono,dni,
               direccion_casa,direccion_tienda,estado, A.* from
               (select TDetalle_pauta.*,pauta.vendedor_id,
                pauta.cantidad_pauta,pauta.fecha,pauta.publicacion_id from
                 (select TPago_total.*, (monto_deuda -
                  pago_total)as saldo_final  from
        (select deuda_id,monto_deuda,pauta_id,fecha_pago,
         sum(monto_pago) as pago_total from (select deuda.id
           as deuda_id,monto_deuda,pauta_id,pago.id as pago_id,
           monto_pago,fecha  as fecha_pago from deuda left join pago on
           deuda_id=deuda.id)TDeuda_pago group by deuda_id)
        TPago_total where monto_deuda >pago_total) TDetalle_pauta inner join
         pauta on pauta.id = TDetalle_pauta.pauta_id)A inner join vendedor on
        A.vendedor_id = vendedor.id) TDetalle_publicacion
inner join publicacion on publicacion.id= TDetalle_publicacion.publicacion_id ');

        return $result->result();*/

    }
    public function nicknamecheck($nick)
    {
        $id = uri_assoc('id');
        if ($id) {
            $result = $this->select('id')
            ->where(
                    array(
                          'nickname' => $nick,
                          'id !=' => $id
                  )
            )
            ->get();
        }
        else {
            $result = $this->select('id')
                      ->where('nickname',$nick)
                      ->get();
        }

        if ($result->num_rows() == 0 ) {

           return true;
        }
        else
        {
            $this->form_validation->set_message('nicknamecheck',  'El nickname ya existe.');
            return false;
        }

    }

    //pagos vendedor por fecha
    function pagos_por_fecha($fecha_inicio)
    {
		$query = $this->db->query("select nickname, sum(monto_pago) as pago
		from vendedor
		inner join detalle_pauta
		on vendedor.id = detalle_pauta.vendedor_id
		inner join
		pago
		on detalle_pauta.id = pago.detalle_pauta_id
		where FROM_UNIXTIME(pago.fecha_pago, '%d/%m/%Y') = '".$fecha_inicio."'
		group by nickname");
        return $query->result();
    }
    public function getPagados($vendedor_id, $curdate_timestamp)
    {
        ($vendedor_id) or die('no existe vendedor_id');
        return $this->select("
                re.status estado_remision,
                dp.id dpid,
                dp.estado estado,
                dr.precio_vendedor,
                nickname,
                dp.cantidad,
                round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3) monto_deuda,
                sum(ifnull(p.monto_pago, 0)) abonado,
                (round(dr.precio_vendedor * (dp.cantidad - ifnull(dev.cantidad_devolucion, 0)), 3)
                -sum(ifnull(p.monto_pago, 0))) saldo,
                pu.nombre,
                pu.shortname,
                pu.tipo_publicacion,
                from_unixtime(fecha, '%d/%m/%Y') fecha,
                fecha fecha_ordenar,
                ifnull(dev.cantidad_devolucion, 0) cantidad_devolucion
             ", false)
             ->join('detalle_pauta dp', 'vendedor.id = dp.vendedor_id')
             ->join('devolucion dev', 'dev.detalle_pauta_id = dp.id', 'left')
             ->join('pauta pa', 'dp.pauta_id = pa.id')
             ->join('detalle_remision dr', 'dr.id = dp.detalle_remision_id')
             ->join('remision re', 're.id = dr.remision_id')
             ->join('publicacion pu', 'pu.id = dr.publicacion_id')
             ->join('pago p', 'p.detalle_pauta_id = dp.id', 'left')
             ->where(
                     array(
                           "(dp.estado = 'pagado' or dp.estado = 'devuelto')" => null,
                           'vendedor.id' => $vendedor_id,
                           "UNIX_TIMESTAMP(FROM_UNIXTIME(p.fecha_pago, '%Y-%m-%d'))=" => $curdate_timestamp,
                       )
              )
             ->group_by('dp.id')
             // ->having('saldo != 0')
             ->order_by('fecha_ordenar', 'desc')
             ->order_by('pu.proveedor_id', 'asc')
             ->order_by('pu.orden', 'asc')
             // ->order_by('pu.proveedor_id', 'asc')
             // ->order_by('pu.tipo_publicacion', 'desc')
             ->get()
             ->result();
             // FB::log($this->db->last_query(), "last la:\n");
    }
}
?>