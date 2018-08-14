<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>添加企业</h5></div>
            <div class="ibox-content">
                {{ form("admin/companys/new", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">企业名称</label>
                    <div class="col-sm-4">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('name') }}
                    </div>

                    <label for="fieldBranch_level" class="col-sm-2 control-label">企业性质</label>
                    <div class="col-sm-4">
                        {{ form.render('type') }}
                    </div>
                </div>


                <div class="form-group">

                    <label for="fieldBranch_admin" class="col-sm-2 control-label">企业账号</label>
                    <div class="col-sm-4">
                        {{ form.render('user_name') }}
                    </div>
                </div>


                <div class="form-group">
                    <label for="fieldBranch_phone" class="col-sm-2 control-label">企业密码</label>
                    <div class="col-sm-4">
                        {{ form.render('user_password') }}
                    </div>
                    <label for="fieldBranch_status" class="col-sm-2 control-label">确认密码</label>
                    <div class="col-sm-4">
                        {{ form.render('confirm_password') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="fieldBranch_status" class="col-sm-2 control-label">企业级别</label>
                    <div class="col-sm-4">
                    {{ form.render('level_id') }}
                    </div>
                    <label for="fieldBranch_status" class="col-sm-2 control-label">激活状态</label>
                    <div class="col-sm-4">
                        {{ form.render('status') }}
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ form.render('submit') }}
                        <button onclick="history.go(-1);" class="btn btn-default" type="button">返回</button>
                    </div>
                </div>
                {{ end_form() }}
            </div>
        </div>
    </div>
</div>

