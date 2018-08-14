<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                {{ acl_menu('admin/news/index/', '所有消息列表') }}
                <li class="active"><a href="javascript:void(0);">未读消息列表</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                {{ form("admin/news/unread", "method":"post", "autocomplete" : "on") }}
                                <div class="input-group">
                                    <input type="text" name="name" class="input-sm form-control" placeholder="搜索消息">
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
                            <th>消息标题</th>
                            <th>创建时间</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {% if page.items is defined %}
                            {% for message in page.items %}
                                <tr>
                                    <td>{{ message.title }}</td>
                                    <td>{{ date('Y-m-d',message.create_time) }}</td>
                                    <td>
                                        <span  class="pull-right"  >
                                        {{ acl_button(["admin/news/new/"~message.id, "查看", 'class':'btn btn-primary btn-xs']) }}
                                        {{ acl_button(["admin/news/delete/", '删除', 'href':'javascript:del('~ message.id ~')', 'class':'btn btn-danger btn-xs']) }}
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
                                <li>{{ link_to("admin/news/unread", "第一页") }}</li>
                                <li class="paginate_button previous">{{ link_to("admin/news/unread?page="~page.before, "前一页") }}</li>
                                <li class="paginate_button next">{{ link_to("admin/news/unread?page="~page.next, "下一页") }}</li>
                                <li>{{ link_to("admin/news/unread?page="~page.last, "最后一页") }}</li>
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
    if(confirm('确认要删除该消息吗？')) {
        $.post("{{ url("admin/news/delete/") }}", {id:id});
        location.reload();
    }
}
</script>