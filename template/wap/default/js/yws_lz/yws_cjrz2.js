/**
 * Created by zxjBigPower on 2017/6/10.
 */
$(function () {
    var inputVal,flag=false;
    $("#dowebok").fullpage(
        {
        afterLoad:function(anchorLink ,index){
            $(".section").removeClass("animation");
            setTimeout(function () {
                $(".section").eq(index-1).addClass("animation");
            },10);
        }
    }
    );
    $(".section1").addClass("animation");
    $(".next").on("click", function () {
        $.fn.fullpage.moveSectionDown();
    })
    $("#select>li").on("click", function () {
        $(this).find("input").prop("checked", true);
        //console.log($(this).find("input").val());
        inputVal = $(this).find("input").val();
        sessionStorage.setItem("ywsCjrzInputVal", inputVal);
        console.log(inputVal);
        if (inputVal) {
            $("#finish").on("click", function () {
                $(this).find("a").attr("href", "yws-cjrz-tjzl.html");
            })
        }
    });
//申请步骤页面
    for(var i=0;i<$("#sqbz>li").length;i++){
        // console.log(i);
        $("#sqbz>li").eq(i).css("backgroundColor","rgba(141,205,255,"+((7-i)/10)+")");
    }
    //申请条件页面
    // $("#sqtj>li").addClass("animation")
    for(var i=0;i<$("#sqtj>li").length;i++){
        //console.log(i);
        $("#sqtj>li").eq(i).css("backgroundColor","rgba(141,205,255,"+((7-i)/10)+")");
    }
    $("#type1").click(function(){
        $("#cjrzfrom2").css('display','none');
        $("#cjrzfrom1").css('display','block');
//$("#test").val($("#select>li>input").val())
        $("#select2>li>label>input").prop("checked",false);
    });
    $("#type2").click(function(){
        $("#cjrzfrom1").css('display','none');
        $("#cjrzfrom2").css('display','block');
        $("#select1>li>label>input").prop("checked",false);

    });

   /* $("#select2>li>input").on("click",function () {
        $("#test").val($(this).prop());
    })*/
});
