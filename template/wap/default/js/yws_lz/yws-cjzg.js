/**
 * Created by Administrator on 2017/6/6.
 */
$(function(){
    var flag = false;
    $(window).scroll(function(){
        /*if(flag){
            //数据加载中
            return false;
        }*/
        //$('input[name=search]').val(124);
        if($(window).scrollTop() / ($('body').height() - $(window).height()) >= 0.95) {
            //请求数据
            flag = true;
            $('input[name=search]').val(123);
        }
    });
});