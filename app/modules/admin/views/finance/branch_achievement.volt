<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>分站业绩列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">


                    <div class="col-sm-4">

                        {#{{ form("admin/finance/achievement", "method":"post", "autocomplete" : "on") }}#}
                        {#<div class="input-group">#}
                            {#<input type="text" name="admin_name" class="input-sm form-control" placeholder="搜索分站名称">#}
                            {#<span class="input-group-btn">#}
                                {#<button type="submit" class="btn btn-sm btn-primary"> 搜索</button>#}
                            {#</span>#}
                        {#</div>#}
                        {#{{ end_form() }}#}
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>分站名称</th>
                        <th>隶属区县</th>
                        <th>负责人</th>
                        <th>本月业绩</th>
                        <th>总业绩</th>
                        <th colspan="2">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        {% for finance in data %}
                            <tr>
                                <td>{{ finance['id'] }}</td>
                                <td><font color="blue">{{ finance['branch_name'] }}</font></td>
                                <td>{{ finance['provinces'] }}{{ finance['cities'] }}{{ finance['areas'] }}</td>
                                <td>{{ finance['branch_admin'] }}</td>
                                <td><font color="green">{{ finance['month_amount'] }} 元</font></td>
                                <td><font color="green">{{ finance['amount'] }} 元</font></td>
                                <td>{{ link_to("admin/finance/branch_achievement_all/"~finance['id'], "总业绩统计", 'class':'btn btn-primary btn-xs') }}</td>
                                <td>{{ link_to("admin/finance/branch_admin_achievement/"~finance['id'], "人员业绩统计", 'class':'btn btn-primary btn-xs') }}</td>
                            </tr>
                        {% endfor %}
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

