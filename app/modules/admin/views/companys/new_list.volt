<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>缴费待申请列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        {{ acl_button(["admin/companys/companyNew", '手动添加新公司', 'class':'btn btn-default']) }}
                    </div>
                    <!--<div class="col-sm-4 m-b-xs">-->
                        <!--<select class="input-sm form-control input-s-sm inline">-->
                            <!--<option value="0">Option 1</option>-->
                            <!--<option value="1">Option 2</option>-->
                            <!--<option value="2">Option 3</option>-->
                            <!--<option value="3">Option 4</option>-->
                        <!--</select>-->
                    <!--</div>-->
                    <div class="col-sm-4">
                        {{ form("admin/companys/new_list", "method":"post", "autocomplete" : "on") }}
                        <div class="input-group">
                            <input type="text" name="name" class="input-sm form-control" placeholder="搜索公司名称">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                        {{ end_form() }}
                    </div>

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>公司名称</th>
                        <th>统一信用代码</th>
                        <th>创建时间</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page['data'] is defined %}
                    {% for company in page['data'] %}
                    <tr>
                        <td>{{ company['id'] }}</td>
                        <td>{{ company['name'] }}</td>
                        <td>{{ company['licence_num'] }}</td>
                        <td>{{ company['time'] }}</td>
                        <td>
                            <span class="pull-right">
                            {{ acl_button(["admin/loan/new/"~company['id'], '普惠申请', 'class':'btn btn-primary btn-xs']) }}
                            {{ acl_button(["admin/apply/payment/"~company['id'], '缴费申请', 'class':'btn btn-primary btn-xs']) }}
                            </span>
                        </td>
                    </tr>
                    {% endfor %}
                    {% endif %}
                    </tbody>
                </table>
                <div class="row" >
                    <div class="col-sm-5">
                        {{ page['current']~"/"~page['total_pages'] }}
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li>{{ link_to("admin/companys/new_list", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/companys/new_list?page="~page['before'], "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/companys/new_list?page="~page['next'], "下一页") }}</li>
                            <li>{{ link_to("admin/companys/new_list?page="~page['total_pages'], "最后一页") }}</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
