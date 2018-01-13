<div class="centered-text">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'agregar egreso')); ?>
</div>

<table class="table table-striped table-hover form-agregar">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>

	</tr>

	<?php foreach ($egresos as $egreso) { ?>
	<tr>
		<?php foreach ($egreso as $value) { ?>
		<td>
			<?php echo $value; ?>
		</td>
		<?php } ?>

		<td>
			<a href="<?php echo site_url('egreso/form/id/' . $egreso->id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
			<?php echo icon('edit'); ?>
			</a>
			<a href="<?php echo site_url('egreso/delete/id/' . $egreso->id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
			<?php echo icon('delete'); ?>
			</a>
		</td>
	</tr>
	<?php } ?>
</table>

<?php if ($this->mdl_egreso->page_links) { ?>
    <div id="loading"></div>
    <div id="pagination" class="pagination pagination-centered">
        <ul>
            <?php echo $this->mdl_egreso->page_links; ?>
        </ul>
    </div>
<?php } ?>