<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>奖金列表</h5>
            </div>
            <div class="ibox-content">
                <div class="row">


                    <div class="col-sm-8 form-inline">
                        <?= $this->tag->form(['admin/finance/bonus_export', 'method' => 'post', 'autocomplete' => 'on']) ?>
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
                        
                        <?= $this->tag->form(['admin/finance/bonus', 'method' => 'post', 'autocomplete' => 'on']) ?>
                        <div class="input-group">
                            <input type="text" name="admin_name" class="input-sm form-control" placeholder="搜索业务员名称">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary"> 搜索</button>
                            </span>
                        </div>
                        <?= $this->tag->endForm() ?>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>业务员</th>
                        <th>企业名称</th>
                        <th>推荐人</th>
                        <th>推荐奖金</th>
                        <th>签订时间</th>
                        <th>成交金额</th>
                        <th>业务员提成</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($page->items)) { ?>
                        <?php foreach ($page->items as $finance) { ?>
                            <tr>
                                <td><?= $finance->id ?></td>
                                <td><font color="blue"><?= $finance->admin_name ?></font></td>
                                <td><?= $finance->company_name ?></td>
                                <td><?= $finance->recommender ?></td>
                                <td><font color="blue"><?= $finance->bonus ?></font> 元</td>
                                <td><?= date('Y-m-d H:i:s', $finance->time) ?></td>
                                <td><font color="green"><?= $finance->money ?></font> 元</td>
                                <td><font color="blue"><?= $finance->commission ?> 元</font></td>
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
                                <?= $this->tag->linkTo(['admin/finance/bonus?page=1' . '&admin_name=' . $admin_name, '第一页']) ?>
                            </li>
                            <li class="paginate_button previous">
                                <?php if ($admin_name) { ?>
                                    <?= $this->tag->linkTo(['admin/finance/bonus?page=' . $page->before . '&admin_name=' . $admin_name, '前一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/finance/bonus?page=' . $page->before, '前一页']) ?>
                                <?php } ?>
                            </li>
                            <li class="paginate_button next">
                                <?php if ($admin_name) { ?>
                                    <?= $this->tag->linkTo(['admin/finance/bonus?page=' . $page->next . '&admin_name=' . $admin_name, '下一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/finance/bonus?page=' . $page->next, '下一页']) ?>
                                <?php } ?>
                            </li>
                            <li>
                                <?php if ($admin_name) { ?>
                                    <?= $this->tag->linkTo(['admin/finance/bonus?page=' . $page->last . '&admin_name=' . $admin_name, '最后一页']) ?>
                                <?php } else { ?>
                                    <?= $this->tag->linkTo(['admin/finance/bonus?page=' . $page->last, '最后一页']) ?>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

