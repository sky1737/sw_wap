<?php

class seller_fx_product_model extends base_model
{
	public function add($data)
	{
		return $this->db->data($data)->add();
	}
}

?>
