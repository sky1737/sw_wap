/**
 * Created by zxjBigPower on 2017/6/16.
 */
$(function (window) {
    console.log(1);
    for(var i=0;i<$("#sqtj>li").length;i++){
        //console.log(i);
        $("#sqtj>li").eq(i).css("backgroundColor","rgba(141,205,255,"+((7-i)/10)+")");
    }
    $("#sqtj>li").addClass("animation")
})