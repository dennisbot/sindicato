<div class="centered-text">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'Agregar Operador')); ?>
</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>

	</tr>

	<?php foreach ($operadors as $operador) { ?>
	<tr>

		<td><?php echo $operador->nombre_usuario; ?></td>
		<td><?php echo $operador->email; ?></td>


		<td>
			<a href="<?php echo site_url('operador/form/id/' . $operador->id); ?>" title="Editar">
			<?php echo icon('edit'); ?>
			</a>
			<a href="<?php echo site_url('operador/delete/id/' . $operador->id); ?>" title="Eliminar" onclick="javascript:if(!confirm('Estar seguro que deseas eliminar este registro')) return false">
			<?php echo icon('delete'); ?>
			</a>
		</td>
	</tr>
	<?php } ?>
</table>
<?php if ($this->mdl_operador->page_links) { ?>
    <div id="loading" style="position: relative"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_operador->page_links; ?>
            </ul>
        </div>
<?php } ?>