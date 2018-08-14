<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/branchs/edit/'~id, '基本信息']) }}</li>
                <li class="">{{ acl_button(['admin/branchs/salesmans/'~id, '人员分配']) }}</li>
                <li class="active">{{ acl_button(['admin/setting/edit_commission_list/'~id, '分公司提成设置']) }}</li>
            </ul>

            <div class="ibox-content">
                <form action="/admin/setting/edit_commission_list/{{ id }}" class="form-horizontal" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="fieldBranch_name" class="col-sm-2 control-label">公司名称</label>
                        <div class="col-sm-2">
                            <input type="text" id="name" name="name" value="{{ data.name }}" class="form-control" disabled="disabled">
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
                            <button onclick="location='/admin/branchs/index'" class="btn btn-default" type="button">返回</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

