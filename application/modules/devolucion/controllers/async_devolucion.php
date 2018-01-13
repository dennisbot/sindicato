<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
* clase para las operaciones asincronas con ajax
*/
class Async_devolucion extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
    }
    
    public function devolver()
    {
        $dpid = $this->input->post('dpid', true);
        $this->input->post('ajax', true) or die('Usted no tiene permiso para ingresar a esta sección');
        $dpid or die('No se ha especificado un detalle pauta id');
        $this->load->model(
                       array(
                            'devolucion/mdl_devolucion',
                            'detalle_pauta/mdl_detalle_pauta'
                       )
                   );
        $db_array['detalle_pauta_id'] = $dpid;
        $db_array['cantidad_devolucion'] = $this->input->post('cant_dev');
        $db_array['operador_id'] = 1;
        $existe =$this->mdl_devolucion->get_by_id($dpid);
        $res;
        if ($existe) {
            /* entonces tenemos que actualizar los montos */
            unset($db_array['detalle_pauta_id']);
            $res = $this->mdl_devolucion->devolver($dpid, $db_array);

        }
        else {
            /* si no hay devolucion entonces hay que agregar todo */
            $res = $this->mdl_devolucion->devolver(null, $db_array);
        }
        /* nos aseguramos de establecer como devuelto el estado del detalle de la pauta */
        $this->mdl_detalle_pauta->save($dpid, array('estado' => 'devuelto'), false);
    }
    
    public function devolver_editar()
    {
        $dpid = $this->input->post('dpid', true);
        $this->input->post('ajax', true) or die('Usted no tiene permiso para ingresar a esta sección');
        $dpid or die('No se ha especificado un detalle pauta id');
        $this->load->model(
                       array(
                            'devolucion/mdl_devolucion',
                            'detalle_pauta/mdl_detalle_pauta'
                       )
                   );
        $db_array['detalle_pauta_id'] = $dpid;
        $db_array['cantidad_devolucion'] = $this->input->post('cant_dev');
        $db_array['operador_id'] = 1;
        $existe = $this->mdl_devolucion->get_by_id($dpid);
        $res;
        if ($existe) {
            /* entonces tenemos que actualizar los montos */
            unset($db_array['detalle_pauta_id']);
            $res = $this->mdl_devolucion->devolver($dpid, $db_array);

        }
        //else {
            /* si no hay devolucion entonces hay que agregar todo */
            //$res = $this->mdl_devolucion->devolver(null, $db_array);
        //}
        /* nos aseguramos de establecer como devuelto el estado del detalle de la pauta */
        //$this->mdl_detalle_pauta->save($dpid, array('estado' => 'devuelto'), false);
    }
    
    public function test()
    {
        $dpid = uri_assoc('dpid', 4);
        $this->load->model('detalle_pauta/mdl_detalle_pauta');
        $db_array['estado'] = 'devuelto';
        $res = $this->mdl_detalle_pauta->save($dpid, $db_array, false);
        var_dump($this->db->last_query());
        var_dump($res);
    }
}