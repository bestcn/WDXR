<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a href="javascript:void(0);">查看权限资源</a></li>
                {{ acl_menu('admin/acl/access/'~name, '权限操作管理') }}
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    {{ form("method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                    <div class="panel-body">
                    <div class="form-group">
                        <label for="fieldName" class="col-sm-2 control-label">权限资源名称</label>
                        <div class="col-sm-4">
                            {{ form.render('csrf', ['value': security.getToken()]) }}
                            {{ form.render('name') }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fieldDescription" class="col-sm-2 control-label">权限资源描述</label>
                        <div class="col-sm-10">
                            {{ form.render('description', ["row":500]) }}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            {{ form.render('submit') }}
                            {{ acl_button(['admin/acl/resource', '返回', 'class':'btn btn-default']) }}
                        </div>
                    </div>
                    </div>
                    {{ end_form() }}
                </div>
            </div>
        </div>
    </div>
</div>
