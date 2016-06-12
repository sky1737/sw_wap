<?php

class user_card_model extends base_model
{
	public function add($data)
	{
		return $this->db->data($data)->add();
	}

	public function save($where, $data)
	{
		return $this->db->where($where)->data($data)->add();
	}

	public function getCount($uid)
	{
		return $this->db->where(array('uid' => $uid))->count('card_id');
	}

	public function getCardWithBank($card_id){
		return $this->db->query('');
	}

	public function getCard($uid, $card_id = 0)
	{
		$where = array('uid' => $uid);
		if($card_id) {
			$where['card_id'] = $card_id;
		}

		return $this->db->where($where)->find();
	}

	public function getCards($uid, $offset = 0, $limit = 10)
	{
		return $this->db->where(array('uid' => $uid))->limit($offset . ',' . $limit)->select();
	}
}

