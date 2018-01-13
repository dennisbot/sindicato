<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>imprimir</title>
</head>
<body>
    <style>
    body {
        margin: 0 1px 0;
        /*margin: 0;*/
        padding: 0px;
        font-size: 15px;
        /*font: 13px/20px normal Helvetica, Arial, sans-serif;*/
        /*font: 12px normal Helvetica, Arial, sans-serif;*/
        /*font: 12px normal "MS Gothic";*/
        /*font-weight: bold;*/
    }
    #toprint {
        margin: 20px 0 0 20px;
    }
    pre {
        margin: 0;
        padding: 0;
        font-family: Consolas,monospace
    }

    @media print {
        #toprint {
            display: none;
        }
    }
</style>
<pre>
Nro Ord: <?php echo str_pad($vendedor->orden, 2, " ", STR_PAD_LEFT) . ' - ' . strtoupper($vendedor->nickname) . ' ' . date('H:i:s') . "\n";?>
Nro Cobranza: <?php echo str_pad($orden, 2, " ", STR_PAD_LEFT); ?> - Fecha : <?php echo format_date_to_show($curdate) . "\n" ?>
<?php $total = 0. ?>
<?php echo str_repeat('-', 41) . "\n"; ?>
<?php foreach ($registro_pagos as $key => $registro): ?>
<?php $total += $registro->abonado; ?>
<?php if ($registro->tipo_publicacion == 'periodico') : ?>
<?php echo str_pad($registro->shortname, 3, " ", STR_PAD_LEFT) ?> - <?php echo $registro->fecha ?> - <?php echo str_pad($registro->cantidad - $registro->cantidad_devolucion, 3, " ", STR_PAD_LEFT) ?> - <?php echo str_pad($registro->precio_vendedor, 6, " ", STR_PAD_LEFT) ?> - <?php echo str_pad($registro->abonado, 7, " ", STR_PAD_LEFT) . "\n" ?>
<?php else: ?>
<?php echo str_pad(substr($registro->nombre, 0, 16), 16, " ", STR_PAD_LEFT) ?> - <?php echo str_pad($registro->cantidad - $registro->cantidad_devolucion, 3, " ", STR_PAD_LEFT)  ?> - <?php echo str_pad($registro->precio_vendedor, 6, " ", STR_PAD_LEFT) ?> - <?php echo str_pad($registro->abonado, 7, " ", STR_PAD_LEFT) . "\n" ?>
<?php endif; ?>
<?php endforeach ?>
<?php echo str_repeat('-', 41) . "\n"; ?>
<?php $total = number_format($total, 3, '.', ''); ?>
<?php echo str_pad("Total S/. " . $total, 41, " ", STR_PAD_LEFT) . "\n" ?>
<?php echo str_repeat('-', 41) . "\n"; ?>
<?php foreach ($revistas_maniana as $key => $registro): ?>
<?php echo str_pad(substr($registro->nombre, 0, 16), 16, " ", STR_PAD_LEFT) ?>
<?php endforeach ?>
</pre>
<button id="toprint">Imprimir</button>
<script>
    window.onload = function() {
        document.getElementById("toprint").onclick = function() {
            window.print();
        }
    }

</script>
</body>
</html>