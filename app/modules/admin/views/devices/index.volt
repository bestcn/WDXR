<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0);">设备登录列表</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                {{ form("admin/devices/index", "method":"post", "autocomplete" : "on") }}
                                <div class="input-group">
                                    <input type="text" name="name" class="input-sm form-control" placeholder="按设备名称搜索">
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
                            <th>用户名</th>
                            <th>设备类别</th>
                            <th>登录时间</th>
                            <th>设备名称</th>
                            <th>型号名称</th>
                            <th ></th>
                        </tr>
                        </thead>
                        <tbody>

                        {% if page.items is defined %}
                            {% for devices in page.items%}
                                <tr>
                                    <td>{{ devices['user_name'] }}</td>
                                    <td>{{ devices['type'] }}</td>
                                    <td>{{ devices['time']}}</td>
                                    <td>{{ devices['name'] }}</td>
                                    <td>{{ devices['device_name'] }}</td>
                                    <td>
                                        <span  class="pull-right"  >
                                            {{ acl_button(["admin/devices/view/"~devices['id'], '查看', 'class':'btn btn-primary btn-xs']) }}
                                            {{ acl_button(["admin/devices/downline/", '下线', 'href':'javascript:del('~ devices['id'] ~')', 'class':'btn btn-danger btn-xs']) }}
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
                                    <li>{{ link_to("admin/devices/index", "第一页") }}</li>
                                    <li class="paginate_button previous">{{ link_to("admin/devices/index?page="~page.before, "前一页") }}</li>
                                    <li class="paginate_button next">{{ link_to("admin/devices/index?page="~page.next, "下一页") }}</li>
                                    <li>{{ link_to("admin/devices/index?page="~page.last, "最后一页") }}</li>
                                </ul>
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function del(id) {
    if(confirm('确认要下线该设备吗？')) {
        $.post("{{ url('admin/devices/downline/') }}", {id:id}, function (res) {
            location.reload();
        });
    }
}
</script>