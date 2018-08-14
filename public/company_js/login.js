
$(function(){
/*登录业务*/
$('.btn_login').on('click',function(){
    var $btn = $(this);
    if($btn.hasClass('btn_waiting')) return false;
    /*获取表单数据*/
    var data = {
        username:$.trim($('[name="username"]').val()),
        admin:$.trim($('[name="admin"]').val())
    };
    /*验证*/
    if(!data.username){
        LT.toast("请输入用户名");
        return false;
    }
    if(!data.admin){
        LT.toast("请输入密码");
        return false;
    }
    /*接口*/
    $btn.addClass('btn_waiting');

    LT.ajax({
        type:'post',
        url:'http:/192.168.1.82//api/public/login.html',
        data:data,
        success:function(data){
            console.log(data)
            /*如果没有跳转到个人中心*/
            var returnUrl = LT.URL_CHAXUN;
            /*如果有回调地址跳转回去*/
            var search = location.search;
            if(search && search.indexOf('returnUrl')>-1){
                returnUrl = search.replace('?returnUrl=','');
            }

            location.href = returnUrl;

        },
        error:function(errInfo){
            $btn.removeClass('btn_waiting');
        }
    });
});
})