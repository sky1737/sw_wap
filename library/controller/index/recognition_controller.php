<?php

class recognition_controller extends base_controller
{

	public function see_login_qrcode()
	{
		$qrcode = $_SESSION['login_qrcode'];
		if (!$qrcode) {
			$qrcode = M('Recognition')->get_login_qrcode($this->store_session['store_id']);
			if ($qrcode['error_code']) {
				exit('<html><head></head><body>' . $qrcode['msg'] .
					'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>');
			}
			$_SESSION['login_qrcode'] = $qrcode;
		}

		$this->assign($qrcode);
		$this->display();
	}

//	public function see_register_qrcode()
//	{
//		$qrcode = $_SESSION['login_qrcode'];
//		if (!$qrcode) {
//			$qrcode = M('Recognition')->get_login_qrcode();
//			if ($qrcode['error_code']) {
//				exit('<html><head></head><body>' . $qrcode['msg'] .
//					'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>');
//			}
//			$_SESSION['login_qrcode'] = $qrcode;
//		}
//
//		$this->assign($qrcode);
//		$this->display();
//	}

//	public function see_order_qrcode()
//	{
//		$qrcode = $_SESSION['order_qrcode'];
//		if (!$qrcode) {
//			$qrcode = M('Recognition')->get_order_qrcode();
//			if ($qrcode['error_code']) {
//				exit('<html><head></head><body>' . $qrcode['msg'] .
//					'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>');
//			}
//			$_SESSION['order_qrcode'] = $qrcode;
//		}
//
//		json_return(0, $qrcode);
////		$this->assign($qrcode);
////		$this->display();
//	}

	public function see_tmp_qrcode()
	{
		if (empty($_GET['qrcode_id'])) {
			json_return(1, '无法得到二维码图片！');
		}
		$qrcode_return = M('Recognition')->get_tmp_qrcode($_GET['type'], $_GET['qrcode_id']);
		json_return(0, $qrcode_return);
	}

}