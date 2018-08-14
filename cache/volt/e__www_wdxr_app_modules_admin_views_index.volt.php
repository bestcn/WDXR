<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->tag->getTitle() ?>

    <?= $this->tag->stylesheetLink('css/bootstrap.min.css') ?>
    <?= $this->tag->stylesheetLink('font-awesome/css/font-awesome.css') ?>

    <?= $this->tag->stylesheetLink('css/animate.css') ?>
    <?= $this->tag->stylesheetLink('css/style.css') ?>
    <?= $this->tag->stylesheetLink('css/c2.css') ?>
    <?= $this->tag->stylesheetLink('css/blueimp-gallery.min.css') ?>

    <?= $this->tag->stylesheetLink('css/bootstrap-datetimepicker.css') ?>
    <?= $this->tag->stylesheetLink('css/plugins/toastr/toastr.min.css') ?>

    <!--曲线图表-->
    <?= $this->tag->stylesheetLink('css/morris-0.4.3.min.css') ?>
    <?= $this->tag->stylesheetLink('css/c3.min.css') ?>

    <!-- Mainly scripts -->
    <?= $this->tag->javascriptInclude('js/jquery-3.1.1.min.js') ?>
    <?= $this->tag->javascriptInclude('js/bootstrap.min.js') ?>
</head>
<?= $this->getContent() ?>
</html>
