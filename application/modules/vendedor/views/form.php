<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
	<div class="control-group label label-info">
		Todos los campos con (*) son obligatorios.
	</div>
    <form class="form-horizontal" method="post" id="form-vendedor" action="<?php echo site_url($this->uri->uri_string()); ?>">
    	<table>
    		<tr>
    			<td>
			        <input type="hidden" name="id" value="<?php echo $this->mdl_vendedor->form_value('id'); ?>" />
			        <div class="control-group <?php echo form_error('nombres') != '' ? 'error' : '';?>">
			            <label class="control-label">* Nombres </label>
			            <div class="controls">
			                <input type="text" name="nombres" value="<?php echo $this->mdl_vendedor->form_value('nombres'); ?>" />
			            </div>
			        </div>
    			</td>
    			<td>
			        <div class="control-group <?php echo form_error('apellidos') != '' ? 'error' : '';?>">
			            <label class="control-label">* Apellidos </label>
			            <div class="controls">
			                <input type="text" name="apellidos" value="<?php echo $this->mdl_vendedor->form_value('apellidos'); ?>" />
			            </div>
			        </div>
    			</td>
    		</tr>
    		<tr>
    			<td>
			        <div class="control-group <?php echo form_error('nickname') != '' ? 'error' : '';?>">
			            <label class="control-label">* Nickname </label>
			            <div class="controls">
			                <input type="text" name="nickname" style="text-transform:uppercase" value="<?php echo $this->mdl_vendedor->form_value('nickname'); ?>" />
			            </div>
			        </div>
    			</td>
    			<td>
			        <div class="control-group <?php echo form_error('dni') != '' ? 'error' : '';?>">
			            <label class="control-label">* DNI </label>
			            <div class="controls">
			                <input type="text" id="dni"  maxlength="8" name="dni" value="<?php echo $this->mdl_vendedor->form_value('dni'); ?>" class="numeroentero"/>
			            </div>
			        </div>
    			</td>
    		</tr>
    		<tr>
    			<td>
			        <div class="control-group <?php echo form_error('telefono') != '' ? 'error' : '';?>">
			            <label class="control-label"> Tel&eacute;fono </label>
			            <div class="controls">
			                <input type="text" id="telefono" name="telefono" value="<?php echo $this->mdl_vendedor->form_value('telefono'); ?>" class="numeroentero"/>
			            </div>
			        </div>
    			</td>
    			<td>
			        <div class="control-group <?php echo form_error('direccion_casa') != '' ? 'error' : '';?>">
			            <label class="control-label"> Dirección </label>
			            <div class="controls">
			                <input type="text" name="direccion_casa" value="<?php echo $this->mdl_vendedor->form_value('direccion_casa'); ?>" />
			            </div>
			        </div>
    			</td>
    		</tr>
    		<tr>
    			<td>
			        <div class="control-group <?php echo form_error('direccion_tienda') != '' ? 'error' : '';?>">
			            <label class="control-label"> Dirección de tienda </label>
			            <div class="controls">
			                <input type="text" name="direccion_tienda" value="<?php echo $this->mdl_vendedor->form_value('direccion_tienda'); ?>" />
			            </div>
			        </div>
    			</td>
    			<td>
			        <div class="control-group <?php echo form_error('fecha_nacimiento') != '' ? 'error' : '';?>">
			            <label class="control-label"> Fecha de nacimiento </label>
			            <div class="controls fecha_nacimiento">
			                <input type="hidden" name="fecha_nacimiento" value="<?php echo $this->mdl_vendedor->form_value('fecha_nacimiento'); ?>" id="fecha_nacimiento" />
			                <select id="dia2" class="chzn-select" >
			                 <option value="<?php echo '01';?>" selected >01</option>
			                  <?php for ($i=2; $i<= 9 ; $i++) { ?>
			                    <option value="<?php echo '0'.$i;?>" <?php if (isset($dia)) { if($dia == $i){echo 'selected ';}} ?> ><?php echo '0'.$i;?></option>
			                  <?php  } ?>
			                   <?php for ($i=10; $i<= 31 ; $i++) { ?>
			                    <option value="<?php echo $i;?>"  <?php if (isset($dia)) { if($dia == $i){echo 'selected ';}} ?>><?php echo $i;?></option>
			                  <?php  } ?>
			                </select> <br />
			                <select id="mes"  class="chzn-select">
			                    <?php $meses = $this->lang->line('months'); $j =1;foreach ($meses as $mes ) {?>
			                    <?php if ($j<10){$j='0'.$j;} ?>
			                       <option value ="<?php echo $j;?>" <?php if (isset($mesf)) { if($mesf == $j){echo 'selected';}} ?>><?php echo $mes;?></option>
			                     <?php  $j++;} ?>
			                </select> <br />
			                <select id="anio"class="chzn-select" data-placeholder="Elija un año" >
			                    <option value ="1940" selected > 1940</option>
			                    <?php for ($i=1941; $i<2000 ; $i++) { ?>
			                        <option value ="<?php echo $i?>" <?php if (isset($anio)) { if($anio == $i){echo 'selected ';}} ?> ><?php echo $i;?></option>
			                    <?php  } ?>
			                </select>
			            </div>
			        </div>
    			</td>
    		</tr>
    	</table>


        <div class="control-group <?php echo form_error('email') != '' ? 'error' : '';?>">
            <label class="control-label"> Email </label>
            <div class="controls">
                <input type="text" name="email" value="<?php echo $this->mdl_vendedor->form_value('email'); ?>" />
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