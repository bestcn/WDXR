<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>业务员业绩列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>业务员</th>
                        <th>企业名称</th>
                        <th>推荐人</th>
                        <th>管理人</th>
                        <th>合同编号</th>
                        <th>签订时间</th>
                        <th>成交金额</th>
                        <th>业务员提成</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for finance in page.items %}
                            <tr>
                                <td>{{ finance.id }}</td>
                                <td><font color="blue">{{ finance.admin_name }}</font></td>
                                <td>{{ finance.company_name }}</td>
                                <td>{{ finance.recommender }}</td>
                                <td>{{ finance.administrator }}</td>
                                <td>{{ finance.contract_num }}</td>
                                <td>{{ date('Y-m-d H:i:s',finance.time) }}</td>
                                <td><font color="green">{{ finance.money }}</font> 元</td>
                                <td><font color="blue">{{ finance.commission }} 元</font></td>
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
                                {{ link_to("admin/finance/admin_achievement_info?page=1"~'&admin_name='~admin_name, "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {% if admin_name %}
                                    {{ link_to("admin/finance/admin_achievement_info?page="~page.before~'&admin_name='~admin_name, "前一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/admin_achievement_info?page="~page.before, "前一页") }}
                                {% endif %}
                            </li>
                            <li class="paginate_button next">
                                {% if admin_name %}
                                    {{ link_to("admin/finance/admin_achievement_info?page="~page.next~'&admin_name='~admin_name, "下一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/admin_achievement_info?page="~page.next, "下一页") }}
                                {% endif %}
                            </li>
                            <li>
                                {% if admin_name %}
                                    {{ link_to("admin/finance/admin_achievement_info?page="~page.last~'&admin_name='~admin_name, "最后一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/admin_achievement_info?page="~page.last, "最后一页") }}
                                {% endif %}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

