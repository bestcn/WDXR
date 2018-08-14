
$(function(){
    var Password=$('[name=Password]');
    Password.blur(function(){
        var num=Password.val().length;
        if(num<6){
            $("#tip1").html("<font color=\"red\" size=\"2\">  密码必须大于6位数</font>");
        }
        else{
            $("#tip1").html("");
        }
    }) ;

    $('.btn_updatePass').on('click',function(){

        if(window.loading) return false;

        var data = {
            oldPassword:$.trim($('[name=oldPassword]').val()),
            Password:$.trim($('[name=Password]').val()),
            ConfirmPassword:$.trim($('[name=ConfirmPassword]').val())
        };

        if(!data.oldPassword){
            LT.toast('请输入原密码');
            return false;
        }

        if(!data.Password){
            LT.toast('请输入新密码');
            return false;
        }

        if(!data.ConfirmPassword){
            LT.toast('请再次输入新密码');
            return false;
        }

        if(data.Password != data.ConfirmPassword){
            LT.toast('密码需要一致');
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
    })
});
