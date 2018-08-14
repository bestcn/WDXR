<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        *{
            margin: 0;
            padding: 0;
            font-family: SimSun, serif;
        }
        body{
            margin: 0; padding: 0;
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAALkAAAC5BAMAAACGg4NvAAAAAXNSR0IArs4c6QAAAC1QTFRFAAAAIhgUDDR8OyYmKBsjHxsiHR4tIhgU1hQYIxgU1hQY2CYZ89YjQythAAAAyVKx6AAAAA90Uk5TACYlBAsYEiMlHhgPHCEAaMiMawAABJtJREFUeNrtWT1PI1cUvbtvPBt7EylXeDxyFz12URqkIRgcJ4o0BoxF5wlrEN0YjC06RhAsupXSjHYbRsa23CGlQdAgpUGQYn8ARUQRRfk1ufNsslumeFeRondkW3ZzfHzufffjGcDAwMDAwMDAwMDAwOA/ggBos5Gv+2BLj4l8JQTYlV/xkNuv6CnlHA/7j41UupzlkU6OZ6SUnuBg/0YCPJJ0iyWuuxK+kHJ+WbLE9VGm0v+QPHGV8gc5wSsW7Qrza/J3gNwNeJp9T/Hafy69Fe/yZ/hV70lNyec8WHltyb/iC8hpZX8+IQc5uyLjd2SOp/c0qXBasiO/ji+81Hq9STOnfsLuPEn/7eHh4YPewypVkZR/kuufE/uDTm8sJZ5yMn5Pn75/uL//oDlrZu1UunL88gK01jMSL3en0uEl2QP6U/5Jeqy7VIrHJ9fFbZry2jsISf/l6uoqjuO3DA2KpN/GKd5x9JBcfK/I4xtgQS7lvmIiB/iOx5Un3Mac02Qu5tQuWLXDe9Y5+y2r7xec7J/dcLLfsmr/Nr6+u2bT/5Kz0KSHlXDN5s3dZcwa2TuzXv9LeLDKyL6/t8VW1nxouUU25UOoILGf8bAHwwpugjXDw15GPAGo5Jms6boeLCY8zqyD2APYZorrWM3fEfosCVm0aAQpN3mcyRTKjg9NELrHnEz68uIock/DMUMqNsiYZ310SydJQ7/lpz4cZBExDFoOh+cCzwM8X8JOxDBd1vsjxKgQYaPEkY4jv+VWEIscyb4/sB0c/IQuB7lFpbGFzUJzj+Ug4THYvZ3jT63S6Hqb2mn15JNsqQ2029OfvlusJXikmX2xStV9adAcU266I93h3bDxDERtPOqdvvFs3QUhgeBjR800dNsOCzP/5EtWM3umCFY4qcZZDxZCvezZaXGs+umjpZnd2pzEFn0LGxCEHKe2jkOoU2YmIUvBccBGCi4Le4ZMqaLbAGwwsGexb+EYqVM1WLQ7FWdx27Ge2IXObxHb6IY08Fl4mEZhrTM61NoBA1UpRRpZijFNCXqzZlt1v6CQfigNk77m/q2IK2oSFsdlvROxaB64akZQKZS30BOh1tOqRuxSuqQ9O4LWWldnYMXBNJKVM7CooqHm1F9W3duOaI5foi/rcVwsZote3a+SReUCR0HzYcFJPNgRCcuqA6KLS72imu71Y8mDjeOao3/VUcoH7hbUQ6bLYnsmwYYYMi3erWKAea/ME1JaFWjic9OsF+peSGtCRsW1gz1/A7+klZD0j7XSV52um+4KNSoLAbFrHXBEVIDlkTdth20PAp1Jn3GSc9GaMFIhc7e0bsnZsExBndTeTHuxlESOVuOpw6YRVSBiq1vU+w/45vo0kkJtVPUCz7my0B34FGiegwUJYn4j4pjPqr1ejwKctyNkuHSy9nfwzfo+TWgMc7EYrpYwBCakiYlMd7dgt9c6AR4ysVu1kdJeZmlRtFyWEhzu8FyBZs6ghkVEnuvbDPbQdUqdVSZ2PA0jrj+RrXGbab38WGYOOdmDPCd71eFkX2BlfzHDyW6z5oyBgYGBgYGBgYGBwf8AfwMUCgAxu1MPawAAAABJRU5ErkJggg==);
            line-height: 35px;
            letter-spacing: 1px;
        }
        div{
            box-sizing: border-box;
            font-size: 20px;
            margin-bottom: 10px;
        }
        span{
            display: inline-block;
            border-bottom: 1px solid #000000;
        }
        .pull-l{
            float: left;
        }
        .pull-r{
            float: right;
        }
        .m-t{
            margin-top: 30px;
        }

        .m-t25{
            margin-top: 25px;
        }
        .m-t40{
            margin-top: 40px;
        }
        .m-t60{
            margin-top: 60px;
        }
        .m-t100{
            margin-top: 200px;
        }
        .m-t300{
            margin-top: 440px;
        }
        .m-b15{
            margin-bottom: 15px;
        }
        .m-b25{
            margin-bottom: 25px;
            margin-top: 30px;
        }
        .m-b30{
            margin-bottom: 30px;
        }
        .m-b35{
            margin-bottom: 35px;
        }
        .m-b50{
            margin-bottom: 50px;
        }
        .m-r30{
            margin-right: 30px;
        }
        .pl-25{
            padding-left: 25px;
        }
        .ta-c{
            text-align: center;
        }
        .ta-l{
            text-align: left;
        }
        .ta-r{
            text-align: right;
        }
        .t-i{
            text-indent:2em;
        }
        .body-box{
            padding: 35px;
            min-width: 770px;

        }
        .w40{
            width: 40%;
        }
        .w100{
            width: 100%;
        }
        .fz17{
            font-size: 20px;
        }
        .ib{
            display: inline-block;
        }
        .ipt{
            border: none;
            border-bottom: 1px solid black;
            width: 100px;
            padding: 1px 25px;
            outline:none;
            font-size: 17px;
        }
        .title1{
            border-bottom: 1px solid #000000;
            font-size: 13px;
        }
        .title{
            line-height: 40px;
            font-size: 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .qingkuang{
            padding-left: 30px;
            padding-right: 60px;
        }
        .w42{
            min-width: 50px;
            vertical-align: middle;
            position: relative;
            top: -5px;
        }
        .w60{
            min-width: 60px;
            vertical-align: middle;
            position: relative;
            top: -6px;
        }
        .w120{
            min-width: 120px;
            vertical-align: middle;
            position: relative;
            top: -6px;
        }
        .w150{
            min-width: 150px;
            vertical-align: middle;
            position: relative;
            top: -5px;
        }
        .w200{
            min-width: 200px;
            vertical-align: middle;
            position: relative;
            top: -3px;
        }
        .w270{
            min-width: 400px;
            vertical-align: middle;
            position: relative;
            top: -6px;
            height:27px;
        }
        .w280{
            min-width: 300px;
            vertical-align: middle;
            position: relative;
            top: -5px;
        }
        .w400{
            width: 400px;
            height:30px;
        }
        .qixian{
            position: relative;
            /*line-height: 25px;*/
        }
        .qixian span{
            text-align: center;
            text-indent: 0;
            height: 28px;

        }
        .qixian2{
            /*line-height: 25px;*/
        }
        .si-yiwu{
            text-indent: 2em;
        }
        table{
            text-indent:0;
            width: 100%;
            min-width: 480px;
            text-align: center;
        }
        table td{
            width: 160px;
            height: 50px;
        }
        tr{
            height: 40px;
        }
        .tr td{
            height: 100px;
        }
        .tr1 td{
            height: 150px;
        }
        .tr2 td{
            height: 140px;
        }
        .mingxi .td1{
            width: 30%;
        }
        .mingxi .td2{
            width: 70%;
        }
        .mingxi .tr1{
            height: 50px;
        }
        .gaizhang1{
            position: relative;
        }
        .gaizhang1 img{
            width: 150px;
            height: 75px;
            position: absolute;
            top: 0;
            left: 213px;
        }
        .gaizhang2{
            position: relative;
        }
        .gaizhang2 img{
            width: 150px;
            height: 75px;
            position: absolute;
            top: 0;
            left: 213px;
        }
        .bianhao{
            font-size: 20px;
        }
        .blank {
            width: 13px;
            height: 13px !important;
            border: 1px solid #000;
            margin-right: 2px;
            margin-left: 2px;
        }
        .qq{
            width: 13px;
            height: 13px !important;
            background-color: #000000;
            border: 1px solid #000;
            margin-right: 2px;
            margin-left: 2px;
        }
        .ww{
            width: 16px;
            height: 16px !important;
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADMAAAA0CAMAAAAQevCgAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpDNjI1NURCNjUyMzIxMUU3QkU5QjlGMjk4NUI4OTVCNiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpDNjI1NURCNzUyMzIxMUU3QkU5QjlGMjk4NUI4OTVCNiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkM2MjU1REI0NTIzMjExRTdCRTlCOUYyOTg1Qjg5NUI2IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkM2MjU1REI1NTIzMjExRTdCRTlCOUYyOTg1Qjg5NUI2Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+ePxHkQAAAVZQTFRF/v7+AQEBampqBQUFx8fHAwMD+/v77+/vRkZGCQkJlZWV9/f3/f39PDw8XV1dSUlJnp6e5eXlICAgzMzMV1dXsbGxDg4OEhISERER8fHxMTExAgICiIiIvr6+GBgYxcXFzs7O6enp1dXV29vb2tra6OjoYWFhycnJGhoa8/Pz9PT06+vr5OTk4uLir6+v7u7u9vb2+vr6/Pz8ubm5kZGR2NjY5+fnCAgIjIyMbm5uTU1N4ODgXl5eq6urLCwsYmJiOzs7Ojo6UVFRKysreXl5aWlpY2NjZmZmFxcXKSkp0NDQu7u7CgoK8vLyBgYGGRkZsrKy1NTU+Pj4xMTEJycnDAwMrq6ujY2NSEhILi4uISEhKioqqqqqFRUVz8/PpKSkiYmJQ0NDfn5+BwcHoqKira2t0dHRBAQEmpqa09PTa2trqKioVVVVGxsbKCgoPj4+AAAA////uyqKQAAAAXVJREFUeNrkllVXw0AQhWP1lnqhpcUdiru7u0tx983/fyHMpsDhkHQvj7Av2eyd75xkMjM3ko4vSWfo+quMYMZApsbrRxl/gKU0kDkzguQxiImuwjlo3KaoQYQpoqDjXoC54g91DeSgZ4di6oBc+1IU8tIEMApF9C0CdbDLX8YL1M5QCQVMAvXmnCA94gKYaZJVDajrpVKS00Av1JaRui+JM5KbxLVKoOfSpDkyQJ9qKmkX1r19svUNSUZIeu20ZoIsWPVDAxRnrWdIhczYrdL8qT/ymmm1mTsxus7H83ILr5kZm1lV7TC7fXaFp/mc7kbbbZj6hfyIOIy+q3u092zYzkRn6NmEPDldv7mkbXehOZpUeHEx+W7zgDZuqfDsLZ8yoXGe5rDIvPbNfTWAEcEZH1I/kGFhX+haNpGGNnEviZtfah3xnwE6iUGe5UoYB4kw5nMPxkE/6I33jB11oH4aYBnYg0+fRHz7n//v/OY/EV9vAgwAUXJmYF7KNZoAAAAASUVORK5CYII=);
            background-size:16px;
            border-bottom: none;
        }
        .double-strong {
            font-weight: bold;
            text-decoration: underline;
            text-decoration-style: double;
        }
        .circle {
            margin-right: 5px; font-size: 18px;text-indent: 0; width:18px; height:18px !important; line-height: 18px; text-align: center; border-radius:25px; border:1px #000 solid;letter-spacing: -2px;  padding-right: 2px;
        }
    </style>
</head>
<body>
<div class="body-box">
    {#<div class="ta-r title1">文本编码：WDXR-HT2017(02)</div>#}
    <div class="title">企业服务协议书</div>
    <div class="fz17">甲方：石家庄微贷鑫融企业信息咨询有限公司</div>
    <div class="fz17">乙方：<span style="width: 357px;" class="w400">{{ name }}</span></div>
    <div class="t-i">
        石家庄微贷鑫融企业信息咨询有限公司独创“冀企管家”服务平台，倾力打造WDXR智能化1+6服务系统，为企业转型升级保驾护航。
    </div>
    <div class="t-i">
        依据我国《合同法》等有关法律法规之规定，甲方与乙方本着诚实信用、平等自愿的原则，经协商一致，订立本合同，以共同信守。
    </div>
    <div class="m-t25">
        一、乙方基本情况
    </div>
    <div class="qingkuang m-b30">
        <div>统一社会信用代码：<span class="w270">{{ licence_num }}</span></div>
        <div>名 &nbsp;&nbsp; 称：<span style="min-width: 484px;" class="w270">{{ name }}</span></div>
        <div>类 &nbsp;&nbsp; 型：<span style="min-width: 484px;" class="w270">{{ type }}</span></div>
        <div>法定代表人：<span style="min-width: 463px;" class="w270">{{ legal_name }}</span></div>
        <div>实际经营地址：<span style="min-width: 442px;" class="w270">{{ address }}</span></div>
        <div>经营范围：<span style="min-width: 484px;" class="w270">{{ scope }}</span></div>
        <div>营业期限：<span style="min-width: 484px;" class="w270">{{ period }}</span></div>
        <div>联 系 人：<span style="min-width: 484px;" class="w270">{{ contacts }}</span></div>
        <div>联系方式：<span style="min-width: 484px" class="w270">{{ contact_phone }}</span></div>
    </div>
    <div class="t-i m-b30">
        <strong>乙方在签订本协议之前，已经充分知悉甲方的服务内容、“冀企管家”服务平台管理办法与收费标准等，乙方已详细了解并无异议。</strong>
    </div>
    <div>
        二、服务期限
    </div>
    <div class="t-i qixian">
        乙方入驻“冀企管家”服务平台期限为<span class="w42">1</span>年，即<span class="w42">{{ start_time ? date('Y', start_time) : '' }}</span>年<span class="w42"> {{ start_time ? date('m', start_time) : '' }}</span>月<span class="w42"> {{ start_time ? date('d', start_time) : '' }}</span>日至<span class="w42"> {{ end_time ? date('Y', end_time) : '' }}</span>年<span class="w42"> {{ end_time ? date('m', end_time) : '' }}</span>月<span class="w42">{{ end_time ? date('d', end_time) : '' }}</span>日。
    </div>
    <div class="m-t25">
        三、服务费用及支付方式
    </div>
    <div class="t-i qixian">
        1、乙方向甲方支付年度服务费用总计人民币（大写）<span class="w120">{{ money_chinese }}</span>元（￥<span class="w60">{{ money }}</span> 元）。
        企业等级为
        Ⅴ1{% if level == 1 %}<span class="ww"></span>{% elseif level == '' %} <span class="blank"></span> {% else %}<span class="qq"></span>{% endif %}
        Ⅴ2{% if level == 2 %}<span class="ww"></span>{% elseif level == '' %} <span class="blank"></span> {% else %}<span class="qq"></span>{% endif %}
        Ⅴ3{% if level == 3 %}<span class="ww"></span>{% elseif level == '' %} <span class="blank"></span> {% else %}<span class="qq"></span>{% endif %}
        。（选择的<span class="ww"></span>，不选择的<span class="qq"></span>）
    </div>
    <div class="t-i qixian">
        2、本协议签订之日，乙方需向甲方支付年度服务费用人民币（大写）<span class="w120">{{ money_chinese }}</span>元（￥<span class="w60">{{ money }}</span> 元）。
        现金{% if payment_type == 2 %}<span class="ww"></span>{% elseif payment_type == '' %} <span class="blank"></span> {% else %}<span class="qq"></span>{% endif %}
        转账{% if payment_type == 1 %}<span class="ww"></span>{% elseif payment_type == '' %} <span class="blank"></span> {% else %}<span class="qq"></span>{% endif %}
        POS{% if payment_type == 3 %}<span class="ww"></span>{% elseif payment_type == '' %} <span class="blank"></span> {% else %}<span class="qq"></span>{% endif %}
        贷款{% if payment_type == 4 %}<span class="ww"></span>{% elseif payment_type == '' %} <span class="blank"></span> {% else %}<span class="qq"></span>{% endif %}
        （选择的<span class="ww"></span>，不选择的<span class="qq"></span>）
        如转账:乙方需提供相应凭证以便甲方财务人员核实。当日乙方如未全额缴纳服务费，本协议不生效。
    </div>
    <div class="m-t25">
        四、双方的权利与义务
    </div>
    <div class="t-i">
        1、甲方的保证、权利与义务
    </div>
    <div class="si-yiwu">
        <div><span class="circle">1</span>甲方保证保守乙方在履行本合同中透露的个人或企业信息、商业秘密，但法律、法规另有规定的除外；</div>
        <div><span class="circle">2</span>根据“冀企管家”服务平台管理办法的第四项《企业审核标准》对乙方进行定期审核，审核不合格的企业，终止其企业服务协议；</div>
        <div><span class="circle">3</span>不干涉乙方的正常生产经营活动；</div>
        <div><span class="circle">4</span>甲方配合协调“冀企管家”服务平台内的企业与企业之间的关系，加强企业与企业之间的合作与交流，促进企业与企业之间的资源整合，增强乙方在同行业当中的竞争优势。</div>
        <div><span class="circle">5</span>向乙方提供“冀企管家”服务及6项环绕辅助项目，具体服务内容如下：</div>
        <table  border="1" cellspacing="0" class="m-b25">
            <tr>
                <td>企业等级</td>
                <td>基本服务</td>
                <td>补充服务</td>
            </tr>
            <tr class="tr">
                <td>Ⅴ1</td>
                <td>1+2</td>
                <td>“冀企管家”<br/>法律维权<br/>培训教育</td>
            </tr>
            <tr class="tr1">
                <td>Ⅴ2</td>
                <td>1+4</td>
                <td>“冀企管家”<br/>法律维权<br/>培训教育<br>24小时企业安保<br>人才推荐</td>
            </tr>
            <tr class="tr2">
                <td>Ⅴ3</td>
                <td>1+6</td>
                <td>“冀企管家”<br/>法律维权<br/>培训教育<br>24小时企业安保<br>人才推荐<br>保健医疗<br>社会诚信</td>
            </tr>
        </table>
    </div>
    <div class="t-i">
        2、乙方的保证、权利与义务
    </div>
    <div class="si-yiwu">
        <div><span class="circle">1</span>乙方保证拥有充分的民事权利能力和民事行为能力订立并履行本合同；</div>
        <div><span class="circle">2</span>乙方保证信用状况良好，无不良信用记录；</div>
        <div><span class="circle">3</span>乙方保证其提供的文件、资料真实、完整、合法、有效；</div>
        <div><span class="circle">4</span>乙方依据企业等级享受甲方提供的 “冀企管家”服务平台相对应服务项目；</div>
        <div><span class="circle">5</span>在服务的过程中，乙方有权提出合理性的建议；</div>
        <div><span class="circle">6</span>乙方应认真履行与甲方签订的服务协议书并遵守 “冀企管家”服务平台管理办法；</div>
        <div><span class="circle">7</span>乙方有义务配合甲方对其企业经营状态的监督和审核，共同推进合作项目及各项工作的开展；</div>
        <div><span class="circle">8</span>乙方不得转让企业等级；</div>
        <div><span class="circle">9</span>乙方有义务定期将企业经营状态告知甲方；</div>
        <div><span class="circle">10</span>乙方如发生住所变动等情形，应立即书面通知甲方。</div>
    </div>
    <div class="m-t25">五、协议变更、解除或终止 </div>
    <div class="t-i">1、本协议未经双方协商一致，不得随意变更或解除。</div>
    <div class="t-i">2、本协议在履行期间，如国家政策有重大调整时，双方任何一方利益受到重大影响，受影响一方可以提出变更或解除本协议。</div>
    <div class="t-i">3、本协议规定的服务期满，双方的权利，义务履行完毕后，本协议自行终止。</div>
    <div class="t-i">4、乙方在服务期满前30日，仍愿享受甲方提供的服务项目，可向甲方申请延长服务期，经甲方批准同意后，双方须签订补充协议，最长有效期三年。</div>
    <div class="t-i">5、在本协议有效期内，乙方有下列情况之一的，甲方有权随时终止本协议：</div>
    <div class="t-i">（1）乙方违反国家、地方法律法规或有重大违纪的；</div>
    <div class="t-i">（2）乙方经营不善、经营状态异常的。</div>
    <div class="m-t25">六、违约责任</div>
    <div class="t-i">1、任何一方发生违反本合同约定义务及承诺的违约事件时，违约方应向守约方支付服务费总额的 10%作为违约金，如违约金不足以弥补守约方损失的，违约方应赔偿守约方因此蒙受的实际损失。</div>
    <div class="t-i">2、乙方向甲方提供虚假信息，伪造、变造文件的，乙方应向甲方支付服务费总额的 20%作为违约金，如违约金不足以弥补甲方损失的，乙方应赔偿甲方因此蒙受的实际损失，同时甲方有权终止本协议。</div>
    <div class="t-i">3、本协议签订后，乙方不得擅自终止本协议，否则视为违约，乙方应向甲方支付两倍服务费作为违约金。</div>
    <div class="t-i">4、乙方在协议期间不得从事违法乱纪经营，否则甲方有权向有关部门进行举报，同时甲方有权解除本协议，服务费不予退还。</div>
    <div class="t-i">5、因不可抗力的因素，如战争、灾害等导致协议无法履行所造成的损失，甲乙双方互不承担责任。</div>
    <div class="m-t25">七、其他</div>
    <div class="t-i">1、甲乙双方仅为服务与被服务关系，甲方提供的服务对乙方的经营、经济活动不承担任何连带责任。</div>
    <div class="t-i">2、按本协议约定书面通知：应以挂号邮寄、图文传真等即时通讯方式发出，送至本协议约定的所列各方地址。</div>
    <div class="t-i">4、本协议自甲乙双方法定代表人（或委托代理人）签字并盖章之日起生效。本协议未尽事宜，经甲乙双方协商一致，签订补充协议，经双方签订的补充协议与本协议具有同等法律效力。</div>
    <div class="t-i">5、本协议履行过程中发生争议，双方应本着友好、互利原则协商解决；如协商不成的，任何一方有权向石家庄仲裁委员会提起仲裁。</div>
    <div class="t-i">6、本协议一式二份，甲乙双方各执一份，具有同等法律效力。</div>
    <div class="t-i double-strong">在签署本合同时，甲方就本合同的全部条款已向乙方进行了详细地说明和解释，双方对合同的全部条款均无疑义，并对当事人有关权利义务和责任限制或免除条款的法律含义有准确无误的理解。</div>
    <div class="t-i m-t25 qixian">本合同于<span class="w42">{{ verify_time ? date('Y', verify_time) : '' }}</span>年<span class="w42">{{ verify_time ? date('m', verify_time) : '' }}</span>月<span class="w42">{{ verify_time ? date('d', verify_time) : '' }}</span>日
        在 <span class="w280">{{ sign_address }}</span>签订。
    </div>
    {#<div class="m-t100 m-b30">（此页无正文）</div>#}
    <div class="gaizhang1 m-b50">
        <div>甲方：（盖章）</div>
        <div>法定代表人：（签字或盖章）</div>
        <div>（或委托代理人）</div>
        <div>电话：4006-617-517</div>
        <div>通讯地址：河北省石家庄市桥西区工农路华域城6、7号楼底商一层 16 号</div>
        <div>邮政编码：050000</div>
    </div>
    <div class="gaizhang2 m-t100">
        <div>乙方：（盖章）</div>
        <div>法定代表人：（签字或盖章）</div>
        <div>（或委托代理人）</div>
        <div>电话： {{ contact_phone }}</div>
        <div>通讯地址： {{ address }}</div>
        <div>邮政编码： {{ zip_code }}</div>
        {% if sign %}<img src="{{ sign }}" alt="" class="zhang2"/>{% endif %}
    </div>
    {#<div style="height: 300px;"></div>#}
    {#<div class="m-b30 m-t300">附件：</div>#}
    {#<table border="1" align="center" cellspacing="0" class="mingxi">#}
    {#<caption align="top" class="m-b15">支付明细表</caption>#}
    {#<tr class="tr1">#}
    {#<td class="td1">甲方</td>#}
    {#<td class="td2">#}
    {#</td>#}
    {#</tr>#}
    {#<tr>#}
    {#<td class="td1">乙方户名</td>#}
    {#<td class="td2">{{ bank_account_name }}</td>#}
    {#</tr>#}
    {#<tr>#}
    {#<td class="td1">开户行</td>#}
    {#<td class="td2">{{ bank_name }}</td>#}
    {#</tr>#}
    {#<tr>#}
    {#<td class="td1">账号</td>#}
    {#<td class="td2">{{ bank_num }}</td>#}
    {#</tr>#}
    {#</table>#}
    {#<div style="height: 300px;"></div>#}
    <div class="m-t300 ta-c bianhao">编号：<span class="w200">{{ num }}</span></div>
</div>
</body>
</html>

