<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/companys/edit/'~id, '基本信息']) }}</li>
                <li class="active">{{ acl_button(['admin/companys/edit_password/'~id~'/'~company_type, '修改企业密码']) }}</li>
                <li class="">{{ acl_button(['admin/companys/company_info/'~id~'/'~company_type, '详细信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/bill_info/'~id~'/'~company_type, '票据信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/report_info/'~id~'/'~company_type, '征信报告']) }}</li>
                <li class="">{{ acl_button(['admin/companys/contract/'~id, '查看合同']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    {{ form(url('admin/companys/edit_password/'~id~'/'~company_type), "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                    <div class="panel-body">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('id') }}
                        <div class="form-group">
                            <label for="fieldPassword" class="col-sm-2 control-label">账号</label>
                            <div class="col-sm-4">
                                <input type="text" id="user_name" name="user_name" disabled="disabled" class="form-control" value="{{ user_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fieldPassword" class="col-sm-2 control-label">密码</label>
                            <div class="col-sm-4">
                                {{ form.render('password') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fieldPassword" class="col-sm-2 control-label">确认密码</label>
                            <div class="col-sm-4">
                                {{ form.render('confirm_password') }}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {{ form.render('submit') }}
                            </div>
                        </div>
                    </div>
                    {{ end_form() }}
                </div>
            </div>
        </div>

        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>密码修改日志</h5></div>
            <div class="ibox-content">
                <table class="table table-hover">
                    <thead>
                    <tr><th>修改时间</th><th>IP</th><th>浏览器代理</th></tr>
                    </thead>
                    {% if page.items is defined %}
                        {% for item in page.items %}
                            <tr><td>{{ date("Y-m-d H:i:s", item.createdAt) }}</td><td>{{ item.ipAddress }}</td><td>{{ item.userAgent }}</td></tr>
                        {% endfor %}
                    {% endif %}
                </table>

                <div class="row">
                    <div class="col-sm-5">
                        {{ page.current~"/"~page.total_pages }}
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination no-margins pull-right">
                            <li>{{ link_to("admin/companys/edit_password/"~id, "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/companys/edit_password/"~id~"?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/companys/edit_password/"~id~"?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/companys/edit_password/"~id~"?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
