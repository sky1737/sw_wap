<?php

class express_model extends base_model
{
	public function getExpress()
	{
		$express = $this->db->select();
		return $express;
	}
}

?>
