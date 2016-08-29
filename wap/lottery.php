<?php
require_once dirname(dirname(__FILE__)) . '/wap/global.php';

//奖品概率
$gifts = array(
    array('pre' => 100, 'name' => '谢谢惠顾',),
    array('pre' => 100, 'name' => '谢谢惠顾',),
    array('pre' => 50, 'name' => '50积分',),
    array('pre' => 4, 'name' => '100积分',),
    array('pre' => 3, 'name' => '200积分',),
    array('pre' => 2, 'name' => '300积分',),
    array('pre' => 1, 'name' => '500积分',),
    array('pre' => 0, 'name' => '双飞游',),
);
$pointMap = array(2 => 50, 3 => 100, 4 => 200, 5 => 300, 6 => 500); //积分礼品表

$uid = $_SESSION['user']['uid'];

$actLotteryLogM = D('Activity_lottery_log');

$act = empty($_GET['a']) ? '' : $_GET['a'];

switch ($act)
{
    case 'do':

        $preSum = 0; //概率分母
        foreach ($gifts as $info) $preSum += $info['pre'];

        //for ($i = 0; $i < 2600; $i++){
        //得到 奖品 i
        $rid = 0;
        do
        {
            $i    = mt_rand(0, count($gifts) - 1);
            $gift = $gifts[ $i ]; //礼品
            $pre  = $gift['pre'];
            if (mt_rand(1, $preSum) < $pre)
            {
                if (!in_array($i, array(0, 1)))
                {
                    $c = $actLotteryLogM->order('id desc')->limit($preSum)->count("CASE WHEN gift_id = '$i' THEN '' END");
                    if ($c < $pre) $rid = $i;
                }
                else $rid = $i;

                if ($rid) break;
            }
        } while (TRUE);
        //print_r($rid.'<br/>'); }

        $giftName = $gifts[ $rid ]['name'];

        $one = $actLotteryLogM->field('id')->where(array(
            'uid'     => $uid,
            'gift_id' => -1,
            'time'    => 0,
        ))->find();

        if ($one)
        {
            $isOK = $actLotteryLogM->where("id={$one['id']}")->data(array(
                'gift_id'   => $rid,
                'gift_name' => $giftName,
                'time'      => time(),
            ))->save();


            isset($pointMap[ $rid ]) && D('User')->setInc('point', $pointMap[ $rid ]);

            echoJson(array('isOK' => $isOK, 'id' => $rid, 'name' => $giftName));

        }
        else echoJson(array('isOK' => 0, 'msg' => '没有剩余次数'));


        echoJson(array('isOK' => $isOK, 'id' => $rid, 'name' => $giftName));

        break;

    case 'list':

        $list = $actLotteryLogM->field('gift_name,time')->where(array('uid' => $uid, 'act_id' => 0, 'time'))->order('id desc')->select();
        foreach ($list as &$l) {
            unset($l['pre']);
            $l['time'] = date('Y-m-d H:i', $l['time']);
        }
        echoJson(array('uid' => $uid, 'list' => $list, 'gifts' => $gifts));
        break;

    case 'rest':

        $leftTimes = $actLotteryLogM->where(array('uid' => $uid, 'time=0 AND gift_id<0'))->count('*');
        echoJson(array('timeLeft' => $leftTimes));
        break;
}

function echoJson($data)
{
    //var_dump($data);exit;
    if (isset($_GET['callback']))
    {
        echo "{$_GET['callback']}(" . json_encode($data) . ");";
    }
    else echo json_encode($data);

    exit;
}