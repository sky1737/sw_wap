<?php

class adver_model extends base_model
{
	public function get_adver_by_key($cat_key, $limit = 3)
	{
		$db = D('Adver_category');
		$condition_category['cat_key'] = $cat_key;
		$category = $db->field('`cat_id`')->where($condition_category)->find();
		$list = array();

		if($category) {
			$condition_adver['cat_id'] = $category['cat_id'];
			$condition_adver['status'] = '1';
			if(!$limit) {
				$list = D('Adver')->where($condition_adver)->order('`id` DESC')->select();
			}
			else {
				$list = D('Adver')->where($condition_adver)->order('`id` DESC')->limit($limit)->select();
			}

			foreach ($list as $key => $value) {
				$list[$key]['pic'] = getAttachmentUrl($value['pic']);
				if($list[$key]['qrcode']) {
					$list[$key]['qrcode'] = getAttachmentUrl($value['qrcode']);
				}
			}

			return $list;
		}
		else {
			return $list;
		}
	}
}
