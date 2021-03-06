<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    <i data-toggle="tooltip" data-placement="right" class="fa fa-info-circle" title="对企业工商信息、联系人信息、业务信息的审核"></i>
                    企业信息审核列表
                </h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-8">
                        {{ form("admin/companys/auditing", "method":"get") }}
                        <div class="input-group">
                            <input type="hidden" name="hidden" value="{{ hidden }}">
                            <input type="text" name="name" value="{{ request.get('name', 'trim', '') }}" class="input-sm form-control" placeholder="搜索企业名称或法人代表姓名">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                        {{ end_form() }}
                    </div>
                    <div class="col-sm-4">
                        <span class="pull-right">
                            {% if hidden == 1 %}
                                <a class="btn btn-sm btn-default" href="{{ url("admin/companys/auditing?hidden=0") }}"> 返回</a>
                            {% else %}
                                <a class="btn btn-sm btn-default" href="{{ url("admin/companys/auditing?hidden=1") }}">
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
                        <th>企业名称</th>
                        <th>法人代表</th>
                        <th>申请时间</th>
                        <th>业务员</th>
                        <th>录入账号</th>
                        <th>推荐企业</th>
                        <th>
                            <span class="pull-right">操作</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% if page.total_items is 0 %}
                            <tr><td colspan="8">无</td></tr>
                        {% endif %}
                        {% for verify in page.items %}
                            <tr>
                                <td>{{ verify.id }}</td>
                                <td>
                                    <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="企业编号：{{ verify.company_id }}"></i>
                                    {{ verify.name }}
                                </td>
                                <td>{{ verify.legal_name }}</td>
                                <td>{{ date('Y-m-d H:i:s', verify.apply_time) }}</td>
                                <td>{{ verify.admin_name }}</td>
                                <td>
                                    {% if verify.partner_name %}
                                        <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="{{ verify.partner_company }}"></i>
                                        <a target="_blank" href="{{ url('admin/companys/info/'~verify.partner_company_id) }}">
                                            {{ verify.partner_name }}
                                        </a>
                                    {% else %}
                                        {{ verify.admin_name }}
                                    {% endif %}
                                </td>
                                <td>{{ verify.recommend_company ? verify.recommend_company : '无' }}</td>
                                <td>
                                    <span class="pull-right">
                                        {% if hidden == 1 %}
                                            {{ acl_button(["admin/companys/hidden_verify", '找回', 'href':'javascript:hidden('~verify.id~', 0)', 'class':'btn btn-default btn-xs']) }}
                                        {% else %}
                                            {{ acl_button(["admin/companys/hidden_verify", '忽略', 'href':'javascript:hidden('~verify.id~', 1)', 'class':'btn btn-default btn-xs']) }}
                                            {{ acl_button(["admin/companys/edit_auditing/"~verify.id, '审核', 'class':'btn btn-primary btn-xs']) }}
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
                        {{ page.current~"/"~page.total_pages }}
                    </div>

                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li>
                                {{ link_to("admin/companys/auditing?page=1"~'&name='~name~'&hidden='~hidden, "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {% if name %}
                                    {{ link_to("admin/companys/auditing?page="~page.before~'&name='~name~'&hidden='~hidden, "前一页") }}
                                {% else %}
                                    {{ link_to("admin/companys/auditing?page="~page.before~'&hidden='~hidden, "前一页") }}
                                {% endif %}
                            </li>
                            <li class="paginate_button next">
                                {% if name %}
                                    {{ link_to("admin/companys/auditing?page="~page.next~'&name='~name~'&hidden='~hidden, "下一页") }}
                                {% else %}
                                    {{ link_to("admin/companys/auditing?page="~page.next~'&hidden='~hidden, "下一页") }}
                                {% endif %}
                            </li>
                            <li>
                                {% if name %}
                                    {{ link_to("admin/companys/auditing?page="~page.last~'&name='~name~'&hidden='~hidden, "最后一页") }}
                                {% else %}
                                    {{ link_to("admin/companys/auditing?page="~page.last~'&hidden='~hidden, "最后一页") }}
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