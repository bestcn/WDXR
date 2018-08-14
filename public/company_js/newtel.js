
$(function(){

    $('body').on('click',".btn_uptel",function(){

        if(window.loading) return false;

        var data = {
            newuser:$.trim($('[name=new_user]').val()),
            newjob:$.trim($('[name=new_job]').val()),
            newtel:$.trim($('[name=new_tel]').val()),
            vCode:$.trim($('[name=code]').val())
        };

        if(!data.newuser){
            LT.toast('请输入姓名');
            return false;
        }

        if(!data.newjob){
            LT.toast('请输入岗位');
            return false;
        }

        if(!data.newtel){
            LT.toast('请输入新号码');
            return false;
        }

        if(!data.vCode){
            LT.toast('请输入验证码');
            return false;
        }

        LT.ajax({
            type:'post',
            url:'/user/updatePassword',
            data:data,
            dataType:'json',
            beforeSend:function(){
                window.loading = 1;
                LT.toast('正在提交');
            },
            success:function(data){
                window.loading = null;
                if(data.success){
                    LT.toast('修改成功！');
                    location.href = LT.URL_CHAXUN;
                }
            }
        });
    }).on('click','.btn_getCode',function(){
        var btn = $('.btn_getCode');
        var phone = $("#new_tel").val();
        if(btn.hasClass('btn_disabled')) return false;

        $.ajax({
            type:'get',
            url:'/company/index/getcode/'+phone,
            dataType:'json',
            beforeSend:function(){
                btn.addClass('btn_disabled').val('正在发送...');
            },
            success:function(data){
                console.log(data.vCode);
        var time = 60;
        btn.html(time+'秒后再获取');
        var timer = setInterval(function(){
            time --;
            btn.val(time+'秒后再获取');
            if(time <= 0) {
                clearInterval(timer);
                btn.removeClass('btn_disabled').val('短信验证');
            }
        },1000);
        }
        });
    });

});
