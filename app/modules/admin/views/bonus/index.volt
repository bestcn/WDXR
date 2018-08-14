<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    奖金制度表
                </h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        {{ acl_button(["admin/bonus/new", '添加制度', 'class':'btn btn-default']) }}
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>推荐人类型</th>
                        <th>新客户类型</th>
                        <th>第1个</th>
                        <th>第2个</th>
                        <th>第3个</th>
                        <th>第4个</th>
                        <th>第5个</th>
                        <th>第6个</th>
                        <th>第7个</th>
                        <th>第8个</th>
                        <th>第9个</th>
                        <th>第10个</th>
                        <th>第11个</th>
                        <th>第12个</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                        {% for admin in page.items %}
                            <tr>
                                <td>{{ admin.id }}</td>
                                <td>{% if admin.recommend == 1 %}事业合伙人{% else %}普惠{% endif %}</td>
                                <td>{% if admin.customer == 1 %}事业合伙人{% else %}普惠{% endif %}</td>
                                <td>{{ admin.first }}</td>
                                <td>{{ admin.second }}</td>
                                <td>{{ admin.third }}</td>
                                <td>{{ admin.fourth }}</td>
                                <td>{{ admin.fifth }}</td>
                                <td>{{ admin.sixth }}</td>
                                <td>{{ admin.seventh }}</td>
                                <td>{{ admin.eighth }}</td>
                                <td>{{ admin.ninth }}</td>
                                <td>{{ admin.tenth }}</td>
                                <td>{{ admin.eleventh }}</td>
                                <td>{{ admin.twelfth }}</td>
                                <td>
                                    <span class="pull-right">
                                        {{ acl_button(["admin/bonus/edit/"~admin.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                        {{ acl_button(["admin/bonus/delete/", '删除', 'href':'javascript:del('~ admin.id ~')', 'class':'btn btn-danger btn-xs']) }}
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
                            <li>{{ link_to("admin/bonus/index", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/bonus/index?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/bonus/index?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/bonus/index?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该奖金制度吗？')) {
            $.post("{{ url("admin/bonus/delete/") }}", {id:id});
            location.reload();
        }
    }
</script>