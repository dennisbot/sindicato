<div class="centered-text" style="width: 60%; margin: 0px auto; padding: 15px;">
<?php $this->load->view('dashboard/system_messages'); ?>
<?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'agregar remision')); ?>
</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>
	</tr>

	<?php
	foreach ($remisions as $remision) { ?>
	<tr>
		<?php $i=0;foreach ($remision as $value) { ?>
		<td>
			<?php
			if ($i==1) {
				foreach ($proveedores as $proveedor) {
					if ($proveedor->id == $value) {
						echo $proveedor->nombre;
					}
				}
			}
			if ($i==3 || $i==4){echo format_date_to_show($value);	 $i++; 	}
			else { 	echo $value;	 $i++; 	}
			 ?>
		</td>
		<?php } ?>

		<td>
			<a href="<?php echo site_url('remision2/form/id/' . $remision->id); ?>" title="<?php echo $this->lang->line('edit'); ?>">

			<?php echo icon('edit'); ?>
			</a>
			<a href="<?php echo site_url('remision2/delete/id/' . $remision->id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
			<?php echo icon('delete'); ?>
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