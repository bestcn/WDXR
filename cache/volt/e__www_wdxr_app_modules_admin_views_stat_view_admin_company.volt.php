<?= $this->tag->javascriptInclude('js/plugins/jsMind/jsmind.js') ?>
<?= $this->tag->stylesheetLink('css/plugins/jsMind/jsmind.css') ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= $admin->name ?>的客户关系</h5>
                <div class="ibox-tools">
                    <a class="fullscreen-link">
                        <i class="fa fa-expand"></i>
                    </a>
                </div>
            </div>
            <div style="height: 1000px;" class="ibox-content" id="jsmind_container"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var mind = {
        "meta":{"name":"<?= $admin->name ?>客户关系"},
        "format":"node_tree",
        "data": <?= $recommends ?>
    };
    var options = {
        container:'jsmind_container',
        editable:true,
        mode:"full",
        theme:'primary'
    };
    var jm = new jsMind(options);
    jm.show(mind);
</script>
