<?php

/*
 * 广告管理
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/06 16:47
 * 
 */

class AdverAction extends BaseAction
{
	public function index()
	{
		$database_adver_category = D('Adver_category');
		$category_list = $database_adver_category->field(true)->order('`cat_id` ASC')->select();
		$this->assign('category_list', $category_list);
		$this->display();
	}

	public function cat_add()
	{
		$this->assign('bg_color', '#F3F3F3');
		$this->display();
	}

	public function cat_modify()
	{
		if(IS_POST) {
			$database_adver_category = D('Adver_category');
			if($database_adver_category->data($_POST)->add()) {
				// 清空缓存
				import('ORG.Util.Dir');
				Dir::delDirnotself('./cache');

				$this->success('添加成功！');
			}
			else {
				$this->error('添加失败！请重试~');
			}
		}
		else {
			$this->error('非法提交,请重新提交~');
		}
	}

	public function cat_edit()
	{
		$this->assign('bg_color', '#F3F3F3');
		$now_category = $this->frame_check_get_category($_GET['cat_id']);
		$this->assign('now_category', $now_category);

		$this->display();
	}

	public function cat_amend()
	{
		if(IS_POST) {
			$database_adver_category = D('Adver_category');
			if($database_adver_category->data($_POST)->save()) {
				$this->success('编辑成功！');
			}
			else {
				$this->error('编辑失败！请重试~');
			}
		}
		else {
			$this->error('非法提交,请重新提交~');
		}
	}

	public function cat_del()
	{
		if(IS_POST) {
			$database_adver_category = D('Adver_category');
			$condition_adver_category['cat_id'] = $_POST['cat_id'];
			if($database_adver_category->where($condition_adver_category)->delete()) {
				//删除所有广告
				$database_adver = D('Adver');
				$condition_adver['cat_id'] = $now_category['cat_id'];
				$adver_list = $database_adver->field(true)->where($condition_adver)->order('`id` DESC')->select();
				foreach ($adver_list as $key => $value) {
					unlink('./upload/' . $value['pic']);

					$attachment_upload_type = C('config.attachment_upload_type');
					// 删除又拍云服务器
					if($attachment_upload_type == '1') {
						import('upyunUser', './source/class/upload/');
						upyunUser::delete('/' . $value['pic']);
					}
				}
				$database_adver->where($condition_adver)->delete();

				// 清空缓存
				import('ORG.Util.Dir');
				Dir::delDirnotself('./cache');

				S('adver_list_' . $_POST['cat_id'], null);
				$this->success('删除成功！');
			}
			else {
				$this->error('删除失败！请重试~');
			}
		}
		else {
			$this->error('非法提交,请重新提交~');
		}
	}

	public function adver_list()
	{
		$now_category = $this->check_get_category($_GET['cat_id']);
		$this->assign('now_category', $now_category);

		$database_adver = D('Adver');
		$condition_adver['cat_id'] = $now_category['cat_id'];
		$adver_list = $database_adver->field(true)->where($condition_adver)->order('`id` DESC')->select();

		foreach ($adver_list as &$adver) {
			$adver['pic'] = getAttachmentUrl($adver['pic']);
			$adver['qrcode'] = getAttachmentUrl($adver['qrcode']);
		}

		$this->assign('adver_list', $adver_list);

		$this->display();
	}

	public function adver_add()
	{
		$this->assign('bg_color', '#F3F3F3');
		$now_category = $this->frame_check_get_category($_GET['cat_id']);
		$this->assign('now_category', $now_category);

		$this->display();
	}

	public function adver_modify()
	{
		//上传图片
		if($_FILES['pic']['error'] != 4) {
			$rand_num = date('Y/m', $_SERVER['REQUEST_TIME']);
			$upload_dir = './upload/adver/' . $rand_num . '/';
			if(!is_dir($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = 10 * 1024 * 1024;
			$upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
			$upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
			$upload->savePath = $upload_dir;
			$upload->saveRule = 'uniqid';
			if($upload->upload()) {
				$uploadList = $upload->getUploadFileInfo();

//            $attachment_upload_type = C('config.attachment_upload_type');
//            // 上传到又拍云服务器
//            if ($attachment_upload_type == '1') {
//                import('upyunUser', './source/class/upload/');
//                upyunUser::upload('./upload/adver/' . $rand_num . '/' . $uploadList[0]['savename'], '/adver/' . $rand_num . '/' . $uploadList[0]['savename']);
//            }

				$_POST['pic'] = 'adver/' . $rand_num . '/' . $uploadList[0]['savename'];
				if($_FILES['qrcode']['error'] != 4) {
					$_POST['qrcode'] = 'adver/' . $rand_num . '/' . $uploadList[1]['savename'];
				}
			}
			else {
				$this->frame_submit_tips(0, $upload->getErrorMsg());
			}
		}
		else {
			$this->frame_submit_tips('广告图片必须上传。');
		}

		$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
		$database_adver = D('Adver');
		if($database_adver->data($_POST)->add()) {
			// 清空缓存
			import('ORG.Util.Dir');
			Dir::delDirnotself('./cache');

			S('adver_list_' . $_POST['cat_id'], null);
			$this->frame_submit_tips(1, '添加成功！');
		}
		else {
			$this->frame_submit_tips(0, '添加失败！请重试~');
		}
	}

	public function adver_edit()
	{
		$this->assign('bg_color', '#F3F3F3');

		$database_adver = D('Adver');
		$condition_adver['id'] = $_GET['id'];
		$now_adver = $database_adver->field(true)->where($condition_adver)->find();
		if(empty($now_adver)) {
			$this->frame_error_tips('该广告不存在！');
		}

		$now_adver['pic'] = getAttachmentUrl($now_adver['pic']);
		$now_adver['qrcode'] = getAttachmentUrl($now_adver['qrcode']);
		$this->assign('now_adver', $now_adver);

		$this->display();
	}

	public function adver_amend()
	{
		$database_adver = D('Adver');
		$condition_adver['id'] = $_POST['id'];
		$now_adver = $database_adver->field(true)->where($condition_adver)->find();

		//上传图片
		if($_FILES['pic']['error'] != 4 || $_FILES['qrcode']['error'] != 4) {
			$rand_num = date('Y/m', $_SERVER['REQUEST_TIME']);
			$upload_dir = './upload/adver/' . $rand_num . '/';
			if(!is_dir($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = 10 * 1024 * 1024;
			$upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
			$upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
			$upload->savePath = $upload_dir;
			$upload->saveRule = 'uniqid';
			if($upload->upload()) {
				$uploadList = $upload->getUploadFileInfo();

//            $attachment_upload_type = C('config.attachment_upload_type');
//            // 上传到又拍云服务器
//            if ($attachment_upload_type == '1') {
//                import('upyunUser', './source/class/upload/');
//                upyunUser::upload('./upload/adver/' . $rand_num . '/' . $uploadList[0]['savename'], '/adver/' . $rand_num . '/' . $uploadList[0]['savename']);
//            }
				$j = 0;
				if($_FILES['pic']['error'] != 4) {
					$_POST['pic'] = 'adver/' . $rand_num . '/' . $uploadList[$j]['savename'];
					$j++;
				}

				if($_FILES['qrcode']['error'] != 4) {
					$_POST['qrcode'] = 'adver/' . $rand_num . '/' . $uploadList[$j]['savename'];
				}
			}
			else {
				$this->frame_submit_tips(0, $upload->getErrorMsg());
			}
		}

		$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
		$database_adver = D('Adver');
		if($database_adver->data($_POST)->save()) {
			S('adver_list_' . $now_adver['cat_id'], null);
			if($_POST['pic']) {
				unlink('./upload/' . $now_adver['pic']);

//				$attachment_upload_type = C('config.attachment_upload_type');
//				// 删除又拍云服务器
//				if($attachment_upload_type == '1') {
//					import('upyunUser', './source/class/upload/');
//					upyunUser::delete('/' . $now_adver['pic']);
//				}
			}

			// 清空缓存
			import('ORG.Util.Dir');
			Dir::delDirnotself('./cache');

			$this->frame_submit_tips(1, '编辑成功！');
		}
		else {
			$this->frame_submit_tips(0, '编辑失败！请重试~');
		}
	}

	public function adver_del()
	{
		$database_adver = D('Adver');
		$condition_adver['id'] = $_POST['id'];
		$now_adver = $database_adver->field(true)->where($condition_adver)->find();
		if($database_adver->where($condition_adver)->delete()) {
			unlink('./upload/' . $now_adver['pic']);

			$attachment_upload_type = C('config.attachment_upload_type');
			// 删除又拍云服务器
			if($attachment_upload_type == '1') {
				import('upyunUser', './source/class/upload/');
				upyunUser::delete('/' . $now_adver['pic']);
			}

			S('adver_list_' . $now_adver['cat_id'], null);

			// 清空缓存
			import('ORG.Util.Dir');
			Dir::delDirnotself('./cache');

			$this->success('删除成功');
		}
		else {
			$this->error('删除失败！请重试~');
		}
	}

	protected function get_category($cat_id)
	{
		$database_adver_category = D('Adver_category');
		$condition_adver_category['cat_id'] = $cat_id;
		$now_category = $database_adver_category->field(true)->where($condition_adver_category)->find();

		return $now_category;
	}

	protected function frame_check_get_category($cat_id)
	{
		$now_category = $this->get_category($cat_id);
		if(empty($now_category)) {
			$this->frame_error_tips('分类不存在！');
		}
		else {
			return $now_category;
		}
	}

	protected function check_get_category($cat_id)
	{
		$now_category = $this->get_category($cat_id);
		if(empty($now_category)) {
			$this->error_tips('分类不存在！');
		}
		else {
			return $now_category;
		}
	}
}