/**
 * Created by zxjBigPower on 2017/6/19.
 */
$(function () {

    for(var i=0;i<$(".logo>ul>li").length;i++){
        //console.log($(".logo>ul>li").find("span").eq(i));
        $(".logo>ul>li").find("span").eq(i).css({
            "background":"url('../template/wap/default/css/images/yws_lz/gxdz/"+(i+11)+".png')no-repeat center /contain",
            "transform":"rotateZ("+(-i*36+144)+"deg)scale(1,1)",
            "-webkit-transform":"rotateZ("+(-i*36+144)+"deg)scale(1,1)",
            "right":"-5px",
            "transition":"all .4s "+(i/5+.5)+"s",
            "opacity":"1"
        });

    }

    setTimeout(function(){
        $(".circle-1").addClass("runleft1");
        $(".circle-2").addClass("runright");
        $(".circle-3").addClass("runleft");
        },100)
})