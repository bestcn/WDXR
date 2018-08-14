<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="{{ admin_name }}" class="img-circle img-sm" src="{{ avatar | default('/img/logo.png') }}">
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle">
                        <span class="clear">
                            <span class="block m-t-xs"> <strong class="font-bold">{{ admin_name }}</strong></span>
                            <span class="text-muted text-xs block">{{ position }} <b class="caret"></b></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{ url('admin/profiles/password') }}">修改密码</a></li>
                        <li><a href="{{ url('admin/public/logout') }}">退出</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    WDXR
                </div>
            </li>

            {{ acl_menu('admin/index/index', '<i class="fa fa-th-large"></i> <span class="nav-label">欢迎</span>') }}

            {{ acl_group('<i class="fa fa-sitemap"></i> <span class="nav-label">公司管理 </span><span class="fa arrow"></span>',
            [['admin/admins/search', '人员管理'],
            ['admin/positions/index', '职能管理'],
            ['admin/acl/index', '权限控制管理'],
            ['admin/branchs/index', '分公司管理'],
            ['admin/bonus/index', '奖金制度管理']]) }}

            {{ acl_group('<i class="fa fa-pencil-square-o"></i> <span class="nav-label">业务申请 </span><span class="fa arrow"></span>',
            [['admin/companys/new_list', '缴费待申请列表'],
            ['admin/apply/list', '企业申请列表'],
            ['admin/loan/edit_list', '普惠待补录列表'],
            ['admin/companys/edit_list', '企业待补录列表'],
            ['admin/companys/verify_list', '我的申请记录']]) }}

            {{ acl_group('<i class="fa fa-group"></i> <span class="nav-label">客户资料 </span><span id="unread_messages_companys_num"><span class="fa arrow"></span></span>',
            [
                ['admin/companys/basic', '基本设置'],
                ['admin/companys/new_company_list', '未入驻企业'],
                ['admin/companys/index', '已入驻企业'],
                ['admin/companys/disabled_company_list', '未生效企业'],
                ['admin/companys/black_list', '客户黑名单'],
                ['admin/stat/company_list_by_admin', '客户统计'],
                ['admin/companys/bill_list', '企业票据列表']
            ]
            ) }}


            {{ acl_group('<i class="fa fa-check-square-o"></i> <span class="nav-label">业务流程 </span><span id="unread_messages_finance_num"><span class="fa arrow"></span></span>',
            [['admin/companys/auditing', '企业审核列表<span id="unread_messages_company_num" class="label label-danger pull-right" ></span>'],
             ['admin/finance/payment', '缴费审核列表<span id="unread_messages_payment_num" class="label label-warning pull-right" ></span>'],
             ['admin/loan/index', '普惠审核列表<span id="unread_messages_loan_num" class="label label-warning pull-right" ></span>'],
             ['admin/companys/billVerify', '待审核票据列表<span id="unread_messages_bill_num" class="label label-warning pull-right" ></span>'],
             ['admin/companys/reportVerify', '待审核征信列表<span id="unread_messages_report_num" class="label label-warning pull-right" ></span>']]) }}

            {{ acl_group('<i class="fa fa-table"></i> <span class="nav-label">财务报表 </span><span class="fa arrow"></span>',
            [['admin/finance/index', '报销财务报表'],
            ['admin/finance/recommend', '推荐奖励报表'],
            ['admin/finance/manage', '管理奖励报表'],
            ['admin/finance/statistics', '详细统计列表']]) }}

            {{ acl_group('<i class="fa fa-bar-chart-o"></i> <span class="nav-label">业绩列表 </span><span class="fa arrow"></span>',
            [['admin/finance/branch_achievement', '分站业绩统计'],
            ['admin/finance/achievement', '业务员业绩统计'],
                ['admin/finance/bonus', '奖金列表(新)']]) }}

            {{ acl_group('<i class="fa fa-envelope"></i> <span class="nav-label">消息 </span><span id="news_num"><span class="fa arrow"></span></span>',
            [['admin/sms/send_list', '短信模板列表'],
            ['admin/sms/index', '短信日志列表'],
            ['admin/news/index', '所有消息列表'],
            ['admin/news/unread', '未读消息列表<span id="new_num" class="label label-info pull-right"></span>']]) }}

            {{ acl_group('<i class="fa fa-file-text"></i> <span class="nav-label">系统设置 </span><span class="fa arrow"></span>',
            [['admin/setting/index', '版本更新'],
            ['admin/message/index', '系统消息'],
            ['admin/setting/account', '企业账户管理'],
            ['admin/setting/term', '票据审核期限设置'],
            ['admin/setting/rterm', '征信审核期限设置'],
            ['admin/setting/feedBack', '系统反馈']]) }}
        </ul>
    </div>
</nav>
