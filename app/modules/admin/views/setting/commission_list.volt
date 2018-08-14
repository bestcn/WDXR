<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class="">{{ acl_button(['admin/setting/commission/'~branch_id, '业绩设置']) }}</li>
                    <li class="active">{{ acl_button(['admin/setting/commission_list/'~branch_id, '业务员业绩列表']) }}</li>
                    <li class="">{{ acl_button(['admin/setting/partner_commission_list/'~branch_id, '合伙人业绩列表']) }}</li>
                    <li class="">{{ acl_button(['admin/setting/probation_commission/'~branch_id,'试用期提成设置']) }}</li>
                </ul>
            </div>
            <div class="ibox-content">
                <div class="row">
                    {#<div class="col-sm-2">#}
                    {#<a title="添加规则" href="{{ url('admin/setting/newterm') }}" class="btn btn-default">添加规则</a>#}
                    {#</div>#}
                    <div class="col-sm-4">
                        {{ form("admin/setting/commission_list/"~branch_id, "method":"post", "autocomplete" : "on") }}
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索名称">
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
                        <th>名称</th>
                        <th>类别</th>
                        <th>提成比率</th>
                        <th>最后修改时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for commission_list in page.items %}
                            <tr>
                                <td>{{ commission_list.id }}</td>
                                <td>{{ commission_list.name }}</td>
                                <td>{% if commission_list.type == 1 %}业务员{% else %}合伙人{% endif %}</td>
                                <td>{{ commission_list.ratio }}</td>
                                <td>{{ commission_list.time }}</td>
                                <td>
                                    {{ acl_button(["admin/setting/edit_commission_list/"~commission_list.id, '查看', 'class':'btn btn-primary btn-xs']) }}
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
                                {{ link_to("admin/setting/commission_list/"~branch_id~"?page=1"~'&name='~name, "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {% if name %}
                                    {{ link_to("admin/setting/commission_list/"~branch_id~"?page="~page.before~'&name='~name, "前一页") }}
                                {% else %}
                                    {{ link_to("admin/setting/commission_list/"~branch_id~"?page="~page.before, "前一页") }}
                                {% endif %}
                            </li>
                            <li class="paginate_button next">
                                {% if name %}
                                    {{ link_to("admin/setting/commission_list/"~branch_id~"?page="~page.next~'&name='~name, "下一页") }}
                                {% else %}
                                    {{ link_to("admin/setting/commission_list/"~branch_id~"?page="~page.next, "下一页") }}
                                {% endif %}
                            </li>
                            <li>
                                {% if name %}
                                    {{ link_to("admin/setting/commission_list/"~branch_id~"?page="~page.last~'&name='~name, "最后一页") }}
                                {% else %}
                                    {{ link_to("admin/setting/commission_list/"~branch_id~"?page="~page.last, "最后一页") }}
                                {% endif %}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
