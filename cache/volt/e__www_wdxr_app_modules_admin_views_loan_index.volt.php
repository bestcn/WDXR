<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>普惠申请列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/companyNew', '添加企业', 'class' => 'btn btn-default']); ?>
                    </div>
                    <div class="col-sm-4">
                        <?= $this->tag->form(['admin/loan/index', 'method' => 'post', 'autocomplete' => 'on']) ?>
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索企业名称或申请人名称">
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
                        <th>申请人姓名</th>
                        <th>提交时间</th>
                        <th>审核状态</th>
                        <th>业务员</th>
                        <th>合伙人</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                    <?php foreach ($page->items as $loan) { ?>
                    <tr>
                        <td><?= $loan->id ?></td>
                        <td><?= $loan->info_name ?></td>
                        <td><?= date('Y-m-d H:i:s', $loan->apply_time) ?></td>
                        <td>
                            <?php if ($loan->status == 0) { ?>
                                <i class="fa text-muted" >未申请</i>
                            <?php } elseif ($loan->status == 1) { ?>
                                <i class="fa fa-clock-o text-navy" > 待审核</i>
                            <?php } elseif ($loan->status == 2) { ?>
                                <i class="fa fa-times text-danger"> 已驳回</i>
                            <?php } elseif ($loan->status == 3) { ?>
                                <i class="fa fa-check text-success"> 已通过</i>
                            <?php } elseif ($loan->status == 5) { ?>
                                <i class="fa fa-clock-o text-muted" > 已处理</i>
                            <?php } elseif ($loan->status == 6) { ?>
                                <i class="text-muted"> 已结束</i>
                            <?php } elseif ($loan->status == 7) { ?>
                                <i class="fa fa-check text-success"> 已完成</i>
                            <?php } else { ?>
                                <i class="fa fa-times text-danger"> 状态错误</i>
                            <?php } ?>
                        </td>
                        <td><?= $loan->admin_name ?></td>
                        <td><?= ($loan->legal_name ? $loan->legal_name : '无') ?></td>
                        <td>
                            <span class="pull-right">
                                <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/info/' . $loan->company_id, '查看', 'class' => 'btn btn-primary btn-xs']); ?>
                            <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/loan/edit/' . $loan->id, '查看', 'class' => 'btn btn-primary btn-xs']); ?>
                            </span>
                        </td>
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
                                <?= $this->tag->linkTo(['admin/loan/index?page=1' . '&name=' . $name, '第一页']) ?>
                            </li>
                            <li class="paginate_button previous">
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/loan/index?page=' . $page->before . '&name=' . $name, '前一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/loan/index?page=' . $page->before, '前一页']) ?>
                                <?php } ?>
                            </li>
                            <li class="paginate_button next">
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/loan/index?page=' . $page->next . '&name=' . $name, '下一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/loan/index?page=' . $page->next, '下一页']) ?>
                                <?php } ?>
                            </li>
                            <li>
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/loan/index?page=' . $page->last . '&name=' . $name, '最后一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/loan/index?page=' . $page->last, '最后一页']) ?>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function del(id) {
    if(confirm('确认要删除该企业吗？')) {
        $.post("<?= $this->url->get('admin/loan/delete/') ?>", {id:id});
        location.reload();
    }
}
</script>