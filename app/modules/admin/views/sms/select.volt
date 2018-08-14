<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>查看详细记录</h5>
            </div>
            <div class="ibox-content">
                <style>.table tr th {width:120px;}</style>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                短信日志

                            </div>

                            <div class="panel-body">
                                <table class="table table-hover">
                                    <tr><th>ID</th><td>{{ log.id}}</td></tr>
                                    <tr><th>发送状态码</th><td>{{ log.result}}</td></tr>
                                    <tr><th>错误消息</th><td>{% if log.error == 'OK' %}无{% else %}{{ log.error}}{% endif %}</td></tr>
                                    <tr><th>本次发送ID</th><td>{{ log.sid}}</td></tr>
                                    <tr><th>电话号码</th><td>{{ log.phone}}</td></tr>
                                    <tr><th>短信计费条数</th><td>{{ log.fee}}</td></tr>
                                    <tr><th>发送时间</th><td>{{ date('Y-m-d H:i:s',log.time) }}</td></tr>
                                    <tr><th>使用模板</th><td>{{ log.template_id}}</td></tr>
                                    <tr><th>传递参数</th><td>{{ log.params}}</td></tr>
                                    <tr><th>用户接收时间</th><td>{{ log.user_receive_time}}</td></tr>
                                    <tr><th>国家码</th><td>{{ log.nationcode}}</td></tr>
                                    <tr><th>接收手机号</th><td>{{ log.mobile}}</td></tr>
                                    <tr><th>短信接收状态</th><td>{{ log.report_status}}</td></tr>
                                    <tr><th>接收短信状态码</th><td>{{ log.errmsg}}</td></tr>
                                    <tr><th>接收短信状态描述</th><td>{{ log.description}}</td></tr>
                                </table>
                            </div>

                        </div>
                        <button onclick="history.go(-1);" class="btn btn-default" type="button">返回</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
