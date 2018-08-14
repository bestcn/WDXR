<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/branchs/edit/'~id, '基本信息']) }}</li>
                <li class="active">{{ acl_button(['admin/branchs/salesmans/'~id, '人员分配']) }}</li>
                <li class="">{{ acl_button(['admin/setting/edit_branchs_commission_list/'~id, '分公司提成设置']) }}</li>
                <li class="">{{ acl_button(['admin/branchs/achievement/'~id, '财务信息']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="ibox">
                                    <div class="ibox-title">
                                        <h5>点击人员以分配</h5>
                                    </div>
                                    <div class="ibox-content">
                                        <input hidden="hidden" id="id" name="id" value="{{ id }}"/>
                                        <select class="form-control dual_select" multiple="multiple">
                                            {% for item in admins %}
                                            <option {% if item['selected'] == 1 %}selected="selected"{% endif %} value="{{ item['id'] }}">{{ item['name'] }}</option>
                                            {% endfor %}
                                        </select>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--DH-->
                        <div class="ibox-title  back-change" style="display: none;">
                            <input  type="checkbox" class="js-switch"  />
                            <input  type="checkbox" class="js-switch_2"  />
                            <input  type="checkbox" class="js-switch_3"  />
                            <input  type="checkbox" class="js-switch_4"  />
                        </div>
                        <!--DH-->

                        <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button onclick="location='{{ url('admin/branchs/index') }}';" class="btn btn-default" type="button">返回</button>
                        </div>
                    </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!--多选转换框插件-->
{{ javascript_include('js/dua/bootstrap-datepicker.js') }}
{{ javascript_include('js/dua/switchery.js') }}
{{ javascript_include('js/dua/icheck.min.js') }}
{{ javascript_include('js/dua/bootstrap-colorpicker.min.js') }}
{{ javascript_include('js/dua/clockpicker.js') }}
{{ javascript_include('js/dua/cropper.min.js') }}
{{ javascript_include('js/dua/moment.min.js') }}
{{ javascript_include('js/dua/daterangepicker.js') }}
{{ javascript_include('js/dua/select2.full.min.js') }}
{{ javascript_include('js/dua/jquery.bootstrap-touchspin.min.js') }}
{{ javascript_include('js/dua/bootstrap-tagsinput.js') }}
{{ javascript_include('js/dua/jquery.bootstrap-duallistbox.js') }}
<!--多选转换框插件-->
<script>
    $(document).ready(function(){
        $('.dual_select').bootstrapDualListbox({
            selectorMinimalHeight: 160
        });

    });
</script>