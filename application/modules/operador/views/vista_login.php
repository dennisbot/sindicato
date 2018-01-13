<div id="administrator-login" class="row-fluid">
	<?php $this->load->view('dashboard/system_messages'); ?>
	<div class="span4 offset2 text-center">
		<img src="<?php echo base_url('assets/img/bg-login.png') ?>" alt="">
	</div>
	<div class="span4 text-center">
		<h4 class="text-center">ACCESO AL SISTEMA</h4>
		<form class="form-horizontal" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">
			<div class="control-group <?php echo form_error('email') != '' ? 'error' : '';?>">
			    <label class="control-label">* Usuario: </label>
			    <div class="controls">
			        <input type="text" name="email" value="luzmarina<?php echo $this->mdl_operador->form_value('email'); ?>" />
			    </div>
			</div>
			<div class="control-group <?php echo form_error('clave') != '' ? 'error' : '';?>">
			    <label class="control-label">* Contrase&ntilde;a: </label>
			    <div class="controls">
			        <input type="password" name="clave" value="sindicato2014" />
			    </div>
			</div>

			<div class="control-group">
			    <div class="controls">
			        <input type="submit" class="btn btn-primary" name="btn_submit" value="Iniciar sesi&oacute;n" />
			    </div>
			</div>
		</form>
	<!-- <label class="text-center"><?php echo anchor("/administrador/recuperar", "¿Olvidaste tu contraseña?, click aquí para recuperarlo", 'style="color:whitesmoke;"') ?></label>  -->
	</div>
</div>