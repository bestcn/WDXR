<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/companys/basic/', '合同编号']) }}</li>
                <li class="active">{{ acl_button(['admin/companys/level/', '客户等级']) }}</li>
                <li class="">{{ acl_button(['admin/companys/bankList/', '银行列表']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-2">
                                {{ acl_button(["admin/companys/add_level", '添加级别', 'class':'btn btn-default']) }}
                            </div>
                            <div class="col-sm-6">
                                {{ form("admin/companys/level", "method":"post", "autocomplete" : "on") }}
                                <div class="input-group">
                                    <input type="text" name="level_name" class="input-sm form-control" placeholder="搜索级别名称">
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
                                <th>级别名称</th>
                                <th>金额</th>
                                <th>每天返现金额</th>
                                <th>详细信息</th>
                                <th>默认级别</th>
                                <th>状态</th>
                                <th><span class="pull-right">操作</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            {% if page.items is defined %}
                            {% for level in page.items %}
                            <tr>
                                <td>{{ level.id }}</td>
                                <td>{{ level.level_name }}</td>
                                <td>{{ level.level_money }} 元</td>
                                <td>{{ level.day_amount }} 元</td>
                                <td>{{ level.info }}</td>
                                <td>{% if level.is_default %} <font color="green">是</font> {% else %}<font color="red">否</font> {% endif %}</td>
                                <td>{% if level.level_status %} <font color="green">开启</font> {% else %}<font color="red">禁用</font> {% endif %}</td>
                                <td>
                            <span class="pull-right">
                                {{ acl_button(["admin/companys/level_edit/"~level.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                {{ acl_button(['admin/companys/level_delete/', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~level.id~'")']) }}
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
                                    <li>{{ link_to("admin/companys/level", "第一页") }}</li>
                                    <li class="paginate_button previous">{{ link_to("admin/companys/level?page="~page.before, "前一页") }}</li>
                                    <li class="paginate_button next">{{ link_to("admin/companys/level?page="~page.next, "下一页") }}</li>
                                    <li>{{ link_to("admin/companys/level?page="~page.last, "最后一页") }}</li>
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
        if(confirm('确认要删除该级别吗？')) {
            $.post("{{ url("admin/companys/level_delete/") }}", {id:id});
            location.reload();
        }
    }
</script>