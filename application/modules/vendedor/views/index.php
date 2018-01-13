<div class="centered-text" style="width: 60%; margin: 0px auto; padding: 15px; float:left;">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'Agregar Vendedor')); ?>
  <div class="btn-group">
    <?php
    $activos ='';
    $todos ='';
    $inactivos='';
    $suplentes ='';
      $estado = uri_assoc('estado');
      if (isset($estado)) {
        if ($estado =='activo') {$activos = 'btn-info';}
        if ($estado =='inactivo') {$inactivos = 'btn-info';}
        if ($estado =='suplente') {$suplentes = 'btn-info';}
        if ($estado =='todos') {$todos = 'btn-info';}
      }
      else $todos = 'btn-info';
     ?>
      <a href="<?php echo  site_url().'vendedor/index/estado/activo';?>"> <button class="btn <?php echo $activos;?>">Activos </button></a>
      <a href="<?php echo  site_url().'vendedor/index/estado/inactivo';?>"> <button class="btn <?php echo $inactivos;?>">Inactivos </button></a>
      <a href="<?php echo  site_url().'vendedor/index/estado/suplente';?>"> <button class="btn <?php echo $suplentes;?>">Suplentes </button></a>
      <a href="<?php echo  site_url().'vendedor/index';?>"> <button class="btn <?php echo $todos;?> ">Todos </button></a>
  </div>
</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
  <tr>
    <?php
        foreach ($table_headers as $key => $value) { ?>
        <th><?php echo $table_headers[$key]; ?></th>
        <?php } ?>
        <th><?php echo $this->lang->line('actions'); ?></th>
	</tr>
	<?php foreach ($vendedors as $vendedor) { ?>
  <?php
  $value = $vendedor->fecha_nacimiento;
    $estilo ='';
    if ($value != '') {
        $dia_mes = $value[6].$value[7].'/'.$value[4].$value[5];
        $hoy = date("d/m/y");
        $aux = explode('/', $hoy);
        $dia_hoy = $aux[0];
        $mes_hoy =$aux[1];

        if ($dia_mes == $dia_hoy.'/'.$mes_hoy)
        {

          $estilo ='style="background-color:#d9edf7; font-weight: bolder;font-size: 1.2em; color:#3a87ad;" title="Cumpleaños de '.$vendedor->nickname.' "';

        }
      }
   ?>
	<tr <?php echo $estilo;?>>
		<td><?php echo $vendedor->nombres; ?> </td>
		<td><?php echo $vendedor->apellidos; ?> </td>
    <td><?php echo $vendedor->nickname; ?> </td>
		<td><?php echo $vendedor->orden; ?> </td>
	 <td><?php  $value = $vendedor->fecha_nacimiento; if ($value != '') {

   echo $dia_mes.'/'.$value[0].$value[1].$value[2].$value[3];} ?> </td>
    <td> <?php
      $now = time();
      echo format_date_to_show((int)$vendedor->created_at,$now);
      ?>
    </td>
		<td>
      <a href="<?php echo site_url('vendedor/form/id/' . $vendedor->id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
      <?php echo icon('edit'); ?>
      </a>
		<div class="btn-group">
			 	<?php
  			 	$class['activo']='btn-warning';
  			 	$class['inactivo']='btn-danger';
  			 	$class['suplente']='btn-success';
			 	 ?>
  		  <button type="button" class="btn <?php echo $class[$vendedor->estado];  ?> dropdown-toggle" data-toggle="dropdown">
  		    <?php
  		    	$estados  = array("INACTIVO","ACTIVO","SUPLENTE");
  		    	echo  strtoupper($vendedor->estado);
  		     ?> <span class="caret"></span>
  		  </button>
  		  <ul class="dropdown-menu">
  		  	<?php
  		  	$estado_actual ="";
  		  	$estado_a=uri_assoc('estado');

  		  	if (isset($estado_a))
  		  		$estado_actual =uri_assoc('estado');

  		  		foreach ($estados as $estado) :
  		  			if (strtolower($estado)!= $vendedor->estado) :
  		  			?>
  		  			<li><a href="<?php echo base_url().'vendedor/cambiar_estado/'.$vendedor->id.'/'.strtolower($estado).'/'.$estado_actual; ?>"><?php echo strtoupper($estado);?></a></li>
    		  		<?php  endif; ?>
            <?php endforeach; ?>
  		  </ul>
			</div>
    </td>
  </tr>
  <?php } ?>
</table>
<?php if ($this->mdl_vendedor->page_links) { ?>
    <div id="loading" style="position: relative"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_vendedor->page_links; ?>
            </ul>
        </div>
<?php } ?>
