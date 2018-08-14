<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>新建职位</h5></div>
            <div class="ibox-content">
                {{ form("admin/positions/new", "method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldName" class="col-sm-2 control-label">用户名</label>
                    <div class="col-sm-4">
                        {{ form.render('csrf', ['value': security.getToken()]) }}
                        {{ form.render('name') }}
                    </div>

                    <label for="fieldStatus" class="col-sm-2 control-label">状态</label>
                    <div class="col-sm-2">
                        {{ form.render('status') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldOrderBy" class="col-sm-2 control-label">排序</label>
                    <div class="col-sm-4">
                        {{ form.render('orderBy') }}
                    </div>

                    <label for="fieldDepartmentId" class="col-sm-2 control-label">所属部门</label>
                    <div class="col-sm-2">
                        {{ form.render('department_id') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldDescription" class="col-sm-2 control-label">分配角色</label>
                    <div class="col-sm-10">
                        {% for role in roles %}
                            <label class="checkbox-inline" for="{{ role.getName() }}">
                                <input id="{{ role.getName() }}" type="checkbox" name="role[]" value="{{ role.getName() }}">
                                {{ role.getName() }}
                            </label>
                        {% endfor %}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldDescription" class="col-sm-2 control-label">描述</label>
                    <div class="col-sm-10">
                        {{ form.render('description', ["row":500]) }}
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ form.render('submit') }}
                        <button onclick="history.go(-1);" class="btn btn-default" type="button">返回</button>
                    </div>
                </div>
                {{ end_form() }}
            </div>
        </div>
    </div>
</div>
