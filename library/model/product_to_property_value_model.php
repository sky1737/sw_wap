<?php

class product_to_property_value_model extends base_model
{
    public function add($data)
    {
        $result = $this->db->data($data)->add();
        return $result;
    }

    public function edit($store_id, $product_id, $prop_vids)
    {
        $db_prefix = option('system.DB_PREFIX');
        $i = 1;
        $pids = array();
        $vids = array();
        foreach ($prop_vids as $pv) {
            $sql = "INSERT INTO `{$db_prefix}product_to_property_value` (`store_id`, `product_id`, `pid`, `vid`, `order_by`)" .
                "SELECT {$store_id}, {$product_id}, {$pv['pid']}, {$pv['vid']}, {$i} FROM dual WHERE NOT EXISTS (" .
                "SELECT * FROM `{$db_prefix}product_to_property_value` WHERE /*`store_id` = {$store_id} and*/ `product_id` = {$product_id} and `pid` = {$pv['pid']} and `vid` = {$pv['vid']})";
            $this->db->execute($sql);

            $pids[$pv['pid']] = $pv['pid'];
            $vids[$pv['vid']] = $pv['vid'];
            $i++;
        }
        $pidStr = join(",", $pids);
        $this->db->execute("DELETE FROM `{$db_prefix}product_to_property_value` WHERE /*`store_id` = {$store_id} and*/ `product_id` = {$product_id} and `pid` NOT in ({$pidStr})");
        $vidStr = join(",", $vids);
        $this->db->execute("DELETE FROM `{$db_prefix}product_to_property_value` WHERE /*`store_id` = {$store_id} and*/ `product_id` = {$product_id} and `pid` in ({$pidStr}) and `vid` NOT IN ($vidStr)");

        return $i;
    }

    public function getVids($store_id, $product_id, $pid, $fields = 'vid')
    {
        $vids = $this->db->field($fields)->where(array('pid' => $pid, 'product_id' => $product_id, /*'store_id' => $store_id*/))->order('order_by ASC')->select();
        return $vids;
    }

    public function getValues($store_id, $product_id, $property_id)
    {
        $db_prefix = option('system.DB_PREFIX');
        $sql = "SELECT ppv.`vid`, ppv.`value`, ppv.`image` FROM `{$db_prefix}product_to_property_value` ptpv INNER JOIN `{$db_prefix}product_property_value` ppv ON ptpv.`vid` = ppv.`vid` WHERE /*ptpv.`store_id` = {$store_id} AND*/ ptpv.`product_id` = {$product_id} AND ptpv.`pid` = {$property_id} ORDER BY `order_by` ASC";
        return $this->db->query($sql);
    }

    public function getPropertyValues($store_id, $product_id)
    {
        return $this->db->where(array(/*'store_id' => $store_id,*/ 'product_id' => $product_id))->select();
    }
}