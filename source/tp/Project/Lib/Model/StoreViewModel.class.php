<?php

/**
 * 店铺数据视图
 * User: pigcms_21
 * Date: 2015/3/18
 * Time: 20:12
 */
class StoreViewModel extends ViewModel
{
    public $viewFields = array(
        'Store' => array('*', '_type' => 'left'),
        'User' => array('nickname', 'uid', 'phone' => 'username', '_on' => 'Store.uid = User.uid', '_type' => 'left'),
        'SaleCategory' => array('name' => 'category', '_on' => 'Store.sale_category_fid = SaleCategory.cat_id', '_type' => 'left'),
        'Agent' => array('agent_id' , '_on' => 'Store.agent_id = Agent.agent_id'),
    );
} 