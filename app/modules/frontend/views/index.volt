<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->tag->getTitle(); ?>
    <style>
        body {  margin: 0;  padding: 0;}
        a { text-decoration: none}
        .content { background-color: #eeeeee; }
        .tab-pane {  padding: 20px;}
        .tab-title {  font-size: 1.5em;  padding: 10px; }
        .tab-title a { color: #000; }
        .active { border-bottom: 2px solid #0A519E; }
        .active a { color: #0A519E;}
        img{ width: 100%;}
        .download {
            height:863px;
            position: relative;
            background-size:100% 100%!important;
        }
        .download .group {
            width: 470px;
            position: absolute;
            /*top: 600px;*/
            bottom: 100px;
            left: 100px;
        }
        .btn-download {
            margin-right: 20px;
            margin-bottom: 20px;
            width: 212px;
            height: 57px;
            display: inline-block;
        }
        .footer {
            margin-top: 30px;
            padding: 10px;
            font-size: 14px;
            text-align: center;
        }
        /*大屏情况下*/
        @media (min-width:720px){
            .big{
                display: block;
            }
            .small{
                display: none;
            }
        }
        /*小屏情况下*/
        @media (max-width:920px){
            .download{
                height: 750px;
            }
            .download .group{
                bottom: 30px;
            }
            .btn-download{
                display: block;
            }
        }
        @media (max-width:780px){
            .download{
                height: 660px;
            }
            .download .group{
                bottom: 0px;
            }
            .btn-download{
                display: block;
            }
        }
        @media (max-width:720px){
            .big{
                display: none;
            }
            .small{
                display: block;
            }
            .tab-title{
                font-size: 1em;
            }
            .download{
                height: 860px;
            }
            .download .group{
                width: 100%;
                left: 0;
            }
            .btn-download{
                margin: 20px auto;
                display: block;
            }
            .tab-title{
                width: 32%;
                display: inline-block;
                text-align: center;
                padding:0 0 10px 0;
                min-width: 84px;
            }
            .ma{
                position: absolute;
                bottom: 335px;
                width: 100%;
                text-align: center;
            }
            .ma img{
                width: 150px;
                height: 150px;
            }
        }
        @media (max-width:460px){
            .ma{
                bottom: 265px;
            }
            .ma img{
                width: 100px;
                height: 100px;
            }
            .download{
                height: 600px;
            }
        }
    </style>
</head>
<body>
<div style="margin: 0 auto; max-width: 1170px;">
    <div class="header">
        <img src="/images/help/banner.jpg" alt="">
    </div>
    <div class="content">
        {{ content() }}
        <div class="footer">
            Copyright &copy; 2015-{{ date('Y') }} JQGUANJIA.COM
        </div>
    </div>
</div>
</body>
</html>
