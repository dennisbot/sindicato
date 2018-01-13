<div class="centered-text">
<?php $this->load->view('dashboard/system_messages'); ?>
<div class="row">
    <div class="span6">
        <?php $this->load->view('dashboard/btn_add', array('btn_value'=> 'agregar Descripcion de Tipo de Plantilla')); ?>
    </div>
    <div class="span6">
        <ul class="nav nav-list">
            <li><?php echo anchor('pauta/plantilla', '<i class=icon-list></i> Volver a listado de plantillas');?></li>
        </ul>
    </div>
</div>
</div>
<table class="table table-striped table-hover form-agregar" style="margin: 0px auto;">
	<tr>
		<?php
                foreach ($table_headers as $key => $value) { ?>
                <th><?php echo $table_headers[$key]; ?></th>
                <?php } ?>
                <th><?php echo $this->lang->line('actions'); ?></th>

	</tr>

	<?php foreach ($descripcion_tipo_plantillas as $descripcion_tipo_plantilla) { ?>
	<tr>
		<?php foreach ($descripcion_tipo_plantilla as $value) { ?>
		<td>
			<?php echo $value; ?>
		</td>
		<?php } ?>

		<td>
			<a href="<?php echo site_url('descripcion_tipo_plantilla/form/iddescripcion/' . $descripcion_tipo_plantilla->iddescripcion); ?>" title="<?php echo $this->lang->line('edit'); ?>">
			<?php echo icon('edit'); ?>
			</a>
			<a href="<?php echo site_url('descripcion_tipo_plantilla/delete/iddescripcion/' . $descripcion_tipo_plantilla->iddescripcion); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
			<?php echo icon('delete'); ?>
			</a>
		</td>
	</tr>
	<?php } ?>
</table>
<?php if ($this->mdl_descripcion_tipo_plantilla->page_links) { ?>
    <div id="loading" style="position: relative"></div>
        <div id="pagination" class="pagination pagination-centered">
            <ul>
                <?php echo $this->mdl_descripcion_tipo_plantilla->page_links; ?>
            </ul>
        </div>
<?php } ?>
