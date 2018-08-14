<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                {{ acl_menu('admin/acl/index/', '角色列表') }}
                <li class="active"><a href="javascript:void(0);">权限资源列表</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                {{ acl_button(['admin/positions/index', '职位管理', 'class':'btn btn-default']) }}
                                {{ acl_button(['admin/acl/new_resource', '添加新权限资源', 'class':'btn btn-default']) }}
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>资源名称</th>
                                <th>资源描述</th>

                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for resource in resources %}
                                <tr>
                                    <td>{{ resource.getName() }}</td>
                                    <td>{{ resource.getDescription() }}</td>
                                    <td>
                                    <span class="pull-right">
                                        {{ acl_button(['admin/acl/edit_resource/'~resource.getName(), '查看权限资源', 'class':'btn btn-primary btn-xs']) }}
                                        {{ acl_button(['admin/acl/access/'~resource.getName(), '权限操作', 'class':'btn btn-primary btn-xs']) }}

                                        {{ acl_button(['admin/acl/delete_resource', '删除权限资源', 'class':'btn btn-danger btn-xs', 'href':'javascript:del_resource("'~resource.getName()~'")']) }}
                                    </span>
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del_resource(name) {
        if(confirm('确认要删除该权限资源吗？')) {
            $.post("{{ url("admin/acl/delete_resource") }}", {resource:name});
            location.reload();
        }
    }
</script>
