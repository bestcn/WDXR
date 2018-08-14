<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/companys/info/'~id~'/', '基本信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/payment/'~id, '缴费信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/business/'~id, '业务信息']) }}</li>
                <li class="active">{{ acl_button(['admin/companys/user/'~id, '账号信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/bill/'~id, '票据信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/report/'~id, '征信报告']) }}</li>
                <li class="">{{ acl_button(['admin/companys/contract/'~id, '合同信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/setting/'~id, '企业设置']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        用户账号
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            {% if user is not empty %}
                                                <tr>
                                                    <th>头像</th>
                                                    <td>
                                                        {% if user['pic'] %}
                                                            <img class="img-rounded img-md" src="{{ user['pic'] }}" alt="头像">
                                                        {% else %}
                                                            无
                                                        {% endif %}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted">用户ID</th>
                                                    <td class="text-muted">
                                                        {{ user['id'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted">
                                                        用户编号
                                                    </th>
                                                    <td class="text-muted">
                                                        {{ user['number'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>用户名</th>
                                                    <td class="data-editable" data-attr="users-name-{{ user['id'] }}">{{ user['name'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>手机号</th>
                                                    <td class="data-editable" data-attr="users-phone-{{ user['id'] }}">{{ user['phone'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>邮箱</th>
                                                    <td class="data-editable" data-attr="users-email-{{ user['id'] }}">{{ user['email'] }}</td>
                                                </tr>
                                                <tr>
                                                    <th>状态</th>
                                                    <td class="data-editable" data-select-url="{{ url('admin/tools/get_status') }}" data-attr="users-status-{{ user['id'] }}">{{ user['status'] }}</td>
                                                </tr>
                                            {% else %}
                                                <tr><td colspan="2">无</td></tr>
                                            {% endif %}
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                <div class="panel-heading">
                                    修改密码
                                </div>
                                <div class="panel-body">
                                    {% if user is not empty %}
                                    {{ form(url('admin/companys/user/'~id), "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                                        {{ form.render('csrf', ['value': security.getToken()]) }}
                                        {{ form.render('id') }}
                                        <div class="form-group">
                                            <label for="fieldPassword" class="col-sm-3 control-label">密码</label>
                                            <div class="col-sm-9">
                                                {{ form.render('password') }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="fieldPassword" class="col-sm-3 control-label">确认密码</label>
                                            <div class="col-sm-9">
                                                {{ form.render('confirm_password') }}
                                            </div>
                                        </div>
                                        <div class="hr-line-dashed"></div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                {{ form.render('submit') }}
                                            </div>
                                        </div>
                                    {{ end_form() }}
                                    {% else %}
                                        无
                                    {% endif %}
                                </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        密码修改日志
                                    </div>
                                    <div class="panel-body">
                                        {% if page.items is defined %}
                                        <table class="table table-hover">
                                            <thead>
                                            <tr><th>修改时间</th><th>IP</th><th>浏览器代理</th></tr>
                                            </thead>
                                                {% for item in page.items %}
                                                    <tr><td>{{ date("Y-m-d H:i:s", item.createdAt) }}</td><td>{{ item.ipAddress }}</td><td>{{ item.userAgent }}</td></tr>
                                                {% endfor %}
                                        </table>

                                        <div class="row">
                                            <div class="col-sm-5">
                                                {{ page.current~"/"~page.total_pages }}
                                            </div>
                                            <div class="col-sm-7">
                                                <ul class="pagination no-margins pull-right">
                                                    <li>{{ link_to("admin/companys/user/"~id, "第一页") }}</li>
                                                    <li class="paginate_button previous">{{ link_to("admin/companys/user/"~id~"?page="~page.before, "前一页") }}</li>
                                                    <li class="paginate_button next">{{ link_to("admin/companys/user/"~id~"?page="~page.next, "下一页") }}</li>
                                                    <li>{{ link_to("admin/companys/user/"~id~"?page="~page.last, "最后一页") }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        {% else %}
                                            <div class="row">无</div>
                                        {% endif %}
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
