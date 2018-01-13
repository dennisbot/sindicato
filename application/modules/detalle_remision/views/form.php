<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-detalle_remision" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="publicacion_id" value="<?php echo $this->mdl_detalle_remision->form_value('publicacion_id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('descripcion') != '' ? 'error' : '';?>">
            <label class="control-label">* descripcion </label>
            <div class="controls">
                <input type="text" name="descripcion" value="<?php echo $this->mdl_detalle_remision->form_value('descripcion'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('cantidad') != '' ? 'error' : '';?>">
            <label class="control-label">* cantidad </label>
            <div class="controls">
                <input type="text" name="cantidad" value="<?php echo $this->mdl_detalle_remision->form_value('cantidad'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('unidad_medida') != '' ? 'error' : '';?>">
            <label class="control-label">* unidad_medida </label>
            <div class="controls">
                <input type="text" name="unidad_medida" value="<?php echo $this->mdl_detalle_remision->form_value('unidad_medida'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('precio_unitario_guia') != '' ? 'error' : '';?>">
            <label class="control-label">* precio_unitario_guia </label>
            <div class="controls">
                <input type="text" name="precio_unitario_guia" value="<?php echo $this->mdl_detalle_remision->form_value('precio_unitario_guia'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('importe') != '' ? 'error' : '';?>">
            <label class="control-label">* importe </label>
            <div class="controls">
                <input type="text" name="importe" value="<?php echo $this->mdl_detalle_remision->form_value('importe'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('cantidad_devolucion') != '' ? 'error' : '';?>">
            <label class="control-label">* cantidad_devolucion </label>
            <div class="controls">
                <input type="text" name="cantidad_devolucion" value="<?php echo $this->mdl_detalle_remision->form_value('cantidad_devolucion'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('importe_neto') != '' ? 'error' : '';?>">
            <label class="control-label">* importe_neto </label>
            <div class="controls">
                <input type="text" name="importe_neto" value="<?php echo $this->mdl_detalle_remision->form_value('importe_neto'); ?>" />
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
            <li><?php echo anchor('detalle_remision/index', '<i class=icon-list></i> Listado de detalle_remisions');?></li>
        </ul>
    </div>
</div><!-- padded -->
