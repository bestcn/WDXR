<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>详细统计列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-8 form-inline">
                        <?= $this->tag->form(['admin/finance/statistics_export', 'method' => 'post', 'autocomplete' => 'on']) ?>
                        <div class="form-group ">
                            <!--指定 date标记-->
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="start_time" class="form-control" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        至
                        <div class="form-group">
                            <!--指定 date标记-->
                            <div class='input-group date' id='datetimepicker2'>
                                <input type='text' name="end_time" class="form-control" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary"> 导出</button>
                        <?= $this->tag->endForm() ?>
                    </div>
                    <div class="col-sm-4">
                        <?= Wdxr\Modules\Admin\Tags\MenuTags::acl_button(['admin/finance/today_statistics_export', '导出昨天的报表', 'class' => 'btn btn-sm btn-primary pull-right']); ?>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>企业名称</th>
                        <th>收款银行</th>
                        <th>收款账户</th>
                        <th>每日报销</th>
                        <th>推荐奖励</th>
                        <th>管理奖励</th>
                        <th>事业合伙人奖金</th>
                        <th>时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                        <?php foreach ($page->items as $finance) { ?>
                            <tr>
                                <td><?= $finance->id ?></td>
                                <td><?= $finance->company_name ?></td>
                                <td><?= $finance->bank_name ?></td>
                                <td><?= $finance->bank_card ?></td>
                                <td><?= $finance->fee ?></td>
                                <td><?= $finance->recommends_fee ?></td>
                                <td><?= $finance->manages_fee ?></td>
                                <td><?= $finance->bonus ?></td>
                                <td><?= $finance->time ?></td>
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
                                <?= $this->tag->linkTo(['admin/finance/statistics?page=1' . '&company_name=' . $company_name, '第一页']) ?>
                            </li>
                            <li class="paginate_button previous">
                                <?php if ($company_name) { ?>
                                    <?= $this->tag->linkTo(['admin/finance/statistics?page=' . $page->before . '&company_name=' . $company_name, '前一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/finance/statistics?page=' . $page->before, '前一页']) ?>
                                <?php } ?>
                            </li>
                            <li class="paginate_button next">
                                <?php if ($company_name) { ?>
                                    <?= $this->tag->linkTo(['admin/finance/statistics?page=' . $page->next . '&company_name=' . $company_name, '下一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/finance/statistics?page=' . $page->next, '下一页']) ?>
                                <?php } ?>
                            </li>
                            <li>
                                <?php if ($company_name) { ?>
                                    <?= $this->tag->linkTo(['admin/finance/statistics?page=' . $page->last . '&company_name=' . $company_name, '最后一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/finance/statistics?page=' . $page->last, '最后一页']) ?>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

