<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title"><h5>添加奖金制度</h5></div>
            <div class="ibox-content">
                <?= $this->tag->form(['admin/bonus/new', 'method' => 'post', 'autocomplete' => 'off', 'class' => 'form-horizontal']) ?>
                <div class="form-group">
                    <label for="fieldBranch_name" class="col-sm-2 control-label">推荐人类别</label>
                    <div class="col-sm-4">
                        <?= $form->render('csrf', ['value' => $this->security->getToken()]) ?>
                        <?= $form->render('recommend') ?>
                    </div>

                    <label for="fieldBranch_level" class="col-sm-2 control-label">新客户类别</label>
                    <div class="col-sm-2">
                        <?= $form->render('customer') ?>
                    </div>
                </div>

                <div class="form-group">

                    <label for="fieldBranch_account" class="col-sm-2 control-label">第1个</label>
                    <div class="col-sm-4">
                        <?= $form->render('first') ?>
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">第2个</label>
                    <div class="col-sm-2">
                        <?= $form->render('second') ?>
                    </div>
                </div>

                <div class="form-group">

                    <label for="fieldBranch_account" class="col-sm-2 control-label">第3个</label>
                    <div class="col-sm-4">
                        <?= $form->render('third') ?>
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">第4个</label>
                    <div class="col-sm-2">
                        <?= $form->render('fourth') ?>
                    </div>
                </div>

                <div class="form-group">

                    <label for="fieldBranch_account" class="col-sm-2 control-label">第5个</label>
                    <div class="col-sm-4">
                        <?= $form->render('fifth') ?>
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">第6个</label>
                    <div class="col-sm-2">
                        <?= $form->render('sixth') ?>
                    </div>
                </div>

                <div class="form-group">

                    <label for="fieldBranch_account" class="col-sm-2 control-label">第7个</label>
                    <div class="col-sm-4">
                        <?= $form->render('seventh') ?>
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">第8个</label>
                    <div class="col-sm-2">
                        <?= $form->render('eighth') ?>
                    </div>
                </div>

                <div class="form-group">

                    <label for="fieldBranch_account" class="col-sm-2 control-label">第9个</label>
                    <div class="col-sm-4">
                        <?= $form->render('ninth') ?>
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">第10个</label>
                    <div class="col-sm-2">
                        <?= $form->render('tenth') ?>
                    </div>
                </div>

                <div class="form-group">

                    <label for="fieldBranch_account" class="col-sm-2 control-label">第11个</label>
                    <div class="col-sm-4">
                        <?= $form->render('eleventh') ?>
                    </div>
                    <label for="fieldBranch_bank" class="col-sm-2 control-label">第12个</label>
                    <div class="col-sm-2">
                        <?= $form->render('twelfth') ?>
                    </div>
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
