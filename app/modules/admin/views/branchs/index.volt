<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class="active">{{ acl_button(['admin/branchs/index', '分站管理']) }}</li>
                    <li class="">{{ acl_button(['admin/branchs/level', '分公司等级设置']) }}</li>
                    <li class="">{{ acl_button(['admin/setting/branchs_commission', '分公司业绩设置']) }}</li>
                </ul>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        {{ acl_button(["admin/branchs/new", '添加分站', 'class':'btn btn-default']) }}
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
                        {{ form("admin/branchs/index", "method":"post", "autocomplete" : "on") }}
                        <div class="input-group">
                            <input type="text" name="branch_name" class="input-sm form-control" placeholder="搜索分站名称">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                        {{ end_form() }}
                    </div>

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>账号</th>
                        <th>分站名称</th>
                        <th>分站地址</th>
                        <th>管理员</th>
                        <th>分站等级</th>
                        <th>状态</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                    {% for admin in page.items %}
                    <tr>
                        <td>{{ admin.id }}</td>
                        <td>{{ admin.branch_admin }}</td>
                        <td>{{ admin.branch_name }}</td>
                        <td>{{ admin.branch_area }}</td>
                        <td>{{ admin.branch_admin }}</td>
                        <td>{{ admin.level_name }}</td>
                        <td>{% if admin.branch_status %}<font color="green">启用</font>{% else %}<font color="red">禁用</font>{% endif %}</td>
                        <td>
                            <span class="pull-right">
                                {{ acl_button(["admin/branchs/edit/"~admin.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                {{ acl_button(["admin/setting/commission/"~admin.id, '人员提成设置', 'class':'btn btn-primary btn-xs']) }}
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
                            <li>{{ link_to("admin/branchs/index", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/branchs/index?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/branchs/index?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/branchs/index?page="~page.last, "最后一页") }}</li>
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
        $.post("{{ url("admin/branchs/delete/") }}", {id:id});
        location.reload();
    }
}
</script>