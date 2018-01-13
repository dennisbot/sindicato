<!DOCTYPE html>
<html lang="en">
<head>
<title>Database Error</title>
<style type="text/css">

::selection{ background-color: #E13300; color: white; }
::moz-selection{ background-color: #E13300; color: white; }
::webkit-selection{ background-color: #E13300; color: white; }

body {
    background-color: #fff;
    margin: 40px;
    font: 13px/20px normal Helvetica, Arial, sans-serif;
    color: #4F5155;
}

a {
    color: #003399;
    background-color: transparent;
    font-weight: normal;
}

h1 {
    color: #444;
    background-color: transparent;
    border-bottom: 1px solid #D0D0D0;
    font-size: 19px;
    font-weight: normal;
    margin: 0 0 14px 0;
    padding: 14px 15px 10px 15px;
}

code {
    font-family: Consolas, Monaco, Courier New, Courier, monospace;
    font-size: 12px;
    background-color: #f9f9f9;
    border: 1px solid #D0D0D0;
    color: #002166;
    display: block;
    margin: 14px 0 14px 0;
    padding: 12px 10px 12px 10px;
}

#container {
    margin: 10px;
    border: 1px solid #D0D0D0;
    -webkit-box-shadow: 0 0 8px #D0D0D0;
}

p {
    margin: 12px 15px 12px 15px;
}
</style>
</head>
<body>
    <div id="container">
        <h1>Ocurrió un Error con la Base de Datos</h1>
        <p>Error Number: 1054</p><p>Unknown column 'v.id' in 'where clause'</p><p>SELECT dp.id dpid, dp.estado estado, dr.precio_vendedor, nickname, dp.cantidad, monto_deuda, sum(if(isnull(p.monto_pago), 0, p.monto_pago)) abonado, (monto_deuda-sum(if(isnull(p.monto_pago), 0, p.monto_pago))) saldo, pu.nombre, from_unixtime(fecha, '%d/%m/%Y') fecha, if(isnull(dev.cantidad_devolucion), 0, dev.cantidad_devolucion) cantidad_devolucion
FROM (`vendedor`)
JOIN `detalle_pauta` dp ON `v`.`id` = `dp`.`vendedor_id`
LEFT JOIN `devolucion` dev ON `dev`.`detalle_pauta_id` = `dp`.`id`
JOIN `pauta` pa ON `dp`.`pauta_id` = `pa`.`id`
JOIN `detalle_remision` dr ON `dr`.`id` = `dp`.`detalle_remision_id`
JOIN `publicacion` pu ON `pu`.`id` = `dr`.`publicacion_id`
LEFT JOIN `pago` p ON `p`.`detalle_pauta_id` = `dp`.`id`
WHERE `dp`.`estado` != 'pagado'
AND `v`.`id` =  '2'
GROUP BY `dp`.`id`
HAVING `saldo` != 0
ORDER BY `fecha` asc, `pu`.`proveedor_id` asc, `pu`.`orden` asc</p><p>Filename: D:\wamp\www\sindicato\system\database\DB_driver.php</p><p>Line Number: 330</p>  </div>
</body>
</html>