<?php $this->load->view('dashboard/system_messages') ?>
<form action="" class="form-horizotal" method="post">
	<div class="control-group">
		<div class="text-center">
			<h6>Arrastre el Nombre para ordenarlo</h6>
		</div>
		<div class="controls">
			<?php echo form_dropdown('vendedores[]', $vendedores, $vendedores, 'class="multiselect" multiple="multiple"') ?>
		</div>
	</div>
	<div class="control-group">
		<div class="controls" style="text-align:center"><input type="submit" value="Guardar Cambios" class="btn"></div>
	</div>
</form>