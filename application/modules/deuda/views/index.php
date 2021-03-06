<div class="centered-text" style="width: 60%; margin: 0px auto; padding: 15px;">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'agregar deuda')); ?>
</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>

	</tr>

	<?php foreach ($deudas as $deuda) { ?>
	<tr>
		<?php foreach ($deuda as $value) { ?>
		<td>
			<?php echo $value; ?>
		</td>
		<?php } ?>

		<td>
			<a href="<?php echo site_url('deuda/form/pauta_id/' . $deuda->pauta_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
			<?php echo icon('edit'); ?>
			</a>
			<a href="<?php echo site_url('deuda/delete/pauta_id/' . $deuda->pauta_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
			<?php echo icon('delete'); ?>
			</a>
		</td>
	</tr>
	<?php } ?>
</table>
<?php if ($this->mdl_deuda->page_links) { ?>
    <div id="loading" style="position: relative"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_deuda->page_links; ?>
            </ul>
        </div>
<?php } ?>