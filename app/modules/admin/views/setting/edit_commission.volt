<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>修改业绩提成设置</h5></div>
            <div class="ibox-content">
                {{ form("admin/setting/edit_commission/"~id, "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">业绩总额</label>
                    <div class="col-sm-2">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('amount') }}
                    </div>

                    <label for="fieldBranch_level" class="col-sm-2 control-label">提成比率</label>
                    <div class="col-sm-2">
                        {{ form.render('ratio') }}
                    </div>
                    <div class="col-sm-2">
                        {{ form.render('type') }}
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
