<?php
/**
 * 配置文件
 */
if(!defined('TWIKER_PATH')) exit('deny access!');

$cfg = array(
	'bg' => '../static/qrcode/limit_bg.jpg',
	'path' => '../upload/qrcode/' . floor($wap_user['uid'] / 1000) . '/' . floor($wap_user['uid'] / 100) . '/',
	'filename' => $wap_user['uid'] . '.png',
	'qrcode' => array('url' => '../static/qrcode/no.png', 'width' => 174 , 'x' => 132, 'y' => 706),
	'avatar' => array('filename' => md5($wap_user['avatar']) . '.jpg', 'width' =>  61, 'x' => 208, 'y' => 68 ),
	'nickname' => array('x' => 380, 'y' => 78),
	'expire' => array('date' => '', 'x' => 170, 'y' => 900)
);