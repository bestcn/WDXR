<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>新建分公司</h5></div>
            <div class="ibox-content">
                {{ form("admin/companys/CompanyNew", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">公司名称</label>
                    <div class="col-sm-3">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('name') }}
                    </div>

                    <label for="fieldBranch_name" class="col-sm-2 control-label">统一信用代码</label>
                    <div class="col-sm-3">
                        {{ form.render('licence_num') }}
                    </div>

                </div>

                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">公司法人</label>
                    <div class="col-sm-3">
                        {{ form.render('legal_name') }}
                    </div>

                    <label for="fieldBranch_name" class="col-sm-2 control-label">公司性质</label>
                    <div class="col-sm-3">
                        {{ form.render('type') }}
                    </div>

                </div>


                <div class="form-group">
                    <label for="fieldBrancharea" class="col-sm-2 control-label">公司地址</label>
                    <div class="col-sm-2">{{ form.render('provinces') }}</div>
                    <div class="col-sm-2">{{ form.render('cities') }}</div>
                    <div class="col-sm-2">{{ form.render('areas') }}</div>
                    <div class="col-sm-2">{{ form.render('address') }}</div>
                </div>

                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">经营期限</label>
                    <div class='input-group date col-sm-2' style="float: left;margin-left: 13px;" id='datetimepicker1'>
                        <input type='text' name="period_start"  class="form-control" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                    <div class='input-group date col-sm-2' style="float: left;margin-left: 13px;" id='datetimepicker2'>
                        <input type='text' name="period_end" class="form-control" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">经营范围</label>
                    <div class="col-sm-8">
                        {{ form.render('scope') }}
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
