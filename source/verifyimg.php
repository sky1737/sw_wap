<?php

define('TWIKER_PATH', dirname(__FILE__) . '/../');
require_once TWIKER_PATH . 'source/init.php';

import('source.class.Image');
Image::buildImageVerify(4, 1, 'jpeg', $_GET['w'] ? $_GET['w'] : 53, $_GET['h'] ? $_GET['h'] : 28,
	$_GET['name'] ? $_GET['name'] : 'verify');

echo ob_get_clean();
