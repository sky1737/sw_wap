<?php

class RechargeAction extends BaseAction
{
	public function index()
	{
		$recharge = M('Recharge');
		$recharge_count = $recharge->count('recharge_id');
		import('@.ORG.system_page');
		$page = new Page($recharge_count, 20);
		$list = $recharge->order('`sort` asc,`recharge_id` DESC')->limit($page->firstRow, $page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $page->show());
		$this->display();
	}

//	public function add()
//	{
//		if(IS_POST) {
//			$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
//			if(M('Recharge')->data($_POST)->save()) {
//				$this->success('修改成功！');
//			}
//			else {
//				$this->error('修改失败！请检查是否有过修改后重试~');
//			}
//		}
//		//$this->assign('bg_color', '#F3F3F3');
//		$this->display();
//	}
//
//	public function modify()
//	{
//		if(IS_POST) {
//			$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
//			if(D('Recharge')->data($_POST)->add()) {
//				$this->success('添加成功！');
//			}
//			else {
//				$this->error('添加失败！请重试~');
//			}
//		}
//		else {
//			$this->error('非法提交,请重新提交~');
//		}
//	}

	public function edit()
	{
		if(IS_POST) {
			$_POST['start_time'] = strtotime($_POST['start_time']);
			$_POST['end_time'] = strtotime($_POST['end_time']);
			if($_POST['recharge_id']) {
				if(M('Recharge')->data($_POST)->save()) {
					$this->success('修改成功！');
				}
				else {
					$this->error('修改失败！请检查是否有过修改后重试~');
				}
			}
			else {
				$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
				if(D('Recharge')->data($_POST)->add()) {
					$this->success('添加成功！');
				}
				else {
					$this->error('添加失败！请重试~');
				}
			}
		}
		$id = intval($_GET['id']);
		$item = array('amount' => 0.00, 'point' => 0, 'profit' => 0.00, 'start_time' => time(), 'end_time' => time(),
		              'status' => 1);
		if($id) {
			$item = D('Recharge')->field(true)->where(array('recharge_id' => $id))->find();
		}
		$this->assign('item', $item);
		$this->display();
	}

	public function del()
	{
		if(IS_POST) {
			$condition_recharge['id'] = intval($_POST['id']);
			if(D('Recharge')->where($condition_recharge)->delete()) {
				S('recharge_list', null);
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
}

