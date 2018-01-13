<?php
function format_date($unix_timestamp_date = NULL) {

	if ($unix_timestamp_date) {
		return date('d/m/Y', $unix_timestamp_date);
	}
	return '';
}

function format_date_to_show($unix_timestamp_date = NULL) {

	if ($unix_timestamp_date) {

		global $CI;
		$day = date('d', $unix_timestamp_date);
		$month = date('M', $unix_timestamp_date);
		$month = map_month_spanish($month);
		$year = date('Y', $unix_timestamp_date);
		return $day . ' ' . $month . ' ' . $year;
	}

	return '';

}
function dia_mes($date,$separator)
{
	//$date= '25-03', devuele 25 de Marzo
	$aux = explode($separator,$date);
	$dia = $aux[0];
	$mes= (int)$aux[1];
	$meses = array(
			1 =>"Enero",
			2 =>"Febrero",
			3 =>"Marzo",
			4 =>"Abril",
			5 =>"Mayo",
			6 =>"Junio",
			7 =>"Julio",
			8 =>"Agosto",
			9 =>"Setiembre",
			10 =>"Octubre",
			11 =>"Noviembre",
			12 =>"Diciembre"
		);
	return  $dia.' '.$meses[$mes];
}
function map_month_spanish($month)
{
	$map_month = array(
		'Jan' => 'Ene',
		'Apr' => 'Abr',
		'Aug' => 'Ago',
		'Sep' => 'Set',
		'Dec' => 'Dic',
		);
	if (isset($map_month[$month]))
		return $map_month[$month];
	return $month;
}
function standardize_date($date) {

	global $CI;
	if (strstr($date, '/')) {
		$delimiter = '/';
	}
	elseif (strstr($date, '-')) {
		$delimiter = '-';
	}
	elseif (strstr($date, '.')) {
		$delimiter = '.';
	}
	else {
		// do not standardize
		return $date;
	}

	//$date_format = explode($delimiter, $CI->mdl_mcb_data->setting('default_date_format'));
	$date_format = explode($delimiter, 'd/m/Y');
    $date = explode($delimiter, $date);
	foreach ($date_format as $key=>$value) {
            $standard_date[strtolower($value)] = $date[$key];
	}
	return $standard_date['m'] . '/' . $standard_date['d'] . '/' . $standard_date['y'];
}

function invertir_fecha($fecha, $hora = false) {
	$dia = substr($fecha, 8, 2);
	$mes = substr($fecha, 5, 2);
	$anio = substr($fecha, 0, 4);
	$time = substr($fecha, 10, 9);
	if($hora)
		return $dia.'/'.$mes.'/'.$anio.' '.$time;
	return $dia.'/'.$mes.'/'.$anio;
}

function date_formats($format = NULL, $element = NULL) {

	$date_formats = array(
		'm/d/Y' => array(
			'key' => 'm/d/Y',
			'picker' => 'mm/dd/yy',
			'mask' => '99/99/9999',
			'dropdown' => 'mm/dd/yyyy'),
		'm/d/y' => array(
			'key' => 'm/d/y',
			'picker' => 'mm/dd/y',
			'mask' => '99/99/99',
			'dropdown' => 'mm/dd/yy'),
		'Y/m/d' => array(
			'key' => 'Y/m/d',
			'picker' => 'yy/mm/dd',
			'mask' => '9999/99/99',
			'dropdown' => 'yyyy/mm/dd'),
		'd/m/Y' => array(
			'key' => 'd/m/Y',
			'picker' => 'dd/mm/yy',
			'mask' => '99/99/9999',
			'dropdown' => 'dd/mm/yyyy'),
		'd/m/y' => array(
			'key' => 'd/m/y',
			'picker' => 'dd/mm/y',
			'mask' => '99/99/99',
			'dropdown' => 'dd/mm/yy'),
		'm-d-Y' => array(
			'key' => 'm-d-Y',
			'picker' => 'mm-dd-yy',
			'mask' => '99-99-9999',
			'dropdown' => 'mm-dd-yyyy'),
		'm-d-y' => array(
			'key' => 'm-d-y',
			'picker' => 'mm-dd-y',
			'mask' => '99-99-99',
			'dropdown' => 'mm-dd-yy'),
		'Y-m-d' => array(
			'key' => 'Y-m-d',
			'picker' => 'yy-mm-dd',
			'mask' => '9999-99-99',
			'dropdown' => 'yyyy-mm-dd'),
		'y-m-d' => array(
			'key' => 'y-m-d',
			'picker' => 'y-mm-dd',
			'mask' => '99-99-99',
			'dropdown' => 'yy-mm-dd'),
		'd.m.Y' => array(
			'key' => 'd.m.Y',
			'picker' => 'dd.mm.yy',
			'mask' => '99.99.9999',
			'dropdown' => 'dd.mm.yyyy'),
		'd.m.y' => array(
			'key' => 'd.m.y',
			'picker' => 'dd.mm.y',
			'mask' => '99.99.99',
			'dropdown' => 'dd.mm.yy')
	);

	if ($format and $element) {

		return $date_formats[$format][$element];

	}

	elseif ($format) {

		return $date_formats[$format];

	}

	else {

		return $date_formats;

	}

}

function day_to_spanish($date)
{
	if (!$date) $date = time();
	$pos = date('N', $date);
	$dias = array(
				"",
				"Lunes",
				"Martes",
				"Miercoles",
				"Jueves",
				"Viernes",
				"Sabado",
				"Domingo"
			);
	if (isset($dias[$pos])) {
		return $dias[$pos];
	}
	return "";
}
function month_to_spanish($date)
{
	if (!$date) $date = time();
	$pos = date('n', $date);
	$meses = array(
				"",
				"Enero",
				"Febrero",
				"Marzo",
				"Abril",
				"Mayo",
				"Junio",
				"Julio",
				"Agosto",
				"Setiembre",
				"Octubre",
				"Noviembre",
				"Diciembre"
		);
	if (isset($meses[$pos])) {
		return $meses[$pos];
	}
	return "";
}
function report_date($date)
{
	if (!$date) $date = time();
	$day_number = date("j", $date);
	$full_date = "";
	$full_date = day_to_spanish($date);
	$full_date .= ", " . $day_number;
	$full_date .= " de " . month_to_spanish($date);
	$full_date .= " del " . date("Y", $date);
	return $full_date;
}
function to_standard_date($date)
{
	$dateFields = explode('/', $date);
	return implode('-', $dateFields);
}

function ObtenerNombreDia($fecha)
{
	//fecha -> 12-07-2013
   $fecha = strtotime($fecha);
   switch (date('w', $fecha)){
     case 0: $dia = "domingo"; break;
     case 1: $dia = "lunes"; break;
     case 2: $dia = "martes"; break;
     case 3: $dia = "miercoles"; break;
     case 4: $dia = "jueves"; break;
     case 5: $dia = "viernes"; break;
     case 6: $dia = "sabado"; break;
   }

  return $dia;
}


