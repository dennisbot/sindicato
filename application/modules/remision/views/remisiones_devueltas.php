<div class="centered-text" style="padding-bottom: 15px;">
<?php $this->load->view('dashboard/system_messages'); ?>

</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
	<tr>
		<?php
			foreach ($table_headers as $key => $value) { ?>
            <th><?php echo $table_headers[$key]; ?></th>
            <?php } ?>
            <th>Estado</th>
            <th>Opci&oacute;n</th>
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
				<a href="<?php echo site_url('remision/ver/remision_id/' . $remision->id); ?>" title="Ver remisi&oacute;n devuelta">
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