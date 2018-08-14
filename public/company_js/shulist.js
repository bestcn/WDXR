/**
 * Created by Administrator on 2017/5/5 0005.
 */
$(function () {
    LT.ajax({
        url:'ddd.html',
        type:'get',
        success:function(data){
            //$('.box_h2').html('<div><span>手机号：'+data.mobile+'</span></div>');
            $('.box_h2').html('<div> <span>'+ data.gsname +'</span><span>（5）</span><br /> <span>2016/4/6至2017/4/5</span> </div><hr />');
        }
    })
})