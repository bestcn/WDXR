<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    <i data-toggle="tooltip" data-placement="right" class="fa fa-info-circle" title="已经通过企业信息审核的且提交普惠申请的企业"></i>
                    普惠审核列表
                </h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    {{ form("admin/loan/index", "method":"get") }}
                    <input type="hidden" name="hidden" value="{{ hidden }}">
                    <div class="col-sm-2 m-b-xs">
                        <select title="普惠状态" class="input-sm form-control" name="status">
                            <option {% if request.get('status', 'trim') == '' %}selected{% endif %} value="">普惠状态</option>
                            {% for status_name in status_names %}
                            <option {% if status_name['key'] == request.get('status', 'trim') %}selected{% endif %} value="{{ status_name['key'] }}">{{ status_name['name'] }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="col-sm-6 m-b-xs">
                        <div class="input-group">
                            <input type="text" name="name" value="{{ request.get('name', 'trim') }}" class="input-sm form-control" placeholder="搜索企业名称或申请人名称">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                    </div>
                    {{ end_form() }}

                    <div class="col-sm-4 pull-right">
                        <span class="pull-right">
                            {% if hidden == 1 %}
                                <a class="btn btn-sm btn-default" href="{{ url("admin/loan/index?hidden=0") }}"> 返回</a>
                            {% else %}
                                <a class="btn btn-sm btn-default" href="{{ url("admin/loan/index?hidden=1") }}">
                                <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="被忽略的企业可在这里找回"></i>
                                    忽略列表
                                </a>
                            {% endif %}
                            {#<a class="btn btn-sm btn-primary" href="">审核记录</a>#}
                        </span>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>审核编号</th>
                        <th>申请人</th>
                        <th>企业名称</th>
                        <th>申请时间</th>
                        <th>审核状态</th>
                        <th>业务员</th>
                        <th>录入账号</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                    {% for loan in page.items %}
                        {% if page.total_items is 0 %}
                            <tr><td colspan="8">无</td></tr>
                        {% endif %}
                    <tr>
                        <td>{{ loan.id }}</td>
                        <td>{{ loan.info_name }}</td>
                        <td>
                            <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="企业编号：{{ loan.company_id }}"></i>
                            {{ loan.company_name }}
                        </td>
                        <td>{{ date('Y-m-d H:i:s',loan.apply_time) }}</td>
                        <td>
                            {% if loan.status == 0 %}
                                <i class="fa text-muted" >未申请</i>
                            {% elseif loan.status == 1 %}
                                <i class="fa fa-clock-o text-navy" > 待审核</i>
                            {% elseif loan.status == 2 %}
                                <i class="fa fa-times text-danger"> 已驳回</i>
                            {% elseif loan.status == 3 %}
                                <i class="fa fa-check text-success"> 初审通过</i>
                            {% elseif loan.status == 5 %}
                                <i class="fa fa-clock-o text-muted" > 已处理</i>
                            {% elseif loan.status == 6 %}
                                <i class="text-muted"> 已结束</i>
                            {% elseif loan.status == 7 %}
                                <i class="fa fa-check text-success"> 已完成</i>
                            {% else %}
                                <i class="fa fa-times text-danger"> 状态错误</i>
                            {% endif %}
                        </td>
                        <td>{{ loan.admin_name }}</td>
                        <td>
                            {% if loan.partner_name  %}
                                <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="{{ loan.partner_company }}"></i>
                                <a target="_blank" href="{{ url('admin/companys/info/'~loan.partner_company_id) }}">
                                    {{ loan.partner_name }}
                                </a>
                            {% else %}
                                {{ loan.admin_name }}
                            {% endif %}
                        </td>
                        <td>
                            <span class="pull-right">
                                {% if hidden == 1 %}
                                    {{ acl_button(["admin/companys/hidden_verify", '找回', 'href':'javascript:hidden('~loan.id~', 0)', 'class':'btn btn-default btn-xs']) }}
                                {% else %}
                                    {{ acl_button(["admin/companys/hidden_verify", '忽略', 'href':'javascript:hidden('~loan.id~', 1)', 'class':'btn btn-default btn-xs']) }}

                                    {{ acl_button(["admin/companys/info/"~loan.company_id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                    {{ acl_button(["admin/loan/edit/"~loan.id, "审核", 'class':'btn btn-primary btn-xs']) }}
                                {% endif %}
                            </span>
                        </td>
                    </tr>
                    {% endfor %}
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
                                {{ link_to("admin/loan/index?page=1"~'&name='~name~'&hidden='~hidden~'&status='~request.get('status'), "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {% if name %}
                                    {{ link_to("admin/loan/index?page="~page.before~'&name='~name~'&hidden='~hidden~'&status='~request.get('status'), "前一页") }}
                                {% else %}
                                    {{ link_to("admin/loan/index?page="~page.before~'&hidden='~hidden~'&status='~request.get('status'), "前一页") }}
                                {% endif %}
                            </li>
                            <li class="paginate_button next">
                                {% if name %}
                                    {{ link_to("admin/loan/index?page="~page.next~'&name='~name~'&hidden='~hidden~'&status='~request.get('status'), "下一页") }}
                                {% else %}
                                    {{ link_to("admin/loan/index?page="~page.next~'&hidden='~hidden~'&status='~request.get('status'), "下一页") }}
                                {% endif %}
                            </li>
                            <li>
                                {% if name %}
                                    {{ link_to("admin/loan/index?page="~page.last~'&name='~name~'&hidden='~hidden~'&status='~request.get('status'), "最后一页") }}
                                {% else %}
                                    {{ link_to("admin/loan/index?page="~page.last~'&hidden='~hidden~'&status='~request.get('status'), "最后一页") }}
                                {% endif %}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{ stylesheet_link("css/plugins/sweetalert/sweetalert.css") }}
{{ javascript_include("js/plugins/sweetalert/sweetalert.min.js") }}

<script type="text/javascript">
    function hidden(id, status) {
        var status_name = status === 1 ? '忽略' : '找回';
        var status_text = status === 1 ? '如果长时间无法审核该记录，可暂时忽略。可在忽略列表找回' : '找回审核记录之后可返回继续审核该企业';
        swal({
            title: "确认要"+status_name+"该审核吗？",
            text: status_text,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定"+status_name,
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.post("{{ url("admin/companys/hidden_verify") }}", {verify_id:id, status:status}, function (res) {
                if(1 === res.status) {
                    swal("已"+status_name, res.info, "success");
                    setTimeout('location.reload()', 1000);
                } else {
                    swal("失败!", res.info, "error");
                }
            });
        });
    }
</script>
