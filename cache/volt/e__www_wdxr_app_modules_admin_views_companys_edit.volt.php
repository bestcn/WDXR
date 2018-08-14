<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/edit/' . $id, '基本信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/edit_password/' . $id . '/' . $type, '修改企业密码']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/company_info/' . $id . '/' . $type, '详细信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/bill_info/' . $id . '/' . $type, '票据信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/report_info/' . $id . '/' . $type, '征信报告']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/contract/' . $id, '查看合同']); ?></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <?= $this->tag->form(['method' => 'post', 'class' => 'form-horizontal']) ?>
                    <div class="panel-body">
                        <input type="hidden" name="csrf" id="csrf" value="<?= $this->security->getToken() ?>"/>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="type" value="<?= $type ?>"/>
                        <?= $form->render('id') ?>
                        <div class="form-group">
                            <label for="fieldBranch_name" class="col-sm-2 control-label">企业名称</label>
                            <div class="col-sm-4">
                                <?= $form->render('name') ?>
                            </div>

                            <label for="fieldBranch_level" class="col-sm-2 control-label">企业性质</label>
                            <div class="col-sm-4">
                                <?= $form->render('type') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fieldBranch_status" class="col-sm-2 control-label">企业状态</label>
                            <div class="col-sm-4">
                                <?= $form->render('status') ?>
                            </div>

                            <label for="fieldBranch_status" class="col-sm-2 control-label">审核状态</label>
                            <div class="col-sm-4">
                                <?= $form->render('auditing') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fieldBranch_status" class="col-sm-2 control-label">缴费状态</label>
                            <div class="col-sm-4">
                                <?= $form->render('payment') ?>
                            </div>

                            <label for="fieldBranch_status" class="col-sm-2 control-label">缴费类型</label>
                            <div class="col-sm-4">
                                <input type="text" disabled="disabled" value="<?= $company_payment ?>" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fieldBranch_status" class="col-sm-2 control-label">登录账号</label>
                            <div class="col-sm-4">
                                <input type="text" disabled class="form-control" value="<?= $username ?>">
                            </div>
                            <label for="fieldBranch_phone" class="col-sm-2 control-label">企业级别</label>
                            <div class="col-sm-4">
                                <?= $form->render('level_id') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fieldBranch_status" class="col-sm-2 control-label">行业分类</label>
                            <div class="col-sm-4">
                                <select id="top_category" name="top_category" class="form-control">
                                    <?php foreach ($all_top as $data) { ?>
                                    <option  <?php if ($top_category['code'] == $data['code']) { ?>selected="selected"<?php } ?> value="<?= $data['code'] ?>"><?= $data['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <label for="fieldBranch_phone" class="col-sm-2 control-label">子分类</label>
                            <div class="col-sm-4">
                                <select id="sub_category" name="sub_category" class="form-control">
                                    <?php foreach ($all_sub as $data2) { ?>
                                        <option  <?php if ($sub_category['code'] == $data2['code']) { ?>selected="selected"<?php } ?> value="<?= $data2['code'] ?>"><?= $data2['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <?php if ($time_status == 1) { ?>
                        <div class="form-group ">
                            <label for="fieldBranch_phone" class="col-sm-2 control-label">服务起始日期</label>
                            <div class="col-sm-4">
                            <!--指定 date标记-->
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="start_time" value="<?= $start_time ?>" class="form-control" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            </div>
                            <label for="fieldBranch_phone" class="col-sm-2 control-label">服务截止日期</label>
                            <div class="col-sm-4">
                            <!--指定 date标记-->
                            <div class='input-group date' id='datetimepicker2'>
                                <input type='text' name="end_time" value="<?= $end_time ?>" class="form-control" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php if ($auditing == 2) { ?>
                            <div class="form-group">
                                <label for="fieldBranch_status" class="col-sm-2 control-label">登录状态</label>
                                <div class="col-sm-4">
                                    <select  name="login_status" class="form-control">
                                        <option value= 0 <?php if ($login_status == 0) { ?>selected="selected"<?php } ?> >禁止登录</option>
                                        <option value= 1 <?php if ($login_status == 1) { ?>selected="selected"<?php } ?> >允许登录</option>
                                    </select>
                                </div>

                                <label for="fieldBranch_status" class="col-sm-2 control-label">企业身份</label>
                                <div class="col-sm-4">
                                    <input type="text" disabled="disabled" value="<?= $is_partner ?>" class="form-control">
                                </div>
                            </div>
                        <?php } ?>



                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?= $form->render('submit') ?>
                                <button onclick="location='<?= $this->url->get('admin/companys/goBack') ?>';" class="btn btn-default" type="button">返回</button>
                            </div>
                        </div>
                    </div>
                    <?= $this->tag->endForm() ?>
                </div>
            </div>
        </div>
    </div>
</div>
        <?= $this->tag->javascriptInclude('js/jquery-3.1.1.min.js') ?>
        <?= $this->tag->javascriptInclude('js/select.js') ?>