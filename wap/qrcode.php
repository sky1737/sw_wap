<?php
/**
 * 店铺二维码
 * User: pigcms_21
 * Date: 2015/4/24
 * Time: 10:57
 */
require_once dirname(__FILE__) . '/global.php';

$twid = intval($_GET['twid']);
if ($twid) {
	$wap_user = D('User')->where(array('uid' => $twid, 'status' => 1))->find();
	if (empty($wap_user) || !$wap_user['stores']) {
		redirect('./qrcode.php');
	}

	$now_store = D('Store')->where(array('uid' => $twid, 'status' => 1))->find();
	if (empty($now_store)) {
		redirect('./qrcode.php');
	}
}

require_once TWIKER_PATH . 'config/qrcode.php';

function imgcreate($imgfile)
{
	$info = getimagesize($imgfile);
	$im = null;
	switch ($info[2]) {
		case 1:
			$im = imagecreatefromgif($imgfile);
			break;
		case 2:
			$im = imagecreatefromjpeg($imgfile);
			break;
		case 3:
			$im = imagecreatefrompng($imgfile);
			break;
	}

	return $im;
}

if (!file_exists($cfg['path'] . $cfg['avatar']['filename'])) {
	Http::getFile($wap_user['avatar'], $cfg['path'] . $cfg['avatar']['filename']);
}

if ($now_store['agent_id'] > 0) {
	$cfg['bg'] = '../static/qrcode/bg.jpg';

	$result = M('Recognition')->get_agent_qrcode('agent', $now_store['store_id'], $now_store['store_id']);
	if (!$result['err_code']) {
		$qrcode = $cfg['path'] . $wap_user['uid'] . '_qrcode.jpg';
		$data = Http::getData($result['qrcode']);
		$fp = @fopen($qrcode, "a"); //将文件绑定到流 
		fwrite($fp, $data);
		fclose($fp);

		$cfg['qrcode']['url'] = $qrcode;
	}
	else {
		logs('AgentQrcode:' . $result['err_code'] . $result['err_msg'], 'ERROR');
	}
}
else {
	$result = M('Recognition')->get_tmp_qrcode('store', $now_store['store_id'], $now_store['store_id']);
	if (!$result['err_code']) {
		$cfg['filename'] = $wap_user['uid'] . '_' . date('Ymd', $result['expire']) . '.png';
		$cfg['expire']['date'] = date('Y-m-d', $result['expire']); //  H:i:s

		$qrcode = $cfg['path'] . $wap_user['uid'] . '_' . date('Ymd', $result['expire']) . '_qrcode.jpg';
		$data = Http::getData($result['qrcode']);
		$fp = @fopen($qrcode, "a"); // 将文件绑定到流
		fwrite($fp, $data);
		fclose($fp);

		$cfg['qrcode']['url'] = $qrcode;
	}
	else {
		logs('TempQrcode:' . $result['err_code'] . $result['err_msg'], 'ERROR');
	}
}

$qrcode = $cfg['path'] . $cfg['filename'];
if (!file_exists($qrcode)) {
	// bg
	$img_bg = imgcreate($cfg['bg']);

	$tx = $cfg['path'] . $cfg['avatar']['filename'];
	$img_tx = imgcreate($tx);
	$img_tx_size = getimagesize($tx);

	// thumb tx
	$txw = $cfg['avatar']['width'];
	$img_tx_thumb = imagecreatetruecolor($txw, $txw);
	if ($img_tx_size[0] == $img_tx_size[1]) {
		imagecopyresampled($img_tx_thumb, $img_tx, 0, 0, 0, 0, $txw, $txw, $img_tx_size[0], $img_tx_size[1]);
	}
	else if ($img_tx_size[0] < $img_tx_size[1]) {
		imagecopyresampled($img_tx_thumb, $img_tx, 0, 0, 0, 0, $txw, $txw, $img_tx_size[0], $img_tx_size[0]);
	}
	else {
		imagecopyresampled($img_tx_thumb, $img_tx, 0, 0, 0, 0, $txw, $txw, $img_tx_size[1], $img_tx_size[1]);
	}
	imagecopy($img_bg, $img_tx_thumb, $cfg['avatar']['x'], $cfg['avatar']['y'], 0, 0, $txw, $txw);

	// nickname
	$color = imagecolorallocate($img_bg, 215, 59, 2);
	imagettftext($img_bg, 14, 0, $cfg['nickname']['x'], ($cfg['nickname']['y'] + 16), $color, '../static/fonts/msyh.ttc', $wap_user['nickname']);
	// expire time
	if (!empty($cfg['expire']['date'])) {
		imagettftext($img_bg, 14, 0, $cfg['expire']['x'], ($cfg['expire']['y'] + 16), $color, '../static/fonts/msyh.ttc', $cfg['expire']['date']);
	}

	// qr
	$qr = $cfg['qrcode']['url'];
	$img_qr = imgcreate($qr);
	$img_qr_size = getimagesize($qr);
	// thumb qr
	$qrw = $cfg['qrcode']['width'];
	$img_qr_thumb = imagecreatetruecolor($qrw, $qrw);
	imagecopyresampled($img_qr_thumb, $img_qr, 0, 0, 0, 0, $qrw, $qrw, $img_qr_size[0], $img_qr_size[1]);
	// copy qrcode
	imagecopy($img_bg, $img_qr_thumb, $cfg['qrcode']['x'], $cfg['qrcode']['y'], 0, 0, $qrw, $qrw);

	if (!is_dir(dirname($qrcode)))
		mkdir(dirname($qrcode), 0777, true);

	// create
	imagepng($img_bg, $qrcode);
}

//分享配置 start
$share_conf = array(
	'title' => $now_store['name'] . '-分销管理', // 分享标题
	'desc' => str_replace(array("\r", "\n"), array('', ''), $now_store['intro']), // 分享描述
	'link' => option('config.wap_site_url') . '/qrcode.php?twid=' . $wap_user['uid'],
	// . getTwikerUrl($now_store['uid']), // 分享链接
	'imgUrl' => $now_store['logo'], // 分享图片链接
	'type' => '', // 分享类型,music、video或link，不填默认为link
	'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
);
import('WechatShare');
$share = new WechatShare();
$shareData = $share->getSgin($share_conf);
//分享配置 end

//$store = $now_store;
//$store_url = $config['wap_site_url'] . '/?twid=' . $twid;

include display('qrcode');
echo ob_get_clean();