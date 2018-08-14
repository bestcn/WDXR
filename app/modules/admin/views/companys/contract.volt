<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/companys/info/'~id~'/', '基本信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/payment/'~id, '缴费信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/business/'~id, '业务信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/user/'~id, '账号信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/bill/'~id, '票据信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/report/'~id, '征信报告']) }}</li>
                <li class="active">{{ acl_button(['admin/companys/contract/'~id, '合同信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/setting/'~id, '企业设置']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="form-horizontal">
                        <div class="panel-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    {% if name is defined %}
                                    {{ name }}
                                    {% else %}
                                        无
                                    {% endif %}
                                </div>
                                <div class="panel-body">
                                    {% if list is defined %}
                                    {% for items in list %}
                                        <table class="table table-hover">
                                            <tr>
                                                <th>合同编号</th><td>{{ items['contract_num'] }}</td>
                                                <td>
                                                    {{ acl_button(['admin/companys/generate/'~items['id']~'/'~id, '生成新合同', 'class':'btn btn-xs btn-primary pull-right']) }}
                                                </td>
                                            </tr>
                                            <tr><th>合同状态</th><td colspan="2"> {% if items['status']  == 0 %} 未使用 {% elseif items['status']  == 1 %}正常 {% elseif items['status']  == 2 %} 暂占 {% endif %}</td></tr>
                                            {% if items['files'] %}
                                                <tr><th>合同文件</th>
                                                    <td colspan="2">
                                                        <table class="table table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th>版本号</th>
                                                                <th>文件状态</th>
                                                                <th>生成时间</th>
                                                                <th>合同日志</th>
                                                                <th>合同地址</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            {% for item in items['files'] %}
                                                                <tr>
                                                                    <td>{{ item['version']}}</td>
                                                                    <td>{% if item['status']  == 1 %} 正常{% elseif item['status']  == 2 %}废弃 {% elseif item['status']  == 3 %} 删除 {% endif %}</td>
                                                                    <td>{{ item['create_at']}}</td>
                                                                    <td> {{ acl_button(['admin/companys/contractLog/'~item['id'], '合同日志']) }}</td>
                                                                    <td>{{ acl_button(["admin/companys/downloadContract/", '获取合同地址', 'href':'javascript:download("'~ item['filename'] ~'","'~ item['id'] ~'")', 'class':'btn btn-primary btn-xs']) }}</td>
                                                                </tr>
                                                            {% endfor %}
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            {% endif %}
                                            <tr>
                                            </tr>
                                        </table>
                                    {% endfor %}
                                    {% else %}
                                        无
                                    {% endif %}
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{#弹框查看信息#}
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true" style="margin-top: 200px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title" id="modal-title"></h5>
            </div>
        </div>
    </div>
</div>

<button id="click" style="display: none" class="btn btn-primary btn-xs" data-toggle='modal' data-target='#myModal5'>点击弹出</button>


<script type="text/javascript">
    function download(dst,file_id) {
        $.ajax({
            type : "POST",
            url : "{{ url('admin/companys/downloadContract') }}",
            data : {dst:dst},
            success : function(data) {
                $("#modal-title").html("<a href='"+data+"' onclick=\"downloadLog('"+file_id+"')\" target=\"_blank\" >点击下载</a>");
                $("#click").click();
            }
        });
    }
    function downloadLog(file_id) {
        $.ajax({
            type : "POST",
            url : "{{ url('admin/companys/downloadLog') }}",
            data : {fileId:file_id},
            success : function(data) {
                if(data!=="SUCCESS"){
                    alert(data)
                }
            }
        });

    }

</script>
