<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 , maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no,email=no" name="format-detection">
    <meta http-equiv="refresh" content="3;URL={{ url }}">
    <title></title>
</head>

<body class="gray-bg">
<!--顶部导航栏-->
<div class="nav_bar">
    {#<a class="fa fa-chevron-left icon_back" href="javascript:history.go(-1);"></a>#}
    <div class="nav_title">3 秒钟后跳转</div>
</div>
<!--顶部导航栏2-->
<div class="nav_bar2">
    <div class="container">
        {#<a class="fa fa-chevron-left icon_back" href="javascript:history.go(-1);"></a>#}
        <div class="nav_title2">3 秒后跳转</div>
    </div>
</div>
<div class="ibox-content box_h2 text-center">
    <!--驳回信息-->
    <div class="tj_img">
        {% if status == 1 %}<img src="/img/success.png" alt=""/>{% else %}<img src="/img/error.png" alt=""/>{% endif %}
    </div>
    <div class="tj_result">{{ content }}</div>

</div>
