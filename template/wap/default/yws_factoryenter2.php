<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="zxjBigPower">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>厂家入驻-中国“新零售”领导品牌</title>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/base.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/yws_factoryenter2.css">
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/base.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/yws_factoryenter2.js"></script>


</head>
<body>
<div id="newzeroselltips" style="display: none; height: 120px; width: 60%; border-radius: 10px; background: rgba(255, 255, 255, 0.95); position: absolute; bottom: 10%; left: 20%; z-index: 1000;">
    <div id="msg" style="margin:0 auto;width:100%;height: 50px;text-align: center;padding-top: 25px;font-size: 14px;color: red;"></div>
    <div style="width: 90px;height: 36px;margin: 0 auto;margin-top: 20px;font-size: 12px;border-radius:7px;background:#99bbf5;color: white;text-align: center;line-height: 36px" id="iknow">知道了</div>
</div>
<div class="con">
    <input type="hidden" value="<?php echo $arr?>" id="factoryjson" name="ahha">
    <form action="./yws_factoryenter.php" id="factoryenter" enctype="multipart/form-data" method="post">
        <div class="con-box">
            <input type="hidden" value="<?= $str2?>" name="post">
            <input type="hidden" name="yws_title" value="厂家入驻">
            <h3>提交资料</h3>
            <p>您需提供：<span style="margin-left: -3px">（支持png、gif、jpg且文件小于3M）</span></p>
            <ul id="con-box">
                <?php foreach ($data as $k=>$val):?>
                    <li>
                        <p><?= $k+1;?><?php echo "、".$val['title']?>:&nbsp;&nbsp; <i>*</i></p>
                        <div class="con-box-son clearfix">
                            <div class="img-box"></div>
                            <a href="#">+
                                <input onclick="showFile(this,<?php echo $k?>)" type="file" name="<?php echo $val['nameId']?>" id="<?php echo $val['nameId']?>" accept="image/png,image/gif,image/bmp,image/jpg" />
                            </a>
                            <div class="filebox">
                                <div class="filebox-box"></div>
                            </div>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>
        <input type="submit" value="提交" id="finish">
    </form>
<!--    <div class="finish" id="finish">完成</div>-->
    <script>
        var countlength = $('ul li').length;
        /*
         * 选择照片并显示
         * file：file文件选择，show：展示页
         * */
        //console.log(countlength);
        var picArray =[],nextpageflag=true;str='';
        function showFile(file,pic_url) {
            //console.log(countlength);
            file = $(file)[0];
            show=$('.filebox-box')[pic_url];
            file.onchange = function () {
                var reader = new FileReader();
                //console.log(typeof this.files[0]);
                var file= this.files[0];
                console.log(file);
                var size=file.size;
                console.log(size);
                if(size>=3145728){
                    //console.log('图片过大');
                    $('#newzeroselltips').css('display','block');
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
            $('#newzeroselltips').css('display','none');
        })
        $('#finish').click(function(){
            //console.log(typeof picArray);
            str='';
            for(var i in picArray){
                str += picArray[i];
                //console.log(picArray[i]);
            }
            console.log(str.length);
            if(str.length*1 <= countlength*1-1){
                nextpageflag=false;
                $('.showbox').css('z-index','999');
                $('#newzeroselltips').css('display','block');
                $('#msg').html('请填写完整');
                return;
            }else{
                $('#finish').val('申请提交中...');
                nextpageflag=true;
            }

        });

        $('#factoryenter').submit(function(){
            if($(this).hasClass('loading')){
                return false;
            }
            if(str.length==countlength ){
                console('true');
                $('#finish').val('申请提交中...');
                $(this).addClass('loading');
                return true;
            }else{
                return false;
            }

            return false;
        });
    </script>
</div>
</body>
</html>
<!--arttemplate模板拼接-->


