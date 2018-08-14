<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>全部企业票据列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <?= $this->tag->form(['admin/companys/bill_list', 'method' => 'post', 'autocomplete' => 'on']) ?>
                    <div class="col-sm-6 form-inline">
                        <div class="col-sm-12 input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索企业名称、法人姓名等">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>

                    </div>
                    <?= $this->tag->endForm() ?>
                    <div class="col-sm-6">
                        <form action="<?= $this->url->get('admin/companys/owe_bill_list') ?>" method="post" autocomplete="on">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-primary pull-right">导出欠费报表</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        <th>企业状态</th>
                        <th>法人名称</th>
                        <th>票据金额</th>
                        <th>时间</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                        <?php foreach ($page->items as $company) { ?>
                            <tr>
                                <td><?= $company->id ?></td>
                                <td><?= $company->name ?></td>
                                <td><?php if ($company->status) { ?><i class="fa fa-check text-success"> 正常</i><?php } else { ?><i class="fa fa-times text-danger"> 未启用</i><?php } ?></td>
                                <td><?= $company->legal_name ?></td>
                                <td>
                                    <?php if ($company->status) { ?>
                                        <?php if ($company->amount > 0) { ?>
                                            <i class="fa fa-check text-success"> <?= $company->amount ?></i>
                                        <?php } else { ?>
                                            <i class="fa fa-times text-danger"> <?= ($company->amount ? $company->amount : '0.00') ?></i>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <i class="fa fa-clock-o text-navy"> <?= ($company->amount ? $company->amount : '0.00') ?></i>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?= ($company->time ? $company->time : $company->company_time) ?>
                                </td>
                                <td>
                            <span class="pull-right">
                                <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/bill/' . $company->id, '查看详情', 'class' => 'btn btn-primary btn-xs']); ?>
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
                                <?= $this->tag->linkTo(['admin/companys/bill_list?page=1' . '&name=' . $name, '第一页']) ?>
                            </li>
                            <li class="paginate_button previous">
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/companys/bill_list?page=' . $page->before . '&name=' . $name, '前一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/companys/bill_list?page=' . $page->before, '前一页']) ?>
                                <?php } ?>
                            </li>
                            <li class="paginate_button next">
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/companys/bill_list?page=' . $page->next . '&name=' . $name, '下一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/companys/bill_list?page=' . $page->next, '下一页']) ?>
                                <?php } ?>
                            </li>
                            <li>
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/companys/bill_list?page=' . $page->last . '&name=' . $name, '最后一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/companys/bill_list?page=' . $page->last, '最后一页']) ?>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>