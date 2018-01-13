<?php
/**
 *
 * Template: administrador login
 *
 */
?>

<?php $this->load->view('layout/header-administrador-login'); ?>


<div id="login-box">
<?php if ( isset($title) && $title != '') : ?>

	<div class="row-fluid">
        <div class="span12">
            <h3 class="text-center"> SINDICATO DE VENDEDORES DE DIARIOS Y REVISTAS DEL CUSCO </h3>
</div>
    </div>
<?php endif; ?>

	<?php echo $content ?>

<?php $this->load->view('layout/footer'); ?>