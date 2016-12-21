<?php

class api_count_model extends base_model
{
	public function visit($key)
	{
		$result = $this->db->where(array('key' => $key))->find();
		if ($result) {
			$this->db->where(array('key' => $key))->setInc('count', 1);
		}
		else {
			$this->db->data(array('key' => $key, 'count' => 1))->add();
		}
	}
}
