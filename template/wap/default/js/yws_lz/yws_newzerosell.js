/**
 * Created by zxjBigPower on 2017/6/10.
 */

$(function () {
    var submitflag=false;
    $('#dowebok').fullpage({
        afterLoad:function(anchorLink ,index){
            $(".section").removeClass("animation");
            setTimeout(function () {
                $(".section").eq(index-1).addClass("animation");
            },10);
        }
    });
    $(".section1").addClass("animation");
    $(".next").on("click", function () {
        $.fn.fullpage.moveSectionDown();
    })

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

    /*setInterval(function(){
     //点击切换下一屏
     $.fn.fullpage.moveSectionDown();
     }, 5000);*/

    /*
     * 选择照片并显示
     * file：file文件选择，show：展示页
     * */
    showFile($("#yyzz")[0], $(".filebox-box")[0],0)
    showFile($("#mgcz")[0], $(".filebox-box")[1],1)
    showFile($("#sfz")[0], $(".filebox-box")[2],2)
    showFile($("#swdj")[0], $(".filebox-box")[3],3)
    showFile($("#zhzm")[0], $(".filebox-box")[4],4)
    /*
     * 选择照片并显示
     * file：file文件选择，show：展示页
     * */
    var picArray =[],nextpageflag=true;
    function showFile(file,show,pic_url) {
        file.onchange = function () {
            //console.log(1);
            //创建对象
            var reader = new FileReader();
            //console.log(typeof this.files[0]);
            var file= this.files[0];
            var size=file.size;
            console.log(size);
            if(size>=3145728){
                //console.log('图片过大');
                $('#alertBox').css('display','block');
                //$('.showbox').css('background-color','#E8E8E8');
                $('.showbox').css('z-index','999');
                $('#msg').html('您上传的图片过大，请重新上传');
                nextpageflag=false;
                return;
            }
            reader.readAsDataURL(this.files[0]);
            reader.onload = function () {
                //console.log(reader.result)
                show.style.background = "url(" + reader.result + ") no-repeat center/contain";
                picArray[pic_url] = pic_url;
            }
        }
    }
    $('#iknow').click(function(){
        //$('.showbox').css('background-color','rgba(255,255,255,0)');
        nextpageflag=true;
        $('.showbox').css('z-index','999');
        $('#alertBox').css('display','none');
    })
//切换下一页
    $("#nextPage").on("click",function () {
        // console.log($(this).parent(".con"));
        //console.log(1);
        //$(this).parent("div").fadeOut(200);
        //$(this).parent("div").css({"display":"none"});
        if(!nextpageflag){
            return;
        }
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
        if(!nextpageflag){
            return;
        }
        $(".con-next").css({"display":"none"});
        $(this).parent(".con").css({"display":"none"});
        $(".con-first").fadeIn(200);
        $(".finish").css({"display":"none"});
    })
//第二页切换第三页
    $("#nextPage1").on("click",function () {
        //console.log(1);
        if(!nextpageflag){
            return;
        }
        $(".con-next").css({"display":"none"});
        $(this).parent(".con").css({"display":"none"});
        $(".con-end").fadeIn(200);
        $(".finish").css({"display":"block"});
    })
//第三页点击切换第二页
    $("#endPage").on("click",function () {
        //console.log(2);
        if(!nextpageflag){
            return;
        }
        $(this).parent(".con").css({"display":"none"});
        $(".con-next").fadeIn(200);
        $(".finish").css({"display":"none"});
    })
    //手机号
    var unum = document.getElementById('tel');
    unum.oninput=function (){
        this.value = this.value.replace(/\D/g,'');
        //this.value =this.value.replace(/(\d{3})(?=\d)/g,'$1 ');
    };
    //电话号码匹配；
    var flag_phone = false;
    $("#tel").bind('keyup blur',function () {
        //console.log($(this).val());
        //获取值：
        var keyUpval=$(this).val()-0;
        //console.log(keyUpval);
        //匹配是否符合
        var isTel=/^1(3|4|5|7|8)\d{9}$/.test(keyUpval);//console.log(isTel);
        if(!isTel){
            $(this).css({"borderColor":"red"});
            flag_phone = false;
        }else{
            $(this).css({"borderColor":"black"});
            flag_phone = true;
        }
    })

    var str='';
    $('.finish').click(function(){
        str='';
        var area = $('input[name=area]').val();linkman = $('input[name=linkman]').val();phone = $('input[name=phone]').val();
        //console.log(area,linkman,phone);
        //console.log(typeof picArray);
        for(var i in picArray){
            str += picArray[i];
            //console.log(picArray[i]);
        }
        console.log(str.length);
        console.log(flag_phone==true);
        if(str.length*1<=4 || !area || !linkman || !phone){
            nextpageflag=false;
            $('.showbox').css('z-index','999');
            $('#alertBox').css('display','block');
            $('#msg').html('请填写完整');
            return;
        }else{
            nextpageflag=true;
            $('.showbox').css('z-index','999');
            $('#alertBox').css('display','none');
            $('#notnull').html('');
        }

        if(!flag_phone){
            //$('#notnull').html('请检查手机号是否正确');
            nextpageflag=false;
            $('.showbox').css('z-index','999');
            $('#alertBox').css('display','block');
            $('#msg').html('请检查手机号是否正确');
            return;
        }else {
            $('#notnull').html('');
        }
        if(str.length>=5 && area && linkman && (flag_phone==true)){
            //console(linkman);
            $('.finish').val('申请提交中...');
            submitflag=true;
        }else{
            submitflag=false;
        }
    });

    $('#newzerofile').submit(function(){
        var area = $('input[name=area]').val();linkman = $('input[name=linkman]').val();phone = $('input[name=phone]').val();
        if($(this).hasClass('loading')){
            return false;
        }

        if((flag_phone==true)&& str.length*1==5 && area && linkman ){
            //console(linkman);
            $('.finish').val('申请提交中...');
            $(this).addClass('loading');
            return true;
        }else{
            return false;
        }
        return false;
    });
});
