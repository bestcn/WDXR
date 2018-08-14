<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>企业申请待审核列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-6">
                        <?= $this->tag->form(['admin/companys/auditing', 'method' => 'post', 'autocomplete' => 'on']) ?>
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索企业名称">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                        <?= $this->tag->endForm() ?>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        <th>客户身份</th>
                        <th>申请时间</th>
                        <th>业务员</th>
                        <th>合伙人</th>
                        <th>缴费状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                        <?php foreach ($page->items as $company) { ?>
                            <tr>
                                <td><?= $company->id ?></td>
                                <td><?= $company->name ?></td>
                                <td>
                                    <?php if ($company->is_partner) { ?>
                                        <?php if ($company->is_partner == 1) { ?>
                                            合伙人
                                        <?php } else { ?>
                                            普惠
                                        <?php } ?>
                                    <?php } else { ?>
                                            待定
                                    <?php } ?>
                                </td>
                                <td><?= date('Y-m-d H:i:s', $company->apply_time) ?></td>
                                <td><?= $company->admin_name ?></td>
                                <td><?= $company->partner_name ?></td>
                                <td>
                                    <?php if ($company->payment_status == 0) { ?>
                                        <i class="fa text-muted"> 未申请</i>
                                    <?php } elseif ($company->payment_status == 1) { ?>
                                        <i class="fa fa-clock-o text-navy"> 待审核</i>
                                    <?php } elseif ($company->payment_status == 2) { ?>
                                        <i class="fa fa-check text-success"> 已支付</i>
                                    <?php } elseif ($company->payment_status == 3) { ?>
                                        <i class="fa fa-times text-danger"> 被驳回</i>
                                    <?php } elseif ($company->payment_status == 4) { ?>
                                        <i class="fa text-muted"> 已撤销</i>
                                    <?php } elseif ($company->payment_status == 5) { ?>
                                        <i class="fa fa-clock-o text-navy"> 普惠审核</i>
                                    <?php } else { ?>
                                        <i class="fa fa-times text-danger"> 状态错误</i>
                                    <?php } ?>
                                </td>
                                <td><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/edit_auditing/' . $company->id, '审核', 'class' => 'btn btn-primary btn-xs']); ?></td>
                            </tr>
                        <?php } ?>
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
                                <?= $this->tag->linkTo(['admin/companys/auditing?page=1' . '&name=' . $name, '第一页']) ?>
                            </li>
                            <li class="paginate_button previous">
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/companys/auditing?page=' . $page->before . '&name=' . $name, '前一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/companys/auditing?page=' . $page->before, '前一页']) ?>
                                <?php } ?>
                            </li>
                            <li class="paginate_button next">
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/companys/auditing?page=' . $page->next . '&name=' . $name, '下一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/companys/auditing?page=' . $page->next, '下一页']) ?>
                                <?php } ?>
                            </li>
                            <li>
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/companys/auditing?page=' . $page->last . '&name=' . $name, '最后一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/companys/auditing?page=' . $page->last, '最后一页']) ?>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


