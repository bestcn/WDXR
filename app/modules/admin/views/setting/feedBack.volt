<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>系统反馈</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    {#<div class="col-sm-2">#}
                        {#<a title="添加新版本" href="{{ url('admin/setting/new') }}" class="btn btn-default">添加新版本</a>#}
                    {#</div>#}


                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>时间</th>
                        <th>反馈</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for feedback in page.items %}
                            <tr>
                                <td>{{ feedback.id }}</td>
                                <td>{{ feedback.time }}</td>
                                <td>{{ feedback.content }}</td>
                                <td>
                                    {{ link_to("admin/setting/feedBackContent/"~feedback.id, "查看", 'class':'btn btn-primary btn-xs') }}
                                    {{ acl_button(['admin/setting/feedBackDelete/', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~feedback.id~'")']) }}
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
                            <li>{{ link_to("admin/setting/feedback", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/setting/feedback?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/setting/feedback?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/setting/feedback?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该反馈吗？')) {
            $.post("{{ url("admin/setting/feedBackDelete/") }}", {id:id});
            location.reload();
        }
    }
</script>