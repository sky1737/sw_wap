<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="zxjBigPower">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>个性定制-中国“新零售”领导品牌</title>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/base.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/yws_individualityCustomization.css">
    <script src="<?php echo TPL_URL; ?>js/yws_lz/lib/js/jquery-3.0.0.min.js"></script>
    <!--<script src="<?php /*echo TPL_URL; */?>js/yws_lz/lib/js/zepto.js"></script>-->
   <!-- <script src="<?php /*echo TPL_URL; */?>js/yws_lz/lib/js/zeptoSelector.js"></script>-->
    <script src="<?php echo TPL_URL; ?>js/yws_lz/base.js"></script>
    <script src="<?php echo TPL_URL; ?>js/yws_lz/yws_individualityCustomization.js"></script>
</head>
<body>
<div class="con">
    <div class="con-box">
        <div class="logo">
            <ul>
                <?php if(!empty($cat_ids)):?>
                    <?php foreach ($cat_ids as $v):?>
                        <li><a href="./category2.php?cat_ids=<?php echo trim($v,',')?>"><span></span></a></li>
                    <?php endforeach;?>
                <?php endif;?>
            </ul>
            <div class="logo-box">
                <div class="circle-1"></div>
                <div class="circle-2"></div>
                <div class="circle-3"></div>

            </div>
        </div>
    </div>
</div>
</body>
</html>