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
    <title><?php echo $z['title']; ?></title>
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
            <h1><?php echo $z['title']; ?></h1>
        </section>
    </nav>
</div>
<style type="text/css">
    .wx_wrap {
        margin-top: 45px;
    }

    .z_box {
        background: #fff;
        padding: 10px;
        margin-bottom: 10px;
    }

    .z_box h3 {
        font-size: 1.2rem;
        margin: 0;
        padding: 0;
        color: #333333;
        font-weight: normal;
        margin-bottom: 5px;
    }

    .z_box h3 b {
        color: #e13045;
    }

    .z_box h4, .z_box label {
        font-size: 1rem;
        margin: 0;
        padding: 0;
        color: #666;
        font-weight: normal;
        margin-bottom: 5px;
    }

    .z_box .note {
        font-size: 1rem;
        line-height: 1.3rem;
        color: #666;
    }

    .agree {
        padding: 0 10px;
    }

    a.btn {
        background-color: #e13045;
        line-height: 1.6rem;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        color: #fff;
        padding: 10px 40px;
        text-decoration: none;
        display: block;
        font-size: 1.1rem;
        text-align: center;
        margin-top: 10px;
    }

    input[type=number], input[type=checkbox] {
        margin-bottom: 0;
    }
</style>
<div class="wx_wrap" style="padding: 10px;;">
    <div class="z_box">
        <h3>档位金额：<b><?php echo $z_item['minimum'] . ' - ' . $z_item['maximum']; ?></b></h3>
        <h4>回报内容：</h4>
        <div class="note"><?php echo $z_item['note']; ?></div>
    </div>
    <div class="z_box">
        <label>请输入支持金额：</label>
        <input type="number" name="amount" id="amount" value="<?php echo $z_item['maximum']; ?>"/>
    </div>
    <div class="z_box">
        <label>账户余额：<b><?php echo $balance; ?></b></label>
        <input type="number" name="balance" id="balance" value="<?php echo $balance; ?>"/>
    </div>
    <!--<div class="z_box">-->
    <!--    <h4>风险说明：</h4>-->
    <!--    <div class="note">-->
    <!--        请您务必审慎阅读、充分理解协议中相关条款内容，其中包括：<br>-->
    <!--        1、风险提示条款和特别提示条款；<br>-->
    <!--        2、与您约定法律适用和管辖的条款；<br>-->
    <!--        3、其他以粗体标识的重要条款。<br>-->
    <!--        如您不同意相关协议、公告、规则、操作流程和项目页面承诺，您有权选择不支持；一旦选择支持，即视为您已确知并完全同意相关协议。-->
    <!--    </div>-->
    <!--</div>-->
    <div class="agree">
        <label><input type="checkbox" value="1" checked="checked" id="agree" name="agree"/> 阅读并同意《<a href="">支持者协议</a>》</label>
    </div>
    <div>
        <a href="javascript:;" class="btn">立即支付 ￥<b><?php echo $z_item['maximum']; ?></b></a>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        var min = parseInt('<?php echo intval($z_item['minimum']); ?>');
        var max = parseInt('<?php echo intval($z_item['maximum']); ?>');
        var balance = parseFloat('<?php echo $balance; ?>')
        var $val = max;
        $('#amount').blur(function () {
            $val = parseInt($(this).val());
            if ($val < min) $val = min;
            if ($val > max) $val = max;

            $(this).val($val);
            $('a.btn b').text($val);
        });
        $('#balance').blur(function () {
            var val = parseInt($(this).val());
            if (val < 0) val = 0;
            if (val > balance) val = balance;

            $(this).val(val);
            $('a.btn b').text(val);
        });
    });
</script>
<?php
include display('drp_footer');
echo $shareData;
?>
</body>
</html>