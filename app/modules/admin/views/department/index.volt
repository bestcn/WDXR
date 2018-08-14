<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>部门列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-12">
                        {{ acl_button(['admin/department/new', '添加部门', 'class':'btn btn-default']) }}
                        {{ acl_button(['admin/positions/index', '职位管理', 'class':'btn btn-default']) }}
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>部门编号</th>
                        <th width="15%">部门名称</th>
                        <th width="30%">部门描述</th>

                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for position in page.items %}
                            <tr>
                                <td>{{ position.id }}</td>
                                <td>{{ position.name }}</td>
                                <td>{{ position.description }}</td>
                                <td>
                                    <span class="pull-right">
                                        {{ link_to("admin/department/edit/"~position.id, "查看", 'class':'btn btn-primary btn-xs') }}
                                        {{ acl_button(['admin/department/delete', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~position.id~'")']) }}
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
        if(confirm('确认要删除该部门吗？')) {
            $.post("{{ url("admin/department/delete") }}", {id:id});
            location.reload();
        }
    }
</script>
