<?php

class ProductViewModel extends ViewModel
{
    protected $viewFields = array(
        'Product'         => array('*'),
        'ProductCategory' => array('cat_name' => 'category', '_on' => 'Product.category_id = ProductCategory.cat_id'),
        'Store'           => array('name' => 'store', '_on' => 'Product.store_id = Store.store_id'),
        'User'            => array('nickname' => 'createUName', 'uid' => 'createUid', '_on' => 'Product.create_uid = User.uid'),
    );
}
