<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="keywords"
          content="<?php echo $topic['seo_title'] ? $topic['seo_title'] : $config['seo_keywords']; ?>"/>
    <meta name="description"
          content="<?php echo $topic['seo_des'] ? $topic['seo_des'] : $config['seo_description']; ?>"/>
    <link rel="icon" href="<?php echo $config['site_url']; ?>/favicon.ico"/>
    <title><?php echo $topic['seo_title'] ? $topic['seo_title'] : $topic['title']; ?></title>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="default"/>
    <meta name="applicable-device" content="mobile"/>
    <style type="text/css">
        * {
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        body {
            max-width: 960px;
            margin: 0 auto;
        }
        img {
            border: none;
            width: 100%;
            display: block;
        }
        input, textarea, button, a {
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }
    </style>
</head>
<body>
<?php echo $topic['content']; ?>
<?php
//$noFooterLinks = true;
//$noFooterCopy = true;
//include display('footer');
//include display('public_menu');
echo $shareData;
?>
</body>
</html>