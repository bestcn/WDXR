<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>人员业绩统计</h5>
            </div>
            <div class="ibox-content">
                <div class="row">


                    <div class="col-sm-4">

                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>业务员</th>
                        <th>总业绩</th>
                        <th>总提成</th>
                        <th>本月业绩</th>
                        <th>本月提成</th>
                        {#<th>操作</th>#}
                    </tr>
                    </thead>
                    <tbody>
                    {% for finance in data %}
                        <tr>
                            <td>{{ finance['admin_name'] }}</td>
                            <td><font color="green">{{ finance['money'] }} 元</font></td>
                            <td><font color="green">{{ finance['commission'] }} 元</font></td>
                            <td><font color="blue">{{ finance['month_money'] }} 元</font></td>
                            <td><font color="blue">{{ finance['month_commission'] }} 元</font></td>
                            <td>{{ acl_button(["admin/finance/admin_achievement_info/"~finance['admin_name'], "查看详情", 'class':'btn btn-primary btn-xs']) }}</td>
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
                <button onclick="location='/admin/finance/branch_achievement';" class="btn btn-default" type="button">返回</button>
            </div>
        </div>
    </div>
</div>

