<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0);">角色列表</a></li>
                <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_menu('admin/acl/resource/', '权限资源列表'); ?>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/positions/index', '职位管理', 'class' => 'btn btn-default']); ?>
                            <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/acl/new_role', '添加新角色', 'class' => 'btn btn-default']); ?>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>角色名称</th>
                            <th>角色描述</th>

                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($roles as $role) { ?>
                            <tr>
                                <td><?= $role->getName() ?></td>
                                <td><?= $role->getDescription() ?></td>
                                <td>
                                    <span class="pull-right">
                                        <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/acl/edit_role/' . $role->getName(), '查看', 'class' => 'btn btn-primary btn-xs']); ?>
                                        <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/acl/delete_role', '删除', 'class' => 'btn btn-danger btn-xs', 'href' => 'javascript:del("' . $role->getName() . '")']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(name) {
        if(confirm('确认要删除该角色吗？')) {
            $.post("<?= $this->url->get('admin/acl/delete_role') ?>", {name:name});
            location.reload();
        }
    }
</script>
