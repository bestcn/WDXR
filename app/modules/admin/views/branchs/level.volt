<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <ul class="nav nav-tabs">
                    <li class="">{{ acl_button(['admin/branchs/index', '分站管理']) }}</li>
                    <li class="active">{{ acl_button(['admin/branchs/level', '分公司等级设置']) }}</li>
                    <li class="">{{ acl_button(['admin/setting/branchs_commission', '分公司业绩设置']) }}</li>
                </ul>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2">
                        {{ acl_button(["admin/branchs/add_level", '添加级别', 'class':'btn btn-default']) }}
                    </div>

                    <div class="col-sm-4">
                        {{ form("admin/branchs/level", "method":"post", "autocomplete" : "on") }}
                        <div class="input-group">
                            <input type="text" name="level_name" class="input-sm form-control" placeholder="搜索级别名称">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                        {{ end_form() }}
                    </div>

                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>级别名称</th>
                        <th>状态</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% if page.items is defined %}
                    {% for level in page.items %}
                    <tr>
                        <td>{{ level.id }}</td>
                        <td>{{ level.level_name }}</td>
                        <td>{% if level.level_status %} <font color="green">开启</font> {% else %}<font color="red">禁用</font> {% endif %}</td>

                        <td>
                            <span class="pull-right">
                                {{ acl_button(["admin/branchs/level_edit/"~level.id, '查看', 'class':'btn btn-primary btn-xs']) }}
                                {{ acl_button(['admin/branchs/level_delete/', '删除', 'class':'btn btn-danger btn-xs', 'href':'javascript:del("'~level.id~'")']) }}
                            </span>
                        </td>


                    </tr>

                    </tbody>
                    {% endfor %}
                    {% endif %}
                </table>
                <div class="row" >
                    <div class="col-sm-5">
                        {{ page.current~"/"~page.total_pages }}
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li>{{ link_to("admin/branchs/level", "第一页") }}</li>
                            <li class="paginate_button previous">{{ link_to("admin/branchs/level?page="~page.before, "前一页") }}</li>
                            <li class="paginate_button next">{{ link_to("admin/branchs/level?page="~page.next, "下一页") }}</li>
                            <li>{{ link_to("admin/branchs/level?page="~page.last, "最后一页") }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function del(id) {
        if(confirm('确认要删除该级别吗？')) {
            $.post("{{ url("admin/branchs/level_delete/") }}", {id:id});
            location.reload();
        }
    }
</script>