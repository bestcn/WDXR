<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{ get_title() }}

    {{ stylesheet_link("css/bootstrap.min.css") }}
    {{ stylesheet_link("font-awesome/css/font-awesome.css") }}

    {{ stylesheet_link("css/animate.css") }}
    {{ stylesheet_link("css/style.css") }}
    {{ stylesheet_link("css/c2.css") }}
    {{ stylesheet_link("css/blueimp-gallery.min.css") }}

    {{ stylesheet_link("css/bootstrap-datetimepicker.css") }}
    {{ stylesheet_link("css/plugins/toastr/toastr.min.css") }}

    <!--曲线图表-->
    {{ stylesheet_link("css/morris-0.4.3.min.css") }}
    {{ stylesheet_link("css/c3.min.css") }}

    <!-- Mainly scripts -->
    {{ javascript_include('js/jquery-3.1.1.min.js') }}
    {{ javascript_include("js/bootstrap.min.js") }}
</head>
{{ content() }}
</html>
