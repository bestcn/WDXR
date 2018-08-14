<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/info/' . $id . '/', '基本信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/payment/' . $id, '缴费信息']); ?></li>
                <li class=""><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/business/' . $id, '业务信息']); ?></li>
                <li class="active"><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/user/' . $id, '账号信息']); ?></li>
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
                                        用户账号
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            <?php if (isset($user)) { ?>
                                                <tr>
                                                    <th>头像</th>
                                                    <td>
                                                        <?php if ($user['pic']) { ?>
                                                            <img class="img-rounded img-md" src="<?= $user['pic'] ?>" alt="头像">
                                                        <?php } else { ?>
                                                            无
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted">用户ID</th>
                                                    <td class="text-muted">
                                                        <?= $user['id'] ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-muted">
                                                        用户编号
                                                    </th>
                                                    <td class="text-muted">
                                                        <?= $user['number'] ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>用户名</th>
                                                    <td class="data-editable" data-attr="users-name-<?= $user['id'] ?>"><?= $user['name'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>手机号</th>
                                                    <td class="data-editable" data-attr="users-phone-<?= $user['id'] ?>"><?= $user['phone'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>邮箱</th>
                                                    <td class="data-editable" data-attr="users-email-<?= $user['id'] ?>"><?= $user['email'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>状态</th>
                                                    <td class="data-editable" data-select-url="<?= $this->url->get('admin/tools/get_status') ?>" data-attr="users-status-<?= $user['id'] ?>"><?= $user['status'] ?></td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr><td colspan="2">无</td></tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                <div class="panel-heading">
                                    修改密码
                                </div>
                                <div class="panel-body">
                                    <?php if (isset($user)) { ?>
                                    <?= $this->tag->form([$this->url->get('admin/companys/user/' . $id), 'method' => 'post', 'autocomplete' => 'off', 'class' => 'form-horizontal']) ?>
                                        <?= $form->render('csrf', ['value' => $this->security->getToken()]) ?>
                                        <?= $form->render('id') ?>
                                        <div class="form-group">
                                            <label for="fieldPassword" class="col-sm-3 control-label">密码</label>
                                            <div class="col-sm-9">
                                                <?= $form->render('password') ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="fieldPassword" class="col-sm-3 control-label">确认密码</label>
                                            <div class="col-sm-9">
                                                <?= $form->render('confirm_password') ?>
                                            </div>
                                        </div>
                                        <div class="hr-line-dashed"></div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <?= $form->render('submit') ?>
                                            </div>
                                        </div>
                                    <?= $this->tag->endForm() ?>
                                    <?php } ?>
                                </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        密码修改日志
                                    </div>
                                    <div class="panel-body">
                                        <?php if (isset($page->items)) { ?>
                                        <table class="table table-hover">
                                            <thead>
                                            <tr><th>修改时间</th><th>IP</th><th>浏览器代理</th></tr>
                                            </thead>
                                                <?php foreach ($page->items as $item) { ?>
                                                    <tr><td><?= date('Y-m-d H:i:s', $item->createdAt) ?></td><td><?= $item->ipAddress ?></td><td><?= $item->userAgent ?></td></tr>
                                                <?php } ?>
                                        </table>

                                        <div class="row">
                                            <div class="col-sm-5">
                                                <?= $page->current . '/' . $page->total_pages ?>
                                            </div>
                                            <div class="col-sm-7">
                                                <ul class="pagination no-margins pull-right">
                                                    <li><?= $this->tag->linkTo(['admin/companys/user/' . $id, '第一页']) ?></li>
                                                    <li class="paginate_button previous"><?= $this->tag->linkTo(['admin/companys/user/' . $id . '?page=' . $page->before, '前一页']) ?></li>
                                                    <li class="paginate_button next"><?= $this->tag->linkTo(['admin/companys/user/' . $id . '?page=' . $page->next, '下一页']) ?></li>
                                                    <li><?= $this->tag->linkTo(['admin/companys/user/' . $id . '?page=' . $page->last, '最后一页']) ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                            <div class="row">无</div>
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
</div>
