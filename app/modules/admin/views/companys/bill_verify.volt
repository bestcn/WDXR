<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{% if v_status == 5 %} <i class="fa fa-times text-danger">已驳回</i> {% elseif v_status == 3 %} <i class="fa fa-check text-success">已通过</i> {% endif %} 企业信息审核</h5>
                <div style="float: right">
                    {{ acl_button(["admin/companys/billVerify/", '返回', 'class':'btn btn-default btn-xs']) }}
                </div>
            </div>
            <div class="ibox-content">
                <style>.table tr th {width:120px;}</style>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                票据
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <tr><th>票据金额</th><td>{{ companybill_data['amount'] }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    {% if companybill_data['rent'] %}
                        {% for data in  companybill_data['rent'] %}
                    <div class="col-sm-3">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                房租票据
                            </div>
                            <div class="panel-body">
                                <a href="{{ data }}" title="营业执照" data-gallery="">
                                    <img width="100%" src="{{ data }}">
                                </a>
                            </div>
                        </div>
                    </div>
                        {% endfor %}
                    {% endif %}

                    {% if companybill_data['rent_receipt'] %}
                        {% for data in  companybill_data['rent_receipt'] %}
                            <div class="col-sm-3">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        房租收条
                                    </div>
                                    <div class="panel-body">
                                        <a href="{{ data }}" title="房租收条" data-gallery="">
                                            <img width="100%" src="{{ data }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    {% endif %}

                    {% if companybill_data['rent_contract'] %}
                        {% for data in  companybill_data['rent_contract'] %}
                            <div class="col-sm-3">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        租房合同
                                    </div>
                                    <div class="panel-body">
                                        <a href="{{ data }}" title="租房合同" data-gallery="">
                                            <img width="100%" src="{{ data }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    {% endif %}

                    {% if companybill_data['property_fee'] %}
                        {% for data in  companybill_data['property_fee'] %}
                            <div class="col-sm-3">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        物业发票
                                    </div>
                                    <div class="panel-body">
                                        <a href="{{ data }}" title="物业发票" data-gallery="">
                                            <img width="100%" src="{{ data }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    {% endif %}

                    {% if companybill_data['water_fee'] %}
                        {% for data in  companybill_data['water_fee'] %}
                            <div class="col-sm-3">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        水费发票
                                    </div>
                                    <div class="panel-body">
                                        <a href="{{ data }}" title="物业发票" data-gallery="">
                                            <img width="100%" src="{{ data }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    {% endif %}

                    {% if companybill_data['electricity'] %}
                        {% for data in  companybill_data['electricity'] %}
                            <div class="col-sm-3">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        电费发票
                                    </div>
                                    <div class="panel-body">
                                        <a href="{{ data }}" title="物业发票" data-gallery="">
                                            <img width="100%" src="{{ data }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    {% endif %}

                </div>
                {% if v_status == 1 %}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">驳回申请</div>
                                <form id="fail_form"  action="{{ url('admin/companys/edit_bill') }}" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                驳回原因
                                                <input type="hidden" value="{{ data_id }}" name="data_id"/>
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" placeholder="如果需要驳回,请填写驳回原因" name="remark"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <input type="hidden" name="status" value="5">
                                        <input type="button" value="驳回" class="fail btn btn-danger"/>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">通过申请</div>
                                <form id="ok_form" action="{{ url('admin/companys/edit_bill') }}" method="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                票据将正式生效
                                                <input type="hidden" value="{{ data_id }}" name="data_id"/>
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
            title: "确认要通过票据申请吗？",
            text: "请在仔细核对客户提交的材料后，通过申请。此操作不可撤销!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定通过",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url('admin/companys/edit_bill') }}", $("#ok_form").serialize(), function (res) {
                if(1 === res.status) {
                    swal("已通过!", "该票据申请已经通过", "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    });
    $('.fail').click(function () {
        swal({
            title: "确认要驳回票据申请吗？",
            text: "请在仔细核对客户提交的材料后，驳回申请。此操作不可撤销!",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定驳回",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url('admin/companys/edit_bill') }}", $("#fail_form").serialize(), function (res) {
                if(1 === res.status) {
                    swal("已驳回!", "该票据申请已经驳回.", "success");
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




