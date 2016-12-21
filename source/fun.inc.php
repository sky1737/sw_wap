<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/1/5
 * Time: 11:26
 */

function pigcms_tips($msg, $url = '', $isAutoGo = false, $showCopy = true)
{
	if (IS_AJAX) {
		echo json_encode(array('msg' => $msg, 'url' => $url));
	}
	else {
		if (empty($url)) {
			$url = 'javascript:history.back(-1);';
		}
		if ($msg == '404') {
			header('HTTP/1.1 404 Not Found');
			$msg = '抱歉，你所请求的页面不存在！';
		}
		include TWIKER_PATH . 'source/sys_tpl/tip.php';
	}

	exit();
}

function appException($e)
{
	$error = array();
	$error['message'] = $e->getMessage();
	$trace = $e->getTrace();

	if ('throw_exception' == $trace[0]['function']) {
		$error['file'] = $trace[0]['file'];
		$error['line'] = $trace[0]['line'];
	}
	else {
		$error['file'] = $e->getFile();
		$error['line'] = $e->getLine();
	}

	halt($error);
}

function appError($errno, $errstr, $errfile, $errline)
{
	switch ($errno) {
		case 1:
		case 4:
		case 16:
		case 64:
		case 256:
			ob_end_clean();

			if (DEBUG) {
				pigcms_tips($errno . '' . $errstr . ' ' . $errfile . ' 第 ' . $errline . ' 行.', 'none');
			}
			else {
				pigcms_tips($errno . '' . $errstr . ' ' . basename($errfile) . ' 第 ' . $errline . ' 行.', 'none');
			}
			break;

		case 8:
		case 2048:
			break;

		case 2048:
		case 512:
		default:
			if (DEBUG) {
				pigcms_tips($errstr . ' ' . $errfile . ' 第 ' . $errline . ' 行.', 'none');
			}
			else {
				pigcms_tips($errstr . ' ' . basename($errfile) . ' 第 ' . $errline . ' 行.', 'none');
			}
			break;
	}
}

function fatalError()
{
	if ($e = error_get_last()) {
		switch ($e['type']) {
			case 1:
			case 4:
			case 16:
			case 64:
			case 256:
				ob_end_clean();

				if (DEBUG) {
					pigcms_tips('ERROR:' . $e['message'] . ' ' . $e['file'] . ' 第' . $e['line'] . ' 行.', 'none');
				}
				else {
					pigcms_tips('ERROR:' . $e['message'] . ' ' . basename($e['file']) . ' 第' . $e['line'] . ' 行.',
						'none');
				}

				break;
		}
	}
}

function require_file($load_file)
{
	if (file_exists($load_file)) {
		return require $load_file;
	}
	else {
		$file = str_replace(TWIKER_PATH, '', $load_file);
		pigcms_tips(TWIKER_PATH . $file . ' 文件不存在。', 'none');
	}
}