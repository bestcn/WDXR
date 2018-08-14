<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0);">短信模板列表</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>模板ID</th>
                                <th>模板名称</th>
                                <th ></th>
                            </tr>
                            </thead>
                            <tbody>

                                    <tr>
                                        <td>53471</td>
                                        <td>短信验证码</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/code", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>53421</td>
                                        <td>短信通知用户证件审核通过</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/success", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>53422</td>
                                        <td>短信通知用户证件审核失败</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/failed", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>53428</td>
                                        <td>短信通知用户票据的审核期限</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/bill", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>53441</td>
                                        <td>短信通知用户征信的审核期限</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/report", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>53445</td>
                                        <td>短信通知用户的服务期限</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/period", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>53461</td>
                                        <td>短信通知普惠审核通过</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/loanSuccess", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>53463</td>
                                        <td>短信通知普惠审核驳回</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/loanFailed", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>53470</td>
                                        <td>用户通过后通知用户的账号密码信息</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/account", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>53758</td>
                                        <td>多类型审核通过</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/apply_success", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>53757</td>
                                        <td>多类型审核驳回</td>
                                        <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/apply_failed", "发送", 'class':'btn btn-primary btn-xs']) }}
                                        </span>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
