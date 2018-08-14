<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class="active"><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/basic/', '合同编号生成']); ?></li>
                    <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/level/', '客户等级管理']); ?></li>
                    <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/bankList/', '银行列表']); ?></li>
                </ul>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-4">
                        <?= $this->tag->form(['admin/companys/make_contract_num', 'method' => 'post', 'autocomplete' => 'on']) ?>
                        <div class="input-group">
                            <input type="text" name="num" class="input-sm form-control" placeholder="请填写生成个数">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 生成</button>
                            </span>
                        </div>
                        <?= $this->tag->endForm() ?>
                    </div>
                    <div class="col-sm-4">
                    <?= $this->tag->form(['admin/companys/basic', 'method' => 'post', 'autocomplete' => 'on']) ?>
                    <div class="input-group">
                        <input type="text" name="contract_num" class="input-sm form-control" placeholder="请输入搜索编号">
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
                        <th>状态</th>
                        <th>操作</th>


                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                    <?php foreach ($page->items as $contract) { ?>
                            <tr>
                                <td><?= $contract->contract_num ?></td>
                                <td><?php if ($contract->contract_status) { ?> <font color="blue">已使用</font> <?php } else { ?><font color="green">未使用</font> <?php } ?></td>
                                <td><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/contract_delete', '删除', 'class' => 'btn btn-danger btn-xs', 'href' => 'javascript:del("' . $contract->id . '")']); ?></td>

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
                            <li><?= $this->tag->linkTo(['admin/companys/basic?page=1' . '&contract_num=' . $contract_num, '第一页']) ?></li>
                            <li class="paginate_button previous"><?php if ($contract_num) { ?><?= $this->tag->linkTo(['admin/companys/basic?page=' . $page->before . '&contract_num=' . $contract_num, '前一页']) ?><?php } else { ?><?= $this->tag->linkTo(['admin/companys/basic?page=' . $page->before, '前一页']) ?><?php } ?></li>
                            <li class="paginate_button next"><?php if ($contract_num) { ?><?= $this->tag->linkTo(['admin/companys/basic?page=' . $page->next . '&contract_num=' . $contract_num, '下一页']) ?><?php } else { ?><?= $this->tag->linkTo(['admin/companys/basic?page=' . $page->next, '下一页']) ?><?php } ?></li>
                            <li><?php if ($contract_num) { ?><?= $this->tag->linkTo(['admin/companys/basic?page=' . $page->last . '&contract_num=' . $contract_num, '最后一页']) ?><?php } else { ?><?= $this->tag->linkTo(['admin/companys/basic?page=' . $page->last, '最后一页']) ?><?php } ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该编号吗？')) {
            $.post("<?= $this->url->get('admin/companys/contract_delete/') ?>", {id:id});
            location.reload();
        }
    }
</script>