
if(!window.LT)window.LT = {};
/*常量*/
LT.URL_LANDING = 'landing.html';
LT.URL_LOGIN = 'login.html';
LT.URL_CHAXUN = 'chaxun.html';

/*
* 1.当服务端返回数据了  并且 有 error 这个属性的时候都是业务层失败
* 1.1 当error 400 的时候 是服务端通用告诉前端  未登录
* 1.2 其他情况 业务处理失败的代码  错误信息
* 2.当服务端返回数据   但是没有error  这个情况都是 业务成功‘
* 3.通讯失败          认为 服务繁忙。
* */
LT.toast= function (options) {
    $(".tips p").text(options);
    var $left=$(document).width()-$(".tips").width();
    $(".tips").css("left",$left/2);
    $(".tips p").stop(true,false).fadeIn(1000).delay(800).fadeOut(1000);
}

// LT.ajax = function(options){
//     $.ajax({
//         type:options.type||'get',
//         url:options.url||location.pathname,
//         data:options.data||'',
//         dataType:options.dataType||'jsonp',
//         success:function(data){
//             /*如果出现业务错误*/
//             if(data.error){
//                 /*并且未登录*/
//                 if(data.error == 400){
//                     /*跳到登录页*/
//                     location.href = LT.URL_LOGIN ;
//                 }
//                 /*常见的业务错误*/
//                 else{
//                     /*业务层面的错误处理*/
//                     setTimeout(function() {
//                         /*提示错误信息*/
//                         LT.toast(data.message);
//                         options.error && options.error(data);
//                     },1000);
//                 }
//             }
//             /*业务成功*/
//             else{
//                 /*和业务有关*/
//                 setTimeout(function(){
//                     options.success && options.success(data);
//                 },1000);
//             }
//         },
//         error:function(){
//             /*提示错误信息*/
//             LT.toast('服务繁忙');
//         }
//     });
// };


