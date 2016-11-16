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

    dl {
        clear: both;
    }

    dl dt {
        font-style: normal;
        font-weight: normal;
        margin: 0;
        float: left;
        font-size: 1rem;
        line-height: 1.5rem;
        color: #999;
    }

    dl dd {
        font-size: 1rem;
        float: right;
        color: #ccc;
        margin: 0;
    }

    dl dd span {
        color: #e13045;
        text-decoration: underline;
    }
</style>
<div class="wx_wrap" style="padding: 10px;;">
    <div class="z_box">
        <h3>档位金额：<b><?php echo $z_item['minimum'] . ' - ' . $z_item['maximum']; ?></b></h3>
        <h4>回报内容：</h4>
        <div class="note"><?php echo $z_item['note']; ?></div>
    </div>
    <div class="z_box">
        <label>请输入投资金额：</label>
        <input type="number" name="invest" id="invest" value="<?php printf('%.2f', $z_item['maximum']); ?>"/>
    </div>
    <div class="z_box">
        <label>使用余额&nbsp; <span style="font-size: .6rem; color: #e13045;">账户余额：<b
                    style="font-weight: normal;"><?php echo $balance; ?></b></span></label>
        <input type="number" name="amount" id="amount" value="0.00"/>
    </div>
    <div class="z_box">
        <dl>
            <dt>投资金额</dt>
            <dd>￥<span id="t"><?php printf('%.2f', $z_item['maximum']); ?></span></dd>
        </dl>
        <dl>
            <dt>余额支付</dt>
            <dd>￥<span id="b">0.00</span></dd>
        </dl>
        <dl>
            <dt>微信支付</dt>
            <dd>￥<span id="w"><?php printf('%.2f', $z_item['maximum']); ?></span></dd>
        </dl>
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
        <label><input type="checkbox" value="1" checked="checked" id="agree" name="agree"/> 阅读并同意《<a
                href="">支持者协议</a>》</label>
    </div>
    <input type="hidden" id="zid" name="zid" value="<?php echo $z['zid']; ?>">
    <input type="hidden" id="item_id" name="item_id" value="<?php echo $z_item['item_id']; ?>">
    <div>
        <a href="javascript:;" class="btn">立即支付</a>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        var MIN = parseInt('<?php echo intval($z_item['minimum']); ?>'); // 最小投资金额
        var MAX = parseInt('<?php echo intval($z_item['maximum']); ?>'); // 增大投资金额
        var DIV = parseInt('<?php echo intval($z_item['amount']); ?>'); // 增加倍数
        var BALANCE = parseFloat('<?php echo $balance; ?>');

        function getv() {
            var $a = $('#invest');
            var v = parseInt($a.val());
            if (isNaN(v) || v < MIN) v = MIN;
            if (v > MAX) v = MAX;
            var rem = (v - MIN) % DIV;
            if (rem != 0) {
                v = MIN + Math.floor((v - MIN) / DIV) * DIV;
            }
            $a.val(v.toFixed(2));
            $('span#t').text(v.toFixed(2));

            var $b = $('#amount');
            var b = parseFloat($b.val());
            if (isNaN(b) || b < 0) b = 0;
            if (b > BALANCE) b = BALANCE;
            if (b > v) b = v;
            $b.val(b.toFixed(2));
            $('span#b').text(b.toFixed(2));

            $('span#w').text((v - b).toFixed(2));

            return (v - b);
        }

        $('input[type=number]').focus(function () {
            var $obj = $(this);
            if (parseFloat($obj.val()) == 0.00) {
                $obj.val('');
            }
        });

        $('#invest').blur(function () {
            getv();
        });

        $('#amount').blur(function () {
            getv();
        });

        $('.btn').click(function () {

            //var loadingCon = $('<div style="overflow:hidden;visibility:visible;position:absolute;z-index:1100;transition:opacity 300ms ease;-webkit-transition:opacity 300ms ease;opacity:1;top:' + (($(window).height() - 100) / 2) + 'px;left:' + (($(window).width() - 200) / 2) + 'px;"><div class="loader-container"><div class="loader center">处理中</div></div></div>');
            var loadingCon = $('<div style="overflow:hidden;visibility:visible;position:absolute;z-index:1100;transition:opacity 300ms ease;-webkit-transition:opacity 300ms ease;opacity:1;top:' + (($(window).height() - 100) / 2) + 'px;left:' + (($(window).width() - 200) / 2) + 'px;"><div class="loader-container" style="width: 200px;background: #fff;padding: 50px 10px;text-align: center;"><div class="loader center">处理中，请稍候...</div></div></div>');
            var loadingBg = $('<div style="height:100%;position:fixed;top:0px;left:0px;right:0px;z-index:1000;opacity:1;transition:opacity 0.2s ease;-webkit-transition:opacity 0.2s ease;background-color:rgba(0,0,0,0.901961);"></div>');
            $('html').css({'position': 'relative', 'overflow': 'hidden', 'height': $(window).height() + 'px'});
            $('body').css({
                'overflow': 'hidden',
                'height': $(window).height() + 'px',
                'padding': '0px'
            }).append(loadingCon).append(loadingBg);
            nowScroll = $(window).scrollTop();

            $.post('app_z.php?a=join&zid=<?php echo $z['zid']; ?>&itemid=<?php echo $z_item['item_id']; ?>', {
                invest: $('#invest').val(),
                amount: $('#amount').val()
            }, function (result) {

                $('html').css({'overflow': 'visible', 'height': 'auto', 'position': 'static'});
                $('body').css({'overflow': 'visible', 'height': 'auto', 'padding-bottom': '45px'});
                $(window).scrollTop(nowScroll);

                //loadingBg.css('opacity', 0);
                setTimeout(function () {
                    loadingCon.remove();
                    loadingBg.remove();
                }, 200);

                // 0 微信支付
                if (result.err_code == 0) {
                    if (typeof(result.err_msg) == "object" && payType == 'weixin' && window.WeixinJSBridge) {
                        window.WeixinJSBridge.invoke("getBrandWCPayRequest", result.err_msg, function (res) {
                            WeixinJSBridge.log(res.err_msg);
                            if (res.err_msg == "get_brand_wcpay_request:ok") {
                                //if (orderNo.indexOf(orderPrefix) == 0) {
                                //    window.location.href = './order.php?orderno=' + orderNo;
                                //}
                                //else {
                                //    window.location.href = './my_order.php';
                                //}
                                alert('支付成功！');
                                location.href = 'app_z_my.php';
                            }
                            else {
                                if (res.err_msg == "get_brand_wcpay_request:cancel") {
                                    alert("您取消了微信支付");
                                }
                                else if (res.err_msg == "get_brand_wcpay_request:fail") {
                                    alert("微信支付失败\n错误信息：" + res.err_desc);
                                }
                                else {
                                    alert(res.err_msg + "\n" + res.err_desc)
                                }
                                return false;
                            }
                        });
                    }
                } else if (result.err_code == 10) {
                    alert('支付成功！');
                    location.href = 'app_z_my.php';
                }
                else {
                    alert(result.err_msg)
                }
            }, 'JSON');
        });
    });
</script>
<?php
include display('drp_footer');
echo $shareData;
?>
</body>
</html>