<body>
<script>
    //左侧导航栏自动收起开关
    var collapse = localStorage.getItem('collapse_menu');
    if(collapse === 'on'){
        $('body').addClass('mini-navbar');
    } else {
        $('body').removeClass('mini-navbar');
    }
</script>
<div id="wrapper">
    <?= $this->partial('layouts/sidebar') ?>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <?= $this->partial('layouts/header') ?>
        </div>
        <div class="wrapper wrapper-content">
            <?= $this->flash->output() ?>
            <?= $this->getContent() ?>
        </div>
        <div class="footer">
            <div class="pull-right">
                Version 4.0.0
            </div>
            <div>
                <strong>Copyright</strong>  &copy; 2017 - <?= date('Y') ?> 华企管家
            </div>
        </div>

    </div>
</div>

<?= $this->tag->javascriptInclude('js/moment-with-locales.min.js') ?>
<?= $this->tag->javascriptInclude('js/bootstrap-datetimepicker.min.js') ?>

<?= $this->tag->javascriptInclude('js/plugins/metisMenu/jquery.metisMenu.js') ?>
<?= $this->tag->javascriptInclude('js/plugins/slimscroll/jquery.slimscroll.min.js') ?>

<!-- Custom and plugin javascript -->
<?= $this->tag->javascriptInclude('js/inspinia.js') ?>
<?= $this->tag->javascriptInclude('js/plugins/pace/pace.min.js') ?>

<?= $this->tag->javascriptInclude('js/plugins/toastr/toastr.min.js') ?>

<?= $this->tag->javascriptInclude('js/jquery.blueimp-gallery.min.js') ?>
<?= $this->tag->javascriptInclude('js/common.js') ?>

<script type="text/javascript">
    $(function () { $("[data-toggle='tooltip']").tooltip(); });
    //左侧导航栏自动收起开关（设置Web Storage）
    $('.collapse_menu').on('click',function(){
        var collapse = localStorage.getItem('collapse_menu');
        if(collapse === 'on'){
            localStorage.setItem('collapse_menu', 'off');
        }else{
            localStorage.setItem('collapse_menu', 'on');
        }
    });
</script>
</body>