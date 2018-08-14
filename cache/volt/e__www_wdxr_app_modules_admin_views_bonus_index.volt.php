<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/bonus/new', '添加制度', 'class' => 'btn btn-default']); ?>
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
                        
                    </div>

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>推荐人类型</th>
                        <th>新客户类型</th>
                        <th>第1个</th>
                        <th>第2个</th>
                        <th>第3个</th>
                        <th>第4个</th>
                        <th>第5个</th>
                        <th>第6个</th>
                        <th>第7个</th>
                        <th>第8个</th>
                        <th>第9个</th>
                        <th>第10个</th>
                        <th>第11个</th>
                        <th>第12个</th>
                        <th>操作</th>

                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                        <?php foreach ($page->items as $admin) { ?>
                            <tr>
                                <td><?= $admin->id ?></td>
                                <td><?php if ($admin->recommend == 1) { ?>事业合伙人<?php } else { ?>普惠<?php } ?></td>
                                <td><?php if ($admin->customer == 1) { ?>事业合伙人<?php } else { ?>普惠<?php } ?></td>
                                <td><?= $admin->first ?></td>
                                <td><?= $admin->second ?></td>
                                <td><?= $admin->third ?></td>
                                <td><?= $admin->fourth ?></td>
                                <td><?= $admin->fifth ?></td>
                                <td><?= $admin->sixth ?></td>
                                <td><?= $admin->seventh ?></td>
                                <td><?= $admin->eighth ?></td>
                                <td><?= $admin->ninth ?></td>
                                <td><?= $admin->tenth ?></td>
                                <td><?= $admin->eleventh ?></td>
                                <td><?= $admin->twelfth ?></td>
                                <td>
                                    <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/bonus/edit/' . $admin->id, '查看', 'class' => 'btn btn-primary btn-xs']); ?>
                                    <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/bonus/delete/', '删除', 'href' => 'javascript:del(' . $admin->id . ')', 'class' => 'btn btn-danger btn-xs']); ?>
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
                            <li><?= $this->tag->linkTo(['admin/branchs/index', '第一页']) ?></li>
                            <li class="paginate_button previous"><?= $this->tag->linkTo(['admin/branchs/index?page=' . $page->before, '前一页']) ?></li>
                            <li class="paginate_button next"><?= $this->tag->linkTo(['admin/branchs/index?page=' . $page->next, '下一页']) ?></li>
                            <li><?= $this->tag->linkTo(['admin/branchs/index?page=' . $page->last, '最后一页']) ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该分站吗？')) {
            $.post("<?= $this->url->get('admin/bonus/delete/') ?>", {id:id});
            location.reload();
        }
    }
</script>