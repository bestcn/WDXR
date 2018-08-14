<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/info/' . $id . '/', '基本信息']); ?></li>
                <li class="active"><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/payment/' . $id, '缴费信息']); ?></li>
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
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    银行卡信息
                                </div>
                                <div class="panel-body">
                                    <?php if (isset($banks)) { ?>
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>编号</th>
                                                <th>账户性质</th>
                                                <th>所属银行</th>
                                                <th>开户行城市</th>
                                                <th>开户行</th>
                                                <th>开户人</th>
                                                <th>银行卡号</th>
                                                <th>用途</th>
                                                <th>银行卡照片</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($banks as $bank) { ?>
                                                <tr>
                                                    <td><?= $bank->id ?></td>
                                                    <td><?php if ($bank->bank_type == 1) { ?>对公账户<?php } else { ?>个人账户<?php } ?></td>
                                                    <td title="所属银行" class="data-editable" data-attr="company_bank-bank-<?= $bank->id ?>"><?= $bank->bank ?></td>
                                                    <td><?= $bank->province_regions->name ?><?= $bank->city_regions->name ?></td>
                                                    <td title="开户行地址" class="data-editable" data-attr="company_bank-address-<?= $bank->id ?>"><?= $bank->address ?></td>
                                                    <td title="开户人" class="data-editable" data-attr="company_bank-account-<?= $bank->id ?>"><?= $bank->account ?></td>
                                                    <td title="银行卡号" class="data-editable" data-attr="company_bank-number-<?= $bank->id ?>"><?= $bank->number ?></td>
                                                    <td><?= ($bank->category == 1 ? '主要银行卡' : '绩效银行卡') ?></td>
                                                    <td>
                                                        <?php $data = \Wdxr\Models\Repositories\Attachment::getAttachmentUrl($bank->bankcard_photo); ?>
                                                        <?php if (empty($data)) { ?>
                                                            无
                                                        <?php } else { ?>
                                                            <?php foreach ($data as $item) { ?>
                                                                <a href="<?= $item ?>" title="银行卡照片" data-gallery="">
                                                                    查看
                                                                </a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        无
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    缴费记录
                                </div>
                                <div class="panel-body">
                                    <style>
                                        .table tr th {width:90px;}
                                        .loan tr th {width:130px;}
                                    </style>
                                    <?php if (isset($payments)) { ?>
                                        <?php foreach ($payments as $payment) { ?>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>编号 </th><td><?= $payment['id'] ?></td>
                                                    <th>金额</th><td><?= $payment['amount'] ?> 元</td>
                                                    <th>缴费状态</th><td>
                                                        <?php if ($payment['status'] == '3') { ?><i data-toggle="tooltip" data-placement="top" class="fa fa-exclamation-circle" title="<?= $payment['remark'] ?>"></i><?php } ?>
                                                        <?= $payment['status_name'] ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>申请时间</th><td><?= date('Y-m-d H:i:s', $payment['time']) ?></td>
                                                    <th>审核时间</th>
                                                    <td>
                                                        <?php if (isset($payment['loan'])) { ?>
                                                            <i data-toggle="tooltip" data-placement="top" title="普惠信息不需要缴费审核" class="fa fa-info-circle"></i> 无
                                                        <?php } else { ?>
                                                            <?php if (empty($payment['verify_time'])) { ?>
                                                                <i data-toggle="tooltip" data-placement="top" title="尚未审核" class="fa fa-info-circle"></i> 无
                                                            <?php } else { ?>
                                                                <?= date('Y-m-d H:i:s', $payment['verify_time']) ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <th>缴费方式</th><td><?= $payment['type'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>所属业务员</th>
                                                    <td>
                                                        <?php if (isset($payment['admin_name'])) { ?>
                                                            <?= $payment['admin_name'] ?>
                                                        <?php } else { ?>
                                                            <i data-toggle="tooltip" data-placement="top" title="申请人信息丢失或参数异常(device_id:<?= $payment['device_id'] ?>)" class="fa fa-warning"></i>
                                                            无
                                                        <?php } ?>
                                                    </td>
                                                    <th>申请人</th>
                                                    <td>
                                                        <?php if (empty($payment['device_name'])) { ?>
                                                            <i data-toggle="tooltip" data-placement="top" title="申请人信息丢失或参数异常(device_id:<?= $payment['device_id'] ?>)" class="fa fa-warning"></i>
                                                            无
                                                        <?php } else { ?>
                                                            <?= $payment['device_name'] ?>
                                                        <?php } ?>
                                                    </td>
                                                    <th>审核人</th>
                                                    <td>
                                                        <?php if (isset($payment['auditor'])) { ?>
                                                            <?= $payment['auditor'] ?>
                                                        <?php } else { ?>
                                                            无
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php if (isset($payment['loan'])) { ?>
                                                    <tr>
                                                        <th>
                                                            <i data-toggle="tooltip" data-placement="top" title="非贷款客户没有普惠信息" class="fa fa-info-circle"></i>
                                                            普惠信息
                                                        </th>
                                                        <td colspan="5">
                                                            <table class="loan table table-bordered">
                                                                <tr>
                                                                    <th><i data-toggle="tooltip" data-placement="top" title="普惠信息的申请人即企业法人，缴费信息的申请人指提交申请的业务员或者事业合伙人" class="fa fa-info-circle"></i> 申请人</th><td title="申请人" class="data-editable" data-attr="loans_info-name-<?= $payment['loan_info']->id ?>"><?= $payment['loan_info']->name ?></td>
                                                                    <th>性别</th><td><?= ($payment['loan_info']->sex == 1 ? '男' : '女') ?></td>
                                                                    <th>身份证号</th><td title="身份证号" class="data-editable" data-attr="loans_info-identity-<?= $payment['loan_info']->id ?>"><?= $payment['loan_info']->identity ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>申请时间</th><td><?= date('Y-m-d H:i:s', $payment['loan_info']->time) ?></td>
                                                                    <th>状态</th><td><?= $payment['loan']->status_name ?></td>
                                                                    <th>地址</th><td><?= \Wdxr\Models\Repositories\Regions::getAddress($payment['loan_info']->province, $payment['loan_info']->city, $payment['loan_info']->area, $payment['loan_info']->address) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>申请金额</th><td title="申请金额" class="data-editable" data-attr="loans_info-money-<?= $payment['loan_info']->id ?>"><?= $payment['loan_info']->money ?> 元</td>
                                                                    <th>申请期数</th><td><?= $payment['loan_info']->term_name ?></td>
                                                                    <th>申请用途</th><td title="申请用途" class="data-editable" data-attr="loans_info-purpose-<?= $payment['loan_info']->id ?>"><?= $payment['loan_info']->purpose ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>职业/经营范围</th><td title="职业/经营范围" class="data-editable" data-attr="loans_info-business-<?= $payment['loan_info']->id ?>" colspan="5"><?= $payment['loan_info']->business ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>联社系统借款</th><td title="联社系统借款" class="data-editable" data-attr="loan-system_loan-<?= $payment['loan']->id ?>"><?= $payment['loan']->system_loan ?></td>
                                                                    <th>对外担保金额</th><td title="对外担保金额" class="data-editable" data-attr="loan-sponsion-<?= $payment['loan']->id ?>"><?= $payment['loan']->sponsion ?></td>
                                                                    <th>其他金融机构借款</th><td title="其他金融机构借款" class="data-editable" data-attr="loan-other_loan-<?= $payment['loan']->id ?>"><?= $payment['loan']->other_loan ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>其中：不良借贷或不良担保金额</th><td title="其中：不良借贷或不良担保金额" class="data-editable" data-attr="loan-unhealthy-<?= $payment['loan']->id ?>"><?= $payment['loan']->unhealthy ?></td>
                                                                    <th>上年收入</th><td title="上年收入" class="data-editable" data-attr="loan-last_year-<?= $payment['loan']->id ?>"><?= $payment['loan']->last_year ?></td>
                                                                    <th>今年收入</th><td title="今年收入" class="data-editable" data-attr="loan-this_year-<?= $payment['loan']->id ?>"><?= $payment['loan']->this_year ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>担保金额</th><td title="担保金额" class="data-editable" data-attr="loan-quota-<?= $payment['loan']->id ?>"><?= $payment['loan']->quota ?></td>
                                                                    <th>备注</th><td title="备注" class="data-editable" data-attr="loan-remarks-<?= $payment['loan']->id ?>" colspan="3"><?= $payment['loan']->remarks ?></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <th>
                                                        <?php if (isset($payment['loan'])) { ?>
                                                            银行回单
                                                        <?php } else { ?>
                                                            缴费凭证
                                                        <?php } ?>
                                                    </th>
                                                    <td colspan="5">
                                                        <?php if ($payment['voucher']) { ?>
                                                            <?php foreach ($payment['voucher'] as $data) { ?>
                                                                <div class="col-sm-6">
                                                                    <div class="panel panel-success">
                                                                        <div class="panel-body">
                                                                            <a href="<?= $data ?>" data-gallery="">
                                                                                <img width="100%" src="<?= $data ?>">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            无
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php } ?>
                                    <?php } else { ?>
                                        无
                                    <?php } ?>
                                </div>
                            </div>
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
