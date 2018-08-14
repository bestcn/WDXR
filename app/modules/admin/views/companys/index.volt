<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    <i data-toggle="tooltip" data-placement="right" class="fa fa-info-circle" title="服务期内的企业"></i>
                    已入驻企业列表
                </h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    {{ form("admin/companys/index", "method":"get", "id":"search_form") }}
                    <div class="col-sm-2 m-b-xs">
                        <select title="客户类型" class="input-sm form-control" name="type">
                            <option {% if request.get('type') == '' %}selected{% endif %} value="">客户类型</option>
                            <option {% if request.get('type') == '1' %}selected{% endif %} value="1">事业合伙人</option>
                            <option {% if request.get('type') == '2' %}selected{% endif %} value="2">普惠客户</option>
                        </select>
                    </div>
                    <div class="col-sm-2 m-b-xs">
                        <select title="所属城市" class="input-sm form-control" name="city">
                            <option value="">所属城市</option>
                            {% for city in cities %}
                                <option {% if request.get('city') == city['id'] %}selected{% endif %} value="{{ city['id'] }}">{{ city['name'] }}</option>
                            {%  endfor %}
                        </select>
                    </div>
                    <div class="col-sm-2 m-b-xs">
                        <input title="生效时间" id="time" class="input-sm form-control" type="text" name="time" placeholder="生效时间" value="{{ request.get('time', 'trim') }}"/>
                    </div>
                    <div class="col-sm-6 m-b-xs">
                        <div class="input-group">
                            <input type="text" name="search" class="input-sm form-control" value="{{ request.get('search') }}" placeholder="搜索企业名称、统一社会信用代码、联系方式、法人姓名、合同编号、用户账号、业务员姓名">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                                <button data-toggle="dropdown" class="btn btn-success btn-sm dropdown-toggle">更多 <span class="caret"></span></button>
                                <ul title="更多" class="dropdown-menu pull-right">
                                    <li><a href="javascript:export_search()">导出客户名单</a></li>
                                    {#<li><a href="#">Something else here</a></li>#}
                                    {#<li class="divider"></li>#}
                                    {#<li><a href="#">Separated link</a></li>#}
                                </ul>
                            </span>
                        </div>
                    </div>
                    {{ end_form() }}
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        <th>级别</th>
                        <th>法人</th>
                        <th>客户类型</th>
                        <th>票据状态</th>
                        <th>征信状态</th>
                        <th>业务员</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                    {% for company in page.items %}
                    <tr>
                        <td>{{ company.id }}</td>
                        <td>
                            {% if company.is_new is 1 %}
                            <i data-toggle="tooltip" data-placement="top" class="fa fa-heart" title="生效时间：{{ date('Y-m-d', company.start_time) }}"></i>
                            {% endif %}
                            {{ company.name }}
                        </td>
                        <td>{{ company.level_name }}</td>
                        <td>{{ company.legal_name }}</td>
                        <td>
                            {% if company.is_partner == 1 %}
                                合伙人
                            {% else %}
                                普惠
                            {% endif %}
                        </td>
                        <td>
                            {% if company.bill_status == 0 %}
                                <i class="fa text-muted"> 待交</i>
                            {% elseif company.bill_status == 1 %}
                                <i class="fa fa-check text-success"> 正常</i>
                            {% elseif company.bill_status == 2 %}
                                <i class="fa fa-clock-o text-navy"> 即将到期</i>
                            {% elseif company.bill_status == 3 %}
                                <i class="fa fa-times text-danger"> 已过期</i>
                            {% else %}
                                <i class="fa fa-times text-danger"> 状态错误</i>
                            {% endif %}
                        </td>
                        <td>
                            {% if company.report_status == 0 %}
                                <i class="fa text-muted"> 待交</i>
                            {% elseif company.report_status == 1 %}
                                <i class="fa fa-check text-success"> 正常</i>
                            {% elseif company.report_status == 2 %}
                                <i class="fa fa-clock-o text-navy"> 即将到期</i>
                            {% elseif company.report_status == 3 %}
                                <i class="fa fa-times text-danger"> 已过期</i>
                            {% else %}
                                <i class="fa fa-times text-danger"> 状态错误</i>
                            {% endif %}
                        </td>
                        <td>{{ company.admin_name | default('无') }}</td>
                        <td>
                            <span class="pull-right">
                                {{ acl_button(["admin/companys/info/"~company.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                {{ acl_button(["admin/companys/refund_add/"~company.id, '加入黑名单', 'class':'btn btn-danger btn-xs']) }}
                            </span>
                        </td>
                    </tr>
                    {% endfor %}
                    {% endif %}
                    {% if page.total_items === 0 %}
                    <tr><td colspan="9">未查询到相关搜索结果</td></tr>
                    {% endif %}
                    </tbody>
                </table>
                <div class="row" >
                    <div class="col-sm-5">
                        {{ page.current~"/"~page.total_pages }} 共{{ page.total_items }}项
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li>
                                {{ link_to("admin/companys/index?page=1"~'&search='~request.get('search')~'&time='~request.get('time')~'&type='~request.get('type'), "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {{ link_to("admin/companys/index?page="~page.before~'&search='~request.get('search')~'&time='~request.get('time')~'&type='~request.get('type'), "前一页") }}
                            </li>
                            <li class="paginate_button next">
                                {{ link_to("admin/companys/index?page="~page.next~'&search='~request.get('search')~'&time='~request.get('time')~'&type='~request.get('type'), "下一页") }}
                            </li>
                            <li>
                                {{ link_to("admin/companys/index?page="~page.last~'&search='~request.get('search')~'&time='~request.get('time')~'&type='~request.get('type'), "最后一页") }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{ stylesheet_link("css/plugins/daterangepicker/daterangepicker-bs3.css") }}
{{ javascript_include("js/plugins/fullcalendar/moment.min.js") }}
{{ javascript_include("js/plugins/daterangepicker/daterangepicker.js") }}

<script type="text/javascript">
$('#time').daterangepicker({
    "linkedCalendars": false,
    "autoUpdateInput": false,
    format: 'YYYY-MM-DD',
    locale: {
        applyLabel: '确定',
        cancelLabel: '取消',
        fromLabel: '起始时间',
        toLabel: '结束时间',
        customRangeLabel: '自定义',
        daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
            '七月', '八月', '九月', '十月', '十一月', '十二月'],
        firstDay: 1
    },
});

function export_search() {
    $.ajax({
        type : "post",
        url : "{{ url('admin/companys/export_company') }}",
        data : $("#search_form").serialize(),
        async : false,
        success : function(res) {
            if (res.status === 1) {
                toastr.success(res.info);
                window.open(res.data);
            } else {
                toastr.error(res.info);
            }
        }
    });
}
</script>