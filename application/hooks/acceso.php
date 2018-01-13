<?php
class Acceso
{
	function identificado()
	{
		$this->CI =& get_instance();

		//definimos los controladores privados
		$controladores_privados = array ('cobranza', 'comision_publicacion', 'comision_sindicato',
					'descuento_publicacion', 'detalle_remision', 'deuda', 'devolucion', 'dia_descuento',
					'egreso', 'ingreso', 'cobranza', 'pago', 'pauta', 'proveedor', 'publicacion',
					'remision', 'vendedor');

		//definimos los controladores y funciones no privadas que podemos acceder normalmente
		$funciones_no_privadas = array (
								'operador/logout',
								'operador/login'
							);

		if($this->CI->session->userdata('logged_in') == true && $this->CI->router->method == 'login')
			redirect('/');
		if($this->CI->session->userdata('logged_in') != true &&
			in_array($this->CI->router->class . "/" . $this->CI->router->method, $funciones_no_privadas))
			return false;
		if($this->CI->session->userdata('logged_in') != true && $this->CI->router->method != 'login'
		&& in_array($this->CI->router->class, $controladores_privados))
			redirect('operador/login');
	}
}

?>