<div class="row-fluid">
    
    <div class="span7">

        <ul class="nav nav-pills" id="myTab">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> publicaciones <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <?php foreach ($publicaciones_list as $publicacion) : ?>
                
                        <li><a href="#<?php echo $publicacion->id ?>" data-toggle="tab"><?php echo $publicacion->nombre . " (" . count($publicacion->comisiones) . ")" ?></a></li>
                    
                    <?php endforeach ?>
                </ul>
            </li>
        </ul>

        <div class="tab-content">
            <?php //var_dump($publicaciones_list) ?>
            <?php foreach ($publicaciones_list as $publicacion) : ?>
                
                <div class="tab-pane" id="<?php echo $publicacion->id ?>">

                <h3><?php echo $publicacion->nombre ?></h3>

                <table class="table table-striped">

                    <tr>
                        <th>Dias</th>
                        <th>Comisi&oacute;n</th>
                        <th>Eliminar</th>
                    </tr>

                    <?php foreach ($publicacion->comisiones as $comision) : ?>
                        
                        <tr>
                            <td><?php echo $comision->dia ?></div>
                            <td id="<?php echo $publicacion->id . "-" . $comision->id ?>" class="edit_comision" data-id="<?php echo $publicacion->id ?>"><?php echo $comision->comision ?></td>
                            <td>
                            <a href="<?php echo site_url('publicacion/eliminar_comision/id/' . $comision->id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
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

    <div class="span5">

        <?php $this->load->view('dashboard/system_messages'); ?>

        <form class="form-horizontal" method="post" id="form-publicacion" action="<?php echo site_url($this->uri->uri_string()); ?>">
            <dl style="display:none" >
                <input type="hidden" name="id" value="<?php echo $this->mdl_publicacion->form_value('id'); ?>" />
            </dl>

            <div class="control-group">
                <p>Utilice el siguiente formulario para agregar nuevos porcentajes de comisi&oacute;n:</p>
            </div>
            
            <div class="control-group <?php echo form_error('publicacion_id') != '' ? 'error' : '';?>">
                <label class="control-label">* Publicacion</label>
                <div class="controls">
                    <?php echo form_dropdown('publicacion_id', $publicaciones, $chosen_publicaciones,'" data-placeholder="Elegir una Opci&oacute;n" class="chzn-select"') ?>
                </div>
            </div>

            <div id="dia" class="control-group <?php echo form_error('dia') != '' ? 'error' : '';?>">
                <label class="control-label">* D&iacute;a </label>
                <div class="controls">
                    <?php echo form_dropdown('dia[]', $dias, $chosen_dias,'" data-placeholder="Elegir una Opci&oacute;n" class="chzn-select" multiple') ?>
                </div>
            </div>

            <div class="control-group <?php echo form_error('comision') != '' ? 'error' : '';?>">
                <label class="control-label">* comision<br/>ej: 1.5</label>
                <div class="controls">
                    <div class="input-append">
                        <?php echo form_input('comision', $this->mdl_publicacion->form_value('comision') ); ?>
                        <span class="add-on">%</span>
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

    </div>

</div>