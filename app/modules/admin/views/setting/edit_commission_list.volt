<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>修改提成比率</h5></div>
            <div class="ibox-content">
                <form action="/admin/setting/edit_commission_list/{{ id }}" class="form-horizontal" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="fieldBranch_name" class="col-sm-2 control-label">名称</label>
                        <div class="col-sm-2">
                            <input type="text" id="name" name="name" value="{{ data.name }}" class="form-control" disabled="disabled">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fieldBranch_name" class="col-sm-2 control-label">类型</label>
                        <div class="col-sm-2">
                            <input type="hidden" id="csrf" name="csrf" value="U2x3S25DcEdBUGdjTkJwZGw5T25HQT09">
                            <input type="text" id="type" name="type" value="{% if data.type == 1 %}业务员{% else %}合伙人{% endif %}" class="form-control" disabled="disabled">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fieldBranch_level" class="col-sm-2 control-label">提成比率</label>
                        <div class="col-sm-1">
                            <input type="text" id="ratio" name="ratio" value="{{ data.ratio }}" class="form-control">
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="hidden" id="id" name="id" value="{{ id }}">
                            <input type="submit" value="保存" class="btn btn-primary">
                            <button onclick="location='/admin/setting/commission_list'" class="btn btn-default" type="button">返回</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

