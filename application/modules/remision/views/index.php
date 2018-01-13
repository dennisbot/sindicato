<div class="centered-text" style="padding-bottom: 15px;">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'Agregar remisi&oacute;n')); ?>
Ver remisiones: <div class="btn-group">
		<?php
			$pendientes = '';
			$pagadas = '';
			$anuladas = '';
			$todos = '';
			$estado = uri_assoc('estado');
			if (isset($estado))
			{ if ($estado == 'pendiente') {$pendientes = 'btn-info';}
			  if ($estado == 'pagado') {$pagadas = 'btn-info';}
			  if ($estado == 'anulado') {$anuladas = 'btn-info';}
			  if ($estado == 'todos') {$todos = 'btn-info';}
			}
			else
			{$todos = 'btn-info';}
		 ?>
		<a href="<?php echo site_url().'remision/index/estado/pendiente';?>"> <button class="btn <?php echo $pendientes; ?>">Pendientes </button></a>
		<a href="<?php echo site_url().'remision/index/estado/pagado';?>"> <button class="btn <?php echo $pagadas; ?>">Pagadas </button></a>
		<a href="<?php echo site_url().'remision/index/estado/anulado';?>"> <button class="btn <?php echo $anuladas;?> ">Anuladas </button></a>
		<a href="<?php echo site_url().'remision/index/estado/todos';?>"> <button class="btn <?php echo $todos;?> ">Todos </button></a>
	</div>
</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th>Estado</th>
                <th><?php echo $this->lang->line('actions'); ?></th>
	</tr>
	<?php
	foreach ($remisions as $remision) { ?>
	<tr>
			<td><?php echo $remision->id; ?></td>
			<td><?php echo $remision->nro_guia; ?></td>
			<td>
				<?php
				foreach ($proveedores as $proveedor) {
					if ($proveedor->id == $remision->proveedor_id) {
						echo $proveedor->nombre;
					}
				}
				 ?>
			</td>
			<td><?php echo $remision->razon_social; ?></td>
			<td> <?php echo format_date_to_show($remision->fecha_emision); ?></td>
			<td><?php echo format_date_to_show($remision->fecha_recepcion); ?></td>
			<td><?php echo $remision->status; ?></td>

		<td>
			<?php
				//si no tiene pautas puede editar
				$tiene = $this->mdl_remision->tiene_pauta($remision->id);
				if(!$tiene){
			?>
					<a href="<?php echo site_url('remision/form/id/' . $remision->id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
					<?php echo icon('edit'); ?>
					</a>
			<?php } ?>
			<div class="btn-group">

			  <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
			    <?php echo strtoupper($remision->status);?> <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" data-remision-id="<?php  echo $remision->id; ?>">
			    <li ><a href="#">Pendiente</a></li>
			    <li><a href="#">Pagado</a></li>
			    <li><a href="#">Anulado</a></li>
			  </ul>
			</div>
			<a href="<?php echo site_url('remision/ver/remision_id/' . $remision->id); ?>" title="Ver remisi&oacute;n">
			<?php echo icon('programados'); ?>
			</a>			
		</td>
	</tr>
	<?php } ?>
</table>
<?php if ($this->mdl_remision->page_links) { ?>
    <div id="loading" style="position: relative"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_remision->page_links; ?>
            </ul>
        </div>
<?php } ?>