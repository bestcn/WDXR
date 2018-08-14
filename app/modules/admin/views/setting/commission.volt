<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class="active">{{ acl_button(['admin/setting/commission/'~branch_id, '业绩设置']) }}</li>
                    <li class="">{{ acl_button(['admin/setting/commission_list/'~branch_id, '业务员业绩列表']) }}</li>
                    <li class="">{{ acl_button(['admin/setting/partner_commission_list/'~branch_id, '合伙人业绩列表']) }}</li>
                    <li class="">{{ acl_button(['admin/setting/probation_commission/'~branch_id,'试用期提成设置']) }}</li>
                </ul>
            </div>

            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        {{ acl_button(['admin/setting/new_commission/'~branch_id, '添加业绩设置', 'class':'btn btn-default']) }}
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>提成类型</th>
                        <th>业绩总额</th>
                        <th>对应提成比率</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for commission in page.items %}
                            <tr>
                                <td>{{ commission.id }}</td>
                                <td>{% if commission.type == 1 %}业务员{% else %}合伙人{% endif %}</td>
                                <td>￥{{ commission.amount }}</td>
                                <td>{{ commission.ratio }}</td>
                                <td>{{ commission.time }}</td>
                                <td>{{ acl_button(["admin/setting/edit_commission/"~commission.id, '查看', 'class':'btn btn-primary btn-xs']) }}</td>
                                <td>
                                    {{ acl_button(['admin/setting/delete_commission/', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~commission.id~'")']) }}
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
                            <li>{{ link_to("admin/setting/commission/"~branch_id, "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/setting/commission/"~branch_id~"?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/setting/commission/"~branch_id~"?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/setting/commission/"~branch_id~"?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该提成设置吗？')) {
            $.post("{{ url("admin/setting/delete_commission/") }}", {id:id});
            location.reload();
        }
    }
</script>