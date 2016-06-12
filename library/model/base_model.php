<?php

class base_model
{
	public $db;

	public function __construct($model)
	{
		import('source.class.mysql');
		$db = new mysql();
		$this->db = $db->table($model);
	}

	public function getTotal($where)
	{
		return $this->db->where($where)->count('1');
	}

	public function getRecords($where, $order, $offset, $limit)
	{
		return $this->db->where($where)->order($order)->limit($offset . ',' . $limit)->select();
	}

//	public function getTotal($where)
//	{
//		return $this->db->where($where)->count('income_id');
//	}
//
//	public function getRecords($where, $order, $offset, $limit)
//	{
//		return $this->db->where($where)->order($order)->limit($offset . ',' . $limit)->select();
//	}
}
