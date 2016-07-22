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
        'refund_reason'=> $_POST['refund_reason'],
        'is_take'=> $_POST['is_take'],
        'status'=> 0,
        'add_time'=> time()
    );
    $result  = M('Refund_package')->add($data);
    if($result['err_code'] == 0 )
    {
        //改变订单状态 退款中
        M('Order')->setOrderStatus($_POST['store_id'],$_POST['order_id'],array('status' => 6));
        json_return(0,'退款申请成功，待管理员确认后即可退款，可在退款订单中查看！');
    } else
    {
        json_return($result['err_code'],$result['err_msg']);
    }
    exit;

}