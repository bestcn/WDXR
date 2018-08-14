<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>添加新权限资源</h5></div>
            <div class="ibox-content">
                {{ form("method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
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
                {{ end_form() }}
            </div>
        </div>
    </div>
</div>
