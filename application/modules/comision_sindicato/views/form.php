<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-comision_sindicato" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="proveedor_id" value="<?php echo $this->mdl_comision_sindicato->form_value('proveedor_id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('comision_sindicato') != '' ? 'error' : '';?>">
            <label class="control-label">* comision_sindicato </label>
            <div class="controls">
                <input type="text" name="comision_sindicato" value="<?php echo $this->mdl_comision_sindicato->form_value('comision_sindicato'); ?>" />
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
            <li><?php echo anchor('comision_sindicato/index', '<i class=icon-list></i> Listado de comision_sindicatos');?></li>
        </ul>
    </div>
</div><!-- padded -->
