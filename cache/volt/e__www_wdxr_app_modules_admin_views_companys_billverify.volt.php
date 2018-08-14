<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>票据待审核列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-6">
                        
                        
                            
                            
                                
                            
                        
                        
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        <th>企业性质</th>
                        <th>申请时间</th>
                        <th>审核状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                        <?php foreach ($page->items as $company) { ?>
                            <tr>
                                <td><?= $company->id ?></td>
                                <td><?= $company->name ?></td>
                                <td><?php if ($company->type == 1) { ?><font color="green">非个体工商户</font><?php } elseif ($company->type == 2) { ?><font color="blue">个体工商户</font><?php } else { ?>未选择<?php } ?></td>
                                <td><?= date('Y-m-d H:i:s', $company->apply_time) ?></td>
                                <td>待审核</td>

                                <td><?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/companys/bill/' . $company->data_id, '审核', 'class' => 'btn btn-primary btn-xs']); ?></td>
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
                                <?= $this->tag->linkTo(['admin/companys/billVerify?page=1', '第一页']) ?>
                            </li>
                            <li class="paginate_button previous">

                                    <?= $this->tag->linkTo(['admin/companys/billVerify?page=' . $page->before, '前一页']) ?>

                            </li>
                            <li class="paginate_button next">

                                    <?= $this->tag->linkTo(['admin/companys/billVerify?page=' . $page->next, '下一页']) ?>

                            </li>
                            <li>

                                    <?= $this->tag->linkTo(['admin/companys/billVerify?page=' . $page->last, '最后一页']) ?>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


