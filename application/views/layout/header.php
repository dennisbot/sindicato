<!DOCTYPE html>
<html lang="<?php echo $this->config->item('language_attributes') ?>" >
    <head>
        <meta charset="utf-8">

        <title><?php echo ($header_title) ? $header_title . " | " . $this->config->item('site_name') : "Proyecto | " . $this->config->item('site_name') ?></title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo bootstrap_css(); ?>bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo bootstrap_css(); ?>bootstrap-responsive.min.css">
        <link rel="stylesheet" href="<?php echo font_awesome_css(); ?>font-awesome.min.css">

        <!-- Css -->
        <link href="<?php echo base_css(); ?>base.css" rel="stylesheet">
        <link href="<?php echo base_css(); ?>custom.css" rel="stylesheet">
        <link href="<?php echo base_css(); ?>fonts.css" rel="stylesheet">
        <link rel="shortcut icon" href="<?php echo base_img(); ?>favicon.ico">
        <!-- Internal Css -->
        <?php echo $_styles ?>

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>