<div class="padded form-agregar">
        <div class="control-group">
        	<div><strong>Numero de guia:</strong> <?php echo $remisiones[0]->nro_guia; ?></div>
            <div><strong>Nombre o razon social:</strong> <?php echo $remisiones[0]->razon_social; ?></div>
            <div><strong>Fecha emision: </strong><?php echo format_date_to_show($remisiones[0]->fecha_emision); ?></div>
            <div><strong>Estado: </strong><?php echo $remisiones[0]->status; ?></div>
            <div><strong>Observaciones: </strong><?php echo $remisiones[0]->observaciones; ?></div>
            <br />
            <div class="controls">
                <div>
                	<?php 
                		if(!empty($remisiones)){
                	?>
                	<table id="pub_seleccionadas" border="0" class="table table-striped table-hover">
                		<tr>
                			<td>Publicacion</td>
                			<td>Cantidad</td>
                			<td>Precio unitario gu&iacute;a</td>
                			<td>Importe</td>
                			<td>Cantidad devuelta</td>
                			<td>Recibido</td>
                			<td>Importe neto a pagar</td>
                		</tr>
                		<?php
    						$cantidad_total = 0;
    						$total_importe = 0;
    						$total_a_pagar = 0;
    						$total_devuelto = 0;                		 
                			foreach ($remisiones as $fila) { ?>
						    	<tr>
							    	<td class="nombre"><?php echo $fila->nombre; ?></td> 
							    	<td> <input type="text" name="cantidad[]" readonly class="cantidad" value="<?php echo $fila->cantidad; ?>" /> </td> 
							        <td> <input type="text" name="precio[]" readonly class="precio" value="<?php echo $fila->precio_unitario_guia; ?>" /> </td> 
							        <td> <input type="text" class="importe" readonly name="importe[]" value="<?php echo $fila->importe; ?>" /></td>
							        <td> <input type="text" class="cantidad_devolucion" name="cantidad_devolucion[]" readonly value="<?php echo $fila->cantidad_devolucion != ''?$fila->cantidad_devolucion:'0'; ?>" /></td> 
							        <td> <input type="text" class="recibido" name="recibido[]" readonly value="<?php echo $fila->cantidad - $fila->cantidad_devolucion; ?>" /></td> 
							        <td> <input type="text" class="importe_neto" name="importe_neto[]" value="<?php echo $fila->importe_neto != ''?$fila->importe_neto:'0'; ?>" readonly /></td> 
						        </tr>
                		<?php 
	    						$cantidad_total += $fila->cantidad;
	    						$total_importe += $fila->importe;
	    						$total_devuelto += $fila->cantidad_devolucion;
	    						$total_a_pagar += $fila->importe_neto;
                			} ?>
                 		<tr>
                			<td>Totales: &nbsp;</td>
                			<td><input type="text" name="cantidad_total_recibida" class="cantidad_total_recibida" value="<?php echo round($cantidad_total, 3); ?>" readonly="readonly" /> </td>
                			<td>&nbsp;</td>
                			<td><input type="text" name="total_importe" class="total_importe" value="S/. <?php echo number_format($total_importe, 3); ?>" readonly="readonly" /> </td>
                			<td><input type="text" name="cantidad_total_devuelta" class="cantidad_total_devuelta" value="<?php echo $total_devuelto; ?>" readonly="readonly" /> </td>
                			<td>&nbsp;</td>
                			<td><input type="text" size="10" name="totalf" class="totalf" value="S/. <?php echo number_format($total_a_pagar, 3); ?>" readonly="readonly" /> </td>
                		</tr>               		
                	</table>
                	<br />
			        <div class="control-group">
			            <div class="controls">
			            	<a class="btn btn-danger" onclick="history.go(-1);">Atr&aacute;s</a>
			            </div>
			        </div>
                	
                	<!-- <table id="resultados">
                		<tr>
                			<td>&nbsp;</td>
                			<td>&nbsp;</td>
                			<td colspan="3">Cantidad total recibida:<input name="cantidad_total_recibida" class="cantidad_total_recibida" value="0" readonly="readonly" /> </td>
                			<td>&nbsp;Importe total</td>
                			<td colspan="3"><input name="total_importe" class="total_importe" value="0" readonly="readonly" /> </td>
                			<td>Cantidad total devuelta:</td>
                			<td colspan="3"><input name="cantidad_total_devuelta" class="cantidad_total_devuelta" value="0" readonly="readonly" /> </td>
                			<td>Total por pagar</td>
                			<td><input size="10" name="total" class="total" value="0" readonly="readonly" /> </td>
                		</tr>                	
                	</table>  -->
                	<?php } else { 
                		echo "Lo sentimos no tenemos remisiones en esa fecha"; 
                	} ?>
                </div>
            </div>
        </div>
</div><!-- padded -->