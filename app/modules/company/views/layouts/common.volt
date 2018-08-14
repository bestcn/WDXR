
<!-- Page Content begin -->{{ content() }}<!-- Page Content end -->
    {{ javascript_include('/company_js/jquery-3.1.1.min.js') }}
    {{ javascript_include('/company_js/bootstrap.js') }}
    {{ javascript_include('/company_js/common.js') }}
    {{ javascript_include('/company_js/login.js') }}
    {{ javascript_include('/company_js/chaxun.js') }}
    {{ javascript_include('/company_js/password.js') }}
    {{ javascript_include('/company_js/shulist.js') }}
    {{ javascript_include('/company_js/dropzone/dropzone.js') }}
    {{ javascript_include('/company_js/fapiao.js') }}
    {{ javascript_include('/company_js/newtel.js') }}
<!-- 底部 -->
<div class="foot1">
    <div class="container " id="patent-footer">
        <p class="text-center">Copright © 2016 河北省冀企管理有限公司 版权所有 &nbsp;|&nbsp; 法律声明 &nbsp;|&nbsp; 隐私条款 &nbsp;|&nbsp; 开发者中心</p>
        <p class="text-center">测试赣ICP备06051111号</p>
    </div>
</div>

</body>

</html>
<script>

    Dropzone.options.report = {
        paramName: "report",
        autoProcessQueue : false,

        init: function() {
            var myDropzone = this;
            var submitbtn=$("#report_btn");
            submitbtn.on("click", function () {
                myDropzone.processQueue();
            })
            myDropzone.on("success",function(file) {
                location.href = "/company/index/result/1/上传成功";
            });
            myDropzone.on("error",function(file) {
                LT.toast("上传失败");
            });
        },
    };

    /*底部始终在底部*/
    $(function () {
        var aa=$(".ibox-content").outerHeight();
        var bb=$(".foot1").outerHeight();
        var cc=$(".nav_bar2").outerHeight();
        var dd=$(window).height()-bb-cc;

        if(aa<dd){
            $(".foot1").addClass("bo");
            $(".box_h2").css({height:dd});
        }else{
            $(".foot1").removeClass("bo");
        }
    })
</script>

