<?php
/**
 *
 * Template: full width
 *
 */
?>

<?php $this->load->view('layout/header'); ?>

<?php $this->load->view('layout/navegacion'); ?>

<div id="title">

	<?php if ($title != '') : ?>

    	<h2 class="text-center"><?php echo $title ?></h2>

	<?php endif; ?>

</div>

<div class="container-fluid">

	<div class="row-fluid">

		<div class="span12">

			<?php echo $content ?>

		</div>

    </div>
<?php $this->load->view('layout/footer'); ?>