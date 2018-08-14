<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>查看企业信息</h5>
                <div class="pull-right">
                {{ acl_button(["admin/companys/info/"~company['id'], '详情', 'class':'btn btn-default btn-xs']) }}
                {{ acl_button(["admin/companys/contract/"~company['id'], '查看合同', 'class':'btn btn-default btn-xs']) }}
                {{ acl_button(["admin/companys/goBack/", '返回', 'class':'btn btn-default btn-xs']) }}
                </div>
            </div>
            <div class="ibox-content">
                <style>.table tr th {width:120px;}</style>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                企业工商信息
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <tr><th>统一社会信用代码</th><td>{{ info['licence_num'] }}</td></tr>
                                    <tr><th>企业名称</th><td>{{ company['name'] }}</td></tr>
                                    <tr><th>企业性质</th><td>{% if info['type'] == 1 %}非个体工商户{% elseif info['type'] == 2 %}个体工商户{% else %}未选择{% endif %}</td></tr>
                                    <tr><th>企业主行业分类</th><td>{{ info['top_category'] }}</td></tr>
                                    <tr><th>企业子行业分类</th><td>{{ info['sub_category'] }}</td></tr>
                                    <tr><th>企业地址</th><td>{{ info['full_address'] }}</td></tr>
                                    <tr><th>法定代表人</th><td>{{ info['legal_name'] }}</td></tr>
                                    <tr><th>法人身份证号</th><td>{{ info['idcard'] | default('无') }}</td></tr>
                                    <tr><th>营业期限</th><td>{{ info['period'] }}</td></tr>
                                    <tr><th>主营业务</th><td>{{ info['scope'] }}</td></tr>
                                    <tr><th>公司简介</th><td>{{ info['intro'] }}</td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                业务信息
                            </div>
                            <div class="panel-body">
                                {% if contract %}
                                <table class="table table-hover">
                                    {% if contract['status'] == 1 %}
                                        <tr><th>合同编号</th><td>{{ contract['contract_num'] }}</td></tr>
                                        <tr><th>服务期限</th><td>{{ contract['start_time'] }} 至 {{ contract['end_time'] }}</td></tr>
                                        <tr><th>票据状态</th><td>{{ contract['bill'] }}</td></tr>
                                        <tr><th>征信状态</th><td>{{ contract['report'] }}</td></tr>
                                        <tr><th>签订地址</th><td>{{ contract['location'] }}</td></tr>
                                    {% endif %}
                                    <tr><th>推荐人</th><td>{{ company['recommend'] }}</td></tr>
                                    <tr><th>管理人</th><td>{{ company['manager'] }}</td></tr>
                                    {#<tr><th>企业状态</th><td>{% if company['out_status'] == 0 %}正常{% elseif company['out_status'] == 1 %}过期作废{% else %}恶意行为{% endif %}</td></tr>#}
                                    {#<tr><th>状态原因</th><td>{{ company['out_info'] ? company['out_info'] : '无' }}</td></tr>#}
                                </table>
                                {% else %}
                                    无
                                {% endif %}
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                账号信息
                            </div>
                            <div class="panel-body">
                                    <table class="table table-hover">
                                        <tr><th>企业账号</th><td>{{ info['account'] }}</td></tr>
                                    </table>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                企业缴费信息
                            </div>
                            <div class="panel-body">
                                {% if payment %}
                                <table class="table table-hover">
                                    <tr><th>缴费方式</th><td>{{ payment['type_name'] }}</td></tr>
                                    <tr><th>企业级别</th><td>{{ level['level_name'] }}</td></tr>
                                    <tr><th>缴费状态</th><td>{{ payment['status_name'] }}</td></tr>
                                </table>
                                {% else %}
                                    无
                                {% endif %}
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                企业联系人信息
                            </div>
                            <div class="panel-body">
                                {% if info['contact_phone'] %}
                                <table class="table table-hover">
                                    <tr><th>联系方式</th><td>{{ info['contact_phone'] }}</td></tr>
                                    <tr><th>联系人</th><td>{{ info['contacts'] }}</td></tr>
                                    <tr><th>联系人职位</th><td>{{ info['contact_title'] }}</td></tr>
                                    <tr><th>邮政编码</th><td>{{ info['zipcode'] }}</td></tr>
                                </table>
                                {% else %}
                                    无
                                {% endif %}
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                开户行信息
                            </div>
                            <div class="panel-body">
                                {% if bank['number'] %}
                                    <table class="table table-hover">
                                        <tr><th>账户性质</th><td>{%if bank['bank_type'] == 1 %}对公账户{% else %}个人账户{% endif %}</td></tr>
                                        <tr><th>所属银行</th><td>{{ bank['bank'] | default('无') }}</td></tr>
                                        <tr><th>开户人</th><td>{{ bank['account'] | default(info['legal_name']) }}</td></tr>
                                        <tr><th>开户行</th><td>{{ bank['address'] | default('无') }}</td></tr>
                                        <tr><th>开户行地址</th><td>{{ bank['province'] | default('') }}{{ bank['city'] | default('') }}</td></tr>
                                        <tr><th>银行卡号</th><td>{{ bank['number'] | default('无') }}</td></tr>
                                    </table>
                                {% else %}
                                    无
                                {% endif %}
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                绩效银行卡信息
                            </div>
                            <div class="panel-body">
                                {% if bank['work_number'] %}
                                    <table class="table table-hover">
                                        <tr><th>所属银行</th><td>{{ bank['work_bank'] | default('无')}}</td></tr>
                                        <tr><th>开户人</th><td>{{ bank['work_account'] | default(info['legal_name']) }}</td></tr>
                                        <tr><th>开户行</th><td>{{ bank['work_address'] | default('无') }}</td></tr>
                                        <tr><th>开户行地址</th><td>{{ bank['work_province'] | default('') }}{{ bank['work_city'] | default('') }}</td></tr>
                                        <tr><th>银行卡号</th><td>{{ bank['work_number'] | default('无') }}</td></tr>
                                    </table>
                                {% else %}
                                    无
                                {% endif %}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
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
                    {% if bank['number'] %}
                        <div class="col-sm-4">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    银行卡照片
                                </div>
                                <div class="panel-body">
                                    {% for data in  bank['bankcard_photo'] %}
                                        <a href="{{ data }}" title="银行卡照片" data-gallery="">
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
                    {% if bank['work_number'] %}
                        <div class="col-sm-4">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    绩效银行卡
                                </div>
                                <div class="panel-body">
                                    {% for data in  bank['work_bankcard_photo'] %}
                                        <a href="{{ data }}" title="绩效银行卡照片" data-gallery="">
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

