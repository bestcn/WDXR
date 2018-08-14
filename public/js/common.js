/**
 * Created by Administrator on 2017/6/5 0005.
 */
$(function(){
    $(".selectList").each(function(){
        var url = "area.json";
        var areaJson;
        var temp_html;
        var oProvince = $(this).find(".province");
        var oCity = $(this).find(".city");
        var oDistrict = $(this).find(".district");
//��ʼ��ʡ
        var province = function(){
            $.each(areaJson,function(i,province){
                temp_html+="<option value='"+province.p+"'>"+province.p+"</option>";
            });
            oProvince.html(temp_html);
            city();
        };
//��ֵ��
        var city = function(){
            temp_html = "";
            var n = oProvince.get(0).selectedIndex;
            console.log(n);
            $.each(areaJson[n].c,function(i,city){
                temp_html+="<option value='"+city.ct+"'>"+city.ct+"</option>";
            });
            oCity.html(temp_html);
            district();
        };
//��ֵ��
        var district = function(){
            temp_html = "";
            var m = oProvince.get(0).selectedIndex;
            var n = oCity.get(0).selectedIndex;
            if(typeof(areaJson[m].c[n].d) == "undefined"){
                oDistrict.css("display","none");
            }else{
                oDistrict.css("display","inline");
                $.each(areaJson[m].c[n].d,function(i,district){
                    temp_html+="<option value='"+district.dt+"'>"+district.dt+"</option>";
                });
                oDistrict.html(temp_html);
            };
        };
//ѡ��ʡ�ı���
        oProvince.change(function(){
            city();
        });
//ѡ���иı���
        oCity.change(function(){
            district();
        });
//��ȡjson����
        $.getJSON(url,function(data){
            areaJson = data;
            province();
        });
    });
});


function refresh() {
    self.location.reload();
}
//todo 返回框样式美化
$("table td.data-editable").dblclick(function () {
    if(!$(this).is('.input')){
        var html = '<input class="form-control input-sm" type="text" value="'+ $(this).text() +'" autofocus />';
        if($(this).attr('data-select-url')) {
            var data = $(this).attr('data-param');
            console.info(data);
            $.ajax({
                type: 'POST',
                url: $(this).attr('data-select-url'),
                async:false,
                data: data,
                success: function (res) {
                    html = '<select class="form-control">';
                    $.each(res, function (index, item) {
                        html += '<option value="'+item.id+'">'+item.name+'</option>';
                    });
                    html += '</select>';
                }
            });
        }
        $(this).addClass('input').html('<form>'+html+'<input type="submit" value="保存" /><input type="button" onclick="refresh()" value="取消"></form>')
            .find('form').click()
            .submit(function(){
                var title = $(this).parent().attr('title');
                title = title ? title : $(this).parent().siblings("th:eq(0)").text();
                var value = $(this).find('.form-control').val();
                var name = $(this).parent().attr("data-attr");
                var param = $(this).parent().attr("data-param");

                var url = $(this).parent().attr("data-callback") ? $(this).parent().attr("data-callback") : '/admin/companys/edit_company';
                var params = param ? "value="+value+"&"+param : {value:value, name:name};
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: params,
                    success: function (res) {
                        if(res.status === '1') {
                            if (res.info) {
                                toastr.success(res.info);
                            } else {
                                toastr.success(title + "修改成功");
                            }
                        } else {
                            if (res.info) {
                                toastr.error(res.info);
                            } else {
                                toastr.error(title + "修改失败");
                            }
                        }
                    },
                    error:function() {
                        toastr.error(title + "服务器错误");
                    }
                });
                if($(this).parent().attr('data-select-url')) {
                    $(this).parent().removeClass('input').html($(this).find('.form-control option:selected').text() || 0);
                } else {
                    $(this).parent().removeClass('input').html($(this).find('.form-control').val() || 0);
                }
            });
    }
}).hover(function(){
    $(this).addClass('hover');
},function(){
    $(this).removeClass('hover');
});

toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": false,
    "preventDuplicates": false,
    "positionClass": "toast-top-right",
    "onclick": null,
    "showDuration": "400",
    "hideDuration": "1000",
    "timeOut": "7000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$(function () {
    var picker1 = $('#datetimepicker1').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: moment.locale('zh-cn'),
        //minDate: '2016-7-1'
    });
    var picker2 = $('#datetimepicker2').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: moment.locale('zh-cn')
    });
    //动态设置最小值
    picker1.on('dp.change', function (e) {
        picker2.data('DateTimePicker').minDate(e.date);
    });
    //动态设置最大值
    picker2.on('dp.change', function (e) {
        picker1.data('DateTimePicker').maxDate(e.date);
    });


    var zum = 1;
    $(".xuanzhuan").on("click",function () {
        var style = $(".slide img").attr("style") ? $(".slide img").attr("style") : '';
        if(zum === 4) {
            zum=0;
        }
        if(style.indexOf("scale(1.5)")){
            $(".slide img").css("transform", "rotate("+zum*90+"deg) scale(1.5)");
        }else{
            $(".slide img").css("transform", "rotate("+zum*90+"deg) scale(1.0)");
        }
        zum++;
    });
    $(".fangda").on("click",function () {
        $(".slide img").css("transform", "scale(1.5)");
    });
    $(".suoxiao").on("click",function () {
        $(".slide img").css("transform", "scale(1.0)");
    });

    var messages_count = 0;
    function newsajax(){
        $.ajax({
            type : "POST",
            url : "{{ url('admin/index/news') }}",
            success : function(result) {
                if(result.status === 1) {
                    var message_length = parseInt(result.count);
                    if(messages_count !== message_length) {
                        messages_count = result.count;
                        var message_html = '';
                        $.each(result.data, function (i, item) {
                            message_html += "<li><a href='{{ url('admin/news/new') }}/"+ item.id +"'><div><i class='fa fa-envelope fa-fw'></i> "+ item.title +"</div></a></li><li class='divider'></li>";
                            $("#unread_messages").html(message_html);
                        });
                        var news_html="<span class='fa arrow'></span>";

                        if(messages_count > 0){
                            news_html = "<span class='label label-info pull-right'>"+messages_count+"</span>";
                        }
                        $("#unread_messages_num").html(messages_count);
                        $("#unread_messages_new_num").html(messages_count);
                        $("#unread_messages_news_num").html(news_html);
                        $("#unread_messages").append("<li><div class='text-center link-block'><a href='{{ url('admin/news/unread') }}'><strong>查看全部未读消息</strong><i class='fa fa-angle-right'></i></a></div></li>");
                    }
                }
            }
        });
    }
    newsajax();
//        setInterval(newsajax,3000);

    function pending(){
        $.ajax({
            type : "POST",
            url : "{{ url('admin/index/pending') }}",
            success : function(result) {
                if(result['status'] === 1){
                    var finance = result['loan'] + result['bill'] + result['credit'] + result['payment']+result['documents'];
                    if(finance > 0){
                        $("#unread_messages_finance_num").html("<span class='label label-warning pull-right' >"+finance+"</span>");
                    }else{
                        $("#unread_messages_finance_num").html("<span class='fa arrow'></span>");
                    }
                    if(result['documents'] > 0){
                        $("#unread_messages_company_num").html(result['documents']);
                    }else{
                        $("#unread_messages_company_num").html("");
                    }
                    if(result['loan'] > 0){
                        $("#unread_messages_loan_num").html(result['loan']);
                    }else{
                        $("#unread_messages_loan_num").html("");
                    }
                    if(result['bill'] > 0){
                        $("#unread_messages_bill_num").html(result['bill']);
                    }else{
                        $("#unread_messages_bill_num").html("");
                    }
                    if(result['credit'] > 0){
                        $("#unread_messages_credit_num").html(result['credit']);
                    }else{
                        $("#unread_messages_credit_num").html("");
                    }
                    if(result['payment'] > 0){
                        $("#unread_messages_payment_num").html(result['payment']);
                    }else{
                        $("#unread_messages_payment_num").html("");
                    }
                }
            }
        });
    }
    pending();
//        setInterval(pending,3000);
});

