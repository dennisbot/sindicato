<div class="centered-text">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'Agregar proveedor')); ?>
</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>

	</tr>

	<?php foreach ($proveedors as $proveedor) { ?>
	<tr>
		<?php 
		echo '<td>'.$proveedor->nombre.'</td>';
		echo '<td>'.$proveedor->direccion.'</td>';
		echo '<td>'.$proveedor->telefonos.'</td>';
		echo '<td>'.$proveedor->ruc.'</td>';
		echo '<td>'.$proveedor->ciudad.'</td>';

		?>

		<td>
			<a href="<?php echo site_url('proveedor/form/id/' . $proveedor->id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
			<?php echo icon('edit'); ?>
			</a>
		
		</td>
	</tr>
	<?php } ?>
</table>
<?php if ($this->mdl_proveedor->page_links) { ?>
    <div id="loading" style="position: relative"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_proveedor->page_links; ?>
            </ul>
        </div>
<?php } ?>