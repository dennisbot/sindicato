<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
	<dl>
    	<input type="hidden" name="vendedor_id" value="<?php echo $this->mdl_remision->form_value('vendedor_id'); ?>" />
	</dl>
	<form class="form-horizontala" method="post" id="form-devolucion" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <div class="control-group">
            <div class="controls">
                <div>
                	<?php
                		if(!empty($remisiones)){
                			$tiene = $this->mdl_remision->tiene_pauta($remisiones[0]->id);
                			if($tiene){
                	?>
                	<input type="hidden" name="remision_id" value="<?php echo $remisiones[0]->id; ?>" />
		        	<div><strong>N&uacute;mero de gu&iacute;a:</strong> <?php echo $remisiones[0]->nro_guia; ?></div>
		            <div><strong>Nombre o raz&oacute;n social:</strong> <?php echo $remisiones[0]->razon_social; ?></div>
		            <div><strong>Fecha emisi&oacute;n: </strong><?php echo format_date_to_show($remisiones[0]->fecha_emision); ?></div>
		            <div><strong>Sector: </strong><?php echo $remisiones[0]->sector; ?></div>
		            <div><strong>Observaciones: </strong><?php echo $remisiones[0]->observaciones; ?></div>
		            <br />
                	
                	<table id="pub_seleccionadas" class="table table-striped table-hover">
                		<tr>
                			<td>Item</td>
                			<td>Publicaci&oacute;n</td>
                			<td>Cantidad</td>
                			<td>Precio unitario</td>
                			<td>Importe</td>
                			<td>Cantidad devoluci&oacute;n</td>
                			<td>Recibido</td>
                			<td>Ganancia estimada</td>
                			<td>Importe neto a pagar</td>
                		</tr>
                		<?php
    						$cantidad_total = 0;
    						$total_importe = 0;
    						$cantidad_total_recibida = 0;
    						$total_devuelto = 0;
    						$total_ganancias = 0;
    						$total_recibido = 0;
    						$i = 1;                		 
                			foreach ($remisiones as $fila) { 
                				$devolucion = $this->mdl_remision->mostrar_cantidad_devolucion($fila->remision_id, $fila->publicacion_id);
                				$importe_net_pagar = ($fila->cantidad - $devolucion)*$fila->precio_unitario_calculado;
                				$ganancia_publicacion = ($fila->cantidad - $devolucion) * ($fila->precio_publicacion*$fila->comision/100);
                				$total_recibido_item = $fila->cantidad - $devolucion;
                				?>
						    	<tr>
							    	<td class="id"><?php echo $i; ?><input type="hidden" name="publicacion[]" class="publicacion" value="<?php echo $fila->publicacion_id; ?>" /></td> 
							    	<td class="nombre"><?php echo $fila->nombre; ?></td> 
							    	<td> <input type="text" name="cantidad[]" disabled="disabled" class="cantidad" value="<?php echo $fila->cantidad; ?>" /> </td> 
							        <td> <input type="text" name="precio[]" disabled="disabled" class="precio" value="<?php echo $fila->precio_unitario_calculado; ?>" /> </td> 
							        <td> <input type="text" class="importe" disabled="disabled" name="importe[]" value="<?php echo $fila->importe; ?>" /></td> 
							        <td> <input type="text" class="cantidad_devolucion" name="cantidad_devolucion[]" value="<?php echo $fila->cantidad_devolucion != '0'?$fila->cantidad_devolucion:$devolucion; ?>" readonly="readonly" /></td>
							        <td> <input type="text" class="recibido" name="recibido[]" disabled="disabled" value="<?php echo $total_recibido_item; ?>" /></td> 
							        
							        <td> <input type="text" class="ganancia" name="ganancia[]" disabled="disabled" value="<?php echo $ganancia_publicacion; ?>" style="background-color: #5FBE5F;color: #FFF" /></td> 
							        
							        <td> <input type="text" class="importe_neto" name="importe_neto[]" value="<?php echo $fila->importe_neto != '0.000'?$fila->importe_neto:$importe_net_pagar; ?>" readonly="readonly" /></td> 
						        </tr>
                		<?php 
	    						$cantidad_total += $fila->cantidad;
	    						$total_importe += $fila->importe;
	    						$total_devuelto += $devolucion;
	    						$cantidad_total_recibida += $importe_net_pagar;
	    						$total_ganancias += $ganancia_publicacion;
	    						$total_recibido += $total_recibido_item;
	    						$i++;	
                			} ?>
                 		<tr>
                			<td>&nbsp;</td>
                			<td>&nbsp;Totales:</td>
                			<td><input type="text" name="cantidad_total_recibida" class="cantidad_total_recibida" value="<?php echo round($cantidad_total, 2); ?>" readonly="readonly" /> </td>
                			<td>&nbsp;</td>
                			<td><input type="text" name="total_importe" class="total_importe" value="S/. <?php echo number_format($total_importe, 2); ?>" readonly="readonly" /> </td>
                			<td><input type="text" name="cantidad_total_devuelta" class="cantidad_total_devuelta" value="<?php echo $total_devuelto; ?>" readonly="readonly" /> </td>
                			<td><input size="10" type="text" name="totalr" class="totalr" value="<?php echo $total_recibido; ?>" readonly="readonly" /> </td>
                			<td><input size="10" type="text" name="total_ganancia" class="total_ganancia" value="S/. <?php echo number_format($total_ganancias, 3); ?>" readonly="readonly" style="background-color: #5FBE5F;color: #FFF" /> </td>
                			<td><input size="10" type="text" name="total" class="total1" value="S/. <?php echo number_format($cantidad_total_recibida, 3); ?>" readonly="readonly" /> </td>
                		</tr>               		
                	</table>
                	<br />
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
                	<?php 		
                			}
                			else{
                				echo 'La(s) remisiones de este proveedor a&uacute;n no tiene pautas y no se puede hacer la devoluci&oacute;n.';
                			}
                		} else { 
                		echo "Lo sentimos no tenemos remisiones en esa fecha"; 
                	} ?>
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-danger" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
                <input type="submit" class="btn btn-success" name="btn_submit" value="Guardar" />
            </div>
        </div>	          
    </form>
</div><!-- padded -->