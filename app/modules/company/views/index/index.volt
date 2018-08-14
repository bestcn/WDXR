<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 , maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no,email=no" name="format-detection">

    <title>企业详情</title>
</head>

<body class="gray-bg">
<!--顶部导航栏-->
<div class="nav_bar">
    {#<a class="fa fa-chevron-left icon_back" href="javascript:history.go(-1);"></a>#}
    <div class="nav_title">企业详情</div>
    <div class="logout"><a class="logout" href="/company/login/remove">退出</a></div>
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
    <!--企业信息-->
    <div class="querylist">
        <div class="loglf lf text1">企业名称：</div>
        <div class="logri lf text2" id="qyname">{{ company_data.name }}</div>
    </div>
    <div class="querylist">
        <div class="loglf lf text1">企业地址：</div>
        <div class="logri lf text2" id="qyaddress">{{ company_info_data.address }}</div>
    </div>
    <div class="querylist">
        <div class="loglf lf text1">企业性质：</div>
        <div class="logri lf text2"><span id="xingzhi1">{% if company_data.type == 1 %}个体企业{% else %}有限公司{% endif %}</span></div>
    </div>
    <div class="querylist">
        <div class="loglf lf text1">联系人：</div>
        <div class="logri lf text2" id="user">{{ company_info_data.contacts }}</div>
    </div>
    <div class="querylist">
        <div class="loglf lf text1">联系人岗位：</div>
        <div class="logri lf text2" id="userjob">{{ company_info_data.contact_title }}</div>
    </div>
    <div class="querylist">
        <div class="loglf lf text1">联系方式：</div>
        <div class="logri lf text2" id="usertel">{{ company_info_data.contact_phone }} &nbsp;
            <a href="/company/index/newtel">修改联系人</a>
        </div>
    </div>

    <hr />
    <!--服务时间-->
    <div class="querylist">
        <div class="loglf lf text1">服务时间截止：</div>
        <div class="logri lf text2" id="timer">{{ date('Y/m/d',service_data.start_time) }}　至　{{ date('Y/m/d',service_data.end_time) }}</div>
    </div>

    <hr />
    <!--报销状态-->
    <div class="querylist">
        <div class="loglf lf text1">企业报销状态：</div>
        <div class="logri lf text2" id="zhuangtai">{% if repor_status == 3 and bill_status == 3 %}您的企业已开始报销{% else %}您的企业未开始报销{% endif %} <br /><br />
            征信报告:
            {% if repor_status == 0 %}
                <a href="/company/index/report">点击补交征信报告</a>
            {% elseif repor_status == 1 %}
                <font color="blue">待审核</font>
            {% elseif repor_status == 2 %}
                <font color="red">已驳回</font>
            {%elseif repor_status == 3  %}
                <font color="green">审核成功</font>
            {% else %}
                <font color="red">已撤销</font>
            {% endif %}
            <br /><br />

            票据信息:
            {% if bill_status == 0 %}
                <a href="/company/index/bill">点击补交发票报告</a>
            {% elseif bill_status == 1 %}
                <font color="blue">待审核</font>
            {% elseif bill_status == 2 %}
                <font color="red">已驳回</font>
            {%elseif bill_status == 3  %}
                <font color="green">审核成功</font>
            {% else %}
                <font color="green">已撤销</font>
            {% endif %}
        </div>
    </div>

    <hr />
    <!--共计收益-->
    <div class="querylist">
        <div class="loglf lf text1">共计收益：</div>
        <div class="logri lf text2" id="money">{{ num }} 元</div>
    </div>

    <hr />
    <!--下级商户列表-->
    <div class="querylist">
        <div class="loglf lf text1">下级商户：</div>
        <div class="logri lf text2"><a href="/company/index/recommend/{{ company_data.id }}"><button type="button" class="btn_p">点击查看</button></a></div>
    </div>

    <hr />
    <!--企业反馈-->
    <div class="querylist">
        <div class="loglf lf text1 lh24">企业反馈：</div>
        <div class="logri lf text2"><button data-toggle="modal" data-target="#exampleModal" class="btn_p">点击输入</button></div>
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

    <hr />
    <!--修改密码-->
    <div class="querylist">
        <div class="loglf lf text1 lh24">修改查询密码：</div>
        <div class="logri lf text2"><a href="/company/index/password"><button type="button" class="btn_p">点击修改</button></a></div>
    </div>

    <hr />
    <!--在线客服-->
    <div class="querylist">
        <div class="loglf lf text1 lh26">在线客服1：</div>
        <div class="logri lf text2 lh24">18654542121 &nbsp&nbsp
            <a href="tel:18303012434" class="fa fa-phone fa-2x v_mid"></a>
        </div>
    </div>
    <div class="querylist">
        <div class="loglf lf text1 lh26">在线客服2：</div>
        <div class="logri lf text2 lh24">13898965654 &nbsp&nbsp
            <a href="tel:18303012434" class="fa fa-phone fa-2x v_mid"></a>
        </div>
    </div>
</div>