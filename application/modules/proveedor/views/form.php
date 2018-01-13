<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
 	<div class="control-group label label-info">
		Todos los campos con (*) son obligatorios.
	</div>
    <form class="form-horizontal" method="post" id="form-proveedor" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="id" value="<?php echo $this->mdl_proveedor->form_value('id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('nombre') != '' ? 'error' : '';?>">
            <label class="control-label">* Nombre </label>
            <div class="controls">
                <input type="text" name="nombre" value="<?php echo $this->mdl_proveedor->form_value('nombre'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('ruc') != '' ? 'error' : '';?>">
            <label class="control-label">* Ruc </label>
            <div class="controls">
                <input maxlength='11' type="text" name="ruc" class="currency" value="<?php echo $this->mdl_proveedor->form_value('ruc'); ?>" />
            </div>
        </div>
        
        <div class="control-group <?php echo form_error('direccion') != '' ? 'error' : '';?>">
            <label class="control-label">Dirección </label>
            <div class="controls">
                <input type="text" name="direccion" value="<?php echo $this->mdl_proveedor->form_value('direccion'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('telefonos') != '' ? 'error' : '';?>">
            <label class="control-label">Teléfonos </label>
            <div class="controls">
                <input type="text" name="telefonos" value="<?php echo $this->mdl_proveedor->form_value('telefonos'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('ciudad') != '' ? 'error' : '';?>">
            <label class="control-label">Ciudad </label>
            <div class="controls">
                <input type="text" name="ciudad" value="<?php echo $this->mdl_proveedor->form_value('ciudad'); ?>" />
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-danger" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
                <input type="submit" class="btn btn-success" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
            </div>
        </div>
    </form>
</div><!-- padded -->
