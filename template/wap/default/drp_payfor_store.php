<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <title>开个微店成为大赢家 - <?php echo option('config.site_name'); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css">
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css">
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css">
    <script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
    <script src="<?php echo TPL_URL; ?>js/base.js"></script>
    <style type="text/css">
        .bottom {
            height: 50px;
            border-top: solid 1px #eee;
            background: #fff;
            line-height: 30px;
        }

        .bottom .left {
            float: left;
            padding: 10px;
            font-size: 12px;
        }

        .bottom .left span {
            float: left;
            height: 30px;
            line-height: 30px;
        }

        .bottom .left span.price {
            font-size: 15px;
            padding: 0 3px 0 0;
        }

        .bottom .right {
            float: right;
            padding: 10px;
        }

        .bottom .right .payfor {
            border-radius: 3px;
            height: 30px;
            line-height: 30px;
            padding: 0 8px;
            background-color: #D73C6B;
            font-size: 14px;
            display: block;
            color: #fff;
        }

        .protocol-container {
            display: none;
            background-color: #FFF;
            position: fixed;
            top: 0;
            bottom:0;
            left: 0;
            right: 0;
            z-index: 9999;
            overflow-y: scroll;
        }

        .protocol-container .title {
            width: 100%;
            background-color: #FFF;
            text-align: center;
            padding: 20px 0;
        }

        .protocol-container .title img {
            width: 66px;
            height: 66px;
            vertical-align: -125%;
        }

        .protocol-container .title div {
            display: inline-block;
            font-size: 15px;
            color: #333;
        }

        .protocol-container .title p {
            margin: 2px 0 0 5px;
            font-weight: 600;
        }

        .protocol-container .protocol-text {
            margin: 0 10px;
            color: #666;
            border: 1px solid #EEE;
            padding: 7px;
        }

        .protocol-container .protocol-text p {
            text-indent: 32px;
            margin: 2px 0;
            letter-spacing: .8px;
            line-height: 20px;
        }

        .protocol-container .footer {
            text-align: center;
        }

        .protocol-container .footer button {
            width: 40%;
            height: 2rem;
            line-height: 2rem;
            margin: 0 0.2rem;
        }

        .protocol-container .footer button:first-child {
            color: #FFF;
        }

        .protocolIsAgree {
            position: absolute;
            margin: 3px 0 5px 7px;
        }

    </style>
</head>

<body style="max-width:640px;background:#9de7fc;">
<div>
    <img src="<?php echo TPL_URL; ?>images/payfor.png" alt="开个微店吧" style="width: 100%;"/>
</div>
<div class="h50"></div>
<div class="fixed bottom" style="">
    <div class="left">
        <select name="agent_id" id="agent_id"
                style="float:left; margin: 0 10px 0 0;padding: 0 0 0 5px;border-radius: 3px;height: 30px;line-height: 30px;width: 130px;">
            <?php
            foreach ($agents as $a) {
                echo '<option value="' . $a['agent_id'] . '">' . $a['name'] . '/￥' . $a['price'] . '</option>';
            }
            ?>
        </select>
        <span id="protocol"><a href="javascript:void(0);">开店协议</a></span>
        <span class="protocolIsAgree"><input type="checkbox" id="protocolIsAgree"></span>
        <!--<span>友情价：</span>
		<span class="price">￥<?php /*printf("%.2f", $config['payfor_store'] * 1); */?></span>
		<span>(数量有限)</span>-->
    </div>
    <div class="right">
        <a href="javascript:void(0);" class="payfor">立即开店</a>
    </div>
</div>

<div class="protocol-container">
    <div class="title">
        <div>
            <p>微店代理销售服务和结算协议</p>
        </div>
    </div>
    <div class="protocol-text">
        <p>1.分销商必须严格履行对消费者的承诺，分销商不得以其与供应商之间的约定对抗其对消费者的承诺,如果分销商与供应商之间的约定不清或不能覆盖分销商对消费者的销售承诺，风险由分销商自行承担；分销商与买家出现任何纠纷，均应当依据淘宝相关规则进行处理</p>
        <p>2.分销商承诺其最终销售给消费者的分销商品零售价格符合与供应商的约定</p>
        <p>3.在消费者（买家）付款后，分销商应当及时向供应商支付采购单货款，否则7天后系统将关闭采购单交易，分销商应当自行承担因此而发生的交易风险</p>
        <p>4.分销商应当在系统中及时同步供应商的实际产品库存，无论任何原因导致买家拍下后无货而产生的纠纷，均应由分销商自行承担风险与责任</p>
        <p>5.分销商承诺分销商品所产生的销售订单均由分销平台相应的的供应商供货，以保证分销商品品质</p>
        <p>6.分销商有义务确认消费者（买家）收货地址的有效性</p>
        <p>7.分销商有义务在买家收到货物后，及时确认货款给供应商。如果在供应商发出货物30天后，分销商仍未确认收货，则系统会自动确认收货并将采购单对应的货款支付给供应商</p>
    </div>
    <div class="footer">
        <button class="red agree">同 意</button>
        <button class="gray cancel">取 消</button>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('.payfor').click(function () {
            /*if(!confirm('继续操作表示您已阅读并且接受开店协议！')) return false;*/
            var isAgree = $('#protocolIsAgree').prop('checked');
            if (!isAgree) {
                $$('.protocol-container').css('display','block');
                return;
            }
            var loadingCon = $('<div style="overflow:hidden;visibility:visible;position:absolute;z-index:1100;transition:opacity 300ms ease;-webkit-transition:opacity 300ms ease;opacity:1;top:' + (($(window).height() - 100) / 2) + 'px;left:' + (($(window).width() - 200) / 2) + 'px;"><div class="loader-container" style="width: 200px;background: #fff;padding: 50px 10px;text-align: center;"><div class="loader center">处理中，请稍候...</div></div></div>');
            var loadingBg = $('<div style="height:100%;position:fixed;top:0px;left:0px;right:0px;z-index:1000;opacity:1;transition:opacity 0.2s ease;-webkit-transition:opacity 0.2s ease;background-color:rgba(0,0,0,0.901961);"></div>');
            $('html').css({'position': 'relative', 'overflow': 'hidden', 'height': $(window).height() + 'px'});
            $('body').css({
                'overflow': 'hidden',
                'height': $(window).height() + 'px',
                'padding': '0px'
            }).append(loadingCon).append(loadingBg);
            nowScroll = $(window).scrollTop();

            $.post('./drp_register.php', {'type': 'payfor','agent_id':$('#agent_id').val() }, function (result) {
                //loadingBg.css('opacity', 0);
                setTimeout(function () {
                    loadingCon.remove();
                    loadingBg.remove();
                }, 200);

                if (result.err_code) {
                    alert(result.err_msg);
                    return false;
                }
                else {
                    if (window.WeixinJSBridge) {
                        window.WeixinJSBridge.invoke("getBrandWCPayRequest", result.err_msg, function (res) {
                            WeixinJSBridge.log(res.err_msg);
                            if (res.err_msg == "get_brand_wcpay_request:ok") {
                                //window.location.href='drp_register.php?type=redpack&order_no='+result.err_dom;
//								//alert('支付成功！');
//								$.post('./drp_register.php', {
//									'type': 'redpack',
//									'order_no': result.err_dom
//								}, function (data) {
//									if (data.err_code) {
//										alert(data.err_msg + '(' + data.err_msg + ')')
//									}
                                window.location.href = './drp_ucenter.php?refresh=1';
//								}, 'JSON');
                            }
                            else {
                                if (res.err_msg == "get_brand_wcpay_request:cancel") {
                                    var err_msg = "您取消了微信支付";
                                }
                                else if (res.err_msg == "get_brand_wcpay_request:fail") {
                                    var err_msg = "微信支付失败<br/>错误信息：" + res.err_desc;
                                }
                                else {
                                    var err_msg = res.err_msg + "<br/>" + res.err_desc;
                                }
                                alert(err_msg);
                            }
                        });
                    }
                    else {
                        alert('请在微信中发起支付请求！')
                        //alert(result.err_msg);
                    }
                    return false;
                }
            }, 'JSON');
        });

    $('#protocol').click(function () {
        $('.protocol-container').css('display','block');
        $('.protocol-container .agree').click(function () {
            $('#protocolIsAgree').prop('checked',true);
            $('.protocol-container').css('display', 'none');
        });
        $('.protocol-container .cancel').click(function () {
            $('#protocolIsAgree').prop('checked',false);
            $('.protocol-container').css('display', 'none');
        });
    });

    });
</script>
</body>
</html>