<?php

class help_controller extends base_controller
{

	public function index()
	{
		$help_categories = array();//F('help_categories');
		if(empty($help_categories)) {
			$categories = D('Help_Category')->where(array('cat_status' => 1))->order('cat_path ASC')->select();
			$helps = D('Help')->where(array('status' => 1))->order('sort asc, help_id desc')->select();
			foreach ($categories as &$k) {
				$category_helps = array();
				foreach ($helps as $m) {
					if($m['cat_id'] != $k['cat_id'])
						continue;
					$category_helps[$m['help_id']] = $m;
				}
				$k['helps'] = $category_helps;
			}

			$help_categories = array();
			foreach ($categories as $k) {
				if($k['cat_fid'] > 0)
					continue;

				$children = array();
				foreach ($categories as $v) {
					if($v['cat_fid'] != $k['cat_id'])
						continue;

					$children[$v['cat_id']] = $v;
				}
				$k['children'] = $children;

				$help_categories[$k['cat_id']] = $k;
			}
			F('help_categories', $help_categories);
		}
		$this->assign('help_categories', $help_categories);

		$cat_fid = I('get.fid', 0, 'trim,intval');
		$cat_id = I('get.cat', 0, 'trim,intval');
//		$help_id = I('get.help', 0, 'trim,intval');
		if($cat_id && $cat_id) {
//			if($help_id) {
			$helps = $help_categories[$cat_fid]['children'][$cat_id]['helps']; //[$help_id];
//			}
//			else {
//				$helps = $help_categories[$cat_fid]['children'][$cat_id]['helps'];
//				foreach ($helps as $h) {
//					$help = $h;
//					break;
//				}
//			}
		}
		else {
			foreach ($help_categories as $f) {
				$cat_fid = $f['cat_id'];
				foreach ($f['children'] as $c) {
					$cat_id = $c['cat_id'];
					$helps = $c['helps'];
//					foreach ($c['helps'] as $h) {
//						$help = $h;
//						break;
//					}
					break;
				}
				break;
			}
		}
		$this->assign('cat_fid', $cat_fid);
		$this->assign('cat_id', $cat_id);
		$this->assign('helps', $helps);

		//print_r($help_categories);
//		$cat_id = I('get.category', 0, 'trim,intval');
//		if($cat_id) {
//			$helps =
//				D('Help')->where("`cat_id`={$cat_id} OR `cat_fid`={$cat_id}")
//					->order('`sort` asc')
//					->select();
//		}
//		else {
//			$helps = D('Help')
//				->where("`cat_id`={$help_categories[0]['cat_id']} OR `cat_fid`={$help_categories[0]['cat_id']}")
//				->order('`sort` asc')
//				->select();
//		}
//		$this->assign('helps', $helps);

		$this->display();
	}
}