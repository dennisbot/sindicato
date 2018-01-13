<div class="centered-text">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'Agregar Ingreso')); ?>
</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>

	</tr>

	<?php foreach ($ingresos as $ingreso) { ?>
	<tr>

		<td><?php echo $ingreso->concepto; ?></td>
		<td><?php echo $ingreso->importe; ?></td>
		<td><?php echo format_date_to_show($ingreso->fecha); ?></td>

		<td>
			<a href="<?php echo site_url('ingreso/form/id/' . $ingreso->id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
			<?php echo icon('edit'); ?>
			</a>
			<a href="<?php echo site_url('ingreso/delete/id/' . $ingreso->id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
			<?php echo icon('delete'); ?>
			</a>
		</td>
	</tr>
	<?php } ?>
</table>
<?php if ($this->mdl_ingreso->page_links) { ?>
    <div id="loading" style="position: relative"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_ingreso->page_links; ?>
            </ul>
        </div>
<?php } ?>