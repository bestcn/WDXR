<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>修改角色</h5></div>
            <div class="ibox-content">
                {{ form("method":"post", "autocomplete" : "off", "class" : "form-horizontal") }}
                <div class="form-group">
                    <label for="fieldName" class="col-sm-2 control-label">角色名称</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="csrf" id="csrf" value="{{ security.getToken() }}">
                        {{ form.render('name', ['readonly':'readonly']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldDescription" class="col-sm-2 control-label">角色描述</label>
                    <div class="col-sm-10">
                        {{ form.render('description', ["row":500]) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="fieldDescription" class="col-sm-2 control-label">权限分配</label>
                    <div class="col-sm-10">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th>权限资源名称</th>
                                <th>权限操作名称</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for resource in resources %}
                                <tr>
                                    <td><input type="checkbox" {% if(resource['is_check']) %}checked{% endif %} name="acl[]" value="{{ resource['resource_name'] }}||{{ resource['access_name'] }}" ></td>
                                    <td>{{ resource['resource_name'] }}</td>
                                    <td>{{ resource['access_name'] }}</td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        {{ form.render('submit') }}
                        {{ acl_button(['admin/acl/index', '返回', 'class':'btn btn-default']) }}
                    </div>
                </div>
                {{ end_form() }}
            </div>
        </div>
    </div>
</div>
