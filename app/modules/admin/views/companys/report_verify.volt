<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{% if v_status == 2 %} <i class="fa fa-times text-danger">已驳回</i> {% elseif v_status == 3 %} <i class="fa fa-check text-success">已通过</i> {% endif %} 企业信息审核</h5>
                <div style="float: right">
                    {{ acl_button(["admin/companys/reportVerify/", '返回', 'class':'btn btn-default btn-xs']) }}
                </div>
            </div>
            <div class="ibox-content">
                <style>.table tr th {width:120px;}</style>
                <div class="row">

                        {% for data in  companyreport_data['report'] %}
                            <div class="col-sm-2">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        征信报告
                                    </div>
                                    <div class="panel-body">
                                        <a href="{{ data }}" title="征信报告" data-gallery="">
                                            <img width="100%" src="{{ data }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        {% endfor %}

                </div>
                {% if v_status == 1 %}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">驳回申请</div>
                                <form id="fail_form"  action="{{ url('admin/companys/edit_report') }}" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                驳回原因
                                                <input type="hidden" value="{{ companyreport_data['id'] }}" name="data_id"/>
                                                <input type="hidden" value="{{ company_data.id }}" name="company_id"/>
                                                <input type="hidden" value="{{ verify_id }}" name="verify_id"/>
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

                        <div class="col-sm-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">通过申请</div>
                                <form id="ok_form" action="{{ url('admin/companys/edit_report') }}" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                征信将正式生效
                                                <input type="hidden" value="{{ companyreport_data['id'] }}" name="data_id"/>
                                                <input type="hidden" value="{{ company_data.id }}" name="company_id"/>
                                                <input type="hidden" value="{{ verify_id }}" name="verify_id"/>
                                            </div>
                                            {#<div class="col-sm-8">#}
                                            {#{{ form.render('account') }}#}
                                            {#</div>#}
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <input type="hidden" name="status" value="3">
                                        <input type="button" value="通过" class="ok btn btn-primary"/>
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
            title: "确认要通过征信申请吗？",
            text: "请在仔细核对客户提交的材料后，通过申请。此操作不可撤销!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定通过",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url('admin/companys/edit_report') }}", $("#ok_form").serialize(), function (res) {
                if(1 === res.status) {
                    swal("已通过!", "该征信申请已经通过", "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    });
    $('.fail').click(function () {
        swal({
            title: "确认要驳回征信申请吗？",
            text: "请在仔细核对客户提交的材料后，驳回申请。此操作不可撤销!",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定驳回",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url('admin/companys/edit_report') }}", $("#fail_form").serialize(), function (res) {
                if(1 === res.status) {
                    swal("已驳回!", "该征信申请已经驳回.", "success");
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









