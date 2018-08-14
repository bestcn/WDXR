<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    <i data-toggle="tooltip" data-placement="right" class="fa fa-info-circle" title="服务期内的企业"></i>
                    已入驻企业列表
                </h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <?= $this->tag->form(['admin/companys/index', 'method' => 'get', 'class' => 'form-inline']) ?>
                        <div class="col-sm-2">
                            <label>
                                <select class="form-control" name="type">
                                    <option <?php if ($this->request->get('type') == '') { ?>selected<?php } ?> value="">客户类型</option>
                                    <option <?php if ($this->request->get('type') == '1') { ?>selected<?php } ?> value="1">事业合伙人</option>
                                    <option <?php if ($this->request->get('type') == '2') { ?>selected<?php } ?> value="2">普惠客户</option>
                                </select>
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <label>
                                <select class="form-control" name="level">
                                    <option <?php if ($this->request->get('level') == '') { ?>selected<?php } ?> value="">级别</option>
                                    <option <?php if ($this->request->get('level') == '1') { ?>selected<?php } ?> value="1">V1</option>
                                    <option <?php if ($this->request->get('level') == '2') { ?>selected<?php } ?> value="2">V2</option>
                                    <option <?php if ($this->request->get('level') == '3') { ?>selected<?php } ?> value="3">V3</option>
                                </select>
                            </label>
                        </div>
                        <div class="col-sm-8 input-group">
                            <input type="text" name="search" class="input-sm form-control" value="<?= $this->request->get('search') ?>" placeholder="搜索企业名称、统一社会信用代码、联系方式、法人姓名、合同编号、用户账号、业务员姓名">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                    <?= $this->tag->endForm() ?>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        <th>级别</th>
                        <th>法人</th>
                        <th>客户类型</th>
                        <th>票据状态</th>
                        <th>征信状态</th>
                        <th>业务员</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                    <?php foreach ($page->items as $company) { ?>
                    <tr>
                        <td><?= $company->id ?></td>
                        <td><?= $company->name ?></td>
                        <td><?= $company->level_name ?></td>
                        <td><?= $company->legal_name ?></td>
                        <td>
                            <?php if ($company->is_partner == 1) { ?>
                                合伙人
                            <?php } else { ?>
                                普惠
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($company->bill_status == 0) { ?>
                                <i class="fa text-muted"> 待交</i>
                            <?php } elseif ($company->bill_status == 1) { ?>
                                <i class="fa fa-check text-success"> 正常</i>
                            <?php } elseif ($company->bill_status == 2) { ?>
                                <i class="fa fa-clock-o text-navy"> 即将到期</i>
                            <?php } elseif ($company->bill_status == 3) { ?>
                                <i class="fa fa-times text-danger"> 已过期</i>
                            <?php } else { ?>
                                <i class="fa fa-times text-danger"> 状态错误</i>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($company->report_status == 0) { ?>
                                <i class="fa text-muted"> 待交</i>
                            <?php } elseif ($company->report_status == 1) { ?>
                                <i class="fa fa-check text-success"> 正常</i>
                            <?php } elseif ($company->report_status == 2) { ?>
                                <i class="fa fa-clock-o text-navy"> 即将到期</i>
                            <?php } elseif ($company->report_status == 3) { ?>
                                <i class="fa fa-times text-danger"> 已过期</i>
                            <?php } else { ?>
                                <i class="fa fa-times text-danger"> 状态错误</i>
                            <?php } ?>
                        </td>
                        <td><?= (empty($company->admin_name) ? ('无') : ($company->admin_name)) ?></td>
                        <td>
                            <span class="pull-right">
                                <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/info/' . $company->id, '查看', 'class' => 'btn btn-primary btn-xs']); ?>
                                <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/refund_add/' . $company->id, '加入黑名单', 'class' => 'btn btn-danger btn-xs']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                    <?php if ($page->total_items === 0) { ?>
                    <tr><td colspan="9">未查询到相关搜索结果</td></tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="row" >
                    <div class="col-sm-5">
                        <?= $page->current . '/' . $page->total_pages ?>
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li>
                                <?= $this->tag->linkTo(['admin/companys/index?page=1' . '&search=' . $this->request->get('search') . '&level=' . $this->request->get('level') . '&type=' . $this->request->get('type'), '第一页']) ?>
                            </li>
                            <li class="paginate_button previous">
                                <?= $this->tag->linkTo(['admin/companys/index?page=' . $page->before . '&search=' . $this->request->get('search') . '&level=' . $this->request->get('level') . '&type=' . $this->request->get('type'), '前一页']) ?>
                            </li>
                            <li class="paginate_button next">
                                <?= $this->tag->linkTo(['admin/companys/index?page=' . $page->next . '&search=' . $this->request->get('search') . '&level=' . $this->request->get('level') . '&type=' . $this->request->get('type'), '下一页']) ?>
                            </li>
                            <li>
                                <?= $this->tag->linkTo(['admin/companys/index?page=' . $page->last . '&search=' . $this->request->get('search') . '&level=' . $this->request->get('level') . '&type=' . $this->request->get('type'), '最后一页']) ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->tag->stylesheetLink('css/plugins/sweetalert/sweetalert.css') ?>
<?= $this->tag->javascriptInclude('js/plugins/sweetalert/sweetalert.min.js') ?>

<script type="text/javascript">
function del(id) {
    swal({
        title: "确认要删除该企业吗？",
        text: "请在仔细核对企业名称及相关信息，企业彻底删除后将无法恢复！",
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定删除",
        cancelButtonText: "取消",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    }, function () {
        $.post("<?= $this->url->get('admin/companys/delete/') ?>", {id:id}, function (res) {
            if(1 === res.status) {
                swal("已驳回!", "该票据申请已经驳回.", "success");
                setTimeout('location.reload()', 1000);
            } else {
                swal("失败!", res.info, "error");
            }
        });
    });
}
</script>