<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
	<dl>
    	<input type="hidden" name="vendedor_id" value="<?php echo $this->mdl_remision->form_value('vendedor_id'); ?>" />
	</dl>
	<form class="form-horizontal" method="post" id="form-devolucion" action="<?php echo site_url($this->uri->uri_string()); ?>">
		<div class="control-group">
	        <div class="controls">
	            <?php $today = date("d/m/Y"); ?>
	            Seleccionar fechas desde:&nbsp;
	            <span class="input-append date calendari" id="dpStartDate">
	            	<input type="text" class="datepicker" name="fecha_inicio" value="<?php echo ($this->input->post('fecha_inicio') != '')?$this->input->post('fecha_inicio'):$today; ?>" readonly="readonly" />
	            	<span class="add-on"><i class="icon-calendar"></i></span>
	            </span>
				&nbsp; hasta: &nbsp;
	            <span class="input-append date calendarf" id="dpStartDate">
	            	<input type="text" class="datepicker" name="fecha_fin" value="<?php echo ($this->input->post('fecha_fin') != '')?$this->input->post('fecha_fin'):$today; ?>" readonly="readonly" />
	            	<span class="add-on"><i class="icon-calendar"></i></span>
	            </span>

	            <input type="submit" class="btn btn-success" name="btn_consulta" value="Mostrar ingresos" />
			</div>
		</div>
	</form>	
        <div class="control-group">
            <div class="controls">
                <div>
                	<?php
                		if(!empty($ingresos)){
                			$suma_total = 0;
                		?>
                		<div class="alert alert-success">
                			<?php echo 'Ingresos del: '.$fecha_inicio.' hasta el: '.$fecha_fin.', un total de: <strong> S/. '.$suma_monto.'</strong>'; ?>
						</div>
                		<table class="table table-striped table-hover">
                			<tr>
                				<td>Fecha</td>
                				<td>Publicaci&oacute;n</td>
                				<td>Porcentaje</td>
                				<td>Cantidad recibida</td>
                				<td>Ganancia total</td>
                			</tr>
                			<?php
                			foreach ($ingresos as $fila) { ?>
                				<tr>
                					<td><?php echo format_date_to_show($fila->fecha_recepcion); ?></td>
                					<td><?php echo $fila->descripcion; ?></td>
                					<td><?php echo $fila->porcentaje; ?></td>
                					<td><?php echo $fila->cantidad_recibida; ?></td>
                					<td style="background:#5FBE5F;color: #FFF">S/. <?php echo $fila->ganancia; ?></td>
                				</tr>
                			<?php
                				$suma_total += $fila->ganancia;
                			}
                			?>
                			<tr>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td>Total:</td>
                				<td style="background:#5FBE5F;color: #FFF">S/. <?php echo $suma_total; ?></td>
                			</tr>
                		</table>

                		<?php }
                		else {
                			echo 'Lo sentimos no tenemos ingresos/ganancias para las fechas seleccionadas.';
                		} ?>
                </div>
            </div>
        </div>
</div><!-- padded -->