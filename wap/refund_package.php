<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/15
 * Time: 15:11
 */
require_once dirname(__FILE__) . '/global.php';

if(IS_POST)
{
    $data = array(
        'express_code'=> $_POST['express_code'],
        'express_no'=> $_POST['express_no'],
        'express_company'=> $_POST['express_company'],
        'store_id'=> $_POST['store_id'],
        'order_id'=> $_POST['order_id'],
        'products'=> $_POST['products'],
        'status'=> 0,
        'add_time'=> time()
    );
    $result  = M('Refund_package')->add($data);
    if($result['err_code'] == 0 )
    {
        //改变订单状态 退款中
        M('Order')->setOrderStatus($_POST['store_id'],$_POST['order_id'],array('status' => 6));
        json_return(0,'提交成功');
    }
    json_return($result['err_code'],$result['err_msg']);
}