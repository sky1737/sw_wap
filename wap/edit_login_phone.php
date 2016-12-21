<?php
/**
 *  填写 登陆 手机号
 */
require_once dirname(__FILE__) . '/global.php';

if (IS_POST)
{
    //随机PW Salt
    $randSalt = '';
    for ($i = 0; $i < 6; $i++) $randSalt .= chr(mt_rand(33, 126));
    $password = md5(md5($_POST['password']) . md5($randSalt)); //生成 最终的 pwMd5

    /**
     * @var $user user_model
     */
    $db_user = D('User');
    $db_user->where(array('uid' => $_POST['uid']))->data(array(
        'phone'    => $_POST['phone'],
        'password' => $password,
        'pw_salt'  => $randSalt,
    ))->save();
    $_SESSION['user'] = NULL; //刷新 user session
}
else  include display('edit_login_phone');


echo ob_get_clean();
