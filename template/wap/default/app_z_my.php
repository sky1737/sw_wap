<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
    <meta name="description" content="<?php echo $config['seo_description']; ?>"/>
    <title>云温商众筹</title>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="default"/>
    <meta name="applicable-device" content="mobile"/>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
    <!--<link rel="stylesheet" href="--><?php //echo TPL_URL; ?><!--index_style/css/my.css"/>-->
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/drp_dis.css"/>
    <script src="<?php echo $config['oss_url']; ?>/static/js/fastclick.js"></script>
    <script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
    <script src="<?php echo TPL_URL; ?>index_style/js/base.js"></script>
</head>
<body class="body-gray">
<div class="fixed" style="background: #eee;">
    <nav class="tab-bar">
        <section class="left-small"><a class="menu-icon" href="./app_z.php"><span></span></a></section>
        <section class="middle tab-bar-section">
            <h1>云温商众筹</h1>
        </section>
    </nav>
</div>
<style type="text/css">
    .wx_wrap {
        margin-top: 45px;
        background: #fff;
    }

    .wx_wrap dl {
        border-top: 5px solid #eee;
    }

    .wx_wrap dl dt {
        font-weight: normal;
        position: relative;
        padding: 10px 10px 0 10px;
        margin: 0;
        font-size: 1rem;
    }

    .wx_wrap dt b {
        color: #f00;
    }

    .wx_wrap dl dt span {
        position: absolute;
        right: 10px;
        top: 10px;
    }

    .wx_wrap dl dd {
        border-bottom: 5px solid #eee;
        padding: 0 10px 10px 10px;
        margin: 0;
        font-size: .8rem;
        color: #999;
        position: relative;
    }

    .wx_wrap dl dd .btn {
        right: 0;
        top: 8px;
        position: absolute;
    }
    .wx_wrap dl dd .over{
        right: 10px;
        top: 12px;
        position: absolute;
    }

    .wx_wrap dl dd .btn a {
        float: left;
        color: #fff;
        cursor: pointer;
        display: block;
        font-size: .8rem;
        padding: 5px 15px;
        text-align: center;
        margin-right: 10px;
        background: #4dbdf5;
        border-radius: 3px;
    }
</style>
<div class="wx_wrap" style="">
    <dl>
        <?php
        foreach ($list as $item) {
            $b = ($item['expire_time'] - time()) <= 0;

            echo '<dt>投资金额：<b>' . number_format($item['total'], 2) . '</b><span>' . ($b?'':floor(($item['expire_time'] - time()) / 60 / 60 / 24).' 天') . '</span></dt>';
            echo '<dd>固定收益：' . number_format($item['profit'], 2) . '<br/>赠送消费金：' . number_format($item['gift'], 2);
            if($item['status']==2){
                echo '<div class="btn"><a '.($b?'href="./app_z_my.php?a=tx&id='.$item['order_id'].'"':'style="background: #ccc;"').'>提现</a> <a '.($b?'href="./app_z_my.php?a=xt&id='.$item['order_id'].'"':'style="background: #ccc;"').'>续投</a></div>';
            } else {
                echo '<div class="over">已完成</div>';
            }
            echo '</dd>';
        } ?>
    </dl>

    <!-- E 入口列表 -->
    <!--div class="my_links">
        <a href="tel:4006560011" class="link_tel">致电客服</a>
        <a href="#" class="link_online">在线客服</a>
    </div-->
</div>
<?php
include display('drp_footer');
echo $shareData;
?>
</body>
</html>