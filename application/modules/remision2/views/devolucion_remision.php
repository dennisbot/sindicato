<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
	<dl>
    	<input type="hidden" name="vendedor_id" value="<?php echo $this->mdl_remision->form_value('vendedor_id'); ?>" />
	</dl>
	<form class="form-horizontal" method="post" id="form-devolucion" action="<?php echo site_url($this->uri->uri_string()); ?>">
		<div class="control-group">
	        <div class="controls">
	            <select name="proveedor_id" class="proveedor_dev">
		            <option value="0">Seleccione:</option>
		            <?php foreach ($proveedores as $fila) { ?>
		            <option value="<?php echo $fila->id; ?>" <?php echo ($proveedor_id == $fila->id)?'selected':''; ?>><?php echo $fila->nombre; ?></option>
		            <?php }?>
	            </select>
	            <?php $today = date("d/m/Y"); ?>
	            <span class="input-append date calendar" id="dpStartDate">
	            	<input type="text" class="datepicker" name="fecha_remision" value="<?php echo ($this->input->post('fecha_remision') != '')?$this->input->post('fecha_remision'):$today; ?>" readonly="readonly" />
	            	<span class="add-on"><i class="icon-calendar"></i></span>
	            </span>
	            <input type="submit" class="btn btn-success" name="btn_consulta" value="Mostrar remisi&oacute;n" /> 
			</div>
		</div>
        <div class="control-group">
        	<div><strong>Numero de guia:</strong> <?php echo $remisiones[0]->nro_guia; ?></div>
            <div><strong>Nombre o razon social:</strong> <?php echo $remisiones[0]->razon_social; ?></div>
            <div><strong>Fecha emision: </strong><?php echo format_date_to_show($remisiones[0]->fecha_emision); ?></div>
            <div><strong>Sector: </strong><?php echo $remisiones[0]->sector; ?></div>
            <div><strong>Observaciones: </strong><?php echo $remisiones[0]->observaciones; ?></div>
            <br />
            
            <div class="controls">
                <div>
                	<?php 
                		if(!empty($remisiones)){
                	?>
                	<table id="pub_seleccionadas" class="table table-striped table-hover">
                		<tr>
                			<td>Numero guia</td>
                			<td>Publicacion</td>
                			<td>Cantidad</td>
                			<td>Precio unitario</td>
                			<td>Importe</td>
                			<td>Cantidad devolucion</td>
                			<td>Recibido</td>
                			<td>Importe neto a pagar</td>
                		</tr>
                		<?php
    						$cantidad_total = 0;
    						$total_importe = 0;
    						$cantidad_total_recibida = 0;
    						$total_devuelto = 0;                		 
                			foreach ($remisiones as $fila) { ?>
						    	<tr>
							    	<td class="id"><input type="text" name="publicacion[]" class="publicacion" value="<?php echo $fila->publicacion_id; ?>" /></td> 
							    	<td class="nombre"><?php echo $fila->nombre; ?></td> 
							    	<td> <input type="text" name="cantidad[]" readonly class="cantidad" value="<?php echo $fila->cantidad; ?>" /> </td> 
							        <td> <input type="text" name="precio[]" readonly class="precio" value="<?php echo $fila->precio_unitario; ?>" /> </td> 
							        <td> <input type="text" class="importe" readonly name="importe[]" value="<?php echo $fila->importe; ?>" /></td> 
							        <td> <input type="text" class="cantidad_devolucion" name="cantidad_devolucion[]" value="<?php echo $fila->cantidad_devolucion != ''?$fila->cantidad_devolucion:'0'; ?>" /></td> 
							        <td> <input type="text" class="recibido" name="recibido[]" readonly value="<?php echo $fila->cantidad - $fila->cantidad_devolucion; ?>" /></td> 
							        <td> <input type="text" class="importe_neto" name="importe_neto[]" value="<?php echo $fila->importe_neto != ''?$fila->importe_neto:'0'; ?>" readonly /></td> 
						        </tr>
                		<?php 
	    						$cantidad_total += $fila->cantidad;
	    						$total_importe += $fila->importe;
	    						$total_devuelto += $fila->cantidad_devolucion;
	    						$cantidad_total_recibida += $fila->importe_neto;
	    						
                			} ?>
                 		<tr>
                			<td>&nbsp;</td>
                			<td>&nbsp;</td>
                			<td><input type="text" name="cantidad_total_recibida" class="cantidad_total_recibida" value="<?php echo round($cantidad_total, 2); ?>" readonly="readonly" /> </td>
                			<td>&nbsp;</td>
                			<td><input type="text" name="total_importe" class="total_importe" value="S/. <?php echo number_format($total_importe, 2); ?>" readonly="readonly" /> </td>
                			<td><input type="text" name="cantidad_total_devuelta" class="cantidad_total_devuelta" value="<?php echo $total_devuelto; ?>" readonly="readonly" /> </td>
                			<td>&nbsp;</td>
                			<td><input size="10" type="text" name="total" class="total" value="<?php echo number_format($cantidad_total_recibida, 2); ?>" readonly="readonly" /> </td>
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
                	<?php } else { 
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