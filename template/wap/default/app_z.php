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
<div class="fixed">
    <nav class="tab-bar">
        <section class="left-small"><a class="menu-icon" href="./"><span></span></a></section>
        <section class="middle tab-bar-section">
            <h1 class="title">云温商众筹</h1>
        </section>
    </nav>
</div>
<div class="wx_wrap" style="margin-top: 45px; background: #fff;">
    <div class="z-img">
        <img src="http://xuediaochina.com/public/attachment/201610/13/21/57ff90c39d56b.jpg" alt=""/>
    </div>
    <h1 style="margin:0; padding: 5px 10px;font-size: 1.2rem;font-weight: normal;text-align: center;">云温商众筹</h1>
    <div style="line-height: 20px;color: #A4A4A4;padding: 5px 10px;overflow: hidden;">
        <div style="float: left;font-size: .6rem;">
            已筹资：<span style="font-size: .8rem; color: #f60;">¥</span><span style="font-size: .8rem;color: #f60;">18,009</span>&nbsp;&nbsp;
            目标：<span style="font-size: .8rem; color:#333">¥</span><span style="font-size: .8rem; color:#333">40,000</span>
        </div>
        <div style="float: right;">
            <span style="height: 22px;line-height: 22px;font-size: .6rem;display: inline-block;padding: 0px 5px;color: #FFF;background: #4dbdf5;">筹资中</span>
        </div>
    </div>
    <div style="padding: 5px 10px;">
        <div style="background: #d5d5d5;height: 5px;border-radius: 4px;position: relative;font-size: 0px;line-height: 0px;overflow: hidden;">
            <span style="position: absolute;max-width: 100%;height: 5px;line-height: 0px;border-radius: 4px;display: block;font-size: 0px;background: #6baaea;width:45%;"></span>
        </div>
        <div style="clear:both;height: 5px; line-height: 5px; overflow: hidden;"></div>
        <div style="float: left;width: 33%;font-size: .8rem;line-height: 1.2rem; margin: 7px 0;overflow: hidden;text-align: center;border-right: 1px solid #dfdfdf;">
            <span class="num">45%</span><br>
            <span class="til">已达</span>
        </div>
        <div style="float: left;width: 33%;font-size: .8rem;line-height: 1.2rem; margin: 7px 0;overflow: hidden;text-align: center;border-right: 1px solid #dfdfdf;">
            <span class="num">4天</span><br>
            <span class="til">剩余时间</span>
        </div>
        <div style="float: left;width: 33%;font-size: .8rem;line-height: 1.2rem; margin: 7px 0;overflow: hidden;text-align: center;border-right: 1px solid #dfdfdf;border:none;">
            <span class="num">12</span><br>
            <span class="til">支持者</span>
        </div>
        <div style="clear:both;height: 5px; line-height: 5px; overflow: hidden;"></div>
    </div>
    <!-- S 账户/积分 -->
    <a href="javascript:;" style="display: block; font-size: .6rem; background: #eee; text-align: center; color: #999;padding: 10px 10px;">展开详情</a>
    <div style="background: #fff;padding: 5px 10px;">

    </div>
    <!-- S 入口列表 -->

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