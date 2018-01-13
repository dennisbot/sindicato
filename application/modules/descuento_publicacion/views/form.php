<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-descuento_publicacion" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="dia_descuento_id" value="<?php echo $this->mdl_descuento_publicacion->form_value('dia_descuento_id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('porcentaje_descuento') != '' ? 'error' : '';?>">
            <label class="control-label">* porcentaje_descuento </label>
            <div class="controls">
                <input type="text" name="porcentaje_descuento" value="<?php echo $this->mdl_descuento_publicacion->form_value('porcentaje_descuento'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('precio_publico') != '' ? 'error' : '';?>">
            <label class="control-label">* precio_publico </label>
            <div class="controls">
                <input type="text" name="precio_publico" value="<?php echo $this->mdl_descuento_publicacion->form_value('precio_publico'); ?>" />
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
            <li><?php echo anchor('descuento_publicacion/index', '<i class=icon-list></i> Listado de descuento_publicacions');?></li>
        </ul>
    </div>
</div><!-- padded -->
