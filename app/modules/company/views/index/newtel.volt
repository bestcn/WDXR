<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no,email=no" name="format-detection">

    <title>修改联系人</title>
</head>

<body  class="gray-bg">
<!--顶部导航栏-->
<div class="nav_bar">
    <a class="fa fa-chevron-left icon_back" href="/company/index/index"></a>
    <div class="nav_title">修改联系人</div>
</div>
<!--顶部导航栏2-->
<div class="nav_bar2">
    <div class="nav_logo clearfix">
        <img src="/img/logo.png" alt=""/>
        <div>boss 您好，欢迎进入此系统 丨 <span class="logout"><a class="logout" href="/company/login/remove">退出</a></span></div>
    </div>
    <ul class="nav_list clearfix">
        <li><a href="/">首 &nbsp; 页</a></li>
        <li><a href="/company/index">企业详情</a></li>
        <li><a href="/company/index/newtel">修改联系人</a></li>
        <li><a href="/company/index/password">修改密码</a></li>
        <li><a href="/company/index/recommend/{{ company_data.id }}">下级商户</a></li>
        <li><a data-toggle="modal" data-target="#exampleModal">企业反馈</a></li>
    </ul>
</div>
<!--内容-->
<div class="ibox-content box_h2 container">
    <form class="form-horizontal m-t" action="/company/index/newtel" method="post" id="commentForm">

        <div class="form-group">
            <label class="col-sm-3 control-label col-lg-4" for="new_user">新联系人姓名：</label>
            <div class="col-sm-8 col-lg-7">
                <input id="new_user" type="text" class="form-control" name="new_user" autocomplete="off">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label col-lg-4" for="new_job">新联系人岗位：</label>
            <div class="col-sm-8 col-lg-7">
                <input id="new_job" type="text" class="form-control" name="new_job" autocomplete="off">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label col-lg-4" for="new_tel">联系人手机号：</label>
            <div class="col-sm-8 col-lg-7">
                <input id="new_tel" type="tel" class="form-control col-xs-8 w50 m-r" name="new_tel"  autocomplete="off">
                <input class="btn btn-primary btn_getCode" id="sms" type="button" value="短信验证">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label col-lg-4" for="test_pass">验证码：</label>
            <div class="col-sm-8 col-lg-7">
                <input id="test_pass" type="text" class="form-control w50" name="code" autocomplete="off">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-3 col-lg-4 col-lg-offset-4">
                <input class="btn btn-primary btn_uptel" type="submit" value="提交">
            </div>
        </div>
    </form>
    <!-- 提示信息 -->
    <div class="tips">
        <p></p>
    </div>

</div>
<!--企业反馈弹窗-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/company/index/feedback" method="post">
                <div class="modal-body mbpb">
                    <div class="form-group">
                        <label for="message-text" class="control-label">反馈信息:</label>
                        <textarea class="form-control fkwindow" rows="6"  id="message-text" name="feedback"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary " data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary" >提交</button>
                </div>
            </form>
        </div>
    </div>
</div>
