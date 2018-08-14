<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>企业缴费申请 <small>提交企业缴费申请审核</small></h5>
            </div>
            <div class="ibox-content">
                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" id="csrf" value="{{ security.getToken() }}">
                    <input type="hidden" name="company_id" id="company_id" value="{{ company_id }}">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">企业名称</label>
                        <div class="col-sm-10">
                            {{ company.name }}
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">业务员</label>
                        <div class="col-sm-10">
                            {{ form.render('admin_id') }}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">企业级别</label>
                        <div class="col-sm-10">
                            {{ form.render('level_id') }}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">缴费类型</label>
                        <div class="col-sm-10">
                            {{ form.render('payment_type') }}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">缴费凭证</label>
                        <div class="col-sm-2">
                            <input type="file" name="voucher[]" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <input type="file" name="voucher[]" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <input type="file" name="voucher[]" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <input type="file" name="voucher[]" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <input type="file" name="voucher[]" class="form-control" />
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="ibox-content">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>开户行银行卡照片</label>
                                {{ form.render('bankcard_photo') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>银行账户类型</label>
                                {{ form.render('bank_type') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>开户行卡号</label>
                                {{ form.render('bankcard') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>开户行所属银行</label>
                                {{ form.render('bank') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>开户行所属省份</label>
                                {{ form.render('bank_province') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>开户行所属城市</label>
                                {{ form.render('bank_city') }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>开户行</label>
                                {{ form.render('bank_name') }}
                            </div>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>绩效银行卡照片</label>
                                {{ form.render('work_photo') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>绩效银行卡号</label>
                                {{ form.render('work_bankcard') }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>所属银行</label>
                                {{ form.render('work_bank') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>绩效银行所属省份</label>
                                {{ form.render('work_bank_province') }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>绩效银行所属城市</label>
                                {{ form.render('work_bank_city') }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>绩效开户行</label>
                                {{ form.render('work_bank_name') }}
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">提交申请</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>