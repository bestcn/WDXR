<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no,email=no" name="format-detection">

    <title>企业申请</title>
</head>

<body class="gray-bg">
<!--顶部导航栏-->
<div class="nav_bar">
    <a class="fa fa-chevron-left icon_back" href="javascript:history.go(-1);"></a>
    <div class="nav_title">企业申请</div>
</div>
<!--顶部导航栏2-->
<div class="nav_bar2">
    <div class="nav_logo clearfix">
        <img src="/img/logo.png" alt=""/>
    </div>
</div>
<!--企业申请内容-->
<div class="ibox-content box_h2">
    <form class="form-horizontal m-t" id="commentForm" action="login.html">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="cname">企业名称：</label>
            <div class="col-sm-8">
                <input id="cname" type="text" class="form-control" name="name" required="required">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="caddress">企业地址：</label>
            <div class="col-sm-8 sel1">
                <select id="provinces" name="provinces">
                    {% for key,data in  province %}
                    <option value="{{ key }}">{{ data }}</option>
                    {% endfor %}
                </select> 省&nbsp;
                <select id="cities" name="cities">
                    {% for key,data in  city %}
                        <option value="{{ key }}">{{ data }}</option>
                    {% endfor %}
                </select> 市&nbsp;
                <select id="areas" name="areas">
                    {% for key,data in  area %}
                        <option value="{{ key }}">{{ data }}</option>
                    {% endfor %}
                </select> 区
            </div>
            <div class="col-sm-8 col-sm-offset-3">
                <input id="caddress" type="text" class="form-control" name="address" required="required" placeholder="详细地址">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ctel">联系电话：</label>
            <div class="col-sm-8">
                <input id="ctel" type="tel" class="form-control" name="tel" required="required">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ccomment">了解渠道：</label>
            <div class="col-sm-8">
                <textarea id="ccomment" name="comment" class="form-control" rows="4" placeholder="您是从哪里获得我司信息"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-3">
                <button class="btn btn-primary " type="submit" id="btnsubmit">提交</button>
            </div>
        </div>
    </form>

    <!--提交弹出模态框-->
    <div class="modal inmodal fade in" id="myModal" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <p><strong style="color:red;">提交成功</strong>，工作人员会尽快与您联系，请保持电话畅通。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 底部 -->
<div class="foot1">
    <div class="container " id="patent-footer">
        <p class="text-center">Copright © 2016 河北省冀企管理有限公司 版权所有 &nbsp;|&nbsp; 法律声明 &nbsp;|&nbsp; 隐私条款 &nbsp;|&nbsp; 开发者中心</p>
        <p class="text-center">赣ICP备06051111号</p>
    </div>
</div>
{{ javascript_include('js/jquery-3.1.1.min.js') }}
{{ javascript_include('js/select.js') }}
<script>
    $(function () {
        $("#btnsubmit").on("click", function () {
            $('#myModal').modal('toggle')
        })
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
    })
</script>
</body>

</html>