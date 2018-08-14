<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>添加级别</h5></div>
            <div class="ibox-content">
                {{ form("admin/branchs/add_level", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">级别名称</label>
                    <div class="col-sm-2">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('level_name') }}
                    </div>

                    <label for="fieldBranch_level" class="col-sm-2 control-label">级别状态</label>
                    <div class="col-sm-2">
                        {{ form.render('level_status') }}
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
