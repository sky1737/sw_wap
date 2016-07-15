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

	public function save_user($condition, $data)
	{
		return array('err_code' => 0, 'err_msg' => $this->db->where($condition)->data($data)->save());
	}
}
