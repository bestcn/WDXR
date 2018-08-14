<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class="">{{ acl_button(['admin/setting/commission/'~branch_id, '业绩设置']) }}</li>
                    <li class="">{{ acl_button(['admin/setting/commission_list/'~branch_id, '业务员业绩列表']) }}</li>
                    <li class="">{{ acl_button(['admin/setting/partner_commission_list/'~branch_id, '合伙人业绩列表']) }}</li>
                    <li class="active">{{ acl_button(['admin/setting/probation_commission/'~branch_id,'试用期提成设置']) }}</li>
                </ul>
            </div>
            <div class="ibox-content">
                {{ form("admin/setting/probation_commission/"~branch_id, "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    {{ form.render('csrf', ['value': security.getToken()]) }}
                    <label for="fieldBranch_level" class="col-sm-2 control-label">试用期提成比率</label>
                    <div class="col-sm-4">
                        {{ form.render('ratio') }}
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
