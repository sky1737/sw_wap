<?php

class product_to_property_model extends base_model
{
    public function add($data)
    {
        $result = $this->db->data($data)->add();
        return $result;
    }

    public function edit($store_id, $product_id, $prop_pids)
    {
        $db_prefix = option('system.DB_PREFIX');
        $i = 1;
        foreach ($prop_pids as $pid) {
            $sql = "INSERT INTO `{$db_prefix}product_to_property`(`store_id`, `product_id`, `pid`, `order_by`)" .
                "SELECT {$store_id}, {$product_id}, {$pid}, {$i} FROM dual WHERE NOT EXISTS " .
                "(SELECT * FROM `{$db_prefix}product_to_property` WHERE `store_id` = {$store_id} and `product_id` = {$product_id} and `pid` = {$pid})";
            $this->db->execute($sql);

            $i++;
        }

        $ids = join(",", $prop_pids);
        $this->db->execute("DELETE FROM `{$db_prefix}product_to_property` WHERE `store_id` = {$store_id} and `product_id` = {$product_id} and `pid` not in ({$ids})");

        return $i;
    }

    public function getPids($store_id, $product_id, $fields = 'pid')
    {
        $pids = $this->db->field($fields)->where(array('store_id' => $store_id, 'product_id' => $product_id))->order('order_by ASC')->select();
        return $pids;
    }

    public function getProperties($store_id, $product_id)
    {
        $db_prefix = option('system.DB_PREFIX');
        $sql = "SELECT pp.`pid`, pp.`name` FROM `{$db_prefix}product_to_property` ptp INNER JOIN `{$db_prefix}product_property` pp ON ptp.`pid` = pp.`pid` WHERE pp.`status` = 1 AND ptp.`store_id` = {$store_id} AND ptp.`product_id` = {$product_id} ORDER BY ptp.`id` ASC;";
        return $this->db->query($sql);
    }

    public function getPropertyNames($store_id, $product_id)
    {
        return $this->db->where(array('store_id' => $store_id, 'product_id' => $product_id))->select();
    }
}

?>
