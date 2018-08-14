<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>普惠待审核列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-6">
                        <?= $this->tag->form(['admin/loan/auditing', 'method' => 'post', 'autocomplete' => 'on']) ?>
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索申请人名称">
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
                        <th>申请人</th>
                        <th>申请时间</th>
                        <th>业务员</th>
                        <th>合伙人</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                        <?php foreach ($page->items as $company) { ?>
                            <tr>
                                <td><?= $company->id ?></td>
                                <td><?= $company->info_name ?></td>
                                <td><?= date('Y-m-d H:i:s', $company->apply_time) ?></td>
                                <td><?= $company->admin_name ?></td>
                                <td><?= ($company->legal_name ? $company->legal_name : '无') ?></td>
                                <td>
                                <span class="pull-right">
                                    <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/loan/edit/' . $company->id, '审核', 'class' => 'btn btn-primary btn-xs']); ?>
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
                                <?= $this->tag->linkTo(['admin/loan/auditing?page=1' . '&name=' . $name, '第一页']) ?>
                            </li>
                            <li class="paginate_button previous">
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/loan/auditing?page=' . $page->before . '&name=' . $name, '前一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/loan/auditing?page=' . $page->before, '前一页']) ?>
                                <?php } ?>
                            </li>
                            <li class="paginate_button next">
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/loan/auditing?page=' . $page->next . '&name=' . $name, '下一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/loan/auditing?page=' . $page->next, '下一页']) ?>
                                <?php } ?>
                            </li>
                            <li>
                                <?php if ($name) { ?>
                                    <?= $this->tag->linkTo(['admin/loan/auditing?page=' . $page->last . '&name=' . $name, '最后一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/loan/auditing?page=' . $page->last, '最后一页']) ?>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


