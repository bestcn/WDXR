{{ stylesheet_link("css/plugins/steps/jquery.steps.css") }}
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h5>企业信息申请</h5>
            </div>
            <div class="ibox-content">
                <form id="form" method="post" class="wizard-big" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" id="csrf" value="{{ security.getToken() }}">
                    <input type="hidden" name="id" value="{{ company_id }}">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>企业名称 *</label>
                                {{ company.name  }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>企业性质 *</label>
                                {% if info.type ==1 %}有限公司{% elseif info.type ==2  %}个体工商户 {% else %}错误{% endif %}
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>统一社会信用代码 *</label>
                                {{ info.licence_num }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>法人代表 *</label>
                                {{ info.legal_name }}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>所在省份 *</label>
                                {{ address['province'] }}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>城市 *</label>
                                {{ address['city'] }}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>区县 *</label>
                                {{ address['district'] }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>详细地址 *</label>
                                {{ info.address }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>营业期限 *</label>
                                {{ info.period }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>驳回原因 *</label>
                                {{ verify.remark }}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>经营范围 *</label>
                                {{ info.scope }}
                            </div>
                        </div>

                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>联系人 *</label>
                            {{ form.render('contacts') }}
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>联系人手机号 *</label>
                            {{ form.render('contact_phone') }}
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>联系人岗位 *</label>
                            {{ form.render('contact_title') }}
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>邮编 *</label>
                            {{ form.render('zipcode') }}
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>主行业 *</label>
                            {{ form.render('top_category') }}
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>子行业 *</label>
                            {{ form.render('sub_category') }}
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>营业执照 *</label>
                                {{ form.render('licence') }}
                                <input type="hidden" name="licence" id="licence_picture" value="{{ info.licence }}">
                                {% for data in  picture['licence'] %}
                                    <a href="{{ data }}" title="营业执照" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>法人手持身份证照片*</label>
                                {{ form.render('photo') }}
                                <input type="hidden" name="photo" id="photo_picture" value="{{ info.photo }}">
                                {% for data in  picture['photo'] %}
                                    <a href="{{ data }}" title="法人手持身份证照片" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>身份证正面 *</label>
                                {{ form.render('idcard_up') }}
                                <input type="hidden" name="idcard_up" id="idcard_up_picture" value="{{ info.idcard_up }}">
                                {% for data in  picture['idcard_up'] %}
                                    <a href="{{ data }}" title="身份证正面" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>身份证背面 *</label>
                                {{ form.render('idcard_down') }}
                                <input type="hidden" name="idcard_down" id="idcard_down_picture" value="{{ info.idcard_down }}">
                                {% for data in  picture['idcard_down'] %}
                                    <a href="{{ data }}" title="身份证背面" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>机构信用代码证</label>
                                {{ form.render('credit_code') }}
                                <input type="hidden" name="credit_code" id="credit_code_picture" value="{{ info.credit_code }}">
                                {% for data in  picture['credit_code'] %}
                                    <a href="{{ data }}" title="机构信用代码证" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>开户许可证</label>
                                {{ form.render('account_permit') }}
                                <input type="hidden" name="account_permit" id="account_permit_picture" value="{{ info.account_permit }}">
                                {% for data in  picture['account_permit'] %}
                                    <a href="{{ data }}" title="开户许可证" data-gallery="">
                                        <img width="100%" src="{{ data }}">
                                    </a>
                                {% endfor %}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>公司简介 *</label>
                                {{ form.render('intro') }}
                            </div>
                        </div>
                    </div>


                        <div class="hr-line-dashed"></div>

                    <div class="row">

                        {#<div class="col-sm-6 form-group">#}
                            {#<label>推荐人或企业</label>#}
                            {#{{ form.render('recommend') }}#}
                        {#</div>#}
                        {#<div class="col-sm-6 form-group">#}
                            {#<label>管理人或企业 </label>#}
                            {#{{ form.render('manager') }}#}
                        {#</div>#}
                        <div class="col-sm-12">
                            <input class="pull-right btn btn-primary" type="submit" value="提交申请">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{ stylesheet_link("css/plugins/sweetalert/sweetalert.css") }}
{{ javascript_include("js/plugins/sweetalert/sweetalert.min.js") }}
<script type="text/javascript">
    function get_regions(pid, sub_node, next_sub) {
        $.post("{{ url('admin/select/getSubRegions') }}", { pid:pid }, function (result) {
            if(result) {
                var html = '';
                $.each(result, function (i, item) {
                    html += "<option value='" + item.id + "'>" + item.name + "</option>";
                });
                sub_node.html(html);
                if(next_sub) {
                    get_regions(result[0].id, next_sub);
                }
            }
        });
    }
    function get_sub(code, sub_node) {
        $.post("{{ url('admin/select/getSubCategory') }}", { code:code }, function (result) {
            if(result) {
                sub_node.html(result);
            }
        });
    }
    $("#province").change(function() {
        get_regions($(this).val(), $("#city"), $("#district"));
    });
    $("#city").change(function() {
        get_regions($(this).val(), $("#district"))
    });
    $("#bank_province").change(function () {
        get_regions($(this).val(), $("#bank_city"));
    });
    $("#work_bank_province").change(function () {
        get_regions($(this).val(), $("#work_bank_city"));
    })
    $("#licence").change(function () {
        $("#licence_picture").val($(this).val());
    })
    $("#credit_code").change(function () {
        $("#credit_code_picture").val($(this).val());
    })
    $("#account_permit").change(function () {
        $("#account_permit_picture").val($(this).val());
    })
    $("#photo").change(function () {
        $("#photo_picture").val($(this).val());
    })
    $("#idcard_up").change(function () {
        $("#idcard_up_picture").val($(this).val());
    })
    $("#idcard_down").change(function () {
        $("#idcard_down_picture").val($(this).val());
    })
    $("#top_category").change(function () {
        get_sub($(this).val(), $("#sub_category"));
    })
</script>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
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

