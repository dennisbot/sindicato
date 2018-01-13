<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_detalle_pauta extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'detalle_pauta';
        $this->primary_key = 'id';
    }

    public function default_select()
    {
        $this->db->select('*');
    }

    public function default_order_by()
    {
        if ($this->order_by && $this->order) {
            $this->db->order_by($this->order_by, $this->order);
        }
        else {
            $this->db->order_by($this->primary_key);
        }
    }
    public function registrarComoPagado($dpid)
    {
        /*FB::log("vamos a ver este detalle si llega a ser pagado", "debug info:\n");
        FB::log($dpid, "\$dpid:\n");*/
        $this->save($dpid, array('estado' => 'pagado'), false);
        // FB::log($this->db->last_query(), "\$this->db->last_query():\n");
    }
    public function get_id_detalle_pauta_ayer($params = array())
    {
        $query = $this->select('id')
                    ->where($params)
                    ->get();
        return $query->num_rows() > 0 ? $query->row()->id : false;
    }
    public function get_detalle_pauta($pauta_id = null, $remision_id = null)
    {
        ($pauta_id and $remision_id) or exit('usted debe especificar pauta y remision ids');
        $detalle_remisiones = $this->mdl_detalle_remision->select('id')
                                    ->where('remision_id', $remision_id)
                                    ->get()
                                    ->result();
        $cols = array();
        foreach ($detalle_remisiones as $detalle_remision) {
            $cols[] = "max(case when detalle_remision_id = " . $detalle_remision->id . " then detalle_pauta.cantidad else 0 end) 'cant_" . $detalle_remision->id . "'";
            $cols[] = "max(case when detalle_remision_id = " . $detalle_remision->id . " then detalle_pauta.id else 0 end) 'dpid_" . $detalle_remision->id . "'";
            $cols[] = "max(case when detalle_remision_id = " . $detalle_remision->id . " then pu.id else 0 end) 'puid_" . $detalle_remision->id . "'";
        }
        $detalle_remisiones = $this->mdl_detalle_remision->select('id')
                                    ->where('remision_id', $remision_id)
                                    ->get()
                                    ->result();
        $weekTimes = 1;
        $ok_remision_id = false;
        $this->load->model('mdl_remision');
        while (!$ok_remision_id) {
            $ok_remision_id = $this->mdl_remision->existeRemisionSemanaAnterior($remision_id, $weekTimes);
            if (!$ok_remision_id) $weekTimes++;
            /* si no se encuentra en 2 semanas atrás no se tomará en cuenta */
            if ($weekTimes > 2) break;
        }
        // $ok_remision_id = 0;
        // var_dump($ok_remision_id);
        // $this->query(sprintf("%s ", $pauta_id));

        $cols_dev = array();
        $detalle_remisiones = $this->mdl_detalle_remision->select('id, publicacion_id')
                                    ->where('remision_id', $ok_remision_id)
                                    ->get()
                                    ->result();
        foreach ($detalle_remisiones as $detalle_remision) {
            $cols_dev[] = "max(case when dp.detalle_remision_id = " . $detalle_remision->id . " then cantidad_devolucion else 0 end) 'dev_pubid_" . $detalle_remision->publicacion_id . "'";
        }
        if (!$ok_remision_id) {
            return $this->select('
                             v.id,
                             v.nickname,' . join(', ', $cols) . '
                    ')
                    ->join('vendedor v', 'v.id = detalle_pauta.vendedor_id')
                    ->join('detalle_remision dr', 'dr.id = detalle_pauta.detalle_remision_id')
                    ->join('publicacion pu', 'pu.id = dr.publicacion_id')
                    ->where('pauta_id', $pauta_id)
                    ->group_by('v.nickname')
                    ->order_by('v.orden')
                    ->get()
                    ->result();
        }
        else {

            // FB::log($ok_remision_id, "\$ok_remision_id:\n");

            $query = $this->db->query(sprintf("
                                    select * from
                                    (
                                    select
                                            v.id,
                                            v.nickname,
                                            %s
                                            from
                                    detalle_pauta
                                    inner join
                                    vendedor v
                                    on detalle_pauta.vendedor_id = v.id
                                    inner join
                                    detalle_remision dr
                                    on dr.id = detalle_pauta.detalle_remision_id
                                    inner join
                                    publicacion pu
                                    on pu.id = dr.publicacion_id
                                    where pauta_id = %d
                                    group by v.nickname
                                    order by v.orden
                                    ) lista
                                    left join
                                    (
                                        select
                                        dp.vendedor_id,
                                        %s
                                        from
                                        detalle_pauta dp
                                        inner join
                                        devolucion dev
                                        on dp.id = dev.detalle_pauta_id
                                        where dp.pauta_id = (select id from pauta where remision_id = %d)
                                        group by vendedor_id
                                    ) devoluciones
                                    on devoluciones.vendedor_id = lista.id
                                    ",
                                    join(', ', $cols),
                                    $pauta_id,
                                    join(', ', $cols_dev),
                                    $ok_remision_id
                                    )
                    );
            return $query->result();
        }
        /*var_dump($this->db->last_query());
        var_dump($query->num_rows());
        var_dump($query->result());
        var_dump($query);
        exit;*/
    }
    public function editar_detalle_pauta($cantidad, $detalle_pauta_id)
    {

        (isset($cantidad) && isset($detalle_pauta_id)) or die('necesita especificar la cantidad y el detalle_pauta_id');
        $query = $this->db->query("select detalle_pauta_id from devolucion where detalle_pauta_id = ?", array($detalle_pauta_id));
        $can_edit = true;
        // FB::log($query->num_rows(), "num rows devolucion:\n");
        /* si ya hubo devolucion ya no se puede modificar estos registros */
        if ($query->num_rows() > 0) $can_edit = false;
        if ($can_edit) {
            $query = $this->db->query("
                            select
                            *
                            from
                                detalle_remision dr
                            inner join
                                remision r
                            on dr.remision_id = r.id
                            inner join
                                detalle_pauta dp
                            on dp.detalle_remision_id = dr.id
                            where dp.id = ?
                            and r.status != 'pendiente'
                            ",
                            array($detalle_pauta_id)
                        );
            if ($query->num_rows() > 0) $can_edit = false;
            // FB::log($query->num_rows(), "num rows remision:\n");
        }
        if ($can_edit) {
            $this->save($detalle_pauta_id, array('cantidad' => $cantidad), false);
            return true;
        }
        return false;
    }
    public function validation_rules()
    {
        return array(
            'vendedor_id' => array(
                    'field' => 'vendedor_id',
                    'label' => 'vendedor_id',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'publicacion_id' => array(
                    'field' => 'publicacion_id',
                    'label' => 'publicacion_id',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'pauta_id' => array(
                    'field' => 'pauta_id',
                    'label' => 'pauta_id',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'cantidad' => array(
                    'field' => 'cantidad',
                    'label' => 'cantidad',
                    'rules' => 'required|trim|xss_clean'
                    )
        );
    }
}
?>