<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>厂家直供-中国“新零售”领导品牌</title>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/base.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/yws-cjzg.css">
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery-3.0.0.min.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/yws-cjzg.js"></script>
</head>
<body>
<!--头部 start-->
<header class="header">
    <div class="logo fl"><a href="./index.php?ywsydy=true"></a></div>
    <div class="search fl">
        <i></i>
        <input type="search" name="search" placeholder="请输入厂家名称">
    </div>
    <a href="./my.php"></a>
</header>
<!--头部 end-->
<div class="con">
    <div class="con-box clearfix">
        <ul>
            <?php foreach($stores as $val):?>
                <?php if($val['s_sid']==91 || $val['s_sid']==212):?>
                    <?php continue;?>
                <?php endif;?>
                <li>
                    <a href="./category2.php?storeid=<?= $val['s_sid']?>" >
                        <img src="<?= $val['logo']?>" alt="">
                        <p><?= $val['name']?></p>
                        <span style="color: #ccc;">认证时间：<?= date('Y-m-d H:i',$val['date_added'])?></span>
                    </a>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
<!--底部 start-->
<footer class="footer">
    <ul>
        <!--<li><a href="./index.php?ywsydy=true">
                <i></i><span style="font-size: 12px;">首页</span>
            </a>
        </li>-->
        <li><a href="./category.php">
                <i></i><span style="font-size: 12px;">分类</span>
            </a></li>
        <li><a href="./cart.php">
                <i></i><span style="font-size: 12px;">购物车</span>
            </a></li>
        <li><a href="./my.php">
                <i></i><span style="font-size: 12px;">个人中心</span>
            </a></li>
    </ul>
</footer>
<script>
    $('input[name=search]').keyup(function(){
        var name = $('input[name=search]').val();
        //console.log(name);
        $.ajax({
            type: "POST",
            url: './yws_tofactory1.php',
            data: {'name':name},
            success:function(data){
                console.log(data);
                $('.con-box ul').html(data);

            },
        });
    });
</script>
<!--底部 end-->
</body>
</html>