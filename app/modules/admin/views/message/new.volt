<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>添加新系统消息</h5></div>
            <div class="ibox-content">
                <form action="/admin/message/new" class="form-horizontal" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="fieldBranch_name" class="col-sm-2 control-label">标题</label>
                        <div class="col-sm-4">
                            <input type="text" id="title" name="title" class="form-control" placeholder="请填写标题">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fieldBranch_level" class="col-sm-2 control-label">内容</label>
                        <div class="col-sm-4">
                            <!--<input type="text" id="log" name="log" class="form-control" placeholder="请填写更新日志">-->
                            <textarea id="body" name="body" class="form-control" placeholder="请填写内容">

                            </textarea>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="保存" class="btn btn-primary">
                            <button onclick="history.go(-1);" class="btn btn-default" type="button">返回</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

