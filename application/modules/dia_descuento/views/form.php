<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-dia_descuento" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="id" value="<?php echo $this->mdl_dia_descuento->form_value('id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('nombre') != '' ? 'error' : '';?>">
            <label class="control-label">* nombre </label>
            <div class="controls">
                <input type="text" name="nombre" value="<?php echo $this->mdl_dia_descuento->form_value('nombre'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('fecha') != '' ? 'error' : '';?>">
            <label class="control-label">* fecha </label>
            <div class="controls">
                <input type="text" name="fecha" value="<?php echo $this->mdl_dia_descuento->form_value('fecha'); ?>" />
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-danger" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
                <input type="submit" class="btn btn-success" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
            </div>
        </div>
    </form>
    <div class="controles">
        <ul class="nav nav-list">
            <li><?php echo anchor('dia_descuento/index', '<i class=icon-list></i> Listado de dia_descuentos');?></li>
        </ul>
    </div>
</div><!-- padded -->
