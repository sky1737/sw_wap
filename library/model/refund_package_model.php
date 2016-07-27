<?php

class refund_package_model extends base_model
{
	function __construct($model)
	{
		parent::__construct($model);
	}
	public function add($data)
	{
		if($id = $this->db->data($data)->add()) {
			$data['id'] = $id;
			return array('err_code' => 0, 'err_msg' => $data);
		}
		else {
			return array('err_code' => 1009, 'err_msg' => '添加失败！请重试。');
		}
	}

	public function save($condition, $data)
	{
		return array('err_code' => 0, 'err_msg' => $this->db->where($condition)->data($data)->save());
	}

	public function getByOrderId($orderId)
	{
		return $this->db->where(array('orderid' => $orderId))->find();
	}

	public function refuse_sign($data)
	{
		$this->save(array('store_id' => $data['store_id'],'order_id' =>$data['order_id']),array('status' => -1,'handle_time'=> time(),'handle_name' =>$data['name'],'refuse_sign_reason' => $data['refuse_sign_reason']));

		Notify::getInstance()->refund($data['openid'],
			option('config.wap_site_url') . '/order.php?orderid=' . $data['order_id'],
			'你好，订单退款失败',
			'申请退款',
			$data['money'],
			'您的订单退款失败，商家拒绝签收！原因：'.$data['refuse_sign_reason'].'  请联系商家 电话：'.$data['service_tel'].'! 或登录商城联系客服！');
	}
}
