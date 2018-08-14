<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="form-horizontal">
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                   合同文件日志
                                </div>

                                <div class="panel-body">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>合同操作</th>
                                            <th>操作时间</th>
                                            <th>操作人</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {% for items in log %}
                                        <tr>
                                            <td>{{ items['type_name'] }}</td>
                                            <td>{{ items['create_at'] }}</td>
                                            <td> {{ items['device_name'] }}</td>
                                        </tr>
                                        {% endfor %}
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button onclick="history.go(-1);" class="btn btn-default" type="button">返回</button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

