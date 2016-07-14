<?php
if(!defined('TWIKER_PATH'))
    exit('deny access!');
?>
    <!DOCTYPE html>
    <html class="no-js" lang="zh-CN">
    <head>
        <meta charset="utf-8" />
        <meta name="keywords" content="<?php echo $config['seo_keywords']; ?>" />
        <meta name="description" content="<?php echo $config['seo_description']; ?>" />
        <meta name="HandheldFriendly" content="true" />
        <meta name="MobileOptimized" content="320" />
        <meta name="format-detection" content="telephone=no" />
        <meta http-equiv="cleartype" content="on" />
        <link rel="icon" href="<?php echo $config['site_url']; ?>/favicon.ico" />
        <title>退款申请</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/base.css" />
        <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/trade.css" />
        <link rel="stylesheet" href="<?php echo TPL_URL; ?>css/offline_shop.css">
        <script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
        <script src="<?php echo $config['oss_url']; ?>/static/js/area/area.min.js"></script>
        <script src="<?php echo TPL_URL; ?>js/base.js"></script>
        <script type="text/javascript">
            var noCart = true,
                orderPrefix = '<?php echo $config['orderid_prefix']; ?>',
                orderNo = '<?php echo $nowOrder['order_no_txt'];?>',
                sub_total =<?php echo $nowOrder['sub_total'];?>,
                isLogin = !<?php echo intval(empty($wap_user));?>,
                pay_url = 'saveorder.php?action=refund';
        </script>
        <script src="http://api.map.baidu.com/api?v=1.2" type="text/javascript"></script>
        <script type="text/javascript">
            var postage = '<?php echo $nowOrder['postage'] ?>';
            var is_logistics = true;
        </script>
    </head>
    <body>
    <div class="container js-page-content wap-page-order">
        <form name="form" id="form" method="post">
            <ul>
                <li>
                    <div>选择物流信息:</div>
                    <div>
                        <select name="express_code">
                            <?php foreach ($express  as $key => $val):?>
                                <option value ="<?php echo $val['code']?>"><?php echo $val['name'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </li>
                <li>
                    <div>物流单号:</div>
                    <div>
                        <input type="text" name="express_no" value="">
                    </div>
                </li>
            </ul>
            <li>
                <button id="">确认提交</button>
            </li>
        </form>
        <?php $noFooterLinks = true;
        include display('footer'); ?>
    </div>
    </body>
    </html>