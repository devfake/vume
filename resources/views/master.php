<!doctype html>
<html>
<head>

  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Vume - PHP MVC & Project Boilerplate</title>

  <link href="<?php echo autoCache('assets/css/core.css'); ?>" rel="stylesheet">
  <link href="<?php echo autoCache('favicon.ico'); ?>" rel="icon" type="image/x-icon">

  <!--[if lt IE 9]> <script src="<?php echo URL; ?>assets/js/html5shiv.js"></script> <![endif]-->

</head>
<body>

    <?php
      require session('vume_view')->get();
    ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo URL; ?>assets/js/jquery-1.11.1.min.js"><\/script>')</script>
<!-- google analytics -->
</body>
</html>