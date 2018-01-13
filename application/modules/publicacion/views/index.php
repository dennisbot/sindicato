<div class="centered-text">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'Agregar Publicaci&oacute;n')); ?>
</div>
<table class="table table-striped table-hover form-agregar">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>
	</tr>
	<?php $idproveedor = -1;
	if (isset($proveedor_actual)) {
		$idproveedor = $proveedor_actual;
	}
	 ?>
	<select id="proveedor" class="chzn-select" >
		<option value="-1">Seleccione Proveedor</option>

		<?php
		foreach ($proveedores as $proveedor) {
		?>
		<option value ="<?php echo $proveedor->id;?>" <?php if ($proveedor->id == $idproveedor) {echo " selected ";} ?>> <?php echo $proveedor->nombre;?>  </option>
		<?php
		} ?>
		<option value="todos">TODOS </option>
	</select>
	<br>
	<br>

	<?php  foreach ($publicacions as $publicacion) { ?>
		<?php
		$estilo="";

		$aux = explode('-', $publicacion->fecha_aniversario);
		$dia = $aux[0];
		$mes = $aux[1];

		$hoy = date("d/m/y");
        $auxh = explode('/', $hoy);
        $dia_hoy = $auxh[0];
        $mes_hoy =$auxh[1];
		if ($dia==$dia_hoy && $mes == $mes_hoy) {
			$estilo ='style="background-color:#d9edf7; font-weight: bolder;font-size: 1.2em; color:#3a87ad;" title="Aniversario de '.$publicacion->nombre.' "';
		}
		?>

		<tr class="nombre" <?php echo $estilo; ?>>
		<td> <?php echo $publicacion->nombre;?> </td>
		<td> <?php echo $publicacion->tipo_publicacion;?> </td>
		<td><?php echo  dia_mes($publicacion->fecha_aniversario,'-');  ?></td>
		<td>
		<?php
			foreach ($proveedores as $proveedor ) {
				if ($publicacion->proveedor_id == $proveedor->id) {
							echo $proveedor->nombre;
					}
				}
		?>
		</td>
		<td>
			<a href="<?php echo site_url('publicacion/form/id/' . $publicacion->id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
			<?php echo icon('edit'); ?>
			</a>
		</td>
	</tr>
	<?php } ?>
</table>
<?php if ($this->mdl_publicacion->page_links) { ?>
    <div id="loading"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_publicacion->page_links; ?>
            </ul>
        </div>
<?php } ?>

