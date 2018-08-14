<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>征信待审核列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-6">
                        {#{{ form("admin/companys/billVerify", "method":"post", "autocomplete" : "on") }}#}
                        {#<div class="input-group">#}
                        {#<input type="text" name="name" class="input-sm form-control" placeholder="搜索企业名称">#}
                        {#<span class="input-group-btn">#}
                        {#<button type="submit" class="btn btn-sm btn-primary"> 搜索</button>#}
                        {#</span>#}
                        {#</div>#}
                        {#{{ end_form() }}#}
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        <th>企业性质</th>
                        <th>征信类别</th>
                        <th>申请时间</th>
                        <th>审核状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for company in page.items %}
                            <tr>
                                <td>{{ company.id }}</td>
                                <td>{{ company.name }}</td>
                                <td>{% if company.type == 1 %}<font color="green">非个体工商户</font>{% elseif company.type == 2 %}<font color="blue">个体工商户</font>{% else %}未选择{% endif %}</td>
                                <td>{% if company.report_type == 1 %}<font color="green">个人征信</font>{% elseif company.report_type == 2 %}<font color="blue">企业征信</font>{% else %}未选择{% endif %}</td>
                                <td>{{ date('Y-m-d H:i:s',company.apply_time) }}</td>
                                <td>待审核</td>
                                <td>{{ acl_button(["admin/companys/report_verify/"~company.id, '审核', 'class':'btn btn-primary btn-xs']) }}</td>
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
                                {{ link_to("admin/companys/reportVerify?page=1", "第一页") }}
                            </li>
                            <li class="paginate_button previous">

                                {{ link_to("admin/companys/reportVerify?page="~page.before, "前一页") }}

                            </li>
                            <li class="paginate_button next">

                                {{ link_to("admin/companys/reportVerify?page="~page.next, "下一页") }}

                            </li>
                            <li>

                                {{ link_to("admin/companys/reportVerify?page="~page.last, "最后一页") }}

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


