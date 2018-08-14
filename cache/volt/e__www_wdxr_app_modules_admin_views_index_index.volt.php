<div class="row">
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-success pull-right">总计</span>
                <h5>营收</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?= $amount ?></h1>
                <small>人民币</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">月收入</span>
                <h5>营收</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?= $month ?></h1>
                <div class="stat-percent font-bold text-info"><i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="上月1号至<?= date('d') ?>号增加<?= $last_month ?>元营收"></i> <?= $income_rate ?>%<i class="fa fa-level-<?php if ($income_rate > 0) { ?>up<?php } else { ?>down<?php } ?>"></i></div>
                <small>人民币</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-primary pull-right">月增量</span>
                <h5>客户数量</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?= $partner_new_companies_count ?>/<?= $ordinary_new_companies_count ?></h1>
                <div class="stat-percent font-bold text-navy"><i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="上月1号至<?= date('d') ?>号新增<?= $last_month_count ?>家客户"></i> <?= $company_rate ?>% <i class="fa fa-level-<?php if ($company_rate > 0) { ?>up<?php } else { ?>down<?php } ?>"></i></div>
                <small>事业合伙人／普惠客户</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-danger pull-right">总量</span>
                <h5>客户数量</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins"><?= $partner_count ?> / <?= $ordinary_count ?></h1>
                <small>事业合伙人 / 普惠客户</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>31日内营收曲线</h5>
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-white active">Today</button>
                        <button type="button" class="btn btn-xs btn-white">Monthly</button>
                        <button type="button" class="btn btn-xs btn-white">Annual</button>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="income-line-chart"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <ul class="stat-list">
                            <li>
                                <h2 class="no-margins">2,346</h2>
                                <small>同期事业合伙人占比</small>
                                <div class="stat-percent">48% <i class="fa fa-level-up text-navy"></i></div>
                                <div class="progress progress-mini">
                                    <div style="width: 48%;" class="progress-bar"></div>
                                </div>
                            </li>
                            <li>
                                <h2 class="no-margins ">4,422</h2>
                                <small>同期普惠客户占比</small>
                                <div class="stat-percent">60% <i class="fa fa-level-down text-navy"></i></div>
                                <div class="progress progress-mini">
                                    <div style="width: 60%;" class="progress-bar"></div>
                                </div>
                            </li>
                            <li>
                                <h2 class="no-margins ">9,180</h2>
                                <small>同期客户增量</small>
                                <div class="stat-percent">22% <i class="fa fa-bolt text-navy"></i></div>
                                <div class="progress progress-mini">
                                    <div style="width: 22%;" class="progress-bar"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<?= $this->tag->javascriptInclude('js/plugins/flot/jquery.flot.js') ?>

<?= $this->tag->javascriptInclude('js/plugins/flot/jquery.flot.spline.js') ?>



<?= $this->tag->javascriptInclude('js/plugins/flot/jquery.flot.time.js') ?>

<script type="text/javascript">
    var achievements = [
        <?php foreach ($achievements as $d) { ?>
        [gd(<?= $d['year'] ?>, <?= $d['month'] ?>, <?= $d['day'] ?>), <?= $d['value'] ?>],
        <?php } ?>
    ];
    var count = [
        <?php foreach ($count as $d) { ?>
        [gd(<?= $d['year'] ?>, <?= $d['month'] ?>, <?= $d['day'] ?>), <?= $d['value'] ?>],
        <?php } ?>
    ];
    var dataset = [
        {
            label: "31日内客户数量",
            data: count,
            color: "#1ab394",
            bars: {
                show: true,
                align: "center",
                barWidth: 24 * 60 * 60 * 600,
                lineWidth:0
            }
        }, {
            label: "31日内收入曲线",
            data: achievements,
            yaxis: 2,
            color: "#1C84C6",
            lines: {
                lineWidth:1,
                show: true,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.2
                    }, {
                        opacity: 0.4
                    }]
                }
            },
            splines: {
                show: true,
                tension: 0.6,
                lineWidth: 1,
                fill: 0.1
            }
        }
    ];
    var options = {
        xaxis: {
            mode: "time",
            tickSize: [3, "day"],
            tickLength: 0,
            axisLabel: "Date",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Arial',
            axisLabelPadding: 10,
            color: "#d5d5d5"
        },
        yaxes: [{
            position: "left",
            color: "#d5d5d5",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Arial',
            axisLabelPadding: 3
        }, {
            position: "right",
            clolor: "#d5d5d5",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: ' Arial',
            axisLabelPadding: 67
        }],
        legend: {
            noColumns: 1,
            labelBoxBorderColor: "#000000",
            position: "nw"
        },
        grid: {
            hoverable: false,
            borderWidth: 0
        }
    };
    function gd(year, month, day) {
        return new Date(year, month - 1, day).getTime();
    }
    $.plot($("#income-line-chart"), dataset, options);

</script>