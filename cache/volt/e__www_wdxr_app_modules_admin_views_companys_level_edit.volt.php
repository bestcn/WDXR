<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>修改级别</h5></div>
            <div class="ibox-content">
                <?= $this->tag->form(['method' => 'post', 'class' => 'form-horizontal']) ?>
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">级别名称</label>
                    <div class="col-sm-2">
                        <?= $form->render('csrf', ['value' => $this->security->getToken()]) ?>
                        <input type="hidden" value="<?= $id ?>" name="id"/>
                        <?= $form->render('level_name') ?>
                    </div>

                    <label for="fieldBranch_level" class="col-sm-2 control-label">级别状态</label>
                    <div class="col-sm-2">
                        <?= $form->render('level_status') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldBrancharea" class="col-sm-2 control-label">级别金额</label>

                    <div class="col-sm-2"><?= $form->render('level_money') ?></div>

                    <label for="fieldBrancharea" class="col-sm-2 control-label">每天返现金额</label>

                    <div class="col-sm-2"><?= $form->render('day_amount') ?></div>

                </div>

                <div class="form-group">
                    <label for="fieldBrancharea" class="col-sm-2 control-label">详细报销信息</label>

                    <div class="col-sm-2"><?= $form->render('info') ?></div>

                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <?= $form->render('submit') ?>
                        <button onclick="history.go(-1);" class="btn btn-default" type="button">返回</button>
                    </div>
                </div>
                <?= $this->tag->endForm() ?>
            </div>
        </div>
    </div>
</div>
