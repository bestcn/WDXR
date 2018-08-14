<div class="wrapper wrapper-content fadeInRight">
    <!-- Page Content begin --><div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>加入黑名单 <small>将限制该用户的所有操作及权限</small></h5>
                </div>
                <div class="ibox-content">
                    <form method="post" action="/admin/companys/refund_save" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="csrf" id="csrf" value="bUNIbVppVHdJaEFFUENxbk5LemJwZz09">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">企业名称</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="company_id" value="<?= $company_id ?>"/>
                                <input type="hidden" name="company_name" value="<?= $company_name ?>"/>
                                <input type="text" value="<?= $company_name ?>" class="form-control" disabled="disabled" />
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">详情</label>
                            <div class="col-sm-10">
                                <input type="text" id="info" name="info" class="form-control" placeholder="请填写详细情况">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-danger" type="submit" onclick="return confirm('确认要将企业加入黑名单吗?')">确认</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- Page Content end -->
</div>