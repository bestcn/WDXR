{{ stylesheet_link("css/plugins/jasny/jasny-bootstrap.min.css") }}
{{ javascript_include("js/plugins/jasny/jasny-bootstrap.min.js") }}

<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active">{{ acl_button(['admin/companys/info/'~id~'/', '基本信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/payment/'~id, '缴费信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/business/'~id, '业务信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/user/'~id, '账号信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/bill/'~id, '票据信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/report/'~id, '征信报告']) }}</li>
                <li class="">{{ acl_button(['admin/companys/contract/'~id, '合同信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/setting/'~id, '企业设置']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        企业工商信息
                                    </div>
                                    <div class="panel-body">
                                        <style type="text/css">
                                            .table tr th {width:120px;}
                                            .table tr td { text-align: right }
                                        </style>
                                        <table class="table table-hover">
                                            {% if company is empty %}
                                                <tr><td>无</td></tr>
                                            {% else %}
                                                <tr><th>统一社会信用代码</th><td class="data-editable" data-attr="company_info-licence_num-{{ company['info_id'] }}">{{ info['licence_num'] }}</td></tr>
                                                <tr><th>企业名称</th><td class="data-editable" data-attr="companys-name-{{ company['id'] }}">{{ company['name'] }}</td></tr>
                                                <tr><th>企业性质</th><td class="data-editable" data-select-url="{{ url('admin/companys/get_company_type') }}" data-attr="company_info-type-{{ company['info_id'] }}">{% if info['type'] == 1 %}非个体工商户{% elseif info['type'] == 2 %}个体工商户{% else %}未选择{% endif %}</td></tr>
                                                <tr><th>企业主行业分类</th><td>{{ info['top_category'] }}</td></tr>
                                                <tr><th>企业子行业分类</th><td>{{ info['sub_category'] }}</td></tr>
                                                <tr><th>企业地址</th><td>{{ info['full_address'] }}</td></tr>
                                                <tr><th>法定代表人</th><td class="data-editable" data-attr="company_info-legal_name-{{ company['info_id'] }}">{{ info['legal_name'] }}</td></tr>
                                                <tr><th>法人身份证号</th><td class="data-editable" data-attr="company_info-idcard-{{ company['info_id'] }}">{{ info['idcard'] | default('无') }}</td></tr>
                                                <tr><th>营业期限</th><td class="data-editable" data-attr="company_info-period-{{ company['info_id'] }}">{{ info['period'] }}</td></tr>
                                                <tr><th>主营业务</th><td class="data-editable" data-attr="company_info-scope-{{ company['info_id'] }}">{{ info['scope'] }}</td></tr>
                                                <tr><th>公司简介</th><td class="data-editable" data-attr="company_info-intro-{{ company['info_id'] }}">{{ info['intro'] | default('无') }}</td></tr>
                                            {% endif %}
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        企业联系人信息
                                    </div>
                                    <div class="panel-body">
                                        {% if info is empty %}
                                            无
                                        {% else %}
                                            <table class="table table-hover">
                                                <tr><th>联系方式</th><td class="data-editable" data-attr="company_info-contact_phone-{{ company['info_id'] }}">{{ info['contact_phone'] }}</td></tr>
                                                <tr><th>联系人</th><td class="data-editable" data-attr="company_info-contacts-{{ company['info_id'] }}">{{ info['contacts'] }}</td></tr>
                                                <tr><th>联系人职位</th><td class="data-editable" data-attr="company_info-contact_title-{{ company['info_id'] }}">{{ info['contact_title'] }}</td></tr>
                                                <tr><th>邮政编码</th><td class="data-editable" data-attr="company_info-zipcode-{{ company['info_id'] }}">{{ info['zipcode'] }}</td></tr>
                                            </table>
                                        {% endif %}
                                    </div>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="审核内容包括企业工商信息、联系人信息、业务信息及相关证照"></i>
                                        审核信息
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            {% if company is defined %}
                                            <tr>
                                                <th>基本状态</th>
                                                <td>
                                                    {{ company['status'] == '1' ? '正常' : '未启用' }}
                                                </td>
                                            </tr>
                                            {% else %}
                                                无
                                            {% endif %}
                                            {% if info is not empty %}
                                                <tr>
                                                    <th>审核状态</th>
                                                    <td>{{ info['audit_name'] }}</td>
                                                </tr>
                                                {% if verify  %}
                                                    <tr><th>申请时间</th><td>{{ verify.apply_time ? date('Y-m-d H:i:s', verify.apply_time) : '无' }}</td></tr>
                                                    <tr>
                                                        <th>
                                                            <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="审核ID：{{ verify.id }}"></i>
                                                            审核时间
                                                        </th>
                                                        <td>{{ verify.verify_time ? date('Y-m-d H:i:s', verify.verify_time) : '无' }}</td>
                                                    </tr>
                                                    <tr><th>申请人</th><td>{{ verify.device_name }}</td></tr>
                                                    <tr>
                                                        <th>所属业务员</th>
                                                        <td>
                                                            {{ company['admin_name'] }}
                                                            {% if company['admin_name'] is not verify.admin_name %}
                                                            <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="申请时所属业务员：{{ verify.admin_name }}"></i>
                                                            {% endif %}
                                                        </td>
                                                    </tr>
                                                    <tr><th>审核人</th><td>{{ verify.auditor }}</td></tr>
                                                {% endif %}
                                            {% endif %}
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {% if info is defined %}
                            {% if info['licence'] %}
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            企业营业执照
                                        </div>
                                        <div class="panel-body">
                                            {% for data in  info['licence'] %}
                                                <a href="{{ data }}" title="营业执照" data-gallery="">
                                                    <img width="100%" src="{{ data }}">
                                                </a>
                                            {% endfor %}
                                        </div>
                                        <div class="panel-footer">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">更换</span>
                                                    <span class="fileinput-exists">修改</span>
                                                    <input type="file" name="..."/>
                                                </span>
                                                <span class="fileinput-filename"></span>
                                                <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {% endif %}
                            {% if info['photo'] %}
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            法人手持身份证照片
                                        </div>
                                        <div class="panel-body">
                                            {% for data in  info['photo'] %}
                                                <a href="{{ data }}" title="法人手持身份证照片" data-gallery="">
                                                    <img width="100%" src="{{ data }}">
                                                </a>
                                            {% endfor %}
                                        </div>
                                    </div>
                                </div>
                            {% endif %}
                            {% if info['idcard_up'] %}
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            法人身份证（正面）
                                        </div>
                                        <div class="panel-body">
                                            {% for data in  info['idcard_up'] %}
                                                <a href="{{ data }}" title="法人身份证（正面）" data-gallery="">
                                                    <img width="100%" src="{{ data }}">
                                                </a>
                                            {% endfor %}
                                        </div>
                                    </div>
                                </div>
                            {% endif %}
                            {% if info['idcard_down'] %}
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            法人身份证（反面）
                                        </div>
                                        <div class="panel-body">
                                            {% for data in  info['idcard_down'] %}
                                                <a href="{{ data }}" title="法人身份证（反面）" data-gallery="">
                                                    <img width="100%" src="{{ data }}">
                                                </a>
                                            {% endfor %}
                                        </div>
                                    </div>
                                </div>
                            {% endif %}
                            {% if info['credit_code'] %}
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            机构信用代码证
                                        </div>
                                        <div class="panel-body">
                                            {% for data in  info['credit_code'] %}
                                                <a href="{{ data }}" title="机构信用代码证" data-gallery="">
                                                    <img width="100%" src="{{ data }}">
                                                </a>
                                            {% endfor %}
                                        </div>
                                    </div>
                                </div>
                            {% endif %}
                            {% if info['account_permit'] %}
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            开户许可证
                                        </div>
                                        <div class="panel-body">
                                            {% for data in info['account_permit'] %}
                                                <a href="{{ data }}" title="开户许可证" data-gallery="">
                                                    <img width="100%" src="{{ data }}">
                                                </a>
                                            {% endfor %}
                                        </div>
                                    </div>
                                </div>
                            {% endif %}
                            {% if info['shop_img'] %}
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            门头照片
                                        </div>
                                        <div class="panel-body">
                                            {% for data in info['shop_img'] %}
                                                <a href="{{ data }}" title="门头照片" data-gallery="">
                                                    <img width="100%" src="{{ data }}">
                                                </a>
                                            {% endfor %}
                                        </div>
                                    </div>
                                </div>
                            {% endif %}
                            {% endif %}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <a class="xuanzhuan">旋转</a>
    <a class="fangda">放大</a>
    <a class="suoxiao">缩小</a>
    <ol class="indicator"></ol>
</div>

