<body>
<script>
    //左侧导航栏自动收起开关
    var collapse = localStorage.getItem('collapse_menu');
    if(collapse === 'on'){
        $('body').addClass('mini-navbar');
    } else {
        $('body').removeClass('mini-navbar');
    }
</script>
<div id="wrapper">
    {{ partial("layouts/sidebar") }}
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            {{ partial("layouts/header") }}
        </div>
        <div class="wrapper wrapper-content">
            {{ flash.output() }}
            {{ content() }}
        </div>
        <div class="footer">
            <div class="pull-right">
                System V4.0.1
                <span class="text-muted font-italic">
                    Core V{{ phalcon_version }}
                </span>
            </div>
            <div>
                <strong>Copyright</strong>  &copy; 2017 - {{ date('Y') }} 华企管家
            </div>
        </div>

    </div>
</div>

{{ javascript_include("js/moment-with-locales.min.js") }}
{{ javascript_include("js/bootstrap-datetimepicker.min.js") }}

{{ javascript_include("js/plugins/metisMenu/jquery.metisMenu.js") }}
{{ javascript_include("js/plugins/slimscroll/jquery.slimscroll.min.js") }}

<!-- Custom and plugin javascript -->
{{ javascript_include("js/inspinia.js") }}
{{ javascript_include("js/plugins/pace/pace.min.js") }}

{{ javascript_include("js/plugins/toastr/toastr.min.js") }}

{{ javascript_include('js/jquery.blueimp-gallery.min.js') }}
{{ javascript_include('js/common.js') }}

<script type="text/javascript">
    $(function () { $("[data-toggle='tooltip']").tooltip(); });
    //左侧导航栏自动收起开关（设置Web Storage）
    $('.collapse_menu').on('click',function(){
        var collapse = localStorage.getItem('collapse_menu');
        if(collapse === 'on'){
            localStorage.setItem('collapse_menu', 'off');
        }else{
            localStorage.setItem('collapse_menu', 'on');
        }
    });
</script>
</body>