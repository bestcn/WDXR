/**
 * Created by Administrator on 2017/4/27 0027.
 */
$(function(){
    /*业务*/

    // /*获取个人信息*/
    // LT.ajax({
    //     type:'get',
    //     url:'shenqing.html',
    //     success:function(data){
    //         $('#qyname').html(data.username);
    //         $('#qyaddress').html(data.username);
    //         $('#xingzhi1').html(data.username);
    //         $('#xingzhi2').html(data.username);
    //         $('#user').html(data.username);
    //         $('#userjob').html(data.username);
    //         $('#usertel').html(data.username);
    //         $('#timer').html(data.username);
    //         $('#zhuangtai').html(data.username);
    //         $('#money').html(data.username);
    //         $('#qyname').html(data.username);
    //         $('#qyname').html(data.username);
    //     }
    // });

    /*退出登录*/
    $('.logout').on('click',function(){
        var $btn = $(this);

        if($btn.hasClass('btn_waiting')) return false;

        $btn.addClass('btn_waiting');
        LT.ajax({
            type:'get',
            url:'/user/logout',
            success:function(data){
                /*退出成功*/
                LT.toast("退出成功")
                location.href = LT.URL_LANDING;
            },
            error:function(){
                $btn.removeClass('btn_waiting');
            }
        });

    });
})