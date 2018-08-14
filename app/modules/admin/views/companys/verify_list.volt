<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>我的申请记录</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-4">
                        {{ form("admin/companys/verify_list", "method":"post", "autocomplete" : "on") }}
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索企业名称">
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
                        <th>申请编号</th>
                        <th>企业名称</th>
                        <th>申请类别</th>
                        <th>申请状态</th>
                        <th>申请时间</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                    {% for admin in page.items %}
                    <tr>
                        <td>{{ admin.id}}</td>
                        <td>{{ admin.company_name}}</td>
                        <td>{% if admin.type == 1 %}证件{% elseif admin.type == 2 %}票据{% elseif admin.type == 3 %}征信{% elseif admin.type == 4 %}缴费{% elseif admin.type == 5 %}普惠{% else %}错误{% endif %}
                        </td>
                        <td>{% if admin.status == 1 %}未审核{% elseif admin.status == 2 %}未通过{% elseif admin.status == 3 %}已通过{% elseif admin.status == 4 %}已取消{% elseif admin.status == 5 %}已处理{% elseif admin.status == 6 %}普惠申请通过银行驳回{% elseif admin.status == 7 %}普惠申请通过银行通过{% else %}错误{% endif %}
                        </td>
                        <td>{{ date('Y-m-d',admin.apply_time) }}</td>
                        <td>
                            <span class="pull-right">
                            {% if admin.type == 1 %}
                                {{ acl_button(["admin/companys/edit_auditing/"~admin.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                            {% elseif admin.type == 2 %}
                                {{ acl_button(["admin/companys/bill/"~admin.data_id, '查看', 'class':'btn btn-primary btn-xs']) }}
                            {% elseif admin.type == 3 %}
                                {{ acl_button(["admin/companys/report/"~admin.company_id, '查看', 'class':'btn btn-primary btn-xs']) }}
                            {% elseif admin.type == 4 %}
                                {{ acl_button(["admin/finance/edit_payment/"~admin.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                            {% elseif admin.type == 5 %}
                                {{ acl_button(["admin/loan/edit/"~admin.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                            {% else %}错误{% endif %}
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
                            <li>{{ link_to("admin/companys/edit_list", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/companys/edit_list?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/companys/edit_list?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/companys/edit_list?page="~page.total_pages, "最后一页") }}</li>
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