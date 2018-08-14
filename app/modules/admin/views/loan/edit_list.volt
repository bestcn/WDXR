<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>普惠待补录列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-4">
                        {{ form("admin/loan/edit_list", "method":"post", "autocomplete" : "on") }}
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索申请人名称">
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
                        <th>申请人</th>
                        <th>申请时间</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page['data'] is defined %}
                    {% for admin in page['data'] %}
                    <tr>
                        <td>{{ admin['id']}}</td>
                        <td>{{ admin['name']}}</td>
                        <td>{{ date('Y-m-d',admin['apply_time']) }}</td>
                        <td>
                            <span class="pull-right">
                            {{ acl_button(["admin/loan/edit_info/"~admin['id'], '普惠补录', 'class':'btn btn-primary btn-xs']) }}
                            </span>
                        </td>
                    </tr>
                    {% endfor %}
                    {% endif %}
                    </tbody>
                </table>
                <div class="row" >
                    <div class="col-sm-5">
                        {{ page['current']~"/"~page['total_pages'] }}
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li>{{ link_to("admin/loan/edit_list", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/loan/edit_list?page="~page['before'], "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/loan/edit_list?page="~page['next'], "下一页") }}</li>
                            <li>{{ link_to("admin/loan/edit_list?page="~page['total_pages'], "最后一页") }}</li>
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