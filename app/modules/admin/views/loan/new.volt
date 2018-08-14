<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>新建普惠申请</h5></div>
            <div class="ibox-content">
                {{ form("admin/loan/new/"~id, "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}

                <div class="form-group">
                    <label for="fieldBranch_admin" class="col-sm-2 control-label">企业名称</label>
                    <div class="col-sm-4">
                        {{ data['company_name'] }}
                    </div>
                    <label for="fieldBranch_admin" class="col-sm-2 control-label">营业执照号</label>
                    <div class="col-sm-4">
                        {{ data['licence_num'] }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">申请人</label>
                    <div class="col-sm-4">
                        {{ form.render('name') }}
                    </div>
                    <label for="fieldBranch_level" class="col-sm-2 control-label">性别</label>
                    <div class="col-sm-4">
                        {{ form.render('sex') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldBrancharea" class="col-sm-2 control-label">地址</label>
                    <div class="col-sm-2">{{ form.render('province') }}</div>
                    <div class="col-sm-2">{{ form.render('city') }}</div>
                    <div class="col-sm-2">{{ form.render('area') }}</div>
                    <div class="col-sm-4">{{ form.render('address') }}</div>
                </div>
                <div class="form-group">
                    <label for="fieldBranch_admin" class="col-sm-2 control-label">身份证号</label>
                    <div class="col-sm-4">
                        {{ form.render('identity') }}
                    </div>
                    <label for="fieldBranch_phone" class="col-sm-2 control-label">申请人手机号</label>
                     <div class="col-sm-4">
                        {{ form.render('tel') }}
                     </div>
                </div>

                <div class="form-group">
                    <label for="fieldBranch_account" class="col-sm-2 control-label">职业/经营项目</label>
                    <div class="col-sm-4">
                        {{ form.render('business') }}
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">企业等级</label>
                    <div class="col-sm-4">
                        {{ form.render('level_id') }}
                    </div>
                </div>
                <div class="form-group">
                     <label for="fieldBranch_admin" class="col-sm-2 control-label">申请金额</label>
                     <div class="col-sm-4">
                        {{ form.render('money') }}
                     </div>
                     <label for="fieldBranch_phone" class="col-sm-2 control-label">申请期限</label>
                     <div class="col-sm-4">
                        {{ form.render('term') }}
                     </div>
                </div>
                <div class="form-group">
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">申请用途</label>
                    <div class="col-sm-4">
                        {{ form.render('purpose') }}
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">其选择业务员</label>
                    <div class="col-sm-4">
                        {{ form.render('admin_id') }}
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
<script>
    $(function(){
        $("#province").change(function () {
            var provinceid = $("#province").val();
            $.ajax({
                url:'/admin/select/change_province?provinceid='+provinceid,
                dataType:'html',
                type:'get',
                success:function(data){
                    $("#city").html(data);
                    $("#city").change();
                }
            })
        });

        $("#city").change(function(){
            var citieid = $("#city").val();
            $.ajax({
                url:'/admin/select/change_citie?citieid='+citieid,
                dataType:'html',
                type:'get',
                success:function(data){
                    $("#area").html(data);
                }
            })
        });

    })
</script>
