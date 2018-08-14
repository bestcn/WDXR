<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>系统消息</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        <a title="添加新消息" href="{{ url('admin/message/new') }}" class="btn btn-default">添加新消息</a>
                    </div>


                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>标题</th>
                        <th>内容</th>
                        <th>时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for message in page.items %}
                            <tr>
                                <td>{{ message.id }}</td>
                                <td>{{ message.title }}</td>
                                <td>{{ message.body }}</td>
                                <td>{{ message.time }}</td>
                                <td>
                                {{ acl_button(['admin/message/push/', '推送', 'class':'btn btn-primary btn-xs', 'href':'javascript:push("'~message.id~'")']) }}
                                {{ acl_button(['admin/message/delete/', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~message.id~'")']) }}
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
                            <li>{{ link_to("admin/message/index", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/message/index?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/message/index?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/message/index?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该消息吗？')) {
            $.post("{{ url("admin/message/delete/") }}", {id:id});
            location.reload();
        }
    }
    function push(id){
        if(confirm('确认要推送给所有用户吗？')) {
            $.post("{{ url("admin/message/push/") }}", {id:id});
            location.reload();
        }
    }
</script>