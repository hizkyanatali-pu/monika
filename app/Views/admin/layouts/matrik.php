<?php
$title = $title ?? '';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin - <?= $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <?php echo link_tag('plugins/@fortawesome/fontawesome-free/css/all.min.css'); ?>
    <?php echo script_tag('js/jquery.js'); ?>
    <?php echo script_tag('js/angular-1.8.js'); ?>
    
    <?php echo link_tag('css/style.bundle.min.css'); ?>
    <?php echo link_tag('css/matrik.css'); ?>
</head>

<body ng-app="matrikApp" class="" style="background:#fff">
    <?php echo $this->renderSection('content') ?>
    <?php echo $this->renderSection('footer_js') ?>
    <?php echo script_tag('js/matrik.js'); ?>
</body>
</html>