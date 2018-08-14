<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>添加企业账户</h5></div>
            <div class="ibox-content">
                {{ form("admin/setting/account_new", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">银行名称</label>
                    <div class="col-sm-4">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('bank') }}
                    </div>

                    <label for="fieldBranch_level" class="col-sm-2 control-label">账户</label>
                    <div class="col-sm-4">
                        {{ form.render('bank_card') }}
                    </div>
                </div>

                <div class="form-group">

                    <label for="fieldBranch_account" class="col-sm-2 control-label">账户类型</label>
                    <div class="col-sm-4">
                        {{ form.render('bank_type') }}
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">状态</label>
                    <div class="col-sm-4">
                        {{ form.render('status') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldBranch_status" class="col-sm-2 control-label">备注</label>
                    <div class="col-sm-4">
                        {{ form.render('remark') }}
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
