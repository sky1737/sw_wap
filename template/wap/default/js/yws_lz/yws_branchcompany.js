/**
 * Created by zxjBigPower on 2017/6/14.
 */
$(function () {
    $("#dowebok").fullpage({
        afterLoad:function(anchorLink ,index){
            $(".section").removeClass("animation");
            setTimeout(function () {
                $(".section").eq(index-1).addClass("animation");
            },10);
        }
    });
    setInterval(function(){
        if($(".section1").hasClass("animation")){
            $(".section1").removeClass("animation");
        }else{
            $(".section1").addClass("animation");
        }
    },10)
    $(".next").on("click", function () {
        $.fn.fullpage.moveSectionDown();
    })
    //申请步骤页面
    for (var i = 0; i < $("#sqbz>li").length; i++) {
        // console.log(i);
        $("#sqbz>li").eq(i).css("backgroundColor", "rgba(141,205,255," + ((7 - i) / 10) + ")");
    }
    //申请条件页面
    // $("#sqtj>li").addClass("animation")
    for (var i = 0; i < $("#sqtj>li").length; i++) {
        //console.log(i);
        $("#sqtj>li").eq(i).css("backgroundColor", "rgba(141,205,255," + ((7 - i) / 10) + ")");
    }

//切换下一页
    $("#nextPage").on("click", function () {
        // console.log($(this).parent(".con"));
        //console.log(1);
        //$(this).parent("div").fadeOut(200);
        //$(this).parent("div").css({"display":"none"});
        $(".con-first").css({"display": "none"});
        $(".con-next").fadeIn(200);
        $(".finish").css({"display": "none"});
    })
//第二页切换到上一页
    $("#prevPage").on("click", function () {
        //console.log($(this).parent(".con"));
        //console.log(1);
        //console.log($(this).parent("div"));
        //$(this).parent("div").fadeOut(200);
        $(".con-next").css({"display": "none"});
        $(this).parent(".con").css({"display": "none"});
        $(".con-first").fadeIn(200);
        $(".finish").css({"display": "none"});
    })
//第二页切换第三页
    $("#nextPage1").on("click", function () {
        console.log(1);
        $(".con-next").css({"display": "none"});
        $(this).parent(".con").css({"display": "none"});
        $(".con-end").fadeIn(200);
        $(".finish").css({"display": "block"});
    })
//第三页点击切换第二页
    $("#endPage").on("click", function () {
        console.log(2);
        $(this).parent(".con").css({"display": "none"});
        $(".con-next").fadeIn(200);
        $(".finish").css({"display": "none"});
    })

    //console.log($("#sqbz>li").length);

    /* //给每个填充背景设置背景
     console.log($(".con-box>.img-box"));
     var ImgBox=["images/fgssq/yyzz.jpg", "images/fgssq/swdj.jpg", "images/fgssq/sfz.jpg", "images/fgssq/zhzm.png", "images/fgssq/mgcz.jpg"]
     for(var i=0;i<ImgBox.length;i++){
     console.log($(".con-box>.img-box").eq(i));
     $(".con-box>.img-box").eq(i).css({"background":"url("+ImgBox[i]+") no-repeat center/contain"});
     }*/

    //电话号码匹配；
    var flag_phone = false;
    $("#tel").keyup(function () {
        //console.log($(this).val());
        //获取值：
        var keyUpval = $(this).val() - 0;
        //console.log(keyUpval);
        //匹配是否符合
        var isTel = /^1(3|4|5|7|8)\d{9}$/.test(keyUpval);//console.log(isTel);
        if (!isTel) {
            $(this).css({"borderColor": "red"});
            flag_phone = false;
        } else {
            $(this).css({"borderColor": "black"});
            flag_phone = true;
        }
    })

    $('.finish').click(function () {
        var area = $('input[name=area]').val();
        linkman = $('inpupt[name=linkman]').val();
        phone = $('input[name=phone]').val();
        if (picArray.length < 5 && !area && !linkman && !phone) {
            $('#notnull').html('请填写完整');
            return;
        } else {
            $('#notnull').html('');
        }

        if (!flag_phone) {
            $('#notnull').html('请检查手机号是否正确');
            return;
        } else {
            $('#notnull').html('');
        }
    });


    showFile($("#yyzz")[0], $(".filebox-box")[0]);
    showFile($("#swdj")[0], $(".filebox-box")[1]);
    showFile($("#sfz")[0], $(".filebox-box")[2]);
    showFile($("#zhzm")[0], $(".filebox-box")[3]);
    showFile($("#mgcz")[0], $(".filebox-box")[4]);
    /*
     * 选择照片并显示
     * file：file文件选择，show：展示页
     * */
    function showFile(file, show) {
        file.onchange = function () {
            //console.log(1);
            //创建对象
            var reader = new FileReader();
            //console.log(this.files[0])
            reader.readAsDataURL(this.files[0]);
            reader.onload = function () {
                //console.log(reader.result)
                show.style.background = "url(" + reader.result + ") no-repeat center/contain";
                console.log(file.parentNode.parentNode.childNodes[1]);
                file.parentNode.parentNode.childNodes[1].style.background = "#fff";
                file.parentNode.parentNode.childNodes[1].style.backgroundImage = "/";
            }
        }
    }
});
