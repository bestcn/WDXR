<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>报销财务报表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-4 form-inline">
                        {{ form("admin/finance/export", "method":"post", "autocomplete" : "on") }}
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
                        <button type="submit" class="btn btn btn-primary"> 导出</button>
                        {{ end_form() }}
                    </div>


                        <form action="{{ url('admin/finance/index') }}" method="post" autocomplete="on">
                            <div class="col-sm-1">
                            <div class="input-group">
                                <select class="input form-control" name="status">
                                    <option value="">不限</option>
                                    <option value="1">正常</option>
                                    <option value="2">票据异常</option>
                                    <option value="3">征信异常</option>
                                    <option value="4">企业信息异常</option>
                                    <option value="5">企业服务期限异常</option>
                                </select>
                            </div>
                            </div>
                            <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" name="company_id" class="input form-control" placeholder="搜索账户名称">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn btn-primary"> 搜索</button>
                                </span>
                            </div>
                            </div>
                        </form>


                    <div class="col-sm-2">
                        <form action="{{ url('admin/finance/today_export') }}" method="post" autocomplete="on">
                            <div class="input-group">
                                <div class="col-sm-10">
                                    <select name="account" class="form-control">
                                        <option value="0">全部</option>
                                        {% for account in account_data %}
                                            <option value="{{ account.id }}">{{ account.bank }}</option>
                                        {% endfor %}
                                    </select>
                                </div>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-primary pull-right">导出今天的报表</button>
                                </span>
                            </div>
                        </form>
                        {#{{ acl_button(["admin/finance/today_export", "导出今天的报表", 'class':'btn btn-sm btn-primary pull-right']) }}#}
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>收款账户</th>
                        <th>开户行</th>
                        <th>账户名称</th>
                        <th>金额</th>
                        <th>起始时间</th>
                        <th>已报次数</th>
                        <th>摘要</th>
                        <th>生成时间</th>
                        <th>状态</th>
                        <th>备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for finance in page.items %}
                            <tr>
                                <td>{{ finance.id }}</td>
                                <td>{{ finance.makecoll }}</td>
                                <td>{{ finance.bank_name }}</td>
                                <td>{{ finance.company_id }}</td>
                                <td>{{ finance.money }}</td>
                                <td>{{ finance.start_time }}</td>
                                <td>{{ finance.day_count }}</td>
                                <td>{{ finance.remark }}</td>
                                <td>{{ finance.time }}</td>
                                <td><font color="red">{% if finance.status == 1 %}<font color="green">正常</font>{% elseif finance.status == 2 %}票据异常{% elseif finance.status == 3 %}征信异常{% elseif finance.status == 4 %}企业信息异常{% elseif finance.status == 5 %}企业服务期限异常{% else %}数据异常{% endif %}</font></td>
                                <td>{{ finance.info }}</td>
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
                                {{ link_to("admin/finance/index?page=1"~'&company_id='~company_id, "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {% if company_id %}
                                    {{ link_to("admin/finance/index?page="~page.before~'&company_id='~company_id, "前一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/index?page="~page.before, "前一页") }}
                                {% endif %}
                            </li>
                            <li class="paginate_button next">
                                {% if company_id %}
                                    {{ link_to("admin/finance/index?page="~page.next~'&company_id='~company_id, "下一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/index?page="~page.next, "下一页") }}
                                {% endif %}
                            </li>
                            <li>
                                {% if company_id %}
                                    {{ link_to("admin/finance/index?page="~page.last~'&company_id='~company_id, "最后一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/index?page="~page.last, "最后一页") }}
                                {% endif %}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

