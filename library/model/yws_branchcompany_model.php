<?php

/**
 * 订单数据模型
 */
class yws_branchcompany_model extends base_model
{

    public function add($data)
    {
        return $this->db->data($data)->add();
    }

}
