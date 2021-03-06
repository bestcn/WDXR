<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="">{{ acl_button(['admin/companys/info/'~id~'/', '基本信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/payment/'~id, '缴费信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/business/'~id, '业务信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/user/'~id, '账号信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/bill/'~id, '票据信息']) }}</li>
                <li class="active">{{ acl_button(['admin/companys/report/'~id, '征信报告']) }}</li>
                <li class="">{{ acl_button(['admin/companys/contract/'~id, '合同信息']) }}</li>
                <li class="">{{ acl_button(['admin/companys/setting/'~id, '企业设置']) }}</li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="panel-body">

                        {% for service in services %}
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5 title="服务订单ID">服务订单ID：{{ service['id'] }}
                                        <small title="状态" class="m-l-sm">
                                            征信状态：{{ service['report_status'] }}
                                        </small>
                                    </h5>
                                </div>
                                <div class="ibox-content">

                                    <div class="row animated fadeInRight">
                                        <div class="col-lg-12">
                                            <div class="ibox float-e-margins">
                                                <div id="ibox-content">
                                                    {% if service['info'] is empty %}
                                                        没有征信记录
                                                    {% else %}
                                                    <div id="vertical-timeline" class="vertical-container light-timeline">
                                                            {% for data in service['info'] %}
                                                                <div class="vertical-timeline-block">
                                                                    <div class="vertical-timeline-icon blue-bg">
                                                                        <i class="fa fa-file-text"></i>
                                                                    </div>
                                                                    <div class="vertical-timeline-content">
                                                                        <h2>{{  data['createAt'] }}</h2>
                                                                        <div class="row">
                                                                            {% for pic in  data['report'] %}
                                                                                <div class="col-sm-4">
                                                                                    <div class="panel panel-success">
                                                                                        <div class="panel-body">
                                                                                            <a href="{{ pic }}" data-gallery="">
                                                                                                <img width="100%" src="{{ pic }}">
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            {% endfor %}
                                                                        </div>
                                                                        <span class="vertical-date">{{  data['device_name'] }} <br/><small>　({{ data['status'] }})</small></span>
                                                                    </div>
                                                                </div>
                                                            {% endfor %}
                                                    </div>
                                                    {% endif %}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        {% endfor %}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <a class="xuanzhuan">旋转</a>
    <a class="fangda">放大</a>
    <a class="suoxiao">缩小</a>
    <ol class="indicator"></ol>
</div>