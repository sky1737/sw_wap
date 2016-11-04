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
        <section class="left-small"><a class="menu-icon" href="./drp_ucenter.php"><span></span></a></section>
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
    <div class="z-img">
        <img src="/upload/<?php echo $item['image']; ?>" alt=""/>
    </div>
    <!--<h1 class="title" style="">云温商众筹</h1>-->
    <div class="z-money" style="">
        <div class="money-left" style="">
            已筹资：<span class="red">¥</span><span class="red"><?php echo $item['achieved']; ?></span>&nbsp;&nbsp;
            目标：<span>¥</span><span><?php echo $item['target']; ?></span>
        </div>
        <div style="float: right;">
            <span class="z-state" style="background: #4dbdf5;">筹资中</span>
        </div>
    </div>
    <div class="z-info">
        <div class="process-bg">
            <span class="process" style="width:<?php printf('%.2f', $item['achieved'] / $item['target'] * 100.00); ?>%;"><?php printf('%.2f', $item['achieved'] / $item['target'] * 100.00); ?>%</span>
        </div>
        <div class="line"></div>
        <div class="z-cols">
            <span class="num"><?php printf('%.2f', $item['profit_rate'] / 100.00); ?>%</span><br>
            <span class="til">预期收益</span>
        </div>
        <div class="z-cols">
            <span class="num"><?php printf('%.2f', $item['gift_rate'] / 100.00); ?>%</span><br>
            <span class="til">赠送消费金</span>
        </div>
        <div class="z-cols" style="border:none;">
            <span class="num"><?php echo $item['days']; ?>天</span><br>
            <span class="til">封闭时间</span>
        </div>
        <div class="line"></div>
    </div>
    <!-- S 账户/积分 -->

    <div class="z-expand">
        <div class="z-intro">
            <?php echo $item['details']; ?>
        </div>
        <script type="text/javascript">
            $(function () {
                $('.z-expand a.btn').click(function () {
                    $('.z-intro').toggle('slow');
                    $(this).text($(this).text() == "展开详情" ? "收起详情" : "展开详情");
                });
            });
        </script>
        <a href="javascript:;" class="btn">展开详情</a>
    </div>
    <style type="text/css">
        .z-join {
            background: #fff;
            margin-top: 5px;
            padding: 0px 10px;
        }

        .z-join-item {
            padding: 10px 0 20px;
            border-bottom: 1px solid #efefef;
        }

        .z-join-title {
        }

        .z-join-title .z-join-left {
            line-height: 30px;
            float: left;
        }

        .z-join-title .z-join-left .z-join-money {
            font-size: 1.3rem;
            color: #6baaea;
        }

        .z-join-title .z-join-left .z-join-money i {
            font-style: normal;
        }

        .z-join-title .z-join-left .z-join-joins {
            margin-left: 10px;
            font-size: .6rem;
            color: #ff7510;
        }

        .z-join-title .z-join-btn {
            float: right;
            width: 80px;
            height: 38px;
            line-height: 38px;
            color: #fff !important;
            cursor: pointer;
            display: block;
            font-size: .8rem;
            overflow: hidden;
            text-align: center;
            background: #4dbdf5;
            border-radius: 5px;
        }

        .z-join-return {
            font-size: .9rem;
            line-height: 22px;
        }
    </style>
    <div class="z-join">
        <?php
        //print_r($now_store);
        foreach($item['items'] as $it){ ?>
            <div class="z-join-item">
                <div class="z-join-title">
                    <div class="z-join-left">
                        <span class="z-join-money"><i>¥</i><?php echo $it['minimum']; ?>-<?php echo $it['maximum']; ?></span>
                        <span class="z-join-joins"><?php echo $it['buys']; ?>人已支持</span>
                    </div>
                    <?php
                    if($now_store['agent_id'] == $it['agent_id']) {
                        echo '<a href="app_z.php?a=join&amp;zid='.$it['zid'].'&amp;itemid='.$it['item_id'].'" class="z-join-btn">立即支持</a>';
                    } else {
                        echo '<a href="javascript:;" class="z-join-btn" style="background: #ccc;">立即支持</a>';
                    }
                    ?>
                    <div class="line" style="height: 10px;"></div>
                </div>
                <div class="z-join-return"><?php echo $it['note']; ?></div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php
include display('drp_footer');
echo $shareData;
?>
</body>
</html>