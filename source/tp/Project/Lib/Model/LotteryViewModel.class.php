<?php

/**
 * 订单数据视图
 * User: pigcms_21
 * Date: 2014/12/29
 * Time: 13:11
 */
class LotteryViewModel extends ViewModel
{
	public $viewFields = array(
        'ActivityLotteryLog'  => array('order_id', 'gift_name', 'time'=>'l_time','_as' => 'l' ),
		'Order' => array('*', '_as' => 'o', '_on' => 'l.order_id=o.order_id', '_type' => 'LEFT'),
		'User'  => array('nickname', 'avatar', '_as' => 'u','_on' => 'u.uid=o.uid', '_type' => 'LEFT'),
	);
}