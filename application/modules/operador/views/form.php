<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-operador" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <div class="control-group <?php echo form_error('nombre_usuario') != '' ? 'error' : '';?>">
            <label class="control-label">* nombre_usuario </label>
            <div class="controls">
                <input type="text" name="nombre_usuario" value="<?php echo $this->mdl_operador->form_value('nombre_usuario'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('clave') != '' ? 'error' : '';?>">
            <label class="control-label">* clave </label>
            <div class="controls">
                <input type="text" name="clave" value="<?php echo $this->mdl_operador->form_value('clave'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('email') != '' ? 'error' : '';?>">
            <label class="control-label">* email </label>
            <div class="controls">
                <input type="text" name="email" value="<?php echo $this->mdl_operador->form_value('email'); ?>" />
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-danger" name="btn_cancel" value="Cancelar" />
                <input type="submit" class="btn btn-success" name="btn_submit" value="Enviar" />
            </div>
        </div>
    </form>
    <div class="controles">
        <ul class="nav nav-list">
            <li><?php echo anchor('operador/index', '<i class=icon-list></i> Listado de operadors');?></li>
        </ul>
    </div>
</div><!-- padded -->
