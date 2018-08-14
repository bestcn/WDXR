<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    <i data-toggle="tooltip" data-placement="right" class="fa fa-info-circle" title="已录入企业信息，尚未入驻的潜在客户，不包含已过期或黑名单客户"></i>
                    未入驻企业列表
                </h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    {{ form("admin/companys/new_company_list", "method":"get", 'class':'form-inline') }}
                    <div class="col-sm-12">
                        <div class="col-sm-9 input-group">
                            <input type="text" name="search" class="input-sm form-control" value="{{ request.get('search', 'trim') }}" placeholder="搜索企业名称、统一社会信用代码、联系方式、法人姓名">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                    </div>
                    {{ end_form() }}
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>统一社会信用代码</th>
                        <th>企业名称</th>
                        <th>法人</th>
                        <th>地址</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                    {% for company in page.items %}
                    <tr>
                        <td>
                            <i data-toggle="tooltip" data-placement="right" class="fa fa-clock-o" title="录入时间：{{ company.time }}"></i>
                            {{ company.id }}
                        </td>
                        <td>{{ company.licence_num }}</td>
                        <td>{{ company.name }}</td>
                        <td>{{ company.legal_name }}</td>
                        <td>{{ get_address(company.province, company.city, company.district, company.address) }}</td>
                        <td>
                            <span class="pull-right">
                                {{ acl_button(["admin/companys/info/"~company.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                {{ acl_button(["admin/companys/delete/", '彻底删除', 'href':'javascript:del('~ company.id ~', "'~company.name~'")', 'class':'btn btn-danger btn-xs']) }}
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
                        {{ page.current~"/"~page.total_pages }}
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li>
                                {{ link_to("admin/companys/new_company_list?page=1"~'&search='~request.get('search')~'&level='~request.get('level')~'&type='~request.get('type'), "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {{ link_to("admin/companys/new_company_list?page="~page.before~'&search='~request.get('search')~'&level='~request.get('level')~'&type='~request.get('type'), "前一页") }}
                            </li>
                            <li class="paginate_button next">
                                {{ link_to("admin/companys/new_company_list?page="~page.next~'&search='~request.get('search')~'&level='~request.get('level')~'&type='~request.get('type'), "下一页") }}
                            </li>
                            <li>
                                {{ link_to("admin/companys/new_company_list?page="~page.last~'&search='~request.get('search')~'&level='~request.get('level')~'&type='~request.get('type'), "最后一页") }}
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
                setTimeout('location.reload()', 1000);
            } else {
                swal("失败!", res.info, "error");
            }
        });
    });
}
</script>