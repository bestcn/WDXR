<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/companys/info/'~id~'/', '基本信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/payment/'~id, '缴费信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/business/'~id, '业务信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/user/'~id, '账号信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/bill/'~id, '票据信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/report/'~id, '征信报告']) }}</li>
                <li class="">{{ acl_button(['admin/companys/contract/'~id, '合同信息']) }}</li>
                <li class="active">{{ acl_button(['admin/companys/setting/'~id, '企业设置']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        票据期限设置
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            {% if bill_end_time is defined %}
                                            <tr>
                                                <th>到期日</th>
                                                <td>
                                                    {{ bill_end_time ? date('Y-m-d', bill_end_time) : '无' }}
                                                </td>
                                            </tr>
                                            {% endif %}
                                            <tr>
                                                <th>默认期限</th>
                                                <td>
                                                    {% if bill_term is empty %}
                                                        无
                                                    {% else %}
                                                        {{ bill_term.term }}
                                                        {% if bill_term.type == 0 %}
                                                            日
                                                        {% elseif bill_term.type == 1 %}
                                                            月
                                                        {% elseif bill_term.type == 2 %}
                                                            年
                                                        {% else %}
                                                            错误
                                                        {% endif %}
                                                    {% endif %}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                {#<div class="panel panel-primary">#}
                                    {#<div class="panel-heading">#}
                                        {#征信期限设置#}
                                    {#</div>#}
                                    {#<div class="panel-body">#}
                                        {#<table class="table table-hover">#}
                                        {#</table>#}
                                    {#</div>#}
                                {#</div>#}
                            </div>

                            <div class="col-sm-6">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="该企业及其所有下级企业的所属业务员变更"></i>
                                        所属关系流转
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <span class="col-sm-12 m-b">
                                                当前所属业务员
                                                <strong>
                                                    {{ admin_name }}
                                                </strong>
                                            </span>
                                            <span class="col-sm-12 m-b">
                                                <i class="fa fa-warning alert-danger"> 注：该企业流转后与其存在推荐关系的下级企业也将全部流转，请谨慎操作！</i>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">流转给</label>
                                            <div class="col-sm-10">
                                                <select class="form-control m-b" id="change_admin" title="所属关系流转" name="admin_id">
                                                    <option value="">选择业务员</option>
                                                    {% for admin in admin_list %}
                                                        <option value="{{ admin.id }}">{{ admin.name }}</option>
                                                    {% endfor %}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-danger">
                                    <div class="panel-heading">
                                        特殊状态
                                    </div>
                                    <div class="panel-body">
                                        {% if company is defined %}
                                        {{ acl_button(["admin/companys/delete/", '彻底删除', 'href':'javascript:del('~ id ~', "'~company.name~'")', 'class':'btn btn-danger']) }}
                                        {{ acl_button(["admin/companys/refund_add/"~id, '加入黑名单', 'class':'btn btn-danger']) }}
                                        {% endif %}
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

{{ stylesheet_link("css/plugins/sweetalert/sweetalert.css") }}
{{ javascript_include("js/plugins/sweetalert/sweetalert.min.js") }}
<script type="text/javascript">
    function del(id, name) {
        swal({
            title: "确认要删除 "+name+" 吗？",
            text: "请在仔细核对企业名称及相关信息，企业彻底删除后将无法恢复！",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定删除",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url("admin/companys/delete/") }}", {id:id}, function (res) {
                if(1 === res.status) {
                    swal("已删除!", name+" 已经被彻底删除.", "success");
                    setTimeout(function () {
                        location.href = "{{ url('admin/companys/index') }}";
                    }, 3000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    }
    $('#change_admin').change(function () {
        var _this = $(this).find("option:selected");
        var id = _this.val();
        var name = _this.text();
        if(!id) return false;
        swal({
            title: "该企业流转后，与其存在推荐关系的下级企业也将全部流转，请谨慎操作！",
            text:  "确认要将该企业流转给 "+name+" 吗？",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定流转",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url("admin/companys/transfer_admin") }}", {company_id:"{{ id }}", admin_id:id}, function (res) {
                if('1' === res.status) {
                    swal("已流转!", "该企业已经流转给 "+name, "success");
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    })
</script>
