<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
            </div>
            <div class="ibox-content">
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">

                <div class="ibox-content">

                    <div class="lightBoxGallery">
                        <div class="boder01">
                        <div class="span0">反馈人: <span style="color: cornflowerblue">{{ data['device'] }}</span></div>
                        <div class="span0">反馈内容: <span style="color: cornflowerblue">{{ data['content'] }}</span></div>
                        <div class="span0">时间: <span style="color: cornflowerblue">{{ data['time'] }}</span></div>
                        </div>


                        <div class="boder01 ">
                            <div class="boder1">
                                <span class="span1">反馈图片:</span>
                                {% for data in  data['pic'] %}
                                    <a href="{{ data }}" title="反馈图片" data-gallery="">
                                        <img src="{{ data }}">
                                    </a>
                                {% endfor %}

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
            </div>
        </div>
    </div>
</div>
<button onclick="location='/admin/setting/feedBack';" class="btn btn-default" type="button">返回</button>
<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <a class="xuanzhuan">旋转</a>
    <a class="fangda">放大</a>
    <a class="suoxiao">缩小</a>
    <ol class="indicator"></ol>
</div>