<body class="gray-bg">

<div class="loginColumns animated fadeInDown">
    <div class="row">
        <div class="col-md-6">
            <h2 class="font-bold">冀企管家业务管理后台</h2>
            <p class="m-t">
                冀企管家由河北冀企之家信息科技有限公司与河北工人报社企业策划服务中心联合运营，构建了--“WDXR”1+6智能化服务系统，利用互联网、云计算、大数据、移动通讯、VR、MA、AR科技等技术为小微企业提供法律维权，培训教育，24小时企业安保，人才推荐，保健医疗，社会诚信等服务，致力于成为河北省小微企业的管家，扶持小微企业转型升级、蓬勃发展，引领中小微企业在大数据时代乘风破浪、与时偕行。
            </p>
        </div>
        <div class="col-md-6">
            <div class="ibox-content">
                {{ form('admin/public/login', 'id':'login_form', 'method':'post', 'class':'m-t', 'role':'form') }}

                {{ flash.output() }}
                <div class="form-group">
                    {{ form.render('csrf', ['value': security.getToken()]) }}
                    {{ form.render('username') }}
                </div>
                <div class="form-group">
                    {{ form.render('password') }}
                </div>
                <div class="form-group">
                    <div class="input-group">
                        {{ form.render('captcha') }}
                        <div class="input-group-addon" style="padding: 0;">
                            <img onclick="this.src = '{{ url('admin/public/captcha?t=') }}'+Math.random();" src="{{ url('admin/public/captcha', ['t' : time()]) }}" alt="请输入验证码" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {{ form.render('remember') }} 记住我
                </div>
                {{ form.render('submit', ['class': 'btn btn-primary block full-width m-b']) }}
                {{ end_form() }}
            </div>
        </div>
    </div>


    <hr/>
    <div class="row">
        <div class="col-md-6">
            <small><strong>Copyright</strong>  &copy; 2017 - {{ date('Y') }} 华企管家</small>
        </div>
        <div class="col-md-6 text-right">
            <small>Version 4.0.0</small>
        </div>
    </div>
</div>
</body>
