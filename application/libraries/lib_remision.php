<?php
defined('BASEPATH') or die('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 * @package CodeIgniter
 * @author   ExpressionEngine Dev Team
 * @copyright Copyright (c) 2006, Ellislab, Inc.
 * @license http://codeigniter.com/user_guide/licence.html
 * @link http://codeigniter.com/
 * @since Version 1.0
 * @filesource
 */

/**
 * CodeIgniter Remision Library Class
 * this class will handle a bunch of operations
 * related to remision entity
 * @package CodeIgniter
 * @copyright Copyright (c) 2013, ApuStudio.
 * @license http://apustudio.com/licence.html
 * @link http://apustudio.com
 * @since  version 1.0
 */

class CI_lib_remision {
	/**
	 * global variable of the main instance of CI
	 * @var object
	 */
	var $CI;
	function __construct()
	{
		$this->CI = & get_instance();
	}
	function formatDetalleRemision($detalles = array(), $extraCol = array(), $asObject = false)
	{

		if (empty($detalles)) return array();
		/* will get the first associative key */
		$rows = count($detalles[key($detalles)]);
		$collection = array();
		$map = array(
			'id' => 'id',
			'publicacion_id' => 'publicacion_id',
			'importe' => 'importe',
			'cantidad' => 'cantidad',
			'descripcion' => 'descripcion',
			'unidadMedida' => 'unidad_medida',
			'precioUnitarioGuia' => 'precio_unitario_guia',
			'precioUnitarioCalculado' => 'precio_unitario_calculado',
		);
		foreach ($detalles as $column => $detalle) {
			for ($i = 0; $i < $rows; $i++) {
				if ($asObject) {
					if (!isset($collection[$i]))
						$collection[$i] = new stdClass();

					$collection[$i]->{$map[$column]} = $detalle[$i];
				}
				else {
					$collection[$i][$map[$column]] = $detalle[$i];
				}
			}
		}
		/* generalmente será la columna remision_id que es común
		para todos los detalles */
		foreach ($extraCol as $key => $value) {
			for ($i = 0; $i < $rows; $i++) {
				if ($asObject) {
					if (!isset($collection[$i]))
						$collection[$i] = new stdClass();

					$collection[$i]->{$key} = $value;
				}
				else {
					$collection[$i][$key] = $value;
				}
			}
		}
		return $collection;
	}
	function formatDetalleRemision1($detalles = array(), $extraCol = array(), $asObject = false)
	{
		//var_dump($detalles); exit();
		if (empty($detalles)) return array();
		/* will get the first associative key */
		$rows = count($detalles[key($detalles)]);
		$collection = array();
		$map = array(
			'id' => 'id',
			'publicacion_id' => 'publicacion_id',
			'importe' => 'importe',
			'cantidad' => 'cantidad',
			'descripcion' => 'descripcion',
			'unidadMedida' => 'unidad_Medida',
			'precioUnitarioGuia' => 'precio_unitario_guia',
			'precioUnitarioCalculado' => 'precio_unitario_calculado',
			'precioPublico' => 'precioPublico',
			'descuentoAplicado' => 'descuentoAplicado',
			'comision' => 'comision',
			'nombrePublicacion' => 'nombrePublicacion',
			'fecha' => 'fecha',
			'precio_vendedor' => 'precio_vendedor',
			'ganancia_sindicato' => 'ganancia_sindicato',
		);
		foreach ($detalles as $column => $detalle) {
			for ($i = 0; $i < $rows; $i++) {
				if ($asObject) {
					if (!isset($collection[$i]))
						$collection[$i] = new stdClass();

					$collection[$i]->{$map[$column]} = $detalle[$i];
				}
				else {
					$collection[$i][$map[$column]] = $detalle[$i];
				}
			}
		}
		/* generalmente será la columna remision_id que es común
		para todos los detalles */
		foreach ($extraCol as $key => $value) {
			for ($i = 0; $i < $rows; $i++) {
				if ($asObject) {
					if (!isset($collection[$i]))
						$collection[$i] = new stdClass();

					$collection[$i]->{$key} = $value;
				}
				else {
					$collection[$i][$key] = $value;
				}
			}
		}
		return $collection;
	}
}
