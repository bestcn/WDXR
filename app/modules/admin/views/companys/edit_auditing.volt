<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{% if v_status == 2 %} <i class="fa fa-times text-danger">已驳回</i> {% elseif v_status == 3 %} <i class="fa fa-check text-success">已通过</i> {% endif %} 企业信息审核</h5>
                <div style="float: right">
                {{ acl_button(["admin/companys/goBack/", '返回', 'class':'btn btn-default btn-xs']) }}
                </div>
            </div>
            <div class="ibox-content">
                <style>.table tr th {width:120px;}</style>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                企业工商信息
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <tr><th>统一社会信用代码</th><td>{{ info['licence_num'] }}</td></tr>
                                    <tr><th>企业名称</th><td>{{ company['name'] }}</td></tr>
                                    <tr><th>企业性质</th><td>{% if info['type'] == 1 %}非个体工商户{% elseif info['type'] == 2 %}个体工商户{% else %}未选择{% endif %}</td></tr>
                                    <tr><th>企业地址</th><td>{{ info['full_address'] }}</td></tr>
                                    <tr><th>法定代表人</th><td>{{ info['legal_name'] }}</td></tr>
                                    <tr><th>法人身份证号</th><td>{{ info['idcard'] | default('无') }}</td></tr>
                                    <tr><th>营业期限</th><td>{{ info['period'] }}</td></tr>
                                    <tr><th>主营业务</th><td>{{ info['scope'] }}</td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                业务信息
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    {#<tr><th>合同编号</th><td>{{ contract.contract_num}}</td></tr>#}
                                    {#<tr><th>签订地址</th><td>{{ contract.location }}</td></tr>#}
                                    <tr><th>推荐人</th><td>{{ company['recommend'] }}</td></tr>
                                    <tr><th>管理人</th><td>{{ company['manager'] }}</td></tr>
                                    <tr><th>公司简介</th><td>{{ info['intro'] }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                企业联系人信息
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <tr><th>联系方式</th><td>{{ info['contact_phone'] }}</td></tr>
                                    <tr><th>联系人</th><td>{{ info['contacts'] }}</td></tr>
                                    <tr><th>联系人职位</th><td>{{ info['contact_title'] }}</td></tr>
                                    <tr><th>邮政编码</th><td>{{ info['zipcode'] }}</td></tr>
                                </table>
                            </div>
                        </div>

                        {#<div class="panel panel-primary">
                            <div class="panel-heading">
                                开户行信息
                            </div>
                            <div class="panel-body">
                                {% if bank['number'] %}
                                <table class="table table-hover">
                                    <tr><th>账户性质</th><td>{%if bank['bank_type'] == 1 %}对公账户{% else %}个人账户{% endif %}</td></tr>
                                    <tr><th>所属银行</th><td>{{ bank['bank'] | default('无') }}</td></tr>
                                    <tr><th>开户人</th><td>{{ bank['account'] | default('无') }}</td></tr>
                                    <tr><th>开户行</th><td>{{ bank['address'] | default('无') }}</td></tr>
                                    <tr><th>银行卡号</th><td>{{ bank['number'] | default('无') }}</td></tr>
                                </table>
                                {% else %}
                                    无
                                {% endif %}
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                绩效银行卡信息
                            </div>
                            <div class="panel-body">
                                {% if bank['work_number'] %}
                                <table class="table table-hover">
                                    <tr><th>所属银行</th><td>{{ bank['work_bank'] | default('无')}}</td></tr>
                                    <tr><th>开户人</th><td>{{ bank['work_account'] | default('无') }}</td></tr>
                                    <tr><th>开户行</th><td>{{ bank['work_address'] | default('无') }}</td></tr>
                                    <tr><th>银行卡号</th><td>{{ bank['work_number'] | default('无') }}</td></tr>
                                </table>
                                {% else %}
                                    无
                                {% endif %}
                            </div>
                        </div>#}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                企业营业执照
                            </div>
                            <div class="panel-body">
                                {% for data in  info['licence'] %}
                                    <a href="{{ data }}" title="营业执照" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                法人手持身份证照片
                            </div>
                            <div class="panel-body">
                                {% for data in  info['photo'] %}
                                    <a href="{{ data }}" title="法人手持身份证照片" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    {#{% if bank['number'] %}
                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                银行卡照片
                            </div>
                            <div class="panel-body">
                                {% for data in  bank['bankcard_photo'] %}
                                    <a href="{{ data }}" title="银行卡照片" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    {% endif %}#}

                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                法人身份证（正面）
                            </div>
                            <div class="panel-body">
                                {% for data in  info['idcard_up'] %}
                                    <a href="{{ data }}" title="法人身份证（正面）" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                法人身份证（反面）
                            </div>
                            <div class="panel-body">
                                {% for data in  info['idcard_down'] %}
                                    <a href="{{ data }}" title="法人身份证（反面）" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    {#{% if bank['work_number'] %}
                    <div class="col-sm-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                绩效银行卡
                            </div>
                            <div class="panel-body">
                                {% for data in  bank['work_bankcard_photo'] %}
                                    <a href="{{ data }}" title="绩效银行卡照片" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    {% endif %}#}
                    {% if info['credit_code'] %}
                        <div class="col-sm-4">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    机构信用代码证
                                </div>
                                <div class="panel-body">
                                    {% for data in  info['credit_code'] %}
                                        <a href="{{ data }}" title="机构信用代码证" data-gallery="">
                                            <img width="100%" src="{{ data }}">
                                        </a>
                                    {% endfor %}
                                </div>
                            </div>
                        </div>
                    {% endif %}
                    {% if info['account_permit'] %}
                        <div class="col-sm-4">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    开户许可证
                                </div>
                                <div class="panel-body">
                                    {% for data in info['account_permit'] %}
                                        <a href="{{ data }}" title="开户许可证" data-gallery="">
                                            <img width="100%" src="{{ data }}">
                                        </a>
                                    {% endfor %}
                                </div>
                            </div>
                        </div>
                    {% endif %}
                    {% if info['shop_img'] %}
                        <div class="col-sm-4">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    门头照片
                                </div>
                                <div class="panel-body">
                                    {% for data in info['shop_img'] %}
                                        <a href="{{ data }}" title="门头照片" data-gallery="">
                                            <img width="100%" src="{{ data }}">
                                        </a>
                                    {% endfor %}
                                </div>
                            </div>
                        </div>
                    {% endif %}
                </div>
                {% if v_status == 1%}
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">通过申请</div>
                                <form id="ok_form" action="{{ url('admin/companys/audit') }}" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                补贴金额放款账户
                                                <input type="hidden" value="{{ company_id }}" name="company_id" />
                                                <input type="hidden" value="{{ verify_id }}" name="verify_id" />
                                            </div>
                                            <div class="col-sm-8">
                                                {{ form.render('account') }}
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
                                <form id="fail_form" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                驳回原因
                                                <input type="hidden" value="{{ company_id }}" name="company_id" />
                                                <input type="hidden" value="{{ verify_id }}" name="verify_id" />
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
{{ javascript_include("js/plugins/sweetalert/sweetalert.min.js") }}

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
            $.post("{{ url('admin/companys/audit') }}", $("#ok_form").serialize(), function (res) {
                if(1 === res.status) {
                    swal("已通过!", "该企业的申请已经通过", "success");
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
            $.post("{{ url('admin/companys/audit') }}", $("#fail_form").serialize(), function (res) {
                if(1 === res.status) {
                    swal("已驳回!", "该企业的申请已经驳回.", "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    });
</script>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <a class="xuanzhuan">旋转</a>
    <a class="fangda">放大</a>
    <a class="suoxiao">缩小</a>
    <ol class="indicator"></ol>
</div>
