<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>修改版本信息版本</h5></div>
            <div class="ibox-content">
                <form action="/admin/setting/edit/{{ data.id }}" class="form-horizontal" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="fieldBranch_name" class="col-sm-2 control-label">版本号</label>
                        <div class="col-sm-4">
                            <input type="text" disabled="disabled" id="id" name="id" value="{{ data.id }}" class="form-control" placeholder="请填写版本号">
                        </div>

                        <!--<label for="fieldBranch_level" class="col-sm-3 control-label">时间</label>-->
                        <!--&lt;!&ndash;指定 date标记&ndash;&gt;-->
                        <!--<div class='input-group date' id='datetimepicker1'>-->
                        <!--<input type='text' name="time" class="form-control" />-->
                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>-->
                        <!--</div>-->
                    </div>

                    <div class="form-group">
                        <label for="fieldBranch_name" class="col-sm-2 control-label">下载地址</label>
                        <div class="col-sm-4">
                            <input type="text" id="url" name="url" value="{{ data.url }}" class="form-control" placeholder="请填写下载地址">
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="fieldBranch_level" class="col-sm-2 control-label">更新日志</label>
                        <div class="col-sm-4">
                            <!--<input type="text" id="log" name="log" class="form-control" placeholder="请填写更新日志">-->
                            <textarea id="log" name="log" class="form-control" placeholder="请填写更新日志">{{ data.log }}</textarea>
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

