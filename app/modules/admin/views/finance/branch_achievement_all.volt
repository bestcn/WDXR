<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>业务员业绩列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">


                    <div class="col-sm-8 form-inline">
                        {{ form("admin/finance/branch_achievement_export/"~branch_id, "method":"post", "autocomplete" : "on") }}
                        <div class="form-group ">
                            <!--指定 date标记-->
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="start_time" class="form-control" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        至
                        <div class="form-group">
                            <!--指定 date标记-->
                            <div class='input-group date' id='datetimepicker2'>
                                <input type='text' name="end_time" class="form-control" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary"> 导出</button>
                        {{ end_form() }}
                    </div>

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
                                {{ link_to("admin/finance/branch_achievement_all?page=1"~'&branch_id='~branch_id, "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {% if branch_id %}
                                    {{ link_to("admin/finance/branch_achievement_all?page="~page.before~'&branch_id='~branch_id, "前一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/branch_achievement_all?page="~page.before, "前一页") }}
                                {% endif %}
                            </li>
                            <li class="paginate_button next">
                                {% if branch_id %}
                                    {{ link_to("admin/finance/branch_achievement_all?page="~page.next~'&branch_id='~branch_id, "下一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/branch_achievement_all?page="~page.next, "下一页") }}
                                {% endif %}
                            </li>
                            <li>
                                {% if branch_id %}
                                    {{ link_to("admin/finance/branch_achievement_all?page="~page.last~'&branch_id='~branch_id, "最后一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/branch_achievement_all?page="~page.last, "最后一页") }}
                                {% endif %}
                            </li>
                        </ul>
                        {#<button onclick="location='/admin/finance/branch_achievement';" class="btn btn-default" type="button">返回</button>#}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


