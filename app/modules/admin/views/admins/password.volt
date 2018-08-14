<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                {{ acl_menu('admin/admins/edit/'~id, '基本信息') }}
                <li class="active"><a href="javascript:void(0);">修改密码</a></li>
                {{ acl_menu('admin/admins/log/'~id, '登录日志') }}
                {{ acl_menu('admin/admins/login_failed/'~id, '登录失败日志') }}
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    {{ form("method":"post", "class" : "form-horizontal") }}
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="fieldPassword" class="col-sm-2 control-label">密码</label>
                            <div class="col-sm-4">
                                <input type="password" placeholder="请填写密码" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fieldPassword" class="col-sm-2 control-label">确认密码</label>
                            <div class="col-sm-4">
                                <input type="password" placeholder="请填写确认密码" name="confirm_password" class="form-control">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" class="btn btn-primary">
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
                            <li>{{ link_to("admin/admins/password/"~id, "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/admins/password/"~id~"?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/admins/password/"~id~"?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/admins/password/"~id~"?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
