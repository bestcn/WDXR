<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>企业待申请列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/companyNew', '手动添加新公司', 'class' => 'btn btn-default']); ?>
                    </div>
                    <!--<div class="col-sm-4 m-b-xs">-->
                        <!--<select class="input-sm form-control input-s-sm inline">-->
                            <!--<option value="0">Option 1</option>-->
                            <!--<option value="1">Option 2</option>-->
                            <!--<option value="2">Option 3</option>-->
                            <!--<option value="3">Option 4</option>-->
                        <!--</select>-->
                    <!--</div>-->
                    <div class="col-sm-4">
                        <?= $this->tag->form(['admin/apply/list', 'method' => 'post', 'autocomplete' => 'on']) ?>
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索公司名称">
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
                        <th>公司名称</th>
                        <th>统一信用代码</th>
                        <th>创建时间</th>
                        <th>操作</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                    <?php foreach ($page->items as $company) { ?>
                    <tr>
                        <td><?= $company->id ?></td>
                        <td><?= $company->name ?></td>
                        <td><?= $company->licence_num ?></td>
                        <td><?= $company->time ?></td>
                        <td>
                            <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/apply/info/' . $company->id, '企业申请', 'class' => 'btn btn-primary btn-xs']); ?>
                        </td>
                        <td></td>
                        <td></td>
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
                            <li><?= $this->tag->linkTo(['admin/apply/list', '第一页']) ?></li>
                            <li class="paginate_button previous"><?= $this->tag->linkTo(['admin/apply/list?page=' . $page->before, '前一页']) ?></li>
                            <li class="paginate_button next"><?= $this->tag->linkTo(['admin/apply/list?page=' . $page->next, '下一页']) ?></li>
                            <li><?= $this->tag->linkTo(['admin/apply/list?page=' . $page->last, '最后一页']) ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
