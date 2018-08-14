{{ stylesheet_link("css/plugins/dataTables/datatables.min.css") }}
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>业务员客户统计</h5>
            </div>
            <div class="ibox-content">

                <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover admin-list" >
                    <thead>
                    <tr>
                        <th>业务员编号</th>
                        <th>业务员</th>
                        <th>直推客户数量</th>
                        <th>总客户数量</th>
                        <th>总业绩</th>
                        <th>状态</th>
                        <th><span class="pull-right">操作</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    {% for admin in admins %}
                    <tr>
                        <td>{{ admin['admin_id'] }}</td>
                        <td>{{ admin['name'] }}</td>
                        <td>{{ admin['d_count'] }}</td>
                        <td>{{ admin['all_count'] }}</td>
                        <td>{{ admin['achievement'] }}</td>
                        <td>
                            {% if admin['status'] == 1 %}
                                正常
                            {% else %}
                                禁用
                            {% endif %}
                        </td>
                        <td>
                            <span class="pull-right">
                                <a href="{{ url('admin/stat/view_admin_company/'~admin['admin_id']) }}">
                                    查看客户
                                </a>
                            </span>
                        </td>
                    </tr>
                    {% endfor %}
                    </tbody>
                </table>
                </div>

            </div>
        </div>
    </div>
</div>


{{ javascript_include("js/plugins/dataTables/datatables.min.js") }}
<script>
    $(document).ready(function() {
        $('.admin-list').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgtip',
            language: {
                "lengthMenu": "每页 _MENU_ 条记录",
                "zeroRecords": "没有找到记录",
                "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
                "infoEmpty": "无记录",
                "infoFiltered": "(从 _MAX_ 条记录过滤)",
                "search":"搜索"
            },
            order:[[3,'desc']],
            buttons: [
                { extend: 'copy'},
                {extend: 'excel', title: '业务员业绩统计'},
                {extend: 'print',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]
        });
    });
</script>