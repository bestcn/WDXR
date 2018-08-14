<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>黑名单列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    {{ form("admin/companys/refund", "method":"post", "autocomplete" : "on") }}
                    {#<div class="col-sm-6 form-inline">
                        <div class="form-group ">
                            <!--指定 date标记-->
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="start_time" class="form-control" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        至
                        <div class="form-group">
                            <!--指定 date标记-->
                            <div class='input-group date' id='datetimepicker2'>
                                <input type='text' name="end_time" class="form-control" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>#}

                    <div class="col-sm-6 form-inline">
                        <div class="col-sm-12 input-group">
                            <input type="text" name="company_name" class="input-sm form-control" placeholder="搜索企业名称">
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
                        <th>企业名称</th>
                        <th>时间</th>
                        <th>详情</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for company in page.items %}
                            <tr>
                                <td>{{ company.id }}</td>
                                <td>{{ company.company_name }}</td>
                                <td>{{ company.time }}</td>
                                <td>{{ company.info }}</td>
                                <td>
                            <span class="pull-right">
                                {{ acl_button(["admin/companys/refund_regain/", '恢复', 'href':'javascript:del('~ company.id ~')', 'class':'btn btn-danger btn-xs']) }}
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
                                {{ link_to("admin/companys/index?page=1", "第一页") }}
                            </li>
                            <li class="paginate_button previous">
                                {{ link_to("admin/companys/index?page="~page.before, "前一页") }}
                            </li>
                            <li class="paginate_button next">
                                {{ link_to("admin/companys/index?page="~page.next, "下一页") }}
                            </li>
                            <li>
                                {{ link_to("admin/companys/index?page="~page.last, "最后一页") }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要恢复该企业正常状态吗？')) {
            $.post("{{ url("admin/companys/refund_regain/") }}", {id:id});
            location.reload();
        }
    }
</script>