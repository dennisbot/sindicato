<div class="row-fluid">
    
    <div class="span7">
        
        <ul class="nav nav-pills" id="myTab">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> publicaciones <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <?php foreach ($publicaciones_list as $publicacion) : ?>
                        
                        <li><a href="#<?php echo $publicacion->id ?>" data-toggle="tab"><?php echo $publicacion->nombre . " (" . count($publicacion->precios) . ")" ?></a></li>
                    
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
                        <th>Precios</th>
                        <th>Eliminar</th>
                    </tr>

                    <?php foreach ($publicacion->precios as $precio) : ?>
                        
                        <tr>
                            <td><?php echo $precio->dia ?></td>
                            <td id="<?php echo $publicacion->id . "-" . $precio->id ?>" class="edit_precio" data-id="<?php echo $publicacion->id ?>"><?php echo $precio->precio ?></td>
                            <td>
                                <a href="<?php echo site_url('publicacion/eliminar_precio/id/' . $precio->id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
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
                <p>Utilice el siguiente formulario para agregar nuevos precios de publicaciones:</p>
            </div>
            
        	<div class="control-group <?php echo form_error('publicacion_id') != '' ? 'error' : '';?>">
                <label class="control-label">* Publicaci&oacute;n</label>
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

            <div class="control-group <?php echo form_error('precio') != '' ? 'error' : '';?>">
                <label class="control-label">* Precio<br/>ej: 0.50</label>
                <div class="controls">
                	<div class="input-prepend">
                        <span class="add-on">S/</span>
        	            <?php echo form_input('precio', $this->mdl_publicacion->form_value('precio') ); ?>
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