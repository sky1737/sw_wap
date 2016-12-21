<?php

class AgentAction extends BaseAction
{
	public function index()
	{
		$agent = M('Agent');
		$agent_count = $agent->count('agent_id');
		import('@.ORG.system_page');
		$page = new Page($agent_count, 20);
		$list = $agent->order('`sort` asc,`agent_id` DESC')->limit($page->firstRow, $page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $page->show());
		$this->display();
	}

//	public function add()
//	{
//		if(IS_POST) {
//			$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
//			if(M('Agent')->data($_POST)->save()) {
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
//			if(D('Agent')->data($_POST)->add()) {
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
			if($_POST['agent_id']) {
				if(M('Agent')->data($_POST)->save()) {
					$this->success('修改成功！');
				}
				else {
					$this->error('修改失败！请检查是否有过修改后重试~');
				}
			}
			else {
				$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
				if(D('Agent')->data($_POST)->add()) {
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
			$item = D('Agent')->field(true)->where(array('agent_id' => $id))->find();
		}
		$this->assign('item', $item);
		$this->display();
	}

	public function del()
	{
		if(IS_POST) {
			$condition_agent['id'] = intval($_POST['id']);
			if(D('Agent')->where($condition_agent)->delete()) {
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

