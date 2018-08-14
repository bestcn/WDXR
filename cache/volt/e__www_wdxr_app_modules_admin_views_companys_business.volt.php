<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/info/' . $id . '/', '基本信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/payment/' . $id, '缴费信息']); ?></li>
                <li class="active"><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/business/' . $id, '业务信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/user/' . $id, '账号信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/bill/' . $id, '票据信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/report/' . $id, '征信报告']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/contract/' . $id, '合同信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/setting/' . $id, '企业设置']); ?></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        服务订单
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>服务订单ID</th>
                                                <th>服务级别</th>
                                                <th>客户类型</th>
                                                <th>开始时间</th>
                                                <th>结束时间</th>
                                                <th>票据状态</th>
                                                <th>征信状态</th>
                                                <th>服务状态</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (isset($services)) { ?>
                                                <?php foreach ($services as $service) { ?>
                                                    <tr>
                                                        <td><?= $service->id ?></td>
                                                        <td><?= $service->level->level_name ?></td>
                                                        <td><?= ($service->type == 1 ? '事业合伙人' : '普惠客户') ?></td>
                                                        <td><?= date('Y-m-d', $service->start_time) ?></td>
                                                        <td><?= date('Y-m-d', $service->end_time) ?></td>
                                                        <td><?= ($service->bill_status == 1 ? '正常' : '待交') ?></td>
                                                        <td><?= ($service->report_status == 1 ? '正常' : '待交') ?></td>
                                                        <td><?= \Wdxr\Models\Repositories\CompanyService::getStatusName($service->service_status) ?></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr><td colspan="9">无</td></tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        他推荐的企业
                                    </div>
                                    <div class="panel-body scroll_content">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                            <tr>
                                                <th>企业名称</th>
                                                <th>法人</th>
                                                <th>客户类型</th>
                                                <th>
                                                    服务状态
                                                    <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="未入驻企业指尚未通过申请的新企业"></i>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (isset($recommendeds)) { ?>
                                                <?php foreach ($recommendeds as $recommended) { ?>
                                                    <tr class="gradeA">
                                                        <td>
                                                            <a href="<?= $this->url->get('admin/companys/business/' . $recommended['company_id']) ?>">
                                                                <?= $recommended['name'] ?>
                                                            </a>
                                                        </td>
                                                        <td><?= $recommended['legal_name'] ?></td>
                                                        <td><?= $recommended['type'] ?></td>
                                                        <td <?php if ($recommended['status'] == '0') { ?>class="text-danger"<?php } else { ?>class="text-navy"<?php } ?>><?= $recommended['status_name'] ?></td>
                                                    </tr>
                                                    <?php if (empty($recommended['next'])) { ?>
                                                    <?php } else { ?>
                                                        <?php foreach ($recommended['next'] as $sub_recommended) { ?>
                                                            <tr>
                                                                <td align="right">
                                                                    <a href="<?= $this->url->get('admin/companys/business/' . $sub_recommended['company_id']) ?>">
                                                                    <?= $sub_recommended['name'] ?>
                                                                    </a>
                                                                    <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="二级客户"></i>
                                                                </td>
                                                                <td><?= $sub_recommended['legal_name'] ?></td>
                                                                <td><?= $sub_recommended['type'] ?></td>
                                                                <td <?php if ($sub_recommended['status'] == '0') { ?>class="text-danger"<?php } else { ?>class="text-navy"<?php } ?>><?= $sub_recommended['status_name'] ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="3">无</td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        推荐他的企业
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>推荐关系</th>
                                                <th>企业名称</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>推荐人</td>
                                                <td class="data-editable" data-select-url="<?= $this->url->get('admin/companys/get_recommend_company_list') ?>" data-param="recommended_id=<?= $id ?>" data-callback="<?= $this->url->get('admin/companys/save_company_recommend') ?>" data-attr="company_info-type-<?= $recommend->id ?>">
                                                    <?php if (empty($recommend)) { ?>
                                                        无
                                                    <?php } else { ?>
                                                        <a href="<?= $this->url->get('admin/companys/business/' . $recommend->id) ?>">
                                                            <?= $recommend->name ?>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>管理人</td>
                                                <td class="data-editable" data-select-url="<?= $this->url->get('admin/companys/get_recommend_company_list') ?>" data-param="recommend_id=<?= $recommend->id ?>" data-attr="company_info-type-<?= $recommend->id ?>">
                                                    <?php if (empty($manager)) { ?>
                                                        无
                                                    <?php } else { ?>
                                                        <a href="<?= $this->url->get('admin/companys/business/' . $manager->id) ?>">
                                                            <?= $manager->name ?>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        续签/退费
                                    </div>
                                    <div class="panel-body">
                                        <?php if ($service_status) { ?>
                                            <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/refund', '退费并停止服务', 'href' => 'javascript:close(' . $id . ')', 'class' => 'btn btn-danger btn-rounded']); ?>
                                        <?php } ?>
                                        <?php if ($recommend_count > 12) { ?>
                                            <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/refund', '退费并停止服务', 'href' => 'javascript:close(' . $id . ')', 'class' => 'btn btn-danger btn-rounded']); ?>
                                            <a href="" class="btn btn-success btn-rounded">免费续费</a>
                                        <?php } else { ?>
                                            <a href="" class="btn btn-success btn-rounded">续费</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->tag->stylesheetLink('css/plugins/sweetalert/sweetalert.css') ?>
<?= $this->tag->javascriptInclude('js/plugins/sweetalert/sweetalert.min.js') ?>

<script type="text/javascript">
    function close(id) {
        swal({
            title: "确认要退费并停止该企业的服务？",
            text: "请仔细确认相关企业信息，操作后将停止该企业的所有相关服务并退还相关费用",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定退费",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("<?= $this->url->get('admin/companys/refund') ?>", {id:id}, function (res) {
                if(1 === res.status) {
                    swal("已退费!", res.info, "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    }
</script>
