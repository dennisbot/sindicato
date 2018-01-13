<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-descripcion_tipo_plantilla" action="<?php echo site_url($this->uri->uri_string()); ?>">
                <div class="control-group <?php echo form_error('descripcion') != '' ? 'error' : '';?>">
            <label class="control-label">* descripcion </label>
            <div class="controls">
                <input type="text" name="descripcion" value="<?php echo $this->mdl_descripcion_tipo_plantilla->form_value('descripcion'); ?>" />
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
            <li><?php echo anchor('descripcion_tipo_plantilla/index', '<i class=icon-list></i> Listado de Descripción de tipo de plantilla');?></li>
            <li><?php echo anchor('pauta/plantilla', '<i class=icon-list></i> Volver a listado de plantillas');?></li>
        </ul>
    </div>
</div><!-- padded -->
