<?php

class custom_page_model extends base_model
{
	public function getPages($store_id, $page_size = 15)
	{
		$where = array();
		$where['store_id'] = $store_id; //array('in', array($store_id, 0));

		if (!empty($_REQUEST['keyword'])) {
			$where['name'] = array('like', '%' . trim($_REQUEST['keyword']) . '%');
		}

		$page_count = $this->db->where($where)->count('page_id');
		import('source.class.user_page');
		$p = new Page($page_count, $page_size);
		$pages = $this->db->where($where)->order('`page_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
		$return['pages'] = $pages;
		$return['page'] = $p->show();

		return $return;
	}

	public function getAllList($page_size = 15)
	{
		$where = array();

		if (!empty($_REQUEST['keyword'])) {
			$where['name'] = array('like', '%' . trim($_REQUEST['keyword']) . '%');
		}

		$page_count = $this->db->where($where)->count('page_id');
		import('source.class.user_page');
		$p = new Page($page_count, $page_size);
		$pages = $this->db->where($where)->order('`page_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
		$return['pages'] = $pages;
		$return['page'] = $p->show();

		return $return;
	}

	public function add($data)
	{
		return $this->db->data($data)->add();
	}

	public function save($where, $data)
	{
		return $this->db->where($where)->data($data)->save();
	}

	public function get($store_id, $page_id)
	{
		$where = array('page_id' => $page_id);
		if ($store_id)
			$where['store_id'] = $store_id;

		$custom_page = $this->db->where($where)->find();

		return $custom_page;
	}

	public function delete($store_id, $page_id)
	{
		$where = array('page_id' => $page_id);
		if ($store_id)
			$where['store_id'] = $store_id;

		return $this->db->where($where)->delete();
	}

	public function rename($store_id, $page_id, $name)
	{
		$where = array('page_id' => $page_id);
		if ($store_id)
			$where['store_id'] = $store_id;

		return $this->db->where($where)->data(array('name' => $name))
			->save();
	}
}
