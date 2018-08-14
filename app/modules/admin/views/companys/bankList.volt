<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/companys/basic/', '合同编号']) }}</li>
                <li class="">{{ acl_button(['admin/companys/level/', '客户等级']) }}</li>
                <li class="active">{{ acl_button(['admin/companys/bankList/', '银行列表']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <a title="添加银行" href="{{ url('admin/companys/bankListAdd') }}" class="btn btn-default">添加银行</a>
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>银行名称</th>
                                <th>状态</th>
                                <th><span class="pull-right">操作</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            {% if page.items is defined %}
                            {% for bank in page.items %}
                            <tr>
                                <td>{{ bank.id }}</td>
                                <td>{{ bank.bank_name }}</td>
                                <td>{% if bank.bank_status %} <font color="green">开启</font> {% else %}<font color="red">禁用</font> {% endif %}</td>
                                <td class="pull-right">
                                    {{ acl_button(["admin/companys/bankListEdit/"~bank.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                    {{ acl_button(['admin/companys/bankListDel/', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~bank.id~'")']) }}
                                </td>
                            </tr>
                            </tbody>
                            {% endfor %}
                            {% endif %}
                        </table>
                        <div class="row" >
                            <div class="col-sm-5">
                                {{ page.current~"/"~page.total_pages }}
                            </div>
                            <div class="col-sm-7">
                                <ul class="pagination pull-right no-margins">
                                    <li>{{ link_to("admin/companys/bankList", "第一页") }}</li>
                                    <li class="paginate_button previous">{{ link_to("admin/companys/bankList?page="~page.before, "前一页") }}</li>
                                    <li class="paginate_button next">{{ link_to("admin/companys/bankList?page="~page.next, "下一页") }}</li>
                                    <li>{{ link_to("admin/companys/bankList?page="~page.last, "最后一页") }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该银行吗？')) {
            $.post("{{ url("admin/companys/bankListDel/") }}", {id:id});
            location.reload();
        }
    }
</script>