<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no,email=no" name="format-detection">

    <title>发票补交</title>
</head>

<body class="gray-bg">
<!--顶部导航栏-->
<div class="nav_bar">
    <a class="fa fa-chevron-left icon_back" href="/company/index/index"></a>
    <div class="nav_title">发票补交</div>
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
<!--内容-->
<div class="ibox-content box_h2 container">
    <!--水费-->
    <div class="ibox">
        <div class="ibox-title del-line">
            <h5><font><font>水费发票:</font></font></h5>
            {#<button type="button" class="btn btn-primary btn-xs pull-right w20 submitbtn" id="_btn">提交</button>#}
        </div>

        <div class="ibox-content">
            <form action="/company/index/bill" class="dropzone dz-clickable" id="waterfee">
                <input type="hidden" name="id" value="{{ id }}"/>
                <div class="dz-default dz-message">
                    <span>
                        <strong><font><font>点击这里删除文件或上传</font></font></strong>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <!--电费-->
    <div class="ibox">
        <div class="ibox-title del-line">
            <h5><font><font>电费发票:</font></font></h5>
            {#<button type="button" class="btn btn-primary btn-xs pull-right w20 submitbtn" id="dianfei_btn">提交</button>#}
        </div>

        <div class="ibox-content">
            <form action="/company/index/bill" class="dropzone dz-clickable" id="electricity">
                <input type="hidden" name="id" value="{{ id }}"/>
                <div class="dz-default dz-message">
                    <span>
                        <strong><font><font>点击这里删除文件或上传</font></font></strong>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <!--物业-->
    <div class="ibox">
        <div class="ibox-title del-line">
            <h5><font><font>物业发票:</font></font></h5>
            {#<button type="button" class="btn btn-primary btn-xs pull-right w20 submitbtn" id="wuye_btn">提交</button>#}
        </div>

        <div class="ibox-content">
            <form action="/company/index/bill" class="dropzone dz-clickable" id="propertyfee">
                <input type="hidden" name="id" value="{{ id }}"/>
                <div class="dz-default dz-message">
                    <span>
                        <strong><font><font>点击这里删除文件或上传</font></font></strong>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <!--租赁合同-->
    <div class="ibox">
        <div class="ibox-title del-line">
            <h5><font><font>租赁合同:</font></font></h5>
            {#<button type="button" class="btn btn-primary btn-xs pull-right w20 submitbtn" id="zulin_btn">提交</button>#}
        </div>

        <div class="ibox-content">
            <form action="/company/index/bill" class="dropzone dz-clickable" id="rentcontract">
                <input type="hidden" name="id" value="{{ id }}"/>
                <div class="dz-default dz-message">
                    <span>
                        <strong><font><font>点击这里删除文件或上传</font></font></strong>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <!--房东收条-->
    <div class="ibox">
        <div class="ibox-title del-line">
            <h5><font><font>房东收条:</font></font></h5>
            {#<button type="button" class="btn btn-primary btn-xs pull-right w20 submitbtn" id="fangdong_btn">提交</button>#}
        </div>

        <div class="ibox-content">
            <form action="/company/index/bill" class="dropzone dz-clickable" id="rentreceipt">
                <input type="hidden" name="id" value="{{ id }}"/>
                <div class="dz-default dz-message">
                    <span>
                        <strong><font><font>点击这里删除文件或上传</font></font></strong>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <!--房租发票-->
    <div class="ibox">
        <div class="ibox-title del-line">
            <h5><font><font>房租发票:</font></font></h5>
            {#<button type="button" class="btn btn-primary btn-xs pull-right w20 submitbtn" id="fangzu_btn">提交</button>#}
        </div>

        <div class="ibox-content">
            <form action="/company/index/bill" class="dropzone dz-clickable" id="rent">
                <input type="hidden" name="id" value="{{ id }}"/>
                <div class="dz-default dz-message">
                    <span>
                        <strong><font><font>点击这里删除文件或上传</font></font></strong>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <button type="button" class="btn btn-primary btn-xs pull-right w20" id="_btn">提交</button>
    <!-- 提示信息 -->
    <div class="tips">
        <p></p>
    </div>
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