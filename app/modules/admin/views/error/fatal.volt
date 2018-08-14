<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>500 Error | 冀企管家业务管理后台</title>

    {{ stylesheet_link("css/bootstrap.min.css") }}
    {{ stylesheet_link("font-awesome/css/font-awesome.css") }}

    {{ stylesheet_link("css/animate.css") }}
    {{ stylesheet_link("css/style.css") }}

</head>

<body class="gray-bg">


<div class="middle-box text-center animated fadeInDown">
    <h1>500</h1>
    <h3 class="font-bold">系统内部错误</h3>

    <div class="error-desc">
        服务器产生了一些异常，因此无法完成您的请求操作。<br/>
        请点击下边的按钮返回首页: <br/><a href="{{ url('index/index') }}" class="btn btn-primary m-t">首页</a>
    </div>
</div>

{{ javascript_include('js/jquery-3.1.1.min.js') }}
{{ javascript_include("js/bootstrap.min.js") }}
</body>
</html>