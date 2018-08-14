<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    {% if info['status'] == 2 %}
                        <i class="fa fa-times text-danger">审核已驳回</i>
                    {% elseif info['status'] == 5 %}
                        <i class="fa fa-check text-success">审核已处理</i>
                    {% elseif info['status'] == 6 %}
                        <i class="fa fa-check text-success">审核已结束（已被银行驳回）</i>
                    {% elseif info['status'] == 7 %}
                        <i class="fa fa-check text-success">审核已完成（已被银行放款，缴费生效）</i>
                    {% endif %}
                    普惠信息审核</h5>
                <div style="float: right">
                    {% if info['status'] == 1 %}
                        {{ acl_button(["admin/loan/auditing/", '返回', 'class':'btn btn-default btn-xs']) }}
                    {% else %}
                        {{ acl_button(["admin/loan/index/", '返回', 'class':'btn btn-default btn-xs']) }}
                    {% endif %}
                </div>
            </div>
            <div class="ibox-content">
                <style>.table tr th {width:120px;}</style>
                <!--这里开始-->
                <div class="wrapper wrapper-content">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    企业普惠申请信息
                                </div>
                                <div class="panel-body">
                                    <table class="table table-hover">
                                        <tr><th>申请人</th><td>{{ info['name'] }}</td></tr>
                                        <tr><th>申请时间</th><td>{{ date('Y-m-d H:i:s',info['time']) }}</td></tr>
                                        <tr><th>性别</th><td>{% if info['sex'] == 1 %}男{% elseif info['sex'] == 2 %}女{% else %}错误{% endif %}</td></tr>
                                        <tr><th>申请人手机号</th><td>{{ info['tel'] }}</td></tr>
                                        <tr><th>身份证号</th><td>{{ info['identity'] | default('无') }}</td></tr>
                                        <tr><th>企业名称</th><td>{{ info['company_name'] | default('无') }}</td></tr>
                                        <tr><th>企业等级</th><td>{{ info['level'] }}</td></tr>
                                        <tr><th>营业执照号</th><td>{{ info['licence_num'] }}</td></tr>
                                        <tr><th>住址</th><td>{{info['province']}}{{ info['city']}}{{ info['area']}}{{ info['address']}}</td></tr>
                                        <tr><th>申请金额</th><td>{{ info['money'] }} 元</td></tr>
                                        <tr><th>申请期限</th><td>{% if info['term'] == 1 %}三个月{% elseif info['term']== 2 %}六个月{% elseif info['term'] == 3 %}九个月{% elseif info['term'] == 4 %}十二个月{% else %}错误{% endif %}</td></tr>
                                        <tr><th>业务员</th><td>{{ info['admin_name'] }}</td></tr>
                                        <tr><th>合伙人</th><td>{{ info['partner_name'] }}</td></tr>
                                        <tr><th>职业/经营项目</th><td>{{ info['business'] }}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {% if info['status'] == 3 or info['status'] == 6 or info['status'] == 7 %}
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        企业贷款信息信息
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            <tr><th>联社系统借款</th><td>{{ info['system_loan'] }}</td></tr>
                                            <tr><th>对外担保金额</th><td>{{ info['sponsion'] }}</td></tr>
                                            <tr><th>其他金融机构借款</th><td>{{ info['other_loan'] }}</td></tr>
                                            <tr><th>其中：不良借贷或不良担金额</th><td>{{ info['unhealthy'] }}</td></tr>
                                            <tr><th>上年收入</th><td>{{ info['last_year'] }}</td></tr>
                                            <tr><th>今年收入</th><td>{{ info['this_year'] }}</td></tr>
                                            <tr><th>担保金额</th><td>{{ info['quota'] }}</td></tr>
                                            <tr><th>备注</th><td>{{ info['remarks'] }}</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        {% endif %}
                    </div>
                </div>
                <!--这里结束-->

                {% if info['status'] == 3 %}
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">通过申请</div>
                                <form id="ok2_form" enctype="multipart/form-data" action="{{ url('admin/loan/bankLoan') }}" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                上传放款凭证
                                            </div>
                                            <div class="col-sm-10">
                                                <input type="hidden" name="company_id" value="{{ company_id }}" />
                                                <input type="hidden" name="verify_id" value="{{ info['verify_id'] }}">
                                                <input type="hidden" name="loan_id" value="{{ info['u_id'] }}">

                                                <div id="fileUploadContent" class="fileUploadContent"></div>
                                                <input type="hidden" value="" id="voucher" name="voucher"/>
                                            </div>
                                        </div>

                                        <div class="hr-line-dashed"></div>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                上传银行卡照片
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="col-sm-4">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <select title="选择银行" name ="address"  style="height: 34px; border: 1px solid #e5e6e7">
                                                                <option value="鹿泉高新区信用社">鹿泉高新区信用社</option>
                                                                <option value="石家庄市鹿泉农村信用合作联社寺家庄信用社">石家庄市鹿泉农村信用合作联社寺家庄信用社</option>
                                                                <option value="晋州农村商业银行">晋州农村商业银行</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <input placeholder="请填写开户人名称" type="text" name="bank_account" class="form-control"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <input placeholder="请填写银行卡号" type="text" name="bankcard" class="form-control"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-8">
                                                    <div id="fileUploadContent2" class="fileUploadContent"></div>
                                                    <input type="hidden" value="" id="card" name="card"/>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <input type="hidden" name="status" value="7">
                                        <input type="button" value="通过" class="ok2 btn btn-success"/>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">导出数据</div>
                                <div class="panel-body">
                                    <form class="col-sm-6" action="{{ url('admin/loan/exportApply') }}" method="post">
                                        <input type="hidden" name="data_id" value="{{ info['id'] }}">
                                        <input type="submit" name="导出普惠申请表" value="导出普惠申请表" class="btn btn-primary"/>
                                    </form>
                                    <form class="col-sm-6" action="{{ url('admin/loan/exportPresentation') }}" method="post">
                                        <input type="hidden" name="data_id" value="{{ info['id'] }}">
                                        <input type="submit" name="导出普惠调查报告" value="导出普惠调查报告" class="btn btn-primary"/>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">关闭</div>
                                <form id="fail2_form" action="{{ url('admin/loan/bankLoan') }}" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                关闭原因
                                                <input type="hidden" name="company_id" value="{{ info['company_id'] }}" />
                                                <input type="hidden" name="verify_id" value="{{ info['verify_id'] }}">
                                                <input type="hidden" name="loan_id" value="{{ info['u_id'] }}">
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" placeholder="如银行确定该企业无法办理贷款，则关闭该申请流程" name="remark"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <input type="hidden" name="status" value="6">
                                        <input type="button" value="关闭" class="fail2 btn btn-danger"/>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                {% elseif info['status'] == 1 %}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    通过申请，请填写以下信息
                                </div>
                                <form id="ok_form" action="{{ url('admin/loan/editAudit') }}" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <input type="hidden" name="id" value="{{ info['verify_id'] }}">
                                            <input type="hidden" name="data_id" value="{{ info['id'] }}">
                                            <input type="hidden" name="u_id" value="{{ info['u_id'] }}">
                                            <input type="hidden" name="tel" value="{{ info['tel'] }}">
                                            <input type="hidden" name="name" value="{{ info['name'] }}">
                                            <div class="col-sm-12">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>请填写联社系统借款信息</label>
                                                        {{ form.render('system_loan') }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label>请填写对外担保金额信息</label>
                                                        {{ form.render('sponsion') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>请填写其他金融机构借款信息</label>
                                                        {{ form.render('other_loan') }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label>请填写不良借贷或不良担保金额信息</label>
                                                        {{ form.render('unhealthy') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>请填写上年收入</label>
                                                        {{ form.render('last_year') }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label>请填写今年收入</label>
                                                        {{ form.render('this_year') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>请填写担保金额</label>
                                                        {{ form.render('quota') }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label>请填写备注</label>
                                                        {{ form.render('remarks') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <input type="hidden" name="status" value="3">
                                        <input type="button" value="通过" class="ok btn btn-success"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">驳回申请</div>
                                <form id="fail_form" action="{{ url('admin/loan/editAudit') }}" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                驳回原因
                                                <input type="hidden" name="id" value="{{ info['verify_id'] }}">
                                                <input type="hidden" name="data_id" value="{{ info['id'] }}">
                                                <input type="hidden" name="u_id" value="{{ info['u_id'] }}">
                                                <input type="hidden" name="tel" value="{{ info['tel'] }}">
                                                <input type="hidden" name="name" value="{{ info['name'] }}">
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" placeholder="如果需要驳回,请填写驳回原因" name="remark"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <input type="hidden" name="status" value="2">
                                        <input type="button" value="驳回" class="fail btn btn-danger"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                {% endif %}
            </div>
        </div>
    </div>
</div>
{{ stylesheet_link("css/plugins/sweetalert/sweetalert.css") }}
{{ stylesheet_link("css/fileUpload.css") }}
{{ stylesheet_link("css/iconfont.css") }}

{{ javascript_include("js/plugins/sweetalert/sweetalert.min.js") }}
{{ javascript_include("js/fileUpload.js") }}
{{ javascript_include("js/iconfont.js") }}
<script type="text/javascript">
    $('.ok').click(function () {
        swal({
            title: "确认要通过申请吗？",
            text: "请在仔细核对客户提交的材料后，通过申请。此操作不可撤销!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定通过",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url('admin/loan/editAudit') }}", $("#ok_form").serialize(), function (res) {
                if(res.status == '1') {
                    swal("已通过!", "该企业的普惠申请已经通过", "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    });
    $('.fail').click(function () {
        swal({
            title: "确认要驳回申请吗？",
            text: "请在仔细核对客户提交的材料后，驳回申请。此操作不可撤销!",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定驳回",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url('admin/loan/editAudit') }}", $("#fail_form").serialize(), function (res) {
                if(res.status == '1') {
                    swal("已驳回!", "该企业的普惠申请已经驳回.", "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    });

    $('.ok2').click(function () {
        swal({
            title: "确认要通过申请吗？",
            text: "请在仔细核对客户提交的材料后，通过申请。此操作不可撤销!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定通过",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url('admin/loan/bankLoan') }}", $("#ok2_form").serialize(), function (res) {
                if('1' == res.status) {
                    swal("已通过!", "该企业的申请已经通过", "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    });
    $('.fail2').click(function () {
        swal({
            title: "确认要关闭该申请吗？",
            text: "请在仔细核对客户提交的材料后关闭申请。此操作不可撤销!",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定关闭",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url('admin/loan/bankLoan') }}", $("#fail2_form").serialize(), function (res) {
                if('1' == res.status) {
                    swal("已关闭!", "该企业的申请已经关闭.", "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    });
</script>

{% if info['status'] == 3 %}
    <script type="text/javascript">
        $("#fileUploadContent").initUpload({
            "uploadUrl":"/admin/loan/upload?id="+{{ info['verify_id'] }},//上传文件信息地址
            //"size":350,//文件大小限制，单位kb,默认不限制
            //"maxFileNumber":3,//文件个数限制，为整数
            //"filelSavePath":"",//文件上传地址，后台设置的根目录
            "beforeUpload":beforeUploadFun,//在上传前执行的函数
            "onUpload":onUploadFun,//在上传后执行的函数
            autoCommit:true,//文件是否自动上传
            "fileType":['png','jpg','docx','doc']//文件类型限制，默认不限制，注意写的是文件后缀
        });
        function beforeUploadFun(opt){
            opt.otherData =[{"name":"name"}];
        }
        function onUploadFun(opt,data){
            if(data.status = 1){
                if($("#voucher").val() != '') {
                    $("#voucher").val($("#voucher").val() + ',' + data.info);
                }else{
                    $("#voucher").val(data.info);
                }
                swal("上传成功");
            }
        }


        function testUpload(){
            var opt = uploadTools.getOpt("fileUploadContent");
            uploadEvent.uploadFileEvent(opt);
        }
    </script>

    <script type="text/javascript">
        $("#fileUploadContent2").initUpload({
            "uploadUrl":"/admin/loan/upload?id="+{{ info['verify_id'] }},//上传文件信息地址
            //"size":350,//文件大小限制，单位kb,默认不限制
            "maxFileNumber":1,//文件个数限制，为整数
            //"filelSavePath":"",//文件上传地址，后台设置的根目录
            "beforeUpload":beforeUploadFun2,//在上传前执行的函数
            "onUpload":onUploadFun2,//在上传后执行的函数
            autoCommit:true,//文件是否自动上传
            "fileType":['png','jpg','docx','doc']//文件类型限制，默认不限制，注意写的是文件后缀
        });
        function beforeUploadFun2(opt){
            opt.otherData =[{"name":"name"}];
        }
        function onUploadFun2(opt,data){
            if(data.status = 1){
                $("#card").val(data.info);
                swal("上传成功");
            }
        }
        function testUpload2(){
            var opt = uploadTools.getOpt("fileUploadContent2");
            uploadEvent.uploadFileEvent(opt);
        }
    </script>
{% endif %}





