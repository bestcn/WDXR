<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?php if ($payment_data['status'] == 3) { ?> <i class="fa fa-times text-danger">已驳回</i> <?php } elseif ($payment_data['status'] == 2) { ?> <i class="fa fa-check text-success">已通过</i> <?php } ?> 企业信息审核</h5>
                <a href="<?= $this->url->get('admin/finance/payment') ?>" class="pull-right">返回</a>
            </div>
            <div class="ibox-content">
                <style>.table tr th {width:120px;}</style>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                企业缴费信息
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <tr><th>客户名称</th><td><?= $payment_data['name'] ?></td></tr>
                                    <tr><th>申请时间</th><td><?= date('Y-m-d H:i:s', $payment_data['time']) ?></td></tr>
                                    <tr><th>金额</th><td><?= $payment_data['amount'] ?> 元</td></tr>
                                    <tr><th>缴费方式</th><td><?= $payment_data['type'] ?></td></tr>
                                    <tr><th>业务员</th><td><?= $payment_data['admin'] ?></td></tr>
                                    <tr><th>合伙人</th><td><?= $payment_data['partner'] ?></td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                开户行信息
                            </div>
                            <div class="panel-body">
                                <?php if ($bank['number']) { ?>
                                    <table class="table table-hover">
                                        <tr><th>账户性质</th><td><?php if ($bank['bank_type'] == 1) { ?>对公账户<?php } else { ?>个人账户<?php } ?></td></tr>
                                        <tr><th>所属银行</th><td><?= (empty($bank['bank']) ? ('无') : ($bank['bank'])) ?></td></tr>
                                        <tr><th>开户人</th><td><?= (empty($bank['account']) ? ('无') : ($bank['account'])) ?></td></tr>
                                        <tr><th>开户行</th><td><?= (empty($bank['address']) ? ('无') : ($bank['address'])) ?></td></tr>
                                        <tr><th>开户行地址</th><td><?= (empty($bank['province']) ? ('') : ($bank['province'])) ?><?= (empty($bank['city']) ? ('') : ($bank['city'])) ?></td></tr>
                                        <tr><th>银行卡号</th><td><?= (empty($bank['number']) ? ('无') : ($bank['number'])) ?></td></tr>
                                    </table>
                                <?php } else { ?>
                                    无
                                <?php } ?>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                绩效银行卡信息
                            </div>
                            <div class="panel-body">
                                <?php if ($bank['work_number']) { ?>
                                    <table class="table table-hover">
                                        <tr><th>所属银行</th><td><?= (empty($bank['work_bank']) ? ('无') : ($bank['work_bank'])) ?></td></tr>
                                        <tr><th>开户人</th><td><?= (empty($bank['work_account']) ? ('无') : ($bank['work_account'])) ?></td></tr>
                                        <tr><th>开户行</th><td><?= (empty($bank['work_address']) ? ('无') : ($bank['work_address'])) ?></td></tr>
                                        <tr><th>开户行地址</th><td><?= (empty($bank['work_province']) ? ('') : ($bank['work_province'])) ?><?= (empty($bank['work_city']) ? ('') : ($bank['work_city'])) ?></td></tr>
                                        <tr><th>银行卡号</th><td><?= (empty($bank['work_number']) ? ('无') : ($bank['work_number'])) ?></td></tr>
                                    </table>
                                <?php } else { ?>
                                    无
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <?php foreach ($payment_data['voucher'] as $data) { ?>
                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                缴费凭证
                            </div>
                            <div class="panel-body">

                                    <a href="<?= $data ?>" title="缴费凭证" data-gallery="">
                                        <img width="100%" src="<?= $data ?>">
                                    </a>

                            </div>
                        </div>
                    </div>
                    <?php } ?>

                <?php if ($bank['number']) { ?>
                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                银行卡照片
                            </div>
                            <div class="panel-body">
                                <?php foreach ($bank['bankcard_photo'] as $data) { ?>
                                    <a href="<?= $data ?>" title="银行卡照片" data-gallery="">
                                        <img width="100%" src="<?= $data ?>">
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($bank['work_number']) { ?>
                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                绩效银行卡
                            </div>
                            <div class="panel-body">
                                <?php foreach ($bank['work_bankcard_photo'] as $data) { ?>
                                    <a href="<?= $data ?>" title="绩效银行卡照片" data-gallery="">
                                        <img width="100%" src="<?= $data ?>">
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                </div>

                <?php if ($payment_data['status'] == 1) { ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">驳回申请</div>
                                <form id="fail_form" action="<?= $this->url->get('admin/finance/save_payment') ?>" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                驳回原因
                                                <input type="hidden" name="verify_id" value="<?= $payment_data['verify_id'] ?>">
                                                <input type="hidden" name="payment_id" value="<?= $payment_data['id'] ?>" />
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" placeholder="如果需要驳回,请填写驳回原因" name="remark"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <input type="hidden" name="status" value="3">
                                        <input type="button" value="驳回" class="fail btn btn-danger"/>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">通过申请</div>
                                <form id="ok_form" action="<?= $this->url->get('admin/finance/save_payment') ?>" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                企业将正式进入报销流程
                                                <input type="hidden" name="verify_id" value="<?= $payment_data['verify_id'] ?>">
                                                <input type="hidden" name="payment_id" value="<?= $payment_data['id'] ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <input type="hidden" name="status" value="2">
                                        <input type="button" value="通过" class="ok btn btn-primary"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?= $this->tag->stylesheetLink('css/plugins/sweetalert/sweetalert.css') ?>
<?= $this->tag->javascriptInclude('js/plugins/sweetalert/sweetalert.min.js') ?>

<script type="text/javascript">
    $('.ok').click(function () {
        swal({
            title: "确认要通过缴费申请吗？",
            text: "请在仔细核对客户提交的缴费信息，通过审核。此操作不可撤销!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定通过",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("<?= $this->url->get('admin/finance/save_payment') ?>", $("#ok_form").serialize(), function (res) {
                if(1 === res.status) {
                    swal("已通过!", "该企业的申请已经通过", "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    });
    $('.fail').click(function () {
        swal({
            title: "确认要驳回缴费申请吗？",
            text: "请在仔细核对客户提交的缴费信息，驳回申请。此操作不可撤销!",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定驳回",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("<?= $this->url->get('admin/finance/save_payment') ?>", $("#fail_form").serialize(), function (res) {
                if(1 === res.status) {
                    swal("已驳回!", "该企业的申请已经驳回.", "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    console.log(res.info);
                    swal("失败!", res.info, "error");
                }
            });
        });
    });
</script>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <a class="xuanzhuan">旋转</a>
    <a class="fangda">放大</a>
    <a class="suoxiao">缩小</a>
    <ol class="indicator"></ol>
</div>





