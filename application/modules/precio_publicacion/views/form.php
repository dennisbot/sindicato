<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-precio_publicacion" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="id" value="<?php echo $this->mdl_precio_publicacion->form_value('id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('precio') != '' ? 'error' : '';?>">
            <label class="control-label">* precio </label>
            <div class="controls">
                <input type="text" name="precio" value="<?php echo $this->mdl_precio_publicacion->form_value('precio'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('fecha') != '' ? 'error' : '';?>">
            <label class="control-label">* fecha </label>
            <div class="controls">
                <input type="text" name="fecha" value="<?php echo $this->mdl_precio_publicacion->form_value('fecha'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('publicacion_id') != '' ? 'error' : '';?>">
            <label class="control-label">* publicacion_id </label>
            <div class="controls">
                <input type="text" name="publicacion_id" value="<?php echo $this->mdl_precio_publicacion->form_value('publicacion_id'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('operador_id') != '' ? 'error' : '';?>">
            <label class="control-label">* operador_id </label>
            <div class="controls">
                <input type="text" name="operador_id" value="<?php echo $this->mdl_precio_publicacion->form_value('operador_id'); ?>" />
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
            <li><?php echo anchor('precio_publicacion/index', '<i class=icon-list></i> Listado de precio_publicacions');?></li>
        </ul>
    </div>
</div><!-- padded -->
