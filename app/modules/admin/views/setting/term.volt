<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="{{ url('admin/setting/term') }}">票据审核期限设置</a></li>
                    <li class=""><a href="{{ url('admin/setting/bill_term') }}">企业票据期限列表</a></li>
                </ul>
            </div>

            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        <a title="添加设置" href="{{ url('admin/setting/newterm') }}" class="btn btn-default">添加设置</a>
                    </div>

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>缴费类型</th>
                        <th>审核期限</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                    {% for term in page.items %}
                    <tr>
                        <td>{{ term.id }}</td>
                        <td>{% if term.payment == 1 %}转账{% elseif term.payment == 2 %}现金{% elseif term.payment == 3 %}POS{% else %}贷款{% endif %}</td>
                        <td>{{ term.term }} {% if term.type == 0 %} 天 {% elseif term.type == 1 %} 个月 {% else %} 年 {% endif %}</td>
                        <td>{{ date('Y-m-d H:i:s',term.time) }}</td>
                        <td>{{ acl_button(["admin/setting/editterm/"~term.id, "查看", 'class':'btn btn-primary btn-xs']) }}</td>
                        <td>
                            {{ acl_button(['admin/setting/deleteterm/', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~term.id~'")']) }}
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
                            <li>{{ link_to("admin/setting/term", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/setting/term?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/setting/term?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/setting/term?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该规则吗？')) {
            $.post("{{ url("admin/setting/deleteterm/") }}", {id:id});
            location.reload();
        }
    }
</script>