<?php
if (!defined('TWIKER_PATH')) exit('deny access!');
//print_r($item);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
    <meta name="description" content="<?php echo $config['seo_description']; ?>"/>
    <title><?php echo $item['title']; ?></title>
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
        <section class="left-small"><a class="menu-icon" href="./"><span></span></a></section>
        <section class="middle tab-bar-section">
            <h1><?php echo $item['title']; ?></h1>
        </section>
    </nav>
</div>
<style type="text/css">
    .wx_wrap {
        margin-top: 45px;
    }

    h1.title {
        background: #fff;
        margin: 0;
        padding: 5px 10px;
        font-size: 1.2rem;
        font-weight: normal;
        text-align: center;
    }

    .z-money {
        background: #fff;
        line-height: 20px;
        color: #A4A4A4;
        padding: 5px 10px;
        overflow: hidden;
    }

    .z-money .money-left {
        float: left;
        font-size: .6rem;
    }

    .z-money .money-left span.red {
        font-size: .8rem;
        color: #f60;
    }

    .z-money .money-left span {
        font-size: .8rem;
        color: #333
    }

    .z-money .z-state {
        height: 22px;
        line-height: 22px;
        font-size: .6rem;
        display: inline-block;
        padding: 0px 5px;
        color: #FFF;
    }

    .z-info {
        background: #fff;
        padding: 5px 10px;
    }

    .z-info .process-bg {
        background: #d5d5d5;
        height: 20px;
        border-radius: 4px;
        position: relative;
        font-size: 12px;
        line-height: 20px;
    }

    .z-info .process {
        max-width: 100%;
        border-radius: 4px;
        display: block;
        font-size: 12px;
        background: #6baaea;
        height: 20px;
        color: #fff;
        line-height: 20px;
        text-indent: 5px;
        overflow: visible;
    }

    .z-info .z-cols {
        float: left;
        width: 33%;
        font-size: .8rem;
        line-height: 1.2rem;
        margin: 7px 0;
        overflow: hidden;
        text-align: center;
        border-right: 1px solid #dfdfdf;
    }

    .line {
        clear: both;
        height: 5px;
        line-height: 5px;
        overflow: hidden;
    }

    .z-expand {
        background: #fff;
        margin-top: 5px;
    }

    .z-expand .z-intro {
        padding: 5px 10px;
        display: none;
    }

    .z-expand .z-intro p {
        margin-bottom: auto;
    }

    .z-expand .btn {
        text-align: center;
        display: block;
        font-size: .6rem;
        color: #999;
        padding: 10px 10px;
    }
</style>
<div class="wx_wrap" style="">

</div>
<?php
include display('drp_footer');
echo $shareData;
?>
</body>
</html>