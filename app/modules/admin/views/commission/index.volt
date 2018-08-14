<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>分成管理</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        {{ acl_button(["admin/commission/new", '添加分成', 'class':'btn btn-default']) }}
                    </div>
                    <!--<div class="col-sm-4 m-b-xs">-->
                        <!--<select class="input-sm form-control input-s-sm inline">-->
                            <!--<option value="0">Option 1</option>-->
                            <!--<option value="1">Option 2</option>-->
                            <!--<option value="2">Option 3</option>-->
                            <!--<option value="3">Option 4</option>-->
                        <!--</select>-->
                    <!--</div>-->

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>业绩总额</th>
                        <th>提成总额</th>
                        <th>分站地址</th>
                        <th>管理员</th>
                        <th>分站等级</th>
                        <th>状态</th>
                        <th>操作</th>

                        <th></th>
                        <th></th>
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
                        <td>{{ admin.branch_level }}级</td>
                        <td>{% if admin.branch_status %}<font color="green">启用</font>{% else %}<font color="red">禁用</font>{% endif %}</td>

                        <td>{{ acl_button(["admin/branchs/edit/"~admin.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                            {{ acl_button(["admin/branchs/edit/"~admin.id, '分成设置', 'class':'btn btn-primary btn-xs']) }}
                        </td>
                        <td> {{ acl_button(["admin/branchs/edit/"~admin.id, '试用期', 'class':'btn btn-primary btn-xs']) }}
                            {{ acl_button(['admin/branchs/delete/', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~admin.id~'")']) }}
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