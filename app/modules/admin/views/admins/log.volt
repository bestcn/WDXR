<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                {{ acl_menu('admin/admins/edit/'~id, '基本信息') }}
                {{ acl_menu('admin/admins/password/'~id, '修改密码') }}
                <li class="active"><a href="javascript:void(0);">登录日志</a></li>
                {{ acl_menu('admin/admins/login_failed/'~id, '登录失败日志') }}
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr><th>登录时间</th><th>IP</th><th>浏览器代理</th></tr>
                        </thead>
                        {% if page.items is defined %}
                            {% for item in page.items %}
                                <tr><td>{{ item.loginTime }}</td><td>{{ item.ipAddress }}</td><td>{{ item.userAgent }}</td></tr>
                            {% endfor %}
                        {% endif %}
                    </table>
                    <div class="row">
                        <div class="col-sm-5">
                            {{ page.current~"/"~page.total_pages }}
                        </div>
                        <div class="col-sm-7">
                            <ul class="pagination no-margins pull-right">
                                <li>{{ link_to("admin/admins/log/"~id, "第一页") }}</li>
                                <li class="paginate_button previous">{{ link_to("admin/admins/log/"~id~"?page="~page.before, "前一页") }}</li>
                                <li class="paginate_button next">{{ link_to("admin/admins/log/"~id~"?page="~page.next, "下一页") }}</li>
                                <li>{{ link_to("admin/admins/log/"~id~"?page="~page.last, "最后一页") }}</li>
                            </ul>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
