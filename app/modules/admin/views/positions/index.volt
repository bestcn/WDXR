<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>职位列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-6">
                        {{ form("admin/positions/index", "method":"post", "autocomplete" : "on") }}
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索职位">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                        {{ end_form() }}
                    </div>
                    <div class="col-sm-6">
                        {{ acl_button(['admin/positions/new', '添加职位', 'class':'btn btn-default']) }}
                        {{ acl_button(['admin/department/index', '部门管理', 'class':'btn btn-default']) }}
                        {{ acl_button(['admin/acl/index', '权限控制管理', 'class':'btn btn-default']) }}
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>职位编号</th>
                        <th width="15%">职位名称</th>
                        <th width="30%">职位描述</th>
                        <th>所属部门</th>
                        <th>职位状态</th>

                        <th class="pull-right">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for position in page.items %}
                            <tr>
                                <td>{{ position.id }}</td>
                                <td>{{ position.name }}</td>
                                <td>{{ position.description }}</td>
                                <td>{{ position.department_name }}</td>
                                <td>
                                {% if position.status == 1 %}
                                    <i class="fa fa-check text-navy"> 启用</i>
                                {% else %}
                                    <i class="fa fa-exclamation-triangle text-danger"> 禁用</i>
                                {% endif %}
                                </td>
                                <td>
                                    <span class="pull-right">
                                        {{ acl_button(["admin/positions/edit/"~position.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                        {{ acl_button(["admin/positions/delete/"~position.id, '删除', 'href':'javascript:del('~ position.id ~')', 'class':'btn btn-danger btn-xs']) }}
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
                            <li>{{ link_to("admin/positions/index", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/positions/index?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/positions/index?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/positions/index?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该职位吗？')) {
            $.post("{{ url("admin/positions/delete") }}", {id:id});
            location.reload();
        }
    }
</script>
