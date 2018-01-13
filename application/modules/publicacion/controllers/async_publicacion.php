<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
* clase usada para recuperar mediante ajax los datos de
* las publicaciones
*/
class Async_publicacion extends MX_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('mdl_publicacion');
	}
	public function getAllPublicaciones()
	{
		if (!$this->input->post('ajax')) {
			$this->session->set_flashdata('custom_error', 'Usted no tiene permiso para acceder a esta sección');
			redirect();
		}
		$tipo = $this->input->post('tipo_publicacion');
		$p = $this->mdl_publicacion->getAllPublicaciones($tipo);
		echo json_encode(array('result' => $p));
	}
}