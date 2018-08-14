<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>查看登录设备信息</h5>
            </div>
            <div class="ibox-content">
                <style>.table tr th {width:140px;} .table tr{word-wrap:break-word}</style>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                登录设备信息
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <tr><th>ID</th><td>{{ data['id'] }}</td></tr>
                                    <tr><th>用户名</th><td>{{ data['user_name'] }}</td></tr>
                                    <tr><th>设备ID</th><td>{{ data['device_id'] }}</td></tr>
                                    <tr><th>设备类型</th><td>{{ data['type'] }}</td></tr>
                                    <tr><th>登录时间</th><td>{{ data['time'] }}</td></tr>
                                    <tr><th>TOKEN</th><td style="word-break:break-all">{{ data['token'] }}</td></tr>
                                    <tr><th>设备名称</th><td>{{ data['name'] }}</td></tr>
                                    <tr><th>型号名称</th><td>{{ data['device_name'] }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{ acl_button(["admin/devices/index", '返回', 'class':'btn btn-primary btn-xs']) }}
            </div>
        </div>
    </div>
</div>

