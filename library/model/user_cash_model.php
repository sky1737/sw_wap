<?php

class user_cash_model extends base_model
{
	public function add($data)
	{
		return $this->db->data($data)->add();
	}

	/* SELECT c.* , b.`bank_id`, b.`name`, d.`bank_name`, d.`card_no`, d.`card_user` FROM `' . option('system.DB_PREFIX') . 'user_cash` c,  `' . option('system.DB_PREFIX') . 'user_card` d,  `' . option('system.DB_PREFIX') . 'bank` b WHERE c.`card_id` = d.`card_id` AND d.`bank_id` = b.`bank_id` */

	public function getCount($where)
	{
		$sql = 'SELECT COUNT(c.id) AS count FROM `' . option('system.DB_PREFIX') . 'user_cash` c,  `' .
			option('system.DB_PREFIX') . 'user_card` d,  `' . option('system.DB_PREFIX') .
			'bank` b WHERE c.`card_id` = d.`card_id` AND d.`bank_id` = b.`bank_id`';

		if($where) {
			foreach ($where as $key => $value) {
				if($key == '_string') {
					$sql .= ' AND ' . $value;
				}
				else {
					$sql .= ' AND ' . $key . ' = \'' . $value . '\'';
				}
			}
		}

		$result = $this->db->query($sql);

		return !empty($result[0]['count']) ? $result[0]['count'] : 0;
	}

	public function getRecords($where, $offset, $limit)
	{
		$sql = 'SELECT c.* , b.`bank_id`, b.`name`, d.`bank_name`, d.`card_no`, d.`card_user` FROM `' .
			option('system.DB_PREFIX') . 'user_cash` c,  `' . option('system.DB_PREFIX') . 'user_card` d,  `' .
			option('system.DB_PREFIX') . 'bank` b WHERE c.`card_id` = d.`card_id` AND d.`bank_id` = b.`bank_id`';

		if($where) {
			foreach ($where as $key => $value) {
				if($key == '_string') {
					$sql .= ' AND ' . $value;
				}
				else {
					$sql .= ' AND ' . $key . ' = \'' . $value . '\'';
				}
			}
		}

		$sql .= ' ORDER BY c.id DESC LIMIT ' . $offset . ', ' . $limit;
		$withdrawals = $this->db->query($sql);

		return $withdrawals;
	}

	public function getStatus($key = null)
	{
		$status = array(1 => '申请中', 2 => '银行处理中', 3 => '提现成功', 4 => '提现失败');

		if(is_null($key)) {
			return $status;
		}
		else {
			return $status[$key];
		}
	}
}

