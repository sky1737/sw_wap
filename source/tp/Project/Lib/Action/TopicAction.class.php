<?php

/**
 * 商品
 * User: pigcms_21
 * Date: 2015/3/21
 * Time: 17:48
 */
class TopicAction extends BaseAction
{

    public function index()
    {
        $topic = D('TopicView');
        $category = M('TopicCategory');

        $where = array();
        $where['topic.title'] = array('like', '%' . $this->_get('keyword', 'trim') . '%');
//	    if ($this->_get('type', 'trim') && $this->_get('keyword', 'trim')) {
//            if ($this->_get('type', 'trim') == 'topic_id') {
//                $where['Topic.topic_id'] = $this->_get('keyword', 'trim,intval');
//            } else if ($this->_get('type', 'trim') == 'name') {
//                $where['Topic.name'] = array('like', '%' . $this->_get('keyword', 'trim') . '%');
//            } else if ($this->_get('type', 'trim') == 'store') {
//                $where['Store.name'] = array('like', '%' . $this->_get('keyword', 'trim') . '%');
//            }
//        }

        if ($this->_get('category', 'trim,intval')) {
            $where['topic.cat_id'] = $this->_get('category', 'trim,intval');
        }

        $topic_count = $topic->where($where)->count('topic.topic_id');
        import('@.ORG.system_page');
        $page = new Page($topic_count, 20);
        $tmp_topics =
            $topic->where($where)->order('topic.topic_id DESC')->limit($page->firstRow, $page->listRows)->select();

        $topics = array();
        foreach ($tmp_topics as $tmp_Topic) {
            $topics[] = array(
                'topic_id' => $tmp_Topic['topic_id'],
                'title' => $tmp_Topic['title'],
                'cat_id' => $tmp_Topic['cat_id'],
                'category' => $tmp_Topic['category'],
                'sort' => $tmp_Topic['sort'],
                'status' => $tmp_Topic['status'],
            );
        }

        $categories = $category->order('cat_path ASC')->select();

        $this->assign('topics', $topics);
        $this->assign('page', $page->show());
        $this->assign('categories', $categories);

        $this->display();
    }

    public function add()
    {
        $category = M('TopicCategory');

        if (IS_POST) {
            $data = array();
            $data['cat_id'] = $this->_post('cat_id', 'trim,intval');
            $cate = $category->where(array('cat_id' => $data['cat_id']))->find();
            if (!empty($cate['cat_fid'])) {
                $data['cat_fid'] = $cate['cat_fid'];
            }
            $data['title'] = $this->_post('title', 'trim');
            $data['content'] = $this->_post('content', 'trim');
            $data['sort'] = $this->_post('sort', 'trim,intval');
            $data['status'] = $this->_post('status', 'trim,intval');
            $data['seo_title'] = $this->_post('seo_title', 'trim');
            $data['seo_key'] = $this->_post('seo_key', 'trim');
            $data['seo_des'] = $this->_post('seo_des', 'trim');
            $data['date_added'] = time();

            if ($topic_id = M('Topic')->add($data)) {
                $this->frame_submit_tips(1, '添加成功！');
            } else {
                $this->frame_submit_tips(0, '添加失败！请重试~');
            }
        }
        $this->assign('bg_color', '#F3F3F3');

        $categories = $category->order('cat_path ASC')->select();
        $this->assign('categories', $categories);

        $this->display();
    }

    public function edit()
    {
        $category = M('TopicCategory');
        $topic = M('Topic');

        if (IS_POST) {
            $topic_id = $this->_post('topic_id', 'trim,intval');

            $data = array();
            $data['cat_id'] = $this->_post('cat_id', 'trim,intval');
            $cate = $category->where(array('cat_id' => $data['cat_id']))->find();
            if (!empty($cate['cat_fid'])) {
                $data['cat_fid'] = $cate['cat_fid'];
            }
            $data['title'] = $this->_post('title', 'trim');
            $data['content'] = $this->_post('content', 'trim');
            $data['sort'] = $this->_post('sort', 'trim,intval');
            $data['status'] = $this->_post('status', 'trim,intval');
            $data['seo_title'] = $this->_post('seo_title', 'trim');
            $data['seo_key'] = $this->_post('seo_key', 'trim');
            $data['seo_des'] = $this->_post('seo_des', 'trim');

            if ($topic_id = $topic->where(array('topic_id' => $topic_id))->save($data)) {
                $this->frame_submit_tips(1, '修改成功！');
            } else {
                $this->frame_submit_tips(0, '修改失败！请重试~');
            }
        }

        $this->assign('bg_color', '#F3F3F3');

        $categories = $category->order('cat_path ASC')->select();
        $this->assign('categories', $categories);

        $id = $this->_get('id', 'trim,intval');
        $now_topic = $topic->find($id);
        $this->assign('topic', $now_topic);

        $this->display();
    }

    public function del()
    {
        $cat_id = $this->_get('id', 'trim,intval');
        if (M('Topic')->delete($cat_id)) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！请重试~');
        }
    }

    public function status()
    {
        $topic = M('Topic');

        $topic_id = $this->_post('id', 'trim,intval');
        $status = $this->_post('status', 'trim,intval');
        if ($topic->where(array('topic_id' => $topic_id))->save(array('status' => $status))) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    public function category()
    {
        $category = M('TopicCategory');

        $where = array();
        if ($this->_get('cat_id', 'trim,intval')) {
            $where['_string'] = "cat_id = '" . $this->_get('cat_id', 'trim,intval') . "' OR cat_fid = '" .
                $this->_get('cat_id', 'trim,intval') . "'";
        }
        $category_count = $category->where($where)->count('cat_id');
        import('@.ORG.system_page');
        $page = new Page($category_count, 30);
        $categories =
            $category->where($where)->order('cat_path ASC')->limit($page->firstRow, $page->listRows)->select();

        //所有分类
        $all_categories = $category->order('cat_path ASC')->select();

        $this->assign('categories', $categories);
        $this->assign('all_categories', $all_categories);
        $this->assign('page', $page->show());
        $this->display();
    }

    public function category_add()
    {
        $category = M('TopicCategory');

        if (IS_POST) {
            $data = array();
            $data['cat_fid'] = $this->_post('parent_id', 'trim,intval');
            $data['cat_name'] = $this->_post('name', 'trim');
            $data['cat_sort'] = $this->_post('order_by', 'trim,intval');
            $data['cat_status'] = $this->_post('status', 'trim,intval');
            $data['cat_desc'] = $this->_post('desc', 'trim');
            if (!empty($data['cat_fid'])) {
                $data['cat_level'] = 2;
                $path = $category->where(array('cat_id' => $data['cat_fid']))->getField('cat_path');
                $data['cat_path'] = $path;
            } else {
                $data['cat_level'] = 1;
                $data['cat_path'] = 0;
            }

            if ($cat_id = $category->add($data)) {
                if ($cat_id <= 9) {
                    $str_cat_id = '0' . $cat_id;
                } else {
                    $str_cat_id = $cat_id;
                }
                $path = $data['cat_path'] . ',' . $str_cat_id;
                $category->where(array('cat_id' => $cat_id))->save(array('cat_path' => $path));
                $this->frame_submit_tips(1, '添加成功！');
            } else {
                $this->frame_submit_tips(0, '添加失败！请重试~');
            }
        }
        $this->assign('bg_color', '#F3F3F3');
        $categories =
            $category->where(array('cat_level' => 1, 'cat_status' => 1))->order('cat_sort ASC, cat_id ASC')->select();

        $this->assign('categories', $categories);

        $this->display();
    }

    public function category_edit()
    {
        $category = M('TopicCategory');

        if (IS_POST) {
            $cat_id = $this->_post('cat_id', 'trim,intval');
            $data = array();
            $data['cat_fid'] = $this->_post('parent_id', 'trim,intval');
            $data['cat_name'] = $this->_post('name', 'trim');
            $data['cat_sort'] = $this->_post('order_by', 'trim,intval');
            $data['cat_status'] = $this->_post('status', 'trim,intval');
            $data['cat_desc'] = $this->_post('desc', 'trim');

            if (!empty($data['cat_fid'])) {
                $data['cat_level'] = 2;
                $path = $category->where(array('cat_id' => $data['cat_fid']))->getField('cat_path');
                $data['cat_path'] = $path;
            } else {
                $data['cat_level'] = 1;
                $data['cat_path'] = 0;
            }

            if ($category->where(array('cat_id' => $cat_id))->save($data)) {
                if ($cat_id <= 9) {
                    $str_cat_id = '0' . $cat_id;
                } else {
                    $str_cat_id = $cat_id;
                }
                $path = $data['cat_path'] . ',' . $str_cat_id;
                $category->where(array('cat_id' => $cat_id))->save(array('cat_path' => $path));

                $this->frame_submit_tips(1, '修改成功！');
            } else {
                $this->frame_submit_tips(0, '修改失败！请重试~');
            }
        }

        $this->assign('bg_color', '#F3F3F3');
        $id = $this->_get('id', 'trim,intval');
        $categories =
            $category->where(array('cat_level' => 1, 'cat_status' => 1))->order('cat_sort ASC, cat_id ASC')->select();

        $category = $category->find($id);
        $category['cat_pic'] = getAttachmentUrl($category['cat_pic']);
        $category['cat_pc_pic'] = getAttachmentUrl($category['cat_pc_pic']);
        $this->assign('categories', $categories);
        $this->assign('category', $category);

        $this->display();
    }

    //删除分类
    public function category_del()
    {
        $category = M('TopicCategory');

        $cat_id = $this->_get('id', 'trim,intval');
        if ($category->delete($cat_id)) {
            $category->where(array('cat_fid' => $cat_id))->delete(); //删除子分类
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！请重试~');
        }
    }

    //修改分类状态
    public function category_status()
    {
        $category = M('TopicCategory');

        $cat_id = $this->_post('cat_id', 'trim,intval');
        $status = $this->_post('status', 'trim,intval');
        if ($category->where(array('cat_id' => $cat_id))
                ->save(array('cat_status' => $status)) &&
            $category->where(array('cat_fid' => $cat_id))
                ->save(array('cat_status' => $status, 'cat_parent_status' => $status))
        ) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

}

