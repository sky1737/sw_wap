<?php
/**
 * 消息通知模板测试
 */
header("Content-Type: text/html;charset='UTF-8'");

define('TWIKER_PATH', dirname(__FILE__).'/../');
require_once TWIKER_PATH.'source/init.php';
require_once 'functions.php';

function send($notify_url, $wecha_id, $token, $type, $href, $title, $buyer_tel, $seller_tel, $order_detail, $products, $remark)
{
    if (!empty($order_detail['address'])) {
        unset($order_detail['address']);
    }
    if (!empty($order_detail['order_no'])) {
        $order_detail['order_no'] = option('config.orderid_prefix') . $order_detail['order_no'];
    }
    $data = array(
        'wecha_id'     => $wecha_id,
        'token'        => $token,
        'type'         => $type,
        'href'         => $href,
        'title'        => $title,
        'buyer_tel'    => $buyer_tel,
        'seller_tel'   => $seller_tel,
        'order_detail' => $order_detail,
        'products'     => $products,
        'remark'       => $remark,
    );
    $sort_data = $data;
    $sort_data['salt'] = 'pigcms';
    ksort($sort_data);
    $sign_key = sha1(http_build_query($sort_data));
    $data['sign_key'] = $sign_key;
    $data['request_time'] = time();

    $request_url = $notify_url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    return curl_exec($ch);
}