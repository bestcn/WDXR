<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class="active">{{ acl_button(['admin/sms/code', '发送短信验证码']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/success", '短信通知用户证件审核通过']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/failed", '短信通知用户证件审核失败']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/bill", '短信通知用户票据的审核期限']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/report", '短信通知用户征信的审核期限']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/period", '短信通知用户的服务期限']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/loanSuccess", '短信通知普惠审核通过']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/loanFailed", '短信通知普惠审核驳回']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/account", '用户通过后通知用户的账号密码信息']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/apply_success", '多类型审核通过']) }}</li>
                    <li class="">{{ acl_button(["admin/sms/apply_failed", '多类型审核驳回']) }}</li>
                </ul>
            </div>
            <div class="ibox-content">
                <div class="wrapper wrapper-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content">
                                    <div class="lightBoxGallery">
                                        <div class="tab-content">
                                            <div class="tab-pane active">
                                                <div class="form-horizontal">

                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <label for="fieldBranch_name" class="col-sm-2 control-label">模板ID</label>
                                                            <div class="col-sm-8">
                                                                <span>53471</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fieldBranch_name" class="col-sm-2 control-label">短信文本</label>
                                                            <div class="col-sm-8">
                                                                <span>您的验证码是{1}</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fieldBranch_name" class="col-sm-2 control-label">电话号码</label>
                                                            <div class="col-sm-8">
                                                                <textarea name="phone" id="phone" class="form-control" placeholder="可填写多个号码,用英文逗号分割"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="hr-line-dashed"></div>
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            {{ acl_button(["admin/sms/code", '发送', 'href':'javascript:send()', 'class':'btn btn-primary']) }}
                                                            <button onclick="history.go(-1);" class="btn btn-default" type="button">返回</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{#弹框查看信息#}
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title" id="modal-title">短信验证码</h5>
                <small class="font-bold" id="font-bold"></small>
            </div>
            <div class="modal-body" id="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

<button id="click" style="display: none" class="btn btn-primary btn-xs" data-toggle='modal' data-target='#myModal5'>点击弹出</button>




<script type="text/javascript">
    function send() {
        var phone =  $("#phone").val();
        $.ajax({
            type : "POST",
            url : "{{ url('admin/sms/code') }}",
            data : {phone:phone},
            success : function(data) {
                $("#modal-body").html(data);
                $("#click").click();
            }
        });
    }
</script>

