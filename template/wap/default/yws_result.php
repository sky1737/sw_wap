<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta CONTENT="zxjBigPower">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title><?php echo isset($title) ? $title.'-'.'中国“新零售”领导品牌' : '新零售申请-中国“新零售”领导品牌'?></title>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/base.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/yws_lz/yws_result.css">
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>js/yws_lz/lib/css/jquery.fullPage.css">
</head>
<body>
<?php var_dump($title)?>
<div id="dowebok">
    <div class="section section1">
        <div class="showbox">
            <div class="con" style="font-size: 16px;text-align: center;">
                <?php if(isset($error)):?>
                    <?php foreach ($error as $val):?>
                        <?php echo $val."<br/>"?>
                    <?php endforeach;?>
                <?php else:?>
                <?php echo $resoult?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
</body>
</html>