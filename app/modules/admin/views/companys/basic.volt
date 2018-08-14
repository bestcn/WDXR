<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active">{{ acl_button(['admin/companys/basic/', '合同编号']) }}</li>
                <li class="">{{ acl_button(['admin/companys/level/', '客户等级']) }}</li>
                <li class="">{{ acl_button(['admin/companys/bankList/', '银行列表']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                {{ form("admin/companys/basic", "method":"get", "autocomplete" : "on") }}
                                <div class="input-group">
                                    <input type="text" name="contract_num" value="{{ contract_num }}" class="input-sm form-control" placeholder="请输入搜索编号">
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
                                <th>合同号</th>
                                <th>所属企业</th>
                                <th>生成时间</th>
                                <th>状态</th>
                                <th>
                                    <span class="pull-right">
                                        操作
                                    </span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            {% if page.items is defined %}
                            {% for contract in page.items %}
                            <tr>
                                <td>{{ contract.id }}</td>
                                <td>
                                    {{ contract.contract_num ? contract.contract_num : '暂无' }}
                                </td>
                                <td>
                                    {% if contract.company_id %}
                                        <a target="_blank" href="{{ url('admin/companys/contract/'~contract.company_id) }}">
                                            {{ contract.name ? contract.name : '暂无' }}
                                        </a>
                                    {% else %}
                                        {{ contract.name ? contract.name : '暂无' }}
                                    {% endif %}
                                </td>
                                <td>
                                    {{ contract.time }}
                                </td>
                                <td>
                                    {% if contract.contract_status is 1 %}
                                        <span class="badge badge-success">
                                            已使用
                                        </span>
                                    {% elseif contract.contract_status is 0 %}
                                        <span class="badge badge-plain">
                                            未使用
                                        </span>
                                        <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="合同号未分配或所属企业退费"></i>
                                    {% else %}
                                        <span class="badge badge-warning">
                                            暂占
                                        </span>
                                        <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="合同号尚未正式生效"></i>
                                    {% endif %}
                                </td>
                                <td>
                                <span class="pull-right">
                                    {{ acl_button(['admin/companys/contract_delete', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~contract.id~'")']) }}
                                </span>
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
                                    <li>{{ link_to("admin/companys/basic?page=1"~'&contract_num='~contract_num, "第一页") }}</li>
                                    <li class="paginate_button previous">{% if contract_num %}{{ link_to("admin/companys/basic?page="~page.before~'&contract_num='~contract_num, "前一页") }}{% else %}{{ link_to("admin/companys/basic?page="~page.before, "前一页") }}{% endif %}</li>
                                    <li class="paginate_button next">{% if contract_num %}{{ link_to("admin/companys/basic?page="~page.next~'&contract_num='~contract_num, "下一页") }}{% else %}{{ link_to("admin/companys/basic?page="~page.next, "下一页") }}{% endif %}</li>
                                    <li>{% if contract_num %}{{ link_to("admin/companys/basic?page="~page.last~'&contract_num='~contract_num, "最后一页") }}{% else %}{{ link_to("admin/companys/basic?page="~page.last, "最后一页") }}{% endif %}</li>
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
        if(confirm('确认要删除该编号吗？')) {
            $.post("{{ url("admin/companys/contract_delete/") }}", {id:id});
            location.reload();
        }
    }
</script>