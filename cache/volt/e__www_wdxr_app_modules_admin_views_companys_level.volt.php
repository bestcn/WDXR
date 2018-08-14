<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/basic/', '合同编号生成']); ?></li>
                    <li class="active"><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/level/', '客户等级管理']); ?></li>
                    <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/bankList/', '银行列表']); ?></li>
                </ul>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/add_level', '添加级别', 'class' => 'btn btn-default']); ?>
                    </div>

                    <div class="col-sm-4">
                        <?= $this->tag->form(['admin/companys/level', 'method' => 'post', 'autocomplete' => 'on']) ?>
                        <div class="input-group">
                            <input type="text" name="level_name" class="input-sm form-control" placeholder="搜索级别名称">
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
                        <th>级别名称</th>
                        <th>金额</th>
                        <th>每天返现金额</th>
                        <th>详细信息</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                    <?php foreach ($page->items as $level) { ?>
                    <tr>
                        <td><?= $level->id ?></td>
                        <td><?= $level->level_name ?></td>
                        <td><?= $level->level_money ?> 元</td>
                        <td><?= $level->day_amount ?> 元</td>
                        <td><?= $level->info ?></td>
                        <td><?php if ($level->level_status) { ?> <font color="green">开启</font> <?php } else { ?><font color="red">禁用</font> <?php } ?></td>

                        <td><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/level_edit/' . $level->id, '查看', 'class' => 'btn btn-primary btn-xs']); ?></td>
                        <td><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/level_delete/', '删除', 'class' => 'btn btn-danger btn-xs', 'href' => 'javascript:del("' . $level->id . '")']); ?></td>

                    </tr>

                    </tbody>
                    <?php } ?>
                    <?php } ?>
                </table>
                <div class="row" >
                    <div class="col-sm-5">
                        <?= $page->current . '/' . $page->total_pages ?>
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li><?= $this->tag->linkTo(['admin/companys/level', '第一页']) ?></li>
                            <li class="paginate_button previous"><?= $this->tag->linkTo(['admin/companys/level?page=' . $page->before, '前一页']) ?></li>
                            <li class="paginate_button next"><?= $this->tag->linkTo(['admin/companys/level?page=' . $page->next, '下一页']) ?></li>
                            <li><?= $this->tag->linkTo(['admin/companys/level?page=' . $page->last, '最后一页']) ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该级别吗？')) {
            $.post("<?= $this->url->get('admin/companys/level_delete/') ?>", {id:id});
            location.reload();
        }
    }
</script>