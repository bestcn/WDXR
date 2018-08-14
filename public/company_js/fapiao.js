
var num = 0;
Dropzone.options.waterfee = {
    paramName: "waterfee",
    autoProcessQueue : false,
    init: function() {
        var myDropzone5 = this;
        var submitbtn=$("#_btn");
        submitbtn.on("click", function () {
            myDropzone5.processQueue();
        })
        myDropzone5.on("success",function(file) {
            num += 1;
            if(num >= 6){
                location.href = "/company/index/result/1/上传成功";
            }
        });
        myDropzone5.on("error",function(file) {
            LT.toast("上传失败");
        });
    },
};
Dropzone.options.electricity = {
    paramName: "electricity",
    autoProcessQueue : false,
    init: function() {
        var myDropzone6 = this;
        var submitbtn=$("#_btn");
        submitbtn.on("click", function () {
            myDropzone6.processQueue();
        })
        myDropzone6.on("success",function(file) {
            num += 1;
            if(num >= 6){
                location.href = "/company/index/result/1/上传成功";
            }
        });
        myDropzone6.on("error",function(file) {
            LT.toast("上传失败");
        });
    },
};
Dropzone.options.propertyfee = {
    paramName: "propertyfee",
    autoProcessQueue : false,
    init: function() {
        var myDropzone7 = this;
        var submitbtn=$("#_btn");
        submitbtn.on("click", function () {
            myDropzone7.processQueue();
        })
        myDropzone7.on("success",function(file) {
            num += 1;
            if(num >= 6){
                location.href = "/company/index/result/1/上传成功";
            }
        });
        myDropzone7.on("error",function(file) {
            LT.toast("上传失败");
        });
    },
};
Dropzone.options.rentcontract = {
    paramName: "rentcontract",
    autoProcessQueue : false,
    init: function() {
        var myDropzone2 = this;
        var submitbtn2=$("#_btn");
        submitbtn2.on("click", function () {
            myDropzone2.processQueue();
        })
        myDropzone2.on("success",function(file) {
            num += 1;
            if(num >= 6){
                location.href = "/company/index/result/1/上传成功";
            }
        });
        myDropzone2.on("error",function(file) {
            LT.toast("上传失败");
        });
    },
};
Dropzone.options.rentreceipt = {
    paramName: "rentreceipt",
    autoProcessQueue : false,
    init: function() {
        var myDropzone3 = this;
        var submitbtn=$("#_btn");
        submitbtn.on("click", function () {
            myDropzone3.processQueue();
        })
        myDropzone3.on("success",function(file) {
            num += 1;
            if(num >= 6){
                location.href = "/company/index/result/1/上传成功";
            }
        });
        myDropzone3.on("error",function(file) {
            LT.toast("上传失败");
        });
    },
};
Dropzone.options.rent = {
    paramName: "rent",
    autoProcessQueue : false,
    init: function() {
        var myDropzone4 = this;
        var submitbtn=$("#_btn");
        submitbtn.on("click", function () {
            myDropzone4.processQueue();
        })
        myDropzone4.on("success",function(file) {
            num += 1;
            if(num >= 6){
                location.href = "/company/index/result/1/上传成功";
            }
        });
        myDropzone4.on("error",function(file) {
            LT.toast("上传失败");
        });
    },
};


