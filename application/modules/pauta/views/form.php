<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
    <form class="form-horizontal" method="post" id="form-pauta" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="remision_id" value="<?php echo $this->mdl_pauta->form_value('remision_id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('cantidad_pauta') != '' ? 'error' : '';?>">
            <label class="control-label">* cantidad_pauta </label>
            <div class="controls">
                <input type="text" name="cantidad_pauta" value="<?php echo $this->mdl_pauta->form_value('cantidad_pauta'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('fecha') != '' ? 'error' : '';?>">
            <label class="control-label">* fecha </label>
            <div class="controls">
                <input type="text" name="fecha" value="<?php echo $this->mdl_pauta->form_value('fecha'); ?>" />
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-danger" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
                <input type="submit" class="btn btn-success" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
            </div>
        </div>
        <div class="control-group <?php echo form_error('proveedor_id') != '' ? 'error' : '';?>">
            <label class="control-label">* Vendedor </label>
            <div class="controls">

                <select id = 'vendedor_id'>

                    <?php foreach ($vendedores as $vendedor) {?>
                        <?php
                         if (isset($vendedor_id)) 
                            {
                                 if ($vendedor->id == $vendedorr_id) 
                                    { ?>
                                        <option value "<?php echo $vendedor->id; ?>" selected><?php echo $vendedor->nombre.' '$vendedor->apellidos; ?> </option>        
                                <?php }
                            } 
                         else { ?>
                        
                                <option value "<?php echo $vendedor->id; ?>" ><?php echo $vendedor->nombre.' '$vendedor->apellidos; ?> </option>        
                    <?php  }?>
                                
                    <?php } ?>
                </select>
            </div>
        </div>

    </form>


    <div class="controles">
        <ul class="nav nav-list">
            <li><?php echo anchor('pauta/index', '<i class=icon-list></i> Listado de pautas');?></li>
        </ul>
    </div>
</div><!-- padded -->
