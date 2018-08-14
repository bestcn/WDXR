<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no,email=no" name="format-detection">

    <title>查询登录</title>

</head>
<style>
    .login-bg{
        background-image: url("/img/beijing.png");
    }
</style>
<body class="login-bg">
<div class="loginColumns text-center">
    <div class="row">

        <!--企业logo-->
        <div class="pa15">
            <div class="login_logo">
                <img src="/img/login.png" class="w62" alt="login"/>
            </div>
        </div>

        <!--企业登录-->
        <div class="pa15">
            <div class="ibox-content ">
                    {{ form("company/login", "method":"post", "autocomplete" : "off", "class" : "m-t") }}
                    <div class="form-group">
                        <i class="fa fa-user-circle-o fa-2x fa-fw pull-left" aria-hidden="true"></i>
                        <input type="text" class="form-control" placeholder="企业名称"  name="username">
                    </div>
                    <div class="form-group">
                        <i class="fa fa-unlock-alt fa-2x fa-fw pull-left" aria-hidden="true"></i>
                        <input type="password" class="form-control" placeholder="查询密码"  name="password" autocomplete="off" >
                    </div>
                    <button class="btn btn-primary full-width m-t btn_login" type="submit">登录</button>
                {{ end_form() }}
            </div>
        </div>
        <div class="text-right back01">
            <a href="/">返回首页</a>
        </div>
        <!-- 提示信息 -->
        <div class="tips">
            <p></p>
        </div>

    </div>


</body>


</html>
