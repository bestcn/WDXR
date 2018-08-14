<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>普惠待申请列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        {{ acl_button(["admin/company/new", "新建企业", 'class':'btn btn-default']) }}
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>编号</th>
                            <th>企业名称</th>
                            <th><span class="pull-right">操作</span></th>
                        </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                    {% for company in page.items %}
                    <tr>
                        <td>{{ company.id }}</td>
                        <td>{{ company.name }}</td>
                        <td>
                            <span class="pull-right">
                                {{ acl_button(["admin/loan/new/"~company.id, "提交普惠申请", 'class':'btn btn-primary btn-xs']) }}
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
                            <li>
                                {{ link_to("admin/loan/new_list?page=1", "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                    {{ link_to("admin/loan/new_list?page="~page.before, "前一页") }}
                            </li>
                            <li class="paginate_button next">
                                    {{ link_to("admin/loan/new_list?page="~page.next, "下一页") }}
                            </li>
                            <li>
                                    {{ link_to("admin/loan/new_list?page="~page.last, "最后一页") }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function del(id) {
    if(confirm('确认要删除该企业吗？')) {
        $.post("{{ url("admin/companys/delete/") }}", {id:id});
        location.reload();
    }
}
</script>