<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-pago" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="deuda_id" value="<?php echo $this->mdl_pago->form_value('deuda_id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('monto_pago') != '' ? 'error' : '';?>">
            <label class="control-label">* monto_pago </label>
            <div class="controls">
                <input type="text" name="monto_pago" value="<?php echo $this->mdl_pago->form_value('monto_pago'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('fecha') != '' ? 'error' : '';?>">
            <label class="control-label">* fecha </label>
            <div class="controls">
                <input type="text" name="fecha" value="<?php echo $this->mdl_pago->form_value('fecha'); ?>" />
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
            <li><?php echo anchor('pago/index', '<i class=icon-list></i> Listado de pagos');?></li>
        </ul>
    </div>
</div><!-- padded -->
