<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_detalle_remision extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'detalle_remision ';
        $this->primary_key = 'id';
    }

    public function default_select()
    {
        $this->select('*');
    }

    public function default_order_by()
    {
        if ($this->order_by && $this->order) {
            $this->order_by($this->order_by, $this->order);
        }
        else {
            $this->order_by($this->primary_key);
        }
    }
    public function getPublicacionesByDetalleRemision($remision_id)
    {
        return $this->select("
                             detalle_remision.id detalle_remision_id,
                             p.id,
                             p.shortname,
                             p.nombre,
                             cantidad
                     ")
                    ->join('publicacion p', 'p.id = detalle_remision.publicacion_id')
                    ->where('remision_id', $remision_id)
                    ->order_by('p.orden')
                    ->get()
                    ->result();
    }
    public function getPublicacionesByDetalleRemisionDetalles($remision_id)
    {
        return $this->select("
                             dp.pauta_id,
                             dp.detalle_remision_id,
                             p.id,
                             p.shortname,
                             p.nombre,
                             sum(dp.cantidad) total,
                             detalle_remision.cantidad,
                             precio_vendedor pv
                     ")
                    ->join('publicacion p', 'p.id = detalle_remision.publicacion_id')
                    ->join('detalle_pauta dp', 'detalle_remision.id=dp.detalle_remision_id')
                    ->where('remision_id', $remision_id)
                    ->group_by(array('dp.pauta_id', 'dp.detalle_remision_id'))
                    ->order_by('p.orden')
                    ->get()
                    ->result();
    }
    public function deleteDetalles($detalles, $id_remision)
    {

        ($detalles and $id_remision) or die("No existe detalles o id_remision");

        $detalles_existentes = $this->getAssocIdDetalles($id_remision);

        foreach ($detalles as $detalle) {

            if (!$detalle['id'] || !isset($detalles_existentes[$detalle['id']])) continue;
            $detalles_existentes[$detalle['id']] = false;

        }

        foreach ($detalles_existentes as $key => $value) {
            if ($detalles_existentes[$key]) {
                $this->mdl_detalle_remision->delete($key);
            }

        }

    }
    public function getDetalles($id_remision)
    {
        ($id_remision) or die("No existe id_remision");
        return $this->select('remision.id as remision_id, detalle_remision.*')
        			->join('remision', 'remision.id = detalle_remision.remision_id')
                    ->where('remision_id', $id_remision)
                    ->get()
                    ->result();

    }
    public function getComboDetalles($id_remision)
    {
        ($id_remision) or die("No existe id_remision");
        $d = $this->getDetalles($id_remision);
        $detalles = array();
        foreach ($d as $detalle) {
            $detalles[$detalle->id] = $detalle->descripcion;
        }
        return $detalles;
    }
    private function getAssocIdDetalles($id_remision)
    {
        ($id_remision) or die("No existe id_remision");
        $detalles = $this->getDetalles($id_remision);
        $d = array();
        foreach ($detalles as $detalle) {
            $d[$detalle->id] = true;
        }
        return $d;
    }
    public function guardarDetalles($detalles = array())
    {
      // aki hay error , al agregar detalles_remision
        foreach ($detalles as $detalle) {
            $id = $detalle['id'];
            unset($detalle['id']);
            /* segun sea el caso, actualizará o agregará records */
            if ($id == -1) {
               $id = null;
            }
            if ($detalle['cantidad'] != '' && $detalle['cantidad'] != 0) {
                $this->mdl_detalle_remision->save($id, $detalle, false);
            }

        }
    }
    public function getPrecioVendedor($detalle_pauta_id)
    {
        ($detalle_pauta_id) or exit('necesita especificar Detalle pauta id');
        return $this->select('precio_vendedor')
                    ->join('detalle_pauta dp', 'dp.detalle_remision_id = detalle_remision.id')
                    ->where('dp.id', $detalle_pauta_id)
                    ->get()
                    ->row()->precio_vendedor;
    }
    public function validation_rules() {
        return array(
            'descripcion' => array(
                    'field' => 'descripcion',
                    'label' => 'descripcion',
                    'rules' => 'trim|xss_clean'
                    ),
            'cantidad' => array(
                    'field' => 'cantidad',
                    'label' => 'cantidad',
                    'rules' => 'trim|xss_clean'
                    ),
            'unidad_medida' => array(
                    'field' => 'unidad_medida',
                    'label' => 'unidad_medida',
                    'rules' => 'trim|xss_clean'
                    ),
            'precio_unitario_guia' => array(
                    'field' => 'precio_unitario_guia',
                    'label' => 'precio_unitario_guia',
                    'rules' => 'trim|xss_clean'
                    ),
            'importe' => array(
                    'field' => 'importe',
                    'label' => 'importe',
                    'rules' => 'trim|xss_clean'
                    ),
            'cantidad_devolucion' => array(
                    'field' => 'cantidad_devolucion',
                    'label' => 'cantidad_devolucion',
                    'rules' => 'trim|xss_clean'
                    ),
            'importe_neto' => array(
                    'field' => 'importe_neto',
                    'label' => 'importe_neto',
                    'rules' => 'trim|xss_clean'
                    )
        );
    }
    public function obtener_detalle_idprov_idremision($idpublicacion,$idremision)
    {
        $result = $this
                    ->db
                    ->select('*')
                    ->from('detalle_remision')
                    ->where(
                        array(
                        'publicacion_id' => $idpublicacion,
                        'remision_id' => $idremision
                        )
                    )
                    ->get();
        return $result->row();
    }
}
?>