<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>版本管理</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        <a title="添加新版本" href="{{ url('admin/setting/new') }}" class="btn btn-default">添加新版本</a>
                    </div>


                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>版本号</th>
                        <th>时间</th>
                        <th>下载地址</th>
                        <th>更新日志</th>
                        <th>管理员</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                    {% for version in page.items %}
                    <tr>
                        <td>{{ version.id }}</td>
                        <td>{{ version.time }}</td>
                        <td>{{ version.url }}</td>
                        <td>{{ version.log }}</td>
                        <td>{{ version.admin_id }}</td>
                        <td>{{ acl_button(["admin/setting/edit/"~version.id, '修改', 'class':'btn btn-primary btn-xs']) }}</td>
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
                            <li>{{ link_to("admin/setting/index", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/setting/index?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/setting/index?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/setting/index?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
