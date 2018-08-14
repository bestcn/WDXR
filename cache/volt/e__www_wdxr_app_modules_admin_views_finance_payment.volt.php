<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>缴费审核列表</h5>
            </div>
            <div class="ibox-content">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        
                        <th>申请时间</th>
                        <th>合伙人</th>
                        <th>业务员</th>
                        <th>申请状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page)) { ?>
                        <?php foreach ($page->items as $company) { ?>
                            <tr>
                                <td><?= $company->id ?></td>
                                <td><?= $company->name ?></td>
                                
                                    
                                        
                                    
                                        
                                    
                                
                                <td><?= date('Y-m-d H:i:s', $company->apply_time) ?></td>
                                <td><?= $company->partner_name ?></td>
                                <td><?= $company->admin_name ?></td>
                                <td>
                                    <?php if ($company->auditing == constant('\Wdxr\Models\Repositories\Company::AUDIT_NOT')) { ?>
                                        <i class="fa text-muted"> 未申请</i>
                                    <?php } elseif ($company->auditing == constant('\Wdxr\Models\Repositories\Company::AUDIT_APPLY')) { ?>
                                        <i class="fa fa-clock-o text-navy"> 待审核</i>
                                    <?php } elseif ($company->auditing == constant('\Wdxr\Models\Repositories\Company::AUDIT_OK')) { ?>
                                        <i class="fa fa-check text-success"> 通过</i>
                                    <?php } elseif ($company->auditing == constant('\Wdxr\Models\Repositories\Company::AUDIT_REVOKED')) { ?>
                                        <i class="fa fa-times text-danger"> 被驳回</i>
                                    <?php } else { ?>
                                        <i class="fa fa-times text-danger"> 状态错误</i>
                                    <?php } ?>
                                </td>

                                <td><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/finance/edit_payment/' . $company->id, '审核', 'class' => 'btn btn-primary btn-xs']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="row" >
                    <div class="col-sm-5">
                        <?= $page->current . '/' . $page->total_pages ?>
                    </div>
                    <div class="col-sm-7">
                        <ul class="pagination pull-right no-margins">
                            <li>
                                <?= $this->tag->linkTo(['admin/finance/payment?page=1', '第一页']) ?>
                            </li>
                            <li class="paginate_button previous">
                                    <?= $this->tag->linkTo(['admin/finance/payment?page=' . $page->before, '前一页']) ?>
                            </li>
                            <li class="paginate_button next">
                                    <?= $this->tag->linkTo(['admin/finance/payment?page=' . $page->next, '下一页']) ?>
                            </li>
                            <li>
                                    <?= $this->tag->linkTo(['admin/finance/payment?page=' . $page->last, '最后一页']) ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


