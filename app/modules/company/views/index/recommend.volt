<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 , maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no,email=no" name="format-detection">

    <title>企业详情</title>

</head>

<body class="gray-bg">
<!--顶部导航栏-->
<div class="nav_bar">
    <a class="fa fa-chevron-left icon_back" href="/company/index/index"></a>
    <div class="nav_title">商户列表</div>
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
<div class="ibox-content box_h2 text-center">
    <!--商户列表-->
    {% for data in  reommend_data %}
    <div>
        <span>{{ data['name'] }}</span><span></span><br />
        <span>{{ data['start_time'] }}　至　{{ data['end_time'] }}</span>
    </div>
    <hr />
    {% endfor %}



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