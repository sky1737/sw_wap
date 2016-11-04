<?php

class TopicViewModel extends ViewModel
{
    protected $viewFields = array(
        'Topic' => array('*'),
        'TopicCategory' => array('cat_name' => 'category', '_on' => 'Topic.cat_id = TopicCategory.cat_id')
    );
}

?>
