<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/info/' . $id . '/', '基本信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/payment/' . $id, '缴费信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/business/' . $id, '业务信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/user/' . $id, '账号信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/bill/' . $id, '票据信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/report/' . $id, '征信报告']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/contract/' . $id, '合同信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/setting/' . $id, '企业设置']); ?></li>
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
                                            <?php if (empty($company)) { ?>
                                                <tr><td>无</td></tr>
                                            <?php } else { ?>
                                            <tr><th>统一社会信用代码</th><td class="data-editable" data-attr="company_info-licence_num-<?= $company['info_id'] ?>"><?= $info['licence_num'] ?></td></tr>
                                            <tr><th>企业名称</th><td class="data-editable" data-attr="companys-name-<?= $company['id'] ?>"><?= $company['name'] ?></td></tr>
                                            <tr><th>企业性质</th><td class="data-editable" data-select-url="<?= $this->url->get('admin/companys/get_company_type') ?>" data-attr="company_info-type-<?= $company['info_id'] ?>"><?php if ($info['type'] == 1) { ?>非个体工商户<?php } elseif ($info['type'] == 2) { ?>个体工商户<?php } else { ?>未选择<?php } ?></td></tr>
                                            <tr><th>企业主行业分类</th><td><?= $info['top_category'] ?></td></tr>
                                            <tr><th>企业子行业分类</th><td><?= $info['sub_category'] ?></td></tr>
                                            <tr><th>企业地址</th><td><?= $info['full_address'] ?></td></tr>
                                            <tr><th>法定代表人</th><td class="data-editable" data-attr="company_info-legal_name-<?= $company['info_id'] ?>"><?= $info['legal_name'] ?></td></tr>
                                            <tr><th>法人身份证号</th><td class="data-editable" data-attr="company_info-idcard-<?= $company['info_id'] ?>"><?= (empty($info['idcard']) ? ('无') : ($info['idcard'])) ?></td></tr>
                                            <tr><th>营业期限</th><td class="data-editable" data-attr="company_info-period-<?= $company['info_id'] ?>"><?= $info['period'] ?></td></tr>
                                            <tr><th>主营业务</th><td class="data-editable" data-attr="company_info-scope-<?= $company['info_id'] ?>"><?= $info['scope'] ?></td></tr>
                                            <tr><th>公司简介</th><td class="data-editable" data-attr="company_info-intro-<?= $company['info_id'] ?>"><?= (empty($info['intro']) ? ('无') : ($info['intro'])) ?></td></tr>
                                            <?php } ?>
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
                                        <?php if (empty($info)) { ?>
                                            无
                                        <?php } else { ?>
                                            <table class="table table-hover">
                                                <tr><th>联系方式</th><td class="data-editable" data-attr="company_info-contact_phone-<?= $company['info_id'] ?>"><?= $info['contact_phone'] ?></td></tr>
                                                <tr><th>联系人</th><td class="data-editable" data-attr="company_info-contacts-<?= $company['info_id'] ?>"><?= $info['contacts'] ?></td></tr>
                                                <tr><th>联系人职位</th><td class="data-editable" data-attr="company_info-contact_title-<?= $company['info_id'] ?>"><?= $info['contact_title'] ?></td></tr>
                                                <tr><th>邮政编码</th><td class="data-editable" data-attr="company_info-zipcode-<?= $company['info_id'] ?>"><?= $info['zipcode'] ?></td></tr>
                                            </table>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="审核内容包括企业工商信息、联系人信息、业务信息及相关证照"></i>
                                        审核信息
                                    </div>
                                    <div class="panel-body">
                                        <?php if (empty($info)) { ?>
                                        无
                                        <?php } else { ?>
                                            <table class="table table-hover">
                                                <tr><th>审核状态</th><td><?= $info['audit_name'] ?></td></tr>
                                                <?php if ($verify) { ?>
                                                <tr><th>申请时间</th><td><?= date('Y-m-d H:i:s', $verify->apply_time) ?></td></tr>
                                                <tr><th>审核时间</th><td><?= date('Y-m-d H:i:s', $verify->verify_time) ?></td></tr>
                                                <tr><th>申请人</th><td><?= $verify->device_name ?></td></tr>
                                                <tr><th>所属业务员</th><td><?= $verify->admin_name ?></td></tr>
                                                <tr><th>审核人</th><td><?= $verify->auditor ?></td></tr>
                                                <?php } ?>
                                            </table>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php if (isset($info['licence'])) { ?>
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            企业营业执照
                                        </div>
                                        <div class="panel-body">
                                            <?php foreach ($info['licence'] as $data) { ?>
                                                <a href="<?= $data ?>" title="营业执照" data-gallery="">
                                                    <img width="100%" src="<?= $data ?>">
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($info['photo'])) { ?>
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            法人照片
                                        </div>
                                        <div class="panel-body">
                                            <?php foreach ($info['photo'] as $data) { ?>
                                                <a href="<?= $data ?>" title="法人照片" data-gallery="">
                                                    <img width="100%" src="<?= $data ?>">
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($info['idcard_up'])) { ?>
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            法人身份证（正面）
                                        </div>
                                        <div class="panel-body">
                                            <?php foreach ($info['idcard_up'] as $data) { ?>
                                                <a href="<?= $data ?>" title="法人身份证（正面）" data-gallery="">
                                                    <img width="100%" src="<?= $data ?>">
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($info['idcard_down'])) { ?>
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            法人身份证（反面）
                                        </div>
                                        <div class="panel-body">
                                            <?php foreach ($info['idcard_down'] as $data) { ?>
                                                <a href="<?= $data ?>" title="法人身份证（反面）" data-gallery="">
                                                    <img width="100%" src="<?= $data ?>">
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($info['credit_code'])) { ?>
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            机构信用代码证
                                        </div>
                                        <div class="panel-body">
                                            <?php foreach ($info['credit_code'] as $data) { ?>
                                                <a href="<?= $data ?>" title="机构信用代码证" data-gallery="">
                                                    <img width="100%" src="<?= $data ?>">
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($info['account_permit'])) { ?>
                                <div class="col-sm-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            开户许可证
                                        </div>
                                        <div class="panel-body">
                                            <?php foreach ($info['account_permit'] as $data) { ?>
                                                <a href="<?= $data ?>" title="开户许可证" data-gallery="">
                                                    <img width="100%" src="<?= $data ?>">
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
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

