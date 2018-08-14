<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                {{ acl_menu('admin/acl/edit_resource/'~name, '查看权限资源') }}
                <li class="active"><a href="javascript:void(0);">权限操作管理</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <form method="post">
                        <table class="table table-hover">
                            <thead>
                            <tr><th>权限资源</th><th>权限操作</th><th></th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input class="form-control" type="text" name="resource" readonly value="{{ name }}" title="resource"></td>
                                    <td><input class="form-control" type="text" name="access" placeholder="使用竖线'|'分割多个操作"  title="access"></td>
                                    <td><input type="submit" class="btn btn-default" value="添加新权限操作"></td>
                                </tr>
                            {% for access in accesses %}
                                <tr><td>{{ name }}</td><td>{{ access }}</td>
                                    <td>
                                        <a href="{{ url('admin/acl/edit_access') }}">修改</a>
                                        {{ acl_button(['admin/acl/delete_access', '删除权限操作', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~name~'","'~access~'")']) }}
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                        </form>
                        {{ acl_button(['admin/acl/resource', '权限资源列表', 'class':'btn btn-default']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function del(resource, access) {
        if(confirm('确认要删除该权限操作吗？')) {
            $.post("{{ url("admin/acl/delete_access") }}", {access:access, resource:resource});
            location.reload();
        }
    }
</script>
