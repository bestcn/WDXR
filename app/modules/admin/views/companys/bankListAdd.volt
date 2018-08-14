<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>添加银行</h5></div>
            <div class="ibox-content">
                <form action="{{ url('admin/companys/bankListAdd') }}" class="form-horizontal" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="fieldBranch_name" class="col-sm-2 control-label">银行名称</label>
                        <div class="col-sm-4">
                            <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="请填写银行名称">
                        </div>

                        <label for="fieldBranch_level" class="col-sm-2 control-label">状态</label>
                        <div class="col-sm-4">
                            <select id="bank_status" name="bank_status" class="form-control">
                                <option value="0">禁用</option>
                                <option value="1">开启</option>
                            </select>
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
