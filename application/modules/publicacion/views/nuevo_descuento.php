<div class="row-fluid">
    
    <div class="span7">
        
        <div class="tabbable tabs-left">
            <ul class="nav nav-tabs">
                
                <?php $active = true; ?>
                <?php foreach ($publicaciones_list as $publicacion) : ?>
                
                    <li <?php if ($active) echo 'class="active"'; $active = false; ?>><a href="#<?php echo $publicacion->id ?>" data-toggle="tab"><?php echo $publicacion->nombre . " (" . count($publicacion->descuentos) . ")" ?></a></li>
                
                <?php endforeach ?>

            </ul>

            <div class="tab-content">
                
                <?php $active = true; ?>
                <?php foreach ($publicaciones_list as $publicacion) : ?>
                    
                    <div class="tab-pane <?php if ($active) echo 'active'; $active = false; ?>" id="<?php echo $publicacion->id ?>">

                    <table class="table table-striped">

                        <tr>
                            <th>Fecha</th>
                            <th>Descuento</th>
                            <th>Eliminar</th>
                        </tr>

                        <?php foreach ($publicacion->descuentos as $descuento) : ?>
                            
                            <?php $fecha = json_decode($descuento->fecha) ?>
                            
                            <tr>
                                <td>
                                    <?php if ( $descuento->tipo_fecha == 'dia') : ?>
                                        <?php echo $fecha->dia ?>
                                    <?php endif ?>

                                    <?php if ( $descuento->tipo_fecha == 'feriado') : ?>
                                        <?php
                                            $fecha = json_decode($descuento->fecha);
                                            echo $fecha->dia . " - " . map_month_spanish(date("M", mktime(0, 0, 0, $fecha->mes)));
                                        ?>
                                    <?php endif ?>

                                    <?php if ( $descuento->tipo_fecha == 'aniversario') : ?>
                                        <?php
                                            $fecha = json_decode($descuento->fecha);
                                            echo $fecha->dia . " - " . $fecha->mes
                                        ?>
                                    <?php endif ?>
                                </td>

                                <td data-id="<?php echo $publicacion->id ?>"class="edit descuento"><?php echo $descuento->porcentaje ?>%</td>
                                
                                <td>
                                <a href="<?php echo site_url('publicacion/eliminar_descuento/id/' . $descuento->dia_descuento_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
                                    <?php echo icon('delete'); ?>
                                </a>
                                </td>
                            </tr>
                            
                        <?php endforeach ?>

                    </table>

                    </div>

                <?php endforeach ?>

            </div>
        </div>

    </div>

    <div class="span5">

        <?php $this->load->view('dashboard/system_messages'); ?>

        <form class="form-horizontal" method="post" id="form-publicacion" action="<?php echo site_url($this->uri->uri_string()); ?>">
            <dl style="display:none" >
                <input type="hidden" name="id" value="<?php echo $this->mdl_publicacion->form_value('id'); ?>" />
            </dl>
            
        	<div class="control-group <?php echo form_error('publicacion_id') != '' ? 'error' : '';?>">
                <label class="control-label">* Publicaci&oacute;n</label>
                <div class="controls">
                    <?php echo form_dropdown('publicacion_id', $publicaciones, $chosen_publicaciones,'" data-placeholder="Elegir una Opci&oacute;n" class="chzn-select"') ?>
                </div>
            </div>

            <div class="control-group <?php echo form_error('tipo_fecha') != '' ? 'error' : '';?>">
                <label class="control-label">* Tipo de Descuento </label>
                <div class="controls">
                    <?php echo form_dropdown('tipo_fecha', $tipos_fecha, $chosen_tipos_fecha,'" id="tipo_fecha" data-placeholder="Elegir una Opci&oacute;n" class="chzn-select"') ?>
                </div>
            </div>

        	<!-- bloque dinamico: place it after tipo_fecha -->

        	<div id="dia" class="control-group <?php echo form_error('dia') != '' ? 'error' : '';?>">
        	    <label class="control-label">* D&iacute;a </label>
        	    <div class="controls">
        	        <?php echo form_dropdown('dia[]', $dias, $chosen_dias,'" data-placeholder="Elegir una Opci&oacute;n" class="chzn-select" multiple') ?>
        	    </div>
        	</div>

        	<div id="aniversario" class="control-group <?php echo form_error('aniversario_dia') != '' || form_error('aniversario_mes') != '' ? 'error force_show' : '';?>">
        	    <label class="control-label">* Aniversario </label>
        	    <div class="controls">
        	        <?php echo form_dropdown('aniversario_dia[]', $fecha_dias, $chosen_aniversario_dias,'" data-placeholder="Elegir un D&iacute;a" class="chzn-select"') ?>
        	        <?php echo form_dropdown('aniversario_mes[]', $fecha_meses, $chosen_aniversario_meses,'" data-placeholder="Elegir un Mes" class="chzn-select"') ?>
        	    </div>
        	</div>

            <div id="feriado" class="control-group <?php echo form_error('feriado_dia') != '' || form_error('feriado_mes') != '' ? 'error force_show' : '';?>">
                <label class="control-label">* Feriado </label>
                <div class="controls">
                    <?php echo form_dropdown('feriado_dia[]', $fecha_dias, $chosen_feriado_dias,'" data-placeholder="Elegir un D&iacute;a" class="chzn-select"') ?>
                    <?php echo form_dropdown('feriado_mes[]', $fecha_meses, $chosen_feriado_meses,'" data-placeholder="Elegir un Mes" class="chzn-select"') ?>
                </div>
            </div>

            <!-- /bloque dinamico -->

            <div class="control-group <?php echo form_error('dia_descuento_nombre') != '' ? 'error' : '';?>">
                <label class="control-label">* Nombre de Columna </label>
                <div class="controls">
                    <?php echo form_input('dia_descuento_nombre', $this->mdl_publicacion->form_value('dia_descuento_nombre') ); ?>
                </div>
            </div>

            <div class="control-group <?php echo form_error('porcentaje_descuento') != '' ? 'error' : '';?>">
                <label class="control-label">* Porcentaje </label>
                <div class="controls">
                	<div class="input-append">
        	            <?php echo form_input('porcentaje_descuento', $this->mdl_publicacion->form_value('porcentaje_descuento') ); ?>
        	            <span class="add-on">%</span>
        	        </div>
                </div>
            </div>

            <div style="display:none" class="control-group <?php echo form_error('dia_descuento_id') != '' ? 'error' : '';?>">
                <label class="control-label">* dia_descuento_id </label>
                <div class="controls">
                    <input type="text" name="dia_descuento_id" value="<?php echo $this->mdl_publicacion->form_value('dia_descuento_id'); ?>" />
                </div>
            </div>
            
            <div class="control-group">
                <div class="controls">
                    <input type="submit" class="btn btn-danger" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
                    <input type="submit" class="btn btn-success" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
                </div>
            </div>
        
        </form>

    </div>

</div>