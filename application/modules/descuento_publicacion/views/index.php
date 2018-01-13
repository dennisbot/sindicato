<div class="centered-text" style="width: 60%; margin: 0px auto; padding: 15px;">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'agregar descuento_publicacion')); ?>
</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>

	</tr>

	<?php foreach ($descuento_publicacions as $descuento_publicacion) { ?>
	<tr>
		<?php foreach ($descuento_publicacion as $value) { ?>
		<td>
			<?php echo $value; ?>
		</td>
		<?php } ?>
		<td>
			<a href="<?php echo site_url('descuento_publicacion/form/dia_descuento_id/' . $descuento_publicacion->dia_descuento_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
			<?php echo icon('edit'); ?>
			</a>
			<a href="<?php echo site_url('descuento_publicacion/delete/dia_descuento_id/' . $descuento_publicacion->dia_descuento_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
			<?php echo icon('delete'); ?>
			</a>
		</td>
	</tr>
	<?php } ?>
</table>
<?php if ($this->mdl_descuento_publicacion->page_links) { ?>
    <div id="loading" style="position: relative"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_descuento_publicacion->page_links; ?>
            </ul>
        </div>
<?php } ?>