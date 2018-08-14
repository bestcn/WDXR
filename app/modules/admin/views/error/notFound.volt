<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>404 Error | 冀企管家业务管理后台</title>

    {{ stylesheet_link("css/bootstrap.min.css") }}
    {{ stylesheet_link("font-awesome/css/font-awesome.css") }}

    {{ stylesheet_link("css/animate.css") }}
    {{ stylesheet_link("css/style.css") }}

</head>

<body class="gray-bg">


<div class="middle-box text-center animated fadeInDown">
    <h1>404</h1>
    <h3 class="font-bold">页面找不到</h3>

    <div class="error-desc">
        您正在访问的页面找不到，也有可能您访问的资源已经丢失或者被删除。请重新检查链接是否错误，或者及时联系技术人员。
    </div>
</div>

<!-- Mainly scripts -->
{{ javascript_include('js/jquery-3.1.1.min.js') }}
{{ javascript_include("js/bootstrap.min.js") }}

</body>

</html>