<div class="padded form-agregar">
    <?php $this->load->view('dashboard/system_messages'); ?>
	<div class="control-group label label-info">
		Todos los campos con (*) son obligatorios.
	</div>
    <form class="form-horizontal" method="post" id="form-publicacion" action="<?php echo site_url($this->uri->uri_string()); ?>">
        <dl>
            <input type="hidden" name="id" value="<?php echo $this->mdl_publicacion->form_value('id'); ?>" />
        </dl>
        <div class="control-group <?php echo form_error('nombre') != '' ? 'error' : '';?>">
            <label class="control-label">* Nombre </label>
            <div class="controls">
                <input type="text"  name="nombre" value="<?php echo $this->mdl_publicacion->form_value('nombre'); ?>" />
            </div>
        </div>
  <!--       <div class="control-group <?php echo form_error('img') != '' ? 'error' : '';?>">
            <label class="control-label"> Imagen </label>
            <div class="controls">
                 <input type="text" name="img" value="<?php echo $this->mdl_publicacion->form_value('img'); ?>" />
            </div>
        </div> -->
        <div class="control-group <?php echo form_error('fecha_aniversario') != '' ? 'error' : '';?>">
            <label class="control-label">* Fecha de Aniversario</label>

               <div class="controls fecha_aniversario">
                <input type="hidden" name="fecha_aniversario" value="<?php echo $this->mdl_publicacion->form_value('fecha_aniversario'); ?>" id="fecha_aniversario" />
                     <?php
                    if (!isset($aniversario_mes)) {
                        $aniversario_mes=-1;
                        $aniversario_dia=-1;
                    }
                     ?>
                <select  data-placeholder="Elegir un Mes" name="aniversario_mes" onchange="Numero_de_Dias(); " class="chzn-select">

                    <?php $meses = $this->lang->line('months'); $j =1;foreach ($meses as $mes ) {?>
                    <?php if ($j<10) { ?>
                        <option value ="<?php echo '0'.$j;?>"<?php if($aniversario_mes=='0'.$j){echo ' selected';} ?>  ><?php echo  $mes;?></option>

                    <?php $j++;
                } else {?>
                       <option value ="<?php echo $j;?>" <?php if($aniversario_mes==$j){echo ' selected';} ?>><?php echo  $mes;?></option>
                     <?php  $j++;}}?>
                </select>
                  <!--  class="chzn-select" -->
                <select data-placeholder="Elegir un Día"  name="aniversario_dia" class="chzn-select">
                  <?php for ($i=1; $i<= 31 ; $i++) { ?>
                  <?php if ($i<10) { ?>
                    <option value="<?php echo '0'.$i;?>" <?php if($aniversario_dia=='0'.$i){echo ' selected';} ?>><?php echo '0'.$i; ?></option>
                    <?php
              }
                else
                {?>
                    <option value="<?php echo $i;?>" <?php if($aniversario_dia==$i){echo ' selected';} ?>><?php echo $i; ?></option>
                <?php

                }

              ?>


                  <?php  } ?>
                </select>

            </div>

        </div>
        <div class="control-group <?php echo form_error('proveedor_id') != '' ? 'error' : '';?>">
            <label class="control-label">* Proveedor </label>
            <div class="controls">
                <select class="chzn-select" name="proveedor_id" >
                    <?php $proveedor_id  = -1;
                    if (isset($idproveedor)) {
                        $proveedor_id = $idproveedor;
                    }
                     ?>
                    <option value=""></option>
                    <?php foreach ($proveedores as $proveedor) {
                       ?>
                        <option value ="<?php echo $proveedor->id;?>"  <?php if ($proveedor->id == $proveedor_id) {
                            echo " selected ";} ?> ><?php echo $proveedor->nombre; ?> </option>
                        <?php
                    } ?>
                </select>
            </div>
        </div>
        <div class="control-group <?php echo form_error('tipo') != '' ? 'error' : '';?>">
            <label class="control-label">* Tipo </label>
            <div class="controls">
                  <select class="chzn-select" name="tipo" >
                    <?php $tipo  = 'periodico';
                    if (isset($tipo_publicacion)) {
                        $tipo = $tipo_publicacion;
                    }
                     ?>
                        <option value ="periodico" <?php if ($tipo == "periodico") {echo 'selected';} ?>> Periódico</option>
                        <option value ="revista" <?php if ($tipo=="revista") {echo 'selected';} ?> > Revista</option>
                  
                </select>
            </div>
        </div>
    
        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-danger" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
                <input type="submit" class="btn btn-success" name="btn_submit" value="<?php echo $this->lang->line('submit');?>" />
            </div>
        </div>
    </form>
  <!--  -->
</div><!-- padded -->
