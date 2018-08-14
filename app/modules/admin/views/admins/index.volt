<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>搜索管理员</h5></div>
            <div class="ibox-content">
                {{ form("admin/admins/search", "method":"post", "autocomplete" : "on", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldId" class="col-sm-2 control-label">ID</label>
                    <div class="col-sm-4">
                        {{ form.render('id') }}
                    </div>

                    <label for="fieldName" class="col-sm-2 control-label">用户名</label>
                    <div class="col-sm-4">
                        {{ form.render('name') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldEmail" class="col-sm-2 control-label">邮箱</label>
                    <div class="col-sm-4">
                        {{ form.render('email') }}
                    </div>

                    <label for="fieldPhone" class="col-sm-2 control-label">手机号</label>
                    <div class="col-sm-4">
                        {{ form.render('phone') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldMustchangepassword" class="col-sm-2 control-label">首次登录修改密码</label>
                    <div class="col-sm-4">
                        {{ form.render('mustChangePassword') }}
                    </div>

                    <label for="fieldProfilesid" class="col-sm-2 control-label">职位ID</label>
                    <div class="col-sm-4">
                        {{ form.render('position_id') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldBanned" class="col-sm-2 control-label">禁用</label>
                    <div class="col-sm-2">
                        {{ form.render('banned') }}
                    </div>
                    <label for="fieldSuspended" class="col-sm-2 control-label">暂停</label>
                    <div class="col-sm-2">
                        {{ form.render('suspended') }}
                    </div>
                    <label for="fieldActive" class="col-sm-2 control-label">激活</label>
                    <div class="col-sm-2">
                        {{ form.render('active') }}
                    </div>
                </div>
                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ form.render('submit',  ['class': 'btn btn-primary', 'value' : '搜索']) }}
                        <a href="{{ url('admin/admins/search') }}" class="btn btn-default">管理员列表</a>
                    </div>
                </div>
                {{ end_form() }}
            </div>
        </div>
    </div>
</div>
