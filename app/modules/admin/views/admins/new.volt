<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>新建管理员</h5></div>
            <div class="ibox-content">
                {{ form("admin/admins/new", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldName" class="col-sm-2 control-label">用户名</label>
                    <div class="col-sm-4">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('name') }}
                    </div>

                    <label for="fieldProfilesid" class="col-sm-2 control-label">职位</label>
                    <div class="col-sm-4">
                        {{ form.render('position_id') }}
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
                    <label for="fieldPassword" class="col-sm-2 control-label">密码</label>
                    <div class="col-sm-4">
                        {{ form.render('password') }}
                    </div>

                    <label for="fieldPassword" class="col-sm-2 control-label">确认密码</label>
                    <div class="col-sm-4">
                        {{ form.render('confirm_password') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldPassword" class="col-sm-2 control-label">是否试用期</label>
                    <div class="col-sm-4">
                        {{ form.render('is_probation') }}
                    </div>
                    <label for="fieldActive" class="col-sm-2 control-label">所属分公司</label>
                    <div class="col-sm-4">
                        {{ form.render('branch_id') }}
                    </div>
                </div>
                <div class="form-group">

                    <label for="fieldActive" class="col-sm-2 control-label">基本状态</label>
                    <div class="col-sm-4">
                        {{ form.render('status') }}
                    </div>

                    <label for="fieldPassword" class="col-sm-2 control-label">是否在职</label>
                    <div class="col-sm-4">
                    {{ form.render('on_job') }}
                    </div>

                </div>
                <div class="form-group">
                    <label for="fieldActive" class="col-sm-2 control-label">是否锁定</label>
                    <div class="col-sm-4">
                        {{ form.render('is_lock') }}
                    </div>
                    {#<label for="fieldActive" class="col-sm-2 control-label">是否激活</label>#}
                    {#<div class="col-sm-4">#}
                    {#{{ form.render('active') }}#}
                    {#</div>#}
                    <label for="fieldPassword" class="col-sm-2 control-label">转正时间</label>
                    <div class="col-sm-4">
                        <!--指定 date标记-->
                        <div class='input-group date' id='datetimepicker1'>
                            {{ form.render('entry_time') }}
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>

                    </div>
                </div>

                {#<div class="form-group">#}
                    {#<label for="fieldMustchangepassword" class="col-sm-2 control-label">首次登录修改密码</label>#}
                    {#<div class="col-sm-2">#}
                        {#{{ form.render('mustChangePassword') }}#}
                    {#</div>#}

                    {#<label for="fieldBanned" class="col-sm-2 control-label">禁用</label>#}
                    {#<div class="col-sm-2">#}
                        {#{{ form.render('banned') }}#}
                    {#</div>#}

                    {#<label for="fieldSuspended" class="col-sm-2 control-label">暂停</label>#}
                    {#<div class="col-sm-2">#}
                        {#{{ form.render('suspended') }}#}
                    {#</div>#}
                {#</div>#}
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ form.render('submit') }}
                        <a href="{{ url('admin/admins/search') }}" class="btn btn-default">管理员列表</a>
                    </div>
                </div>
                {{ end_form() }}
            </div>
        </div>
    </div>
</div>

