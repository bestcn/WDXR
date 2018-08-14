{{ javascript_include("js/plugins/jsMind/jsmind.js") }}
{{ stylesheet_link("css/plugins/jsMind/jsmind.css") }}
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ admin.name }}的客户关系</h5>
                <div class="ibox-tools">
                    <a id="down_a"></a>
                    <a id="down">下载</a>
                    <a class="fullscreen-link"><i class="fa fa-expand"></i>
                    </a>
                </div>
            </div>

            <div style="height: 1000px;" class="ibox-content" id="jsmind_container">
                <div class="jsmind-inner"><div class="demo_box"></div></div>
            </div>

        </div>
    </div>
</div>
{{ javascript_include('js/html2canvas.min.js') }}
<script type="text/javascript">
    var mind = {
        "meta":{"name":"{{ admin.name }}客户关系"},
        "format":"node_tree",
        "data": {{ recommends }}
    };
    var options = {
        container:'jsmind_container',
        editable:true,
        mode:"full",
        theme:'primary'
    };
    var jm = new jsMind(options);
    jm.show(mind);



    /**
     * 下载图片
     */
    $('#down')[0].onclick=function () {
        down()
    }
    $("canvas").appendTo($('.demo_box'))
    $(".theme-primary").appendTo($('.demo_box'))
    var width = $('.theme-primary').width();
    var height = $('.theme-primary').height();
    $('.demo_box').css({width:width,height:height});
    $(".jsmind-inner")[1].remove();
    function down() {
        html2canvas(document.querySelector(".demo_box")).then(canvas => {
            var image_src = canvas.toDataURL("image/png");
//        console.log(image_src);
        window.location.href = image_src;
        var triggerDownload = $("#down_a").attr("href", image_src).attr("download", "{{ admin.name }}的推荐关系图.png");
            triggerDownload[0].click();
        });
    }

</script>

