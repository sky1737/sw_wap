<?php

class HelpViewModel extends ViewModel
{
    protected $viewFields = array(
        'Help' => array('*'),
        'HelpCategory' => array('cat_name' => 'category', '_on' => 'Help.cat_id = HelpCategory.cat_id')
    );
}

?>
