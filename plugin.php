<?php
ini_set("session.cookie_domain", 'zy.budingtao.com');
//ini_set('session.cookie_lifetime', '1800');

/**
 *
 **/
define('TWIKER_PATH', dirname(__FILE__) . '/');
require_once TWIKER_PATH . 'source/init.php';

if (check_plugin($_GET['plugin'])) {
	if (!empty($_GET['do_plugin_file'])) {
		include
			TWIKER_PATH . 'source/plugins/' . $_GET['plugin'] . '/' . $_GET['plugin'] . '_' . $_GET['do_plugin_file'] .
			'.php';
	}
	else {
		include TWIKER_PATH . 'source/plugins/' . $_GET['plugin'] . '/' . $_GET['plugin'] . '.php';
	}
}
else {
	pigcms_tips('站点未开启插件： <b>' . $_GET['plugin'] . '</b>');
}

echo ob_get_clean();
