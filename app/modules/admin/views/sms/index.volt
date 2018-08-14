<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0);">短信日志列表</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                {{ form("admin/sms/index", "method":"post", "autocomplete" : "on") }}
                                <div class="input-group">
                                    <input type="text" name="phone" class="input-sm form-control" placeholder="根据电话号码搜索短信记录">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                                    </span>
                                </div>
                                {{ end_form() }}
                            </div>
                        </div>
                        <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>电话号码</th>
                            <th>使用模板</th>
                            <th>接收状态</th>
                            <th>发送时间</th>
                            <th>接收时间</th>
                            <th ></th>
                        </tr>
                        </thead>
                        <tbody>
                        {% if page.items is defined %}
                            {% for message in page.items %}
                                <tr>
                                    <td>{{ message.phone}}</td>
                                    <td>{{ message.template_id}}</td>
                                    <td>{% if message.report_status == "SUCCESS" %}成功{% elseif message.report_status == "FAIL" %}失败{% else %}错误{% endif %}</td>
                                    <td>{{ date('Y-m-d H:i:s',message.time) }}</td>
                                    <td>{{ message.user_receive_time }}</td>
                                    <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/sms/select/"~message.id, "查看", 'class':'btn btn-primary btn-xs']) }}
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
                                <li>{{ link_to("admin/sms/index", "第一页") }}</li>
                                <li class="paginate_button previous">{{ link_to("admin/sms/index?page="~page.before, "前一页") }}</li>
                                <li class="paginate_button next">{{ link_to("admin/sms/index?page="~page.next, "下一页") }}</li>
                                <li>{{ link_to("admin/sms/index?page="~page.last, "最后一页") }}</li>
                                </ul>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
