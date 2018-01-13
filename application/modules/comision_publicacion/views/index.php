<div class="centered-text">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'agregar comision_publicacion')); ?>
</div>

<table class="table table-striped table-hover form-agregar">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>

	</tr>

	<?php foreach ($comision_publicaciones as $comision_publicacion) { ?>
	<tr>
		<?php foreach ($comision_publicacion as $value) { ?>
		<td>
			<?php echo $value; ?>
		</td>
		<?php } ?>

		<td>
			<a href="<?php echo site_url('comision_publicacion/form/id/' . $comision_publicacion->id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
			<?php echo icon('edit'); ?>
			</a>
			<a href="<?php echo site_url('comision_publicacion/delete/id/' . $comision_publicacion->id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
			<?php echo icon('delete'); ?>
			</a>
		</td>
	</tr>
	<?php } ?>
</table>

<?php if ($this->mdl_comision_publicacion->page_links) { ?>
    <div id="loading"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_comision_publicacion->page_links; ?>
            </ul>
        </div>
<?php } ?>