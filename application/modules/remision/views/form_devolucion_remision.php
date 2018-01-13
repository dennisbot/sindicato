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
            <div class="controls">
                <div>
                	<?php
                		if(!empty($remisiones)){
                			echo 'Se tiene '.count($nro_remisiones).' remisi&oacute;n(es) para esta fecha:<br />';
                			foreach ($nro_remisiones as $fila) {
                				$tiene_pauta = $this->mdl_remision->tiene_pauta($fila->id);
                				$tiene_devolucion = $this->mdl_remision->tiene_devolucion($fila->id);
                				if($tiene_pauta && $tiene_devolucion){ ?>
									<div class="ver-remision" data-title="Hacer devoluci&oacute;n a esta remisi&oacute;n" data-original-title="" title="">
		                        		<a href="<?php echo base_url();?>remision/devolver/remision_id/<?php echo $fila->id; ?>" title="Ver esta remisi&oacute;n"><?php echo 'Nro Gu&iacute;a: '.$fila->nro_guia.' - Raz&oacute;n social: '.$fila->razon_social; ?></a>
		                        	</div>              				
                			<?php  
                				}
	                			else{
	                				echo 'Esta remisi&oacute;n a&uacute;n no tiene pautas y no se puede hacer la devoluci&oacute;n.<br />';
	                			}
                			}
                		} 
                		else { 
                			echo 'Lo sentimos no tenemos remisiones en esta fecha.'; 
                		} ?>
                </div>
            </div>
        </div>
    </form>
</div><!-- padded -->