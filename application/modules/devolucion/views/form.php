<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-devolucion" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="vendedor_id" value="<?php echo $this->mdl_devolucion->form_value('vendedor_id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('cantidad_devolucion') != '' ? 'error' : '';?>">
            <label class="control-label">* cantidad_devolucion </label>
            <div class="controls">
                <input type="text" name="cantidad_devolucion" value="<?php echo $this->mdl_devolucion->form_value('cantidad_devolucion'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('fecha') != '' ? 'error' : '';?>">
            <label class="control-label">* fecha </label>
            <div class="controls">
                <input type="text" name="fecha" value="<?php echo $this->mdl_devolucion->form_value('fecha'); ?>" />
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-danger" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
                <input type="submit" class="btn btn-success" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
            </div>
        </div>
        
        <div class="control-group">
        	<table>
        		<tr>
        			<td>Vendedor</td>
        			<td>Entregado</td>
        			<td>Devuelto</td>
        		</tr>
        		<tr>
        			<?php foreach ($vendedores as $fila) ?>
        				<td></td>
        			<?php ?>
        		</tr>
        	</table>
        </div>
    </form>
</div><!-- padded -->
