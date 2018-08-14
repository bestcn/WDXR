<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>账户管理</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        <a title="添加账户" href="{{ url('admin/setting/account_new') }}" class="btn btn-default">添加账户</a>
                    </div>

                    {#<div class="col-sm-4">#}
                        {#{{ form("admin/account/index", "method":"post", "autocomplete" : "on") }}#}
                        {#<div class="input-group">#}
                            {#<input type="text" name="branch_name" class="input-sm form-control" placeholder="搜索分站名称">#}
                            {#<span class="input-group-btn">#}
                                {#<button type="submit" class="btn btn-sm btn-primary"> 搜索</button>#}
                            {#</span>#}
                        {#</div>#}
                        {#{{ end_form() }}#}
                    {#</div>#}

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>银行名称</th>
                        <th>账户</th>
                        <th>账户类型</th>
                        <th>状态</th>
                        <th>备注</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for admin in page.items %}
                            <tr>
                                <td>{{ admin.id }}</td>
                                <td>{{ admin.bank }}</td>
                                <td>{{ admin.bank_card }}</td>
                                <td>{% if admin.bank_type == 1 %}<font color="green">企业账户</font>{% else %}<font color="blue">个人账户</font>{% endif %}</td>
                                <td>{% if admin.status %}<font color="green">启用</font>{% else %}<font color="red">禁用</font>{% endif %}</td>
                                <td>{{ admin.remark }}</td>
                                <td>{{ acl_button(["admin/setting/account_edit/"~admin.id, "查看", 'class':'btn btn-primary btn-xs']) }}
                                    {{ acl_button(['admin/setting/account_delete/', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~admin.id~'")']) }}
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
                            <li>{{ link_to("admin/setting/account", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/setting/account?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/setting/account?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/setting/account?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该账户吗？')) {
            $.post("{{ url("admin/setting/account_delete/") }}", {id:id});
            location.reload();
        }
    }
</script>