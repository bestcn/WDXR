<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class=""><a href="{{ url('admin/setting/rterm') }}">征信审核期限设置</a></li>
                    <li class="active"><a href="{{ url('admin/setting/report_term') }}">企业征信期限列表</a></li>
                </ul>
            </div>
            <div class="ibox-content">
                <div class="row">
                    {#<div class="col-sm-2">#}
                    {#<a title="添加规则" href="{{ url('admin/setting/newterm') }}" class="btn btn-default">添加规则</a>#}
                    {#</div>#}

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        <th>缴费类型</th>
                        <th>审核期限</th>
                        <th>提交时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for term in page.items %}
                            <tr>
                                <td>{{ term.id }}</td>
                                <td>{{ term.company_name }}</td>
                                <td>{% if term.payment == 1 %}转账{% elseif term.payment == 2 %}现金{% elseif term.payment == 3 %}POS{% else %}贷款{% endif %}</td>
                                <td>{{ term.term }} {% if term.type == 0 %} 天 {% elseif term.type == 1 %} 个月 {% else %} 年 {% endif %}</td>
                                <td>{{ date('Y-m-d H:i:s',term.time) }}</td>
                                <td>{{ link_to("admin/setting/edit_report_term/"~term.id, "查看", 'class':'btn btn-primary btn-xs') }}</td>
                                {#<td><button class="btn btn-danger btn-xs" type="button" onclick="del({{ term.id }})">删除</button></td>#}
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
                            <li>{{ link_to("admin/setting/report_term", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/setting/report_term?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/setting/report_term?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/setting/report_term?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
