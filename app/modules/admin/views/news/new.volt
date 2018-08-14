            <div class="col-lg-9 animated fadeInRight " style="width:100%;">
            <div class="mail-box-header">
                <div class="pull-right tooltip-demo">
                    <a href="javascript:;" onclick="history.go(-1);" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="返回"><i class="fa fa-reply"></i>返回</a>
                    {{ acl_button(["admin/news/delete/", '<i class="fa fa-trash-o"></i>', 'href':'javascript:del('~ message["id"] ~')', 'class':'btn btn-white btn-sm']) }}
                </div>
                <h2>
                    {{message['title']}}
                </h2>
                <div class="mail-tools tooltip-demo m-t-md">
                    <h5>
                        <span class="pull-right font-normal">创建时间：{{date('Y-m-d H:i:s',message['create_time'])}}</span>
                        {% if message['name']!="" %}<span class="font-normal">查看人：</span>{{ message['name'] }}{% endif %}
                        {% if message['select_time'] != 0 %}<span class="font-normal">查看时间：</span>{{date('Y-m-d H:i:s',message['select_time'])}}{% endif %}
                    </h5>
                </div>
            </div>
                <div class="mail-box">
                    <div class="mail-body">
                        {{message['content']}}
                    </div>
                    <div class="mail-body text-right tooltip-demo">
                        <a class="btn btn-sm btn-white" href="javascript:;" onclick="history.go(-1);" ><i class="fa fa-reply"></i>返回</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
<script type="text/javascript">
function del(id) {
    if(confirm('确认要删除该消息吗？')) {
        $.post("{{ url("admin/news/delete/") }}", {id:id});
        window.location.href="/admin/news/index";
    }
}
</script>