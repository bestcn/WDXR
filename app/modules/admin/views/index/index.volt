<div class="row">
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-success pull-right">总计</span>
                <h5>营收</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ amount }}</h1>
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
                <h1 class="no-margins">{{ month }}</h1>
                <div class="stat-percent font-bold text-info"><i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="上月1号至{{ date('d') }}号增加{{ last_month }}元营收"></i> {{ income_rate }}%<i class="fa fa-level-{% if income_rate > 0 %}up{% else %}down{% endif %}"></i></div>
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
                <h1 class="no-margins">{{ partner_new_companies_count }}/{{ ordinary_new_companies_count }}</h1>
                <div class="stat-percent font-bold text-navy"><i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="上月1号至{{ date('d') }}号新增{{ last_month_count }}家客户"></i> {{ company_rate }}% <i class="fa fa-level-{% if company_rate > 0 %}up{% else %}down{% endif %}"></i></div>
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
                <h1 class="no-margins">{{ partner_count }} / {{ ordinary_count }}</h1>
                <small>事业合伙人 / 普惠客户</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>31日客户增长趋势</h5>
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
                                <h2 class="no-margins">{{ partner_month_companies }}</h2>
                                <small>同期事业合伙人占比</small>
                                <div class="stat-percent">{{ partner_percent }}% </div>
                                <div class="progress progress-mini">
                                    <div style="width: {{ partner_percent }}%;" class="progress-bar"></div>
                                </div>
                            </li>
                            <li>
                                <h2 class="no-margins ">{{ ordinary_month_companies }}</h2>
                                <small>同期普惠客户占比</small>
                                <div class="stat-percent">{{ ordinary_percent }}% </div>
                                <div class="progress progress-mini">
                                    <div style="width: {{ ordinary_percent }}%;" class="progress-bar"></div>
                                </div>
                            </li>
                            <li>
                                <h2 class="no-margins ">{{ month_count }}</h2>
                                <small>同期客户增量</small>
                                <div class="stat-percent">
                                    <i data-toggle="tooltip" data-placement="top" class="fa fa-info-circle" title="上期客户增长{{ last_company_count }}家"></i>
                                    {{ company_percent }}%
                                </div>
                                <div class="progress progress-mini">
                                    <div style="width: {{ company_percent }}%;" class="progress-bar"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


{#{{ javascript_include('js/plugins/raphael-2.1.0.min.js') }}#}
{{ javascript_include('js/plugins/flot/jquery.flot.js') }}
{#{{ javascript_include('js/plugins/flot/jquery.flot.tooltip.min.js') }}#}
{{ javascript_include('js/plugins/flot/jquery.flot.spline.js') }}
{#{{ javascript_include('js/plugins/flot/jquery.flot.resize.js') }}#}
{#{{ javascript_include('js/plugins/flot/jquery.flot.pie.js') }}#}
{#{{ javascript_include('js/plugins/flot/jquery.flot.symbol.js') }}#}
{{ javascript_include('js/plugins/flot/jquery.flot.time.js') }}

<script type="text/javascript">
    var count = [
        {% for d in count %}
        [gd({{ d['year'] }}, {{ d['month'] }}, {{ d['day'] }}), {{ d['value'] }}],
        {% endfor %}
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