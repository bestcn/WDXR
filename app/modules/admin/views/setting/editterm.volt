<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>修改票据期限设置</h5></div>
            <div class="ibox-content">
                {{ form("admin/setting/editterm/"~id, "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">缴费类型</label>
                    <div class="col-sm-2">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('payment') }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="fieldBranch_level" class="col-sm-2 control-label">审核期限</label>
                    <div class="col-sm-1">
                        {{ form.render('term') }}
                    </div>
                    <div class="col-sm-1">
                        {{ form.render('type') }}
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ form.render('id') }}
                        {{ form.render('submit') }}
                        <button onclick="history.go(-1);" class="btn btn-default" type="button">返回</button>
                    </div>
                </div>
                {{ end_form() }}
            </div>
        </div>
    </div>
</div>

