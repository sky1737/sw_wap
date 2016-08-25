<?php
/**
 *  填写 登陆 手机号
 */
require_once dirname(__FILE__) . '/global.php';

if (IS_POST)
{
    /**
     * @var $user user_model
     */
    $db_user = D('User');
    $db_user->where(array('uid' => $_POST['uid']))->data(array('phone' => $_POST['phone']))->save();
    $_SESSION['user'] = NULL; //刷新 user session
}
else  include display('edit_login_phone');


echo ob_get_clean();
