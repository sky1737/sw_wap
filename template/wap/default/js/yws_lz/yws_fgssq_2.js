/**
 * Created by zxjBigPower on 2017/6/16.
 */
$(function () {
    for(var i=0;i<$("#sqbz>li").length;i++){
        // console.log(i);
        $("#sqbz>li").eq(i).css("backgroundColor","rgba(141,205,255,"+((7-i)/10)+")");
    }
    $("#sqbz>li").addClass("animation")
})