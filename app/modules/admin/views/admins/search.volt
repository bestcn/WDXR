<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><a href="{{ url('admin/admins/search') }}">管理员列表</a></h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-4">
                        {{ form("admin/admins/search", "method":"post", "autocomplete" : "on") }}
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索用户名">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                        {{ end_form() }}
                    </div>
                    <div class="col-sm-8">
                        {{ acl_button(['admin/admins/new', '添加管理员', 'class':'btn btn-default']) }}
                        {{ acl_button(['admin/admins/index', '高级搜索', 'class':'btn btn-default pull-right']) }}
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>用户名</th>
                        <th>邮箱</th>
                        <th>手机号</th>
                        <th>职位</th>
                        {#<th>状态</th>#}

                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for admin in page.items %}
                            <tr>
                                <td>{{ admin.id }}</td>
                                <td>{{ admin.name }}</td>
                                <td>{{ admin.email }}</td>
                                <td>{{ admin.phone }}</td>
                                <td>{{ admin.positions.name }}</td>
                                {#<td>#}
                                    {#{% if admin.active == 'N' %}#}
                                        {#<i class="fa fa-times text-danger"> 未激活</i>#}
                                    {#{% elseif   admin.banned == 'Y' %}#}
                                        {#<i class="fa fa-times text-danger"> 禁用</i>#}
                                    {#{% elseif admin.suspended == 'Y' %}#}
                                        {#<i class="fa fa-warning text-warning"> 暂停</i>#}
                                    {#{% else %}#}
                                        {#<i class="fa fa-check text-navy"> 正常</i>#}
                                    {#{% endif %}#}
                                {#</td>#}

                                <td>
                                    <span class="pull-right">
                                        {{ acl_button(['admin/admins/edit/'~admin.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                        {{ acl_button(['admin/admins/delete', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del('~admin.id~')']) }}
                                    </span>
                                </td>
                            </tr>
                        {% endfor %}
                    {% endif %}
                    </tbody>
                </table>
                <div class="row" >
                    <div class="col-sm-5">
                        {{ page.current~"/"~page.total_pages }}
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li>{{ link_to("admin/admins/search", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/admins/search?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/admins/search?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/admins/search?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该管理员吗？')) {
            $.post("{{ url("admin/admins/delete/") }}", {id:id});
            location.reload();
        }
    }
</script>