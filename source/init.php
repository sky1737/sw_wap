<?php
if (!defined('TWIKER_PATH'))
{
    exit('deny access!');
}
define('SSL_CERT_PATH', TWIKER_PATH . 'config/cert/apiclient_cert.pem');
define('SSL_KEY_PATH', TWIKER_PATH . 'config/cert/apiclient_key.pem');
require_once TWIKER_PATH . 'source/fun.inc.php';
require_file(TWIKER_PATH . 'source/class/360_safe3.php');
defined('DEBUG') || define('DEBUG', TRUE);
if (DEBUG == TRUE)
{
    if (version_compare(phpversion(), "5.3.0", ">=") == 1)
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    }
    else
    {
        error_reporting(E_ALL & ~E_NOTICE);
    }
}
else
{
    error_reporting(0);
    register_shutdown_function('fatalError');
    set_error_handler('appError');
    set_exception_handler('appException');
}
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Asia/Shanghai');
ini_set('session.cookie_domain', '.191ws.com');
session_start();
defined('GROUP_NAME') || define('GROUP_NAME', 'index');
defined('MODULE_NAME') || define('MODULE_NAME', isset($_GET['c']) ? strtolower($_GET['c']) : 'index');
defined('ACTION_NAME') || define('ACTION_NAME', isset($_GET['a']) ? strtolower($_GET['a']) : 'index');
defined('DATA_PATH') || define('DATA_PATH', TWIKER_PATH . 'cache/data/');
defined('CACHE_PATH') || define('CACHE_PATH', TWIKER_PATH . 'cache/cache/');
defined('USE_FRAMEWORK') || define('USE_FRAMEWORK', FALSE);
defined('IS_SUB_DIR') || define('IS_SUB_DIR', FALSE);
define('NOW_TIME', $_SERVER['REQUEST_TIME']);
define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
define('IS_GET', REQUEST_METHOD == 'GET' ? TRUE : FALSE);
define('IS_POST', REQUEST_METHOD == 'POST' ? TRUE : FALSE);
define('IS_PUT', REQUEST_METHOD == 'PUT' ? TRUE : FALSE);
define('IS_DELETE', REQUEST_METHOD == 'DELETE' ? TRUE : FALSE);
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? TRUE : FALSE);
require_file(TWIKER_PATH . 'source/functions/common.php');
foreach ($_GET as &$get_value)
{
    $get_value = htmlspecialchars(str_replace(array('<', '>', '\'', '"', '(', ')'), '', $get_value));
}
doStripslashes();
$_G['system'] = require_file(TWIKER_PATH . 'config/config.php');
$config       = F('config');
if (empty($config))
{
    $configs = D('Config')->field('`name`,`value`')->select();
    foreach ($configs as $key => $value)
    {
        $config[ $value['name'] ] = $value['value'];
    }
    F('config', $config);
}
$_G['config'] = $config;
define('TMPL_CACHE_ON', FALSE);
defined('TPL_PATH') || define('TPL_PATH', TWIKER_PATH . 'template/');
defined('TPL_URL') || define('TPL_URL', !IS_SUB_DIR ? $config['oss_url'] . '/template/' . GROUP_NAME . '/' . $_G['config'][ 'theme_' . GROUP_NAME . '_group' ] . '/' : $config['oss_url'] . '/template/' . GROUP_NAME . '/' . $config[ 'theme_' . GROUP_NAME . '_group' ] . '/');
$_G['plugins'] = array();
if (!empty($_G['config']['active_plugins']))
{
    $active_plugins = json_decode($_G['config']['active_plugins'], TRUE);
    if (is_array($active_plugins))
    {
        foreach ($active_plugins as $plugin)
        {
            if (check_plugin($plugin) === TRUE)
            {
                $_G['plugins'][ $plugin ] = TRUE;
            }
        }
    }
}
$twid = is_numeric($_GET['twid']) ? intval($_GET['twid']) : 0;
if (!$twid)
{
    $twid = $_SESSION['twid'];
    if (!$twid)
    {
        $twid = cookies::get('twid');
    }
}
if ($twid && (empty($_SESSION['store']) || $twid != $_SESSION['store']['uid']))
{
    if ($store = M('Store')->getStore(array('uid' => $twid, 'status' => 1)))
    {
        if ($store['agent_id'])
        {
            $store['agent'] = D('Agent')->where(array('agent_id' => $store['agent_id']))->find();
        }
        $_SESSION['store'] = $store;
        $_SESSION['twid']  = $twid;
        cookies::put('twid', $twid, 365);
    }
}
if (USE_FRAMEWORK == TRUE)
{
    R(GROUP_NAME, MODULE_NAME, ACTION_NAME);
    echo ob_get_clean();
}
