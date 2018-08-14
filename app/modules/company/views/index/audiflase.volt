<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 , maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no,email=no" name="format-detection">

    <title>查询结果</title>

</head>

<body class="gray-bg">
<!--顶部导航栏-->
<div class="nav_bar">

    <div class="nav_title">查询结果</div>
</div>
<!--顶部导航栏2-->
<div class="nav_bar2">
    <div class="container">

        <div class="nav_title2">查询结果</div>
    </div>
</div>
<div class="ibox-content box_h2 text-center">
    <!--驳回信息-->
    <div>您的企业证件未提交或已被驳回</div>
    <br/>
    <div class="querylist">
        <div >驳回原因：&nbsp; </div>
        <div class="cause">{{ content }}</div>
    </div>
    <div class="querylist">
        <div>业务人员会尽快与您取得联系</div>
    </div>
    <div class="querylist">
        <div>如有问题请拨打客服电话：110</div>
    </div>
    <div class="m-t2 full-width ">
        <a class="btn btn-primary w50 bohui" href="/company/login/remove">确认</a>
    </div>
</div>
