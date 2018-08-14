<body class="gray-bg">

<div class="loginColumns animated fadeInDown">
    <div class="row">
        <div class="col-md-6">
            <h2 class="font-bold">Welcome to WDXR</h2>
            <p>
                Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
            </p>

            <p>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
            </p>

            <p>
                When an unknown printer took a galley of type and scrambled it to make a type specimen book.
            </p>

            <p>
                <small>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</small>
            </p>
        </div>
        <div class="col-md-6">
            <div class="ibox-content">
                {{ form('frontend/public/adminlogin', 'id':'login_form', 'method':'post', 'class':'m-t', 'role':'form') }}

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
                            <img onclick="this.src = '{{ url('frontend/public/captcha?t=') }}'+Math.random();" src="{{ url('frontend/Public/captcha', ['t' : time()]) }}" alt="请输入验证码" />
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
            WDXR
        </div>
        <div class="col-md-6 text-right">
            <small>© 2015-2017</small>
        </div>
    </div>
</div>
</body>
