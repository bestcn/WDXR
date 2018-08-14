/**
 * Created by DH on 2017/4/20.
 */
$(function(){


    $("#provinces").change(function () {
        var provinceid = $("#provinces").val();
        $.ajax({
            url:'/admin/select/change_province?provinceid='+provinceid,
            dataType:'html',
            type:'get',
            success:function(data){
                $("#cities").html(data);
                $("#cities").change();
            }
        })
    });

    $("#cities").change(function(){
        var citieid = $("#cities").val();
        $.ajax({
            url:'/admin/select/change_citie?citieid='+citieid,
            dataType:'html',
            type:'get',
            success:function(data){
                $("#areas").html(data);
            }
        })
    });

    $("#branch_admin_id").change(function(){
        var branch_admin = $("#branch_admin_id").find("option:selected").text();
        $("#branch_admin").val(branch_admin);
    });

    $("#top_category").change(function(){
        var top_category = $("#top_category").val();
        $.ajax({
            url:'/admin/select/category?top_category='+top_category,
            dataType:'html',
            type:'get',
            success:function(data){
                $("#sub_category").html(data);
            }
        })
    })

})
