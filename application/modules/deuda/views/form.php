<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-deuda" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="pauta_id" value="<?php echo $this->mdl_deuda->form_value('pauta_id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('monto_deuda') != '' ? 'error' : '';?>">
            <label class="control-label">* monto_deuda </label>
            <div class="controls">
                <input type="text" name="monto_deuda" value="<?php echo $this->mdl_deuda->form_value('monto_deuda'); ?>" />
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
            <li><?php echo anchor('deuda/index', '<i class=icon-list></i> Listado de deudas');?></li>
        </ul>
    </div>
</div><!-- padded -->
