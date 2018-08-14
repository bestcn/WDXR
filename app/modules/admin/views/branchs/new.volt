<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>新建分公司</h5></div>
            <div class="ibox-content">
                {{ form("admin/branchs/new", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">分公司名称</label>
                    <div class="col-sm-4">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('branch_name') }}
                    </div>

                    <label for="fieldBranch_level" class="col-sm-2 control-label">分公司等级</label>
                    <div class="col-sm-2">
                        {{ form.render('branch_level') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldBrancharea" class="col-sm-2 control-label">分公司地区</label>

                    <div class="col-sm-2">{{ form.render('provinces') }}</div>
                    <div class="col-sm-2">{{ form.render('cities') }}</div>
                    <div class="col-sm-2">{{ form.render('areas') }}</div>
                    <div class="col-sm-2">{{ form.render('branch_area') }}</div>

                </div>

                <div class="form-group">

                    <label for="fieldBranch_account" class="col-sm-2 control-label">收款账户</label>
                    <div class="col-sm-4">
                        {{ form.render('branch_account') }}
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">开户行</label>
                    <div class="col-sm-2">
                        {{ form.render('branch_bank') }}
                    </div>
                </div>

                <div class="form-group">
                    {{ form.render('branch_admin') }}
                    <label for="fieldBranch_admin" class="col-sm-2 control-label">分公司管理员</label>
                    <div class="col-sm-4">
                        {{ form.render('branch_admin_id') }}
                    </div>

                    <label for="fieldBranch_phone" class="col-sm-2 control-label">联系方式</label>
                    <div class="col-sm-2">
                        {{ form.render('branch_phone') }}
                    </div>

                </div>
                <div class="form-group">
                <label for="fieldBranch_status" class="col-sm-2 control-label">激活</label>
                <div class="col-sm-2">
                    {{ form.render('branch_status') }}
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
        {{ javascript_include('js/jquery-3.1.1.min.js') }}
        {{ javascript_include('js/select.js') }}
<script>
$(function(){
    $("#branch_admin_id").change();
})
</script>
