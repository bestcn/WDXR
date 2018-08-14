<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>企业票据列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    {{ form("admin/companys/bill_list", "method":"get", "autocomplete" : "on") }}
                    <div class="col-sm-6 form-inline">
                        <div class="col-sm-12 input-group">
                            <input type="text" name="name" value="{{ request.get('name', 'trim') }}" class="input-sm form-control" placeholder="搜索企业名称、法人代表、联系方式">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                    </div>
                    {{ end_form() }}
                    <div class="col-sm-6">
                        <form action="{{ url('admin/companys/owe_bill_list') }}" method="post" autocomplete="on">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-primary pull-right">导出欠费报表</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        <th>企业状态</th>
                        <th>法人代表</th>
                        <th>票据金额</th>
                        <th>时间</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for company in page.items %}
                            <tr>
                                <td>{{ company.id }}</td>
                                <td>{{ company.name }}</td>
                                <td>{% if company.status %}<i class="fa fa-check text-success"> 正常</i>{% else %}<i class="fa fa-times text-danger"> 未启用</i>{% endif %}</td>
                                <td>{{ company.legal_name }}</td>
                                <td>
                                    {% if company.status %}
                                        {% if  company.amount > 0 %}
                                            <i class="fa fa-check text-success"> {{ company.amount }}</i>
                                        {% else %}
                                            <i class="fa fa-times text-danger"> {{ company.amount ? company.amount : '0.00'}}</i>
                                        {% endif %}
                                    {% else %}
                                        <i class="fa fa-clock-o text-navy"> {{ company.amount ? company.amount : '0.00'}}</i>
                                    {% endif %}
                                </td>
                                <td>
                                    {{ company.time ? company.time : company.company_time }}
                                </td>
                                <td>
                                    <span class="pull-right">
                                        {{ acl_button(["admin/companys/bill/"~company.id, '查看详情', 'class':'btn btn-primary btn-xs']) }}
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
                                {{ link_to("admin/companys/bill_list?page=1"~'&name='~name, "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {% if name %}
                                    {{ link_to("admin/companys/bill_list?page="~page.before~'&name='~name, "前一页") }}
                                {% else %}
                                    {{ link_to("admin/companys/bill_list?page="~page.before, "前一页") }}
                                {% endif %}
                            </li>
                            <li class="paginate_button next">
                                {% if name %}
                                    {{ link_to("admin/companys/bill_list?page="~page.next~'&name='~name, "下一页") }}
                                {% else %}
                                    {{ link_to("admin/companys/bill_list?page="~page.next, "下一页") }}
                                {% endif %}
                            </li>
                            <li>
                                {% if name %}
                                    {{ link_to("admin/companys/bill_list?page="~page.last~'&name='~name, "最后一页") }}
                                {% else %}
                                    {{ link_to("admin/companys/bill_list?page="~page.last, "最后一页") }}
                                {% endif %}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>