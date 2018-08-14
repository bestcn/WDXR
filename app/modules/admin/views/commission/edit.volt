<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0);">基本信息</a></li>
                <li class=""><a href="{{ url('admin/branchs/salesmans/'~id) }}">人员分配</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    {{ form("method":"post", "class" : "form-horizontal") }}
                    <div class="panel-body">
                        <input type="hidden" name="csrf" id="csrf" value="{{ security.getToken() }}"/>
                        {{ form.render('id') }}
                        <div class="form-group">
                            <label for="fieldBranch_name" class="col-sm-2 control-label">分公司名称</label>
                            <div class="col-sm-4">
                                {{ form.render('branch_name') }}
                            </div>

                            <label for="fieldBranch_level" class="col-sm-2 control-label">分公司等级</label>
                            <div class="col-sm-2">
                                {{ form.render('branch_level') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fieldBranch_area" class="col-sm-2 control-label">分公司地区</label>
                            <div class="col-sm-2">{{ form.render('provinces') }}</div>
                            <div class="col-sm-2">{{ form.render('cities') }}</div>
                            <div class="col-sm-2">{{ form.render('areas') }}</div>
                            <div class="col-sm-2">
                                {{ form.render('branch_area') }}
                            </div>

                        </div>

                        <div class="form-group">

                            <label for="fieldBranch_account" class="col-sm-2 control-label">收款账户</label>
                            <div class="col-sm-4">
                                {{ form.render('branch_account') }}
                            </div>
                            <label for="fieldBranch_bank" class="col-sm-2 control-label">开户行</label>
                            <div class="col-sm-2">
                                {{ form.render('branch_bank') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ form.render('branch_admin') }}
                            <label for="fieldBranch_admin" class="col-sm-2 control-label">分公司管理员</label>
                            <div class="col-sm-4">
                                {{ form.render('branch_admin_id') }}
                            </div>

                            <label for="fieldBranch_phone" class="col-sm-2 control-label">联系方式</label>
                            <div class="col-sm-2">
                                {{ form.render('branch_phone') }}
                            </div>
                        </div>

                        <div class="form-group">
                        <label for="fieldActive" class="col-sm-2 control-label">状态</label>
                        <div class="col-sm-2">
                            {{ form.render('branch_status') }}
                        </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {{ form.render('submit') }}
                                {{ acl_button(['admin/branchs/index', '返回', 'class':'btn btn-default']) }}
                            </div>
                        </div>
                    </div>
                    {{ end_form() }}
                </div>
            </div>
        </div>
    </div>
</div>
        {{ javascript_include('js/jquery-3.1.1.min.js') }}
        {{ javascript_include('js/select.js') }}