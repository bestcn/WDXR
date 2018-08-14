<style>
	@-webkit-keyframes twinkling{
		0%{
			opacity:0;
		}
		100%{
			opacity:1;
		}
	}
</style>


<nav class="navbar navbar-static-top white-bg m-b-none" role="navigation" >
    <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary collapse_menu" href="#"><i class="fa fa-bars"></i> </a>
        <form role="search" class="navbar-form-custom" method="get" action="{{ url('admin/tools/search') }}">
            <div class="input-group">
                <span style="border: none;" class="input-group-addon">
                    <i data-toggle="tooltip" data-placement="bottom" title="搜索企业名称、统一社会信用代码、法人姓名、联系方式、业务员姓名 ……" class="fa fa-search"></i>
                </span>
                <input autofocus type="text" placeholder="搜索企业" class="form-control" name="top-search" value="{{ request.get('top-search', 'trim') }}" id="top-search">
            </div>
        </form>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <li>
            <span class="m-r-sm text-muted welcome-message">
            </span>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                <i class="fa fa-bell"></i>  <span class="label label-primary" id="unread_messages_num"></span>
            </a>
            <ul class="dropdown-menu dropdown-alerts" id="unread_messages">
                <li class="divider"></li>
                <li>
                    <div class="text-center link-block">
                        <a href="{{ url('/admin/news/index') }}">
                            <strong>查看全部消息列表</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ url('admin/public/logout') }}">
                <i class="fa fa-sign-out"></i> 退出
            </a>
        </li>
    </ul>
</nav>
