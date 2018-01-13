<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
	<form class="form-horizontal" method="post" id="form-devolucion" action="<?php echo site_url($this->uri->uri_string()); ?>">
		<div class="control-group">
	        <div class="controls">
	            <?php $today = date("d/m/Y"); ?>
	            Seleccionar fecha:&nbsp;
	            <span class="input-append date calendari" id="dpStartDate">
	            	<input type="text" class="datepicker" name="fecha_inicio" value="<?php echo ($this->input->post('fecha_inicio') != '')?$this->input->post('fecha_inicio'):$today; ?>" readonly="readonly" />
	            	<span class="add-on"><i class="icon-calendar"></i></span>
	            </span> |
	            <select name="publicacion">
	            	<option value="">Todos</option>
	            	<?php foreach ($publicaciones as $fila) { ?>
	            		<option value="<?php echo $fila->id; ?>" 
	            		<?php if(isset($publi)) echo ($publi == $fila->id)?'selected="selected"':''; ?>><?php echo $fila->nombre; ?></option>
	            	<?php } ?>
	            </select>
	            <input type="submit" class="btn btn-success" name="btn_consulta" value="Mostrar deudores" />
			</div>
			<div class="controls">
				
			</div>
		</div>
	</form>	
        <div class="control-group">
            <div class="controls">
                <div>
                	<?php
                		if(!empty($deudores)){
                			$suma_total = 0;
                		?>
                		<div class="alert alert-success">
                			<?php echo 'Deudores del: '.$fecha_inicio.'</strong>, total: '.count($deudores); ?>
						</div>
                		<table class="table table-striped table-hover">
                			<tr>
                				<td>Nombre</td>
                				<td>Fecha</td>
                				<td>Publicaci&oacute;n</td>
                				<td>Cantidad repartida</td>
                				<td>Total a cobrar</td>
                				<td>Saldo a cobrar</td>
                			</tr>
                			<?php
                			foreach ($deudores as $fila) { ?>
                				<tr>
                					<td><?php echo $fila->nickname; ?></td>
                					<td><?php echo $fila->fecha; ?></td>
                					<td><?php echo $fila->nombre; ?></td>
                					<td><?php echo $fila->cantidad; ?></td>
                					<td><?php echo $fila->monto_deuda; ?></td>
                					<td style="background:#D9534F;color: #FFF">S/. <?php echo $fila->saldo; ?></td>
                				</tr>
                			<?php
                				$suma_total += $fila->saldo;
                			}
                			?>
                			<tr>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td></td>
                				<td>Total:</td>
                				<td style="background:#D9534F;color: #FFF">S/. <?php echo $suma_total; ?></td>
                			</tr>
                		</table>

                		<?php }
                		else {
                			echo 'Lo sentimos no tenemos deudores para la fecha y/o para la publicaci&oacute;n seleccionada.';
                		} ?>
                </div>
            </div>
        </div>
</div><!-- padded -->