<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/companys/info/'~id~'/', '基本信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/payment/'~id, '缴费信息']) }}</li>
                <li class="active">{{ acl_button(['admin/companys/business/'~id, '业务信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/user/'~id, '账号信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/bill/'~id, '票据信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/report/'~id, '征信报告']) }}</li>
                <li class="">{{ acl_button(['admin/companys/contract/'~id, '合同信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/setting/'~id, '企业设置']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        服务订单
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>服务订单ID</th>
                                                <th>服务级别</th>
                                                <th>客户类型</th>
                                                <th>开始时间</th>
                                                <th>结束时间</th>
                                                <th>票据状态</th>
                                                <th>征信状态</th>
                                                <th>服务状态</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {% if services is defined %}
                                                {% for service in services %}
                                                    <tr>
                                                        <td>{{ service.id }}</td>
                                                        <td>{{ service.level.level_name }}</td>
                                                        <td>{{ service.type == 1 ? '事业合伙人' : '普惠客户' }}</td>
                                                        <td>{{ date('Y-m-d', service.start_time) }}</td>
                                                        <td>{{ date('Y-m-d', service.end_time) }}</td>
                                                        <td>{{ service.bill_status == 1 ? '正常' : '待交' }}</td>
                                                        <td>{{ service.report_status == 1 ? '正常' : '待交' }}</td>
                                                        <td>{{ get_status_name(service.service_status) }}</td>
                                                    </tr>
                                                {% endfor %}
                                            {% else %}
                                                <tr><td colspan="9">无</td></tr>
                                            {% endif %}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        他推荐的企业
                                    </div>
                                    <div class="panel-body scroll_content">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                            <tr>
                                                <th>
                                                    企业名称
                                                    <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="靠左显示的是直推企业，靠右显示的是管理的企业"></i>
                                                </th>
                                                <th>法人</th>
                                                <th>客户类型</th>
                                                <th>
                                                    服务状态
                                                    <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="未入驻企业指尚未通过申请的新企业"></i>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {% if recommendeds is defined %}
                                                {% for recommended in recommendeds %}
                                                    <tr class="gradeA">
                                                        <td>
                                                            <i data-toggle="tooltip" data-placement="top" class="fa fa-clock-o" title="生效时间 {{ recommended['time'] is '无' ? '无' :  date('Y-m-d', recommended['time']) }}"></i>
                                                            <a href="{{ url('admin/companys/business/'~recommended['company_id']) }}">
                                                                {{ recommended['name'] }}
                                                            </a>
                                                        </td>
                                                        <td>{{ recommended['legal_name'] }}</td>
                                                        <td>{{ recommended['type'] }}</td>
                                                        <td {% if recommended['status'] == '0' %}class="text-danger"{% else %}class="text-navy"{% endif %}>{{ recommended['status_name'] }}</td>
                                                    </tr>
                                                    {% if recommended['next'] is empty %}
                                                    {% else %}
                                                        {% for sub_recommended in recommended['next'] %}
                                                            <tr>
                                                                <td align="right">
                                                                    <i data-toggle="tooltip" data-placement="top" class="fa fa-clock-o" title="生效时间 {{ sub_recommended['time'] is '无' ? '无' : date('Y-m-d', sub_recommended['time']) }}"></i>
                                                                    <a href="{{ url('admin/companys/business/'~sub_recommended['company_id']) }}">
                                                                    {{ sub_recommended['name'] }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ sub_recommended['legal_name'] }}</td>
                                                                <td>{{ sub_recommended['type'] }}</td>
                                                                <td {% if sub_recommended['status'] == '0' %}class="text-danger"{% else %}class="text-navy"{% endif %}>{{ sub_recommended['status_name'] }}</td>
                                                            </tr>
                                                        {% endfor %}
                                                    {% endif %}
                                                {% endfor %}
                                            {% else %}
                                                <tr>
                                                    <td colspan="3">无</td>
                                                </tr>
                                            {% endif %}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        推荐他的企业
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>推荐关系</th>
                                                <th>企业名称</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>推荐人</td>
                                                <td class="data-editable" data-select-url="{{ url('admin/companys/get_recommend_company_list') }}" data-param="recommended_id={{ id }}&old_recommender={{ recommend ? recommend.id : 0 }}" data-callback="{{ url('admin/companys/save_company_recommend') }}">
                                                    {% if recommend is empty %}
                                                        无
                                                    {% else %}
                                                        <a href="{{ url('admin/companys/business/'~recommend.id) }}">
                                                            {{ recommend.name }}
                                                        </a>
                                                    {% endif %}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>管理人</td>
                                                <td>
                                                    {% if manager is empty %}
                                                        无
                                                    {% else %}
                                                        <a href="{{ url('admin/companys/business/'~manager.id) }}">
                                                            {{ manager.name }}
                                                        </a>
                                                    {% endif %}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        续签/退费
                                    </div>
                                    <div class="panel-body">
                                        {% if service_status is defined %}
                                            {{ acl_button(["admin/companys/refund", '退费并停止服务', 'href':"javascript:close("~id~")", 'class':"btn btn-danger btn-rounded"]) }}
                                        {% endif %}
                                        {% if recommend_count is defined and recommend_count > 12 %}
                                            {#<a href="" class="btn btn-success btn-rounded">免费续费</a>#}
                                        {% else %}
                                            {#<a href="" class="btn btn-success btn-rounded">续费</a>#}
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
    function close(id) {
        swal({
            title: "确认要退费并停止该企业的服务？",
            text: "请仔细确认相关企业信息，操作后将停止该企业的所有相关服务并退还相关费用",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定退费",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url("admin/companys/refund") }}", {id:id}, function (res) {
                if(1 === res.status) {
                    swal("已退费!", res.info, "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    }
</script>
