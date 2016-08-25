<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
    <meta name="description" content="<?php echo $config['seo_description']; ?>"/>
    <link rel="icon" href="<?php echo $config['site_url']; ?>/favicon.ico"/>
    <title>登陆手机号</title>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="default"/>
    <meta name="applicable-device" content="mobile"/>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>index_style/css/address.css"/>
    <link rel="stylesheet" href="<?php echo $config['site_url']; ?>/template/wap/default/css/gonggong.css"/>
    <script src="<?php echo $config['oss_url']; ?>/static/js/fastclick.js"></script>
    <script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
    <script src="<?php echo TPL_URL; ?>index_style/js/base.js"></script>
    <style>.address_list ul:before {
            display: none;
        }</style>
    <script>
        $(function () {
            $('#edit').click(function () {
                if (!/^((\+86)|(86))?(1)\d{10}$/.test($('#mobile').val())) {
                    alert('请填写正确的手机号码');
                } else {
                    $('#wxloading').show();
                    $.post('', {
                        uid: $('#uid').val(),
                        phone: $('#mobile').val(),
                    }, function (result) {
                        if (result.err_code) {
                            $('#wxloading').hide();
                            alert(result.err_msg);
                        } else {
                            location.href = '/wap/';
                        }
                    });
                }
            });
        });

    </script>
</head>
<body>
<div class="wx_wrap">
    <div class="address_new">
        <input type="hidden" id="uid" name="uid" value="<?php echo $_SESSION['user']['uid']; ?>"/>
        <p>
            <label for="mobile">
                <span class="tit">完善手机号</span>
                <input type="tel" id="mobile" name="mobile" value="<?php echo $_SESSION['user']['phone']; ?>" placeholder="登陆手机号，一经填写不可修改，请认真填写">
            </label>
        </p>
        <p class="action">
            <button class="submit" id="edit">确认</button>
        </p>
    </div>
</div>
<?php include display('public_menu'); ?>
</body>
</html>