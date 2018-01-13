<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-ingreso" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <div class="control-group <?php echo form_error('concepto') != '' ? 'error' : '';?>">
            <label class="control-label">* Concepto </label>
            <div class="controls">
            <textarea style ="width:300px;"  rows="2" cols="5" name="concepto" id="concepto"><?php echo $this->mdl_ingreso->form_value('concepto'); ?></textarea>
            </div>
        </div>
        <div class="control-group <?php echo form_error('importe') != '' ? 'error' : '';?>">
            <label class="control-label">* Importe S/.</label>
            <div class="controls">
                <input class="currency" type="text" name="importe" value="<?php echo $this->mdl_ingreso->form_value('importe'); ?>"/>
            </div>
        </div>
        <div class="control-group <?php echo form_error('fecha') != '' ? 'error' : '';?>">
            <label class="control-label">* Fecha </label>
            <div class="controls">
                <div class="input-append date datepicker">
                <input name="fecha" type="text" value="<?php echo $this->mdl_ingreso->form_value('fecha') ?: date("d/m/Y", time()); ?>" readonly="readonly" placeholder="click para seleccionar">
                <span class="add-on"><i class="icon-calendar"></i></span>
              </div>
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