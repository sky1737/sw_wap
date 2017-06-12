/**
 * Created by zxjBigPower on 2017/6/10.
 */
/**
 * Created by Administrator on 2017/6/6.
 */
$(function () {
    $('#dowebok').fullpage({
        continuousVertical: true
    });
    $(".next").on("click", function () {
        $.fn.fullpage.moveSectionDown();
    })
    /*setInterval(function(){
     //点击切换下一屏
     $.fn.fullpage.moveSectionDown();
     }, 5000);*/
    showFile($("#yyzz")[0],$(".filebox-box")[0])
    showFile($("#swdj")[0],$(".filebox-box")[1])
    showFile($("#sfz")[0],$(".filebox-box")[2])
    showFile($("#zhzm")[0],$(".filebox-box")[3])
    showFile($("#mgcz")[0],$(".filebox-box")[4])
    /*
     * 选择照片并显示
     * file：file文件选择，show：展示页
     * */
    function showFile(file,show) {
        file.onchange = function () {
            console.log(1);
            //创建对象
            var reader = new FileReader();
            console.log(this.files[0])
            reader.readAsDataURL(this.files[0]);
            reader.onload = function () {
                //console.log(reader.result)
                show.style.background = "url(" + reader.result + ") no-repeat center/contain"
            }
        }
    }

//切换下一页
    $("#nextPage").on("click",function () {
        // console.log($(this).parent(".con"));
        //console.log(1);
        //$(this).parent("div").fadeOut(200);
        //$(this).parent("div").css({"display":"none"});
        $(".con-first").css({"display":"none"});
        $(".con-next").fadeIn(200);
        $(".finish").css({"display":"none"});
    })
//第二页切换到上一页
    $("#prevPage").on("click",function () {
        //console.log($(this).parent(".con"));
        //console.log(1);
        //console.log($(this).parent("div"));
        //$(this).parent("div").fadeOut(200);
        $(".con-next").css({"display":"none"});
        $(this).parent(".con").css({"display":"none"});
        $(".con-first").fadeIn(200);
        $(".finish").css({"display":"none"});
    })
//第二页切换第三页
    $("#nextPage1").on("click",function () {
        console.log(1);
        $(".con-next").css({"display":"none"});
        $(this).parent(".con").css({"display":"none"});
        $(".con-end").fadeIn(200);
        $(".finish").css({"display":"block"});
    })
//第三页点击切换第二页
    $("#endPage").on("click",function () {
        console.log(2);
        $(this).parent(".con").css({"display":"none"});
        $(".con-next").fadeIn(200);
        $(".finish").css({"display":"none"});
    })

    //上传省身份证照片
    function uploadpic(obj,id) {
        var divcontainal = $(this).parent().next().children('.filebox-box');
        var filepic = new FormData('#'+id);
        $('#'+id).submit(function(){
            $.ajax({
                type:'get',
                data:{'id':id,'file':filepic},
                url:'yws_newzerosell.php',
                success:function(data){

                },
                error:function(msg){
                    alert('系统错误，请刷新后重试');
                },
            });
        });
    }
});
