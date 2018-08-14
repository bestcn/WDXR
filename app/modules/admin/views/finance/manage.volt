<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>导出管理费报表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-4 form-inline">
                        {{ form("admin/finance/export/manage", "method":"post", "autocomplete" : "on") }}
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

                        <form action="/admin/finance/manage" method="post" autocomplete="on">
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
                            <div class="col-sm-4">
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
                                <input type="hidden" name="type" value="manage">
                                <div class="col-sm-10">
                                    <select name="account" class="form-control">
                                        <option value="0">全部</option>
                                        {% for account in account_data %}
                                            <option value="{{ account.id }}">{{ account.bank }}</option>
                                        {% endfor %}
                                    </select>
                                </div>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn btn-primary pull-right">导出今天的报表</button>
                                </span>
                            </div>
                        </form>
                        {#{{ acl_button(["admin/finance/today_export/manage", "导出今天的报表", 'class':'btn btn-sm btn-primary pull-right']) }}#}
                    </div>
                    <div class="col-sm-1">
                        {{ acl_button(["admin/finance/all_manage", "导出全部管理关系", 'class':'btn btn btn-default pull-right']) }}
                    </div>
                    {#<div class="col-sm-4">#}
                    {#{{ form("admin/finance/index", "method":"post", "autocomplete" : "on") }}#}
                    {#<div class="input-group">#}
                    {#<input type="text" name="company_id" class="input-sm form-control" placeholder="搜索账户名称">#}
                    {#<span class="input-group-btn">#}
                    {#<button type="submit" class="btn btn-sm btn-primary"> 搜索</button>#}
                    {#</span>#}
                    {#</div>#}
                    {#{{ end_form() }}#}
                    {#</div>#}

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
                        <th>关系</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for manage in page.items %}
                            <tr>
                                <td>{{ manage.id }}</td>
                                <td>{{ manage.makecoll }}</td>
                                <td>{{ manage.bank_name }}</td>
                                <td>{{ manage.company_id }}</td>
                                <td>{{ manage.money }}</td>
                                <td>{{ manage.start_time }}</td>
                                <td>{{ manage.day_count }}</td>
                                <td>{{ manage.remark }}</td>
                                <td>{{ manage.time }}</td>
                                <td><font color="red">{% if manage.status == 1 %}<font color="green">正常</font>{% elseif manage.status == 2 %}票据异常{% elseif manage.status == 3 %}征信异常{% elseif manage.status == 4 %}企业信息异常{% elseif manage.status == 5 %}企业服务期限异常{% else %}数据异常{% endif %}</font></td>
                                <td>{{ manage.info }}</td>
                                <td>{{ acl_button(["admin/finance/manage_info/", '点击查看','href':'javascript:manage_info('~ manage.byid ~')', 'class':'btn btn-primary btn-xs']) }}</td>
                                <button id="click" style="display: none" class="btn btn-primary btn-xs" data-toggle='modal' data-target='#myModal5'>点击弹出</button>
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
                                {{ link_to("admin/finance/manage?page=1"~'&company_id='~company_id, "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {% if company_id %}
                                    {{ link_to("admin/finance/manage?page="~page.before~'&company_id='~company_id, "前一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/manage?page="~page.before, "前一页") }}
                                {% endif %}
                            </li>
                            <li class="paginate_button next">
                                {% if company_id %}
                                    {{ link_to("admin/finance/manage?page="~page.next~'&company_id='~company_id, "下一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/manage?page="~page.next, "下一页") }}
                                {% endif %}
                            </li>
                            <li>
                                {% if company_id %}
                                    {{ link_to("admin/finance/manage?page="~page.last~'&company_id='~company_id, "最后一页") }}
                                {% else %}
                                    {{ link_to("admin/finance/manage?page="~page.last, "最后一页") }}
                                {% endif %}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">管理关系</h5>
                <small class="font-bold" id="font-bold"></small>
            </div>
            <div class="modal-body" id="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function manage_info(id) {
        $.ajax({
            type : "POST",
            url : "{{ url('admin/finance/manage_info/') }}",
            data : {id:id},
            success : function(data) {
                $("#font-bold").html(data.recommend);
                $("#modal-body").html(data.data);
                $("#click").click();
            }
        });
    }
</script>

