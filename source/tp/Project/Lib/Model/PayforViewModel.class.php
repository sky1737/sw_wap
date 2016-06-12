<?php

/**
 * 订单数据视图
 * User: pigcms_21
 * Date: 2014/12/29
 * Time: 13:11
 */
class PayforViewModel extends ViewModel
{
	public $viewFields = array(
		'Payfor_order' => array('*', '_as' => 'o', '_type' => 'LEFT'),
		'User'  => array('nickname', 'avatar', '_as' => 'u', '_on' => 'u.uid=o.uid'),
	);

	//订单状态
	public function status()
	{
		return array(
			0 => '临时订单',
			1 => '待付款订单',
			2 => '已付款订单'
		);
	}

//	//支付方式
//	public function getPaymentMethod()
//	{
//		return array(
//			'alipay'    => '支付宝',
//			'tenpay'    => '财付通',
//			'yeepay'    => '易宝支付',
//			'allinpay'  => '通联支付',
//			'chinabank' => '网银在线',
//			'weixin'    => '微信支付',
//			'offline'   => '货到付款',
//			'balance'   => '余额支付'
//		);
//	}
}