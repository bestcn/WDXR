<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>修改密码</h5></div>
            <div class="ibox-content">
                {{ form(url('admin/profiles/password'), "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="panel-body">
                    {{ form.render('csrf', ['value': security.getToken()]) }}
                    <input type="hidden" name="id" value="{{ id }}">
                    <div class="form-group">
                        <label for="fieldPassword" class="col-sm-2 control-label">旧密码</label>
                        <div class="col-sm-4">
                            {{ form.render('old_password') }}
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
</div>
