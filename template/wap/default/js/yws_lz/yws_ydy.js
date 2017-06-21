/**
 * Created by Administrator on 2017/6/2.
 */
$(function () {
    $(".circle-1").addClass("runleft1");
    $(".circle-2").addClass("runright");
    $(".circle-3").addClass("runleft");
    setTimeout(function () {
        //console.log(1);
        $(".lightSpeedIn1").addClass("opacity");
    },10);
    setTimeout(function () {
        //console.log(1);
        $(".lightSpeedIn2").addClass("opacity");
    },520);
    setTimeout(function () {
        //console.log(1);
        $(".lightSpeedIn3").addClass("opacity");
    },1020);
})