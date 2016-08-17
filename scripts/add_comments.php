<?php
/**
 * @url /scripts/add_comments.php?run&pid=980&cid=6&limit=100,2
 * 刷 产品 评论脚本
 **/

define('TPL_URL', '/template/supplier/default/');

define('TWIKER_PATH', dirname(__FILE__) . '/../');
define('GROUP_NAME', 'supplier');
define('USE_FRAMEWORK', TRUE);
require_once TWIKER_PATH . 'source/init.php';

error_reporting(E_ALL); //开启所有错误
//get 自定义参数
{
    !isset($_GET['run']) || (!isset($_GET['limit']) && !isset($_GET['notimeout'])) && die('请加参安全运行!');
    $wherePid   = isset($_GET['pid']) ? $_GET['pid'] : 0; //产品id
    $whereCid   = isset($_GET['cid']) ? $_GET['cid'] : 0; //产品的 分类id
    $maxAddSale = isset($_GET['maxaddsale']) ? $_GET['maxaddsale'] : 2000; //销量增加阀值
    $addComm    = isset($_GET['addcomm']) ? $_GET['addcomm'] : 0; //评论增加数
    $sqlLimit   = isset($_GET['limit']) ? $_GET['limit'] : ''; //sql limt 条件, 例如: 2,1
    //如果不增加销量, 但也不增加 评论
    empty($maxAddSale) && empty($addComm) && die('如果您不增加销量,请填写要增加的评论数!');
}
set_time_limit(isset($_GET['notimeout']) ? 0 : 120); //默认 两分钟 运行限制

//得到通用评论
$baseDir      = dirname(__FILE__) . '/comments';
$commFile     = file("$baseDir/_.txt");
$maxCommIndex = count($commFile) - 1;

//得到 最大的 用户 id
//$maxUser = D('User')->order('uid desc')->find(); $maxUid  = $maxUser['uid'];

//取所有用户 的 uid, 一次性查出, 避免多次 随机sql 查询, 用户量如果大了 请考虑缓存
$allUid  = array();
$allUser = D('User')->field('uid')->select();
foreach ($allUser as $u) $allUid[] = $u['uid'];
$maxUidIndex = count($allUid) - 1;

//得到 产品表
/**
 * @var $product_m mysql
 */
$product_m = D('Product');
$whereAry  = array();
$wherePid && $whereAry['product_id'] = "$wherePid";
$whereCid && $whereAry['category_id'] = "$whereCid";
$whereAry && $product_m->where($whereAry);
$sqlLimit && $product_m->limit($sqlLimit);
$products = $product_m->select();
//var_dump($products,$product_m->last_sql);exit;

echo "<pre>";

$notFoundCountMap = array(); //未找到分类统计
$pCount           = 0;
foreach ($products as $p)
{
    $pCount++; //序数计数

    $pid    = $p['product_id']; //产品PID
    $catgId = $p['category_id']; //产品类别ID

    //----------销量增加
    $incSalesNum = mt_rand(0, $maxAddSale);
    if ($maxAddSale) //如果指定了 最大 增加销量数
    {
        D('Product')->where(array('product_id' => $pid))->setInc('sales', $incSalesNum);
        $incCommNum = intval($incSalesNum * (mt_rand(50, 200) / 1000)); //根据 新增销量数 确定 新增评论数范围 5% - 20%
    }
    else $incCommNum = $addComm; //直接指定

    $sPre = -1;

    //----------评论增加
    $catgFilePath = "$baseDir/{$catgId}.txt"; //评论文案文件 (一行一个,纯文本)
    if (!file_exists($catgFilePath))
    {
        echo "$pCount\t 商品ID：$pid\t 新增销量 $incSalesNum \t件，【未找到{$catgId}类别评论文案，使用通用评论】\r\n";
        empty($notFoundCountMap[ $catgId ]) ? ($notFoundCountMap[ $catgId ] = 1) : $notFoundCountMap[ $catgId ]++;//统计 未找到的分类
    }
    else $sPre = 3;//30% 用 特定类别 评论

    //当前商品 评论已用过的 uid
    $hasUseUids = array();
    for ($i = 0; $i < $incCommNum; $i++)
    {
        if (mt_rand(0, 9) < $sPre)
        {
            $catgFile         = file($catgFilePath);
            $maxCatgCommIndex = count($catgFile) - 1;
            $content          = $catgFile[ mt_rand(0, $maxCatgCommIndex) ]; //随机取 对应类别 评论
        }
        else $content = $commFile[ mt_rand(0, $maxCommIndex) ]; //70% 用 通用评论

        //var_dump($content);exit;
        do
        {
            //next while 初始化
            if (isset($randUid)) empty($hasUseUids[ $randUid ]) ? ($hasUseUids[ $randUid ] = 1) : $hasUseUids[ $randUid ]++; //统计用户 在该商品 评论的出现次数

            //随机取一个用户
            //$randUser = D('User')->where("uid >= " . mt_rand(0, $maxUid))->field('uid')->select();
            //$randUid  = $randUser['uid'];
            $randUid = $allUid[ mt_rand(0, $maxUidIndex) ];
            //var_dump($randUid);exit;

            //如果该用户在本商品评论中 未出现过, 或 随机到的次数为 即将增加销量数的 30% (很可能没有其他可随机用户了)
            if (!isset($hasUseUids[ $randUid ]) || ($hasUseUids[ $randUid ] > $incCommNum * 0.3))
            {
                //增加评论
                $cid = D('Comment')->data(array(
                    'dateline'    => time() + mt_rand(-2592000 * 2, 0),
                    'order_id'    => 0,
                    'relation_id' => $pid,
                    'uid'         => $randUid,
                    'store_id'    => $p['store_id'],
                    'score'       => mt_rand(0, 99) > 10 ? mt_rand(4, 5) : 3, //满意占 80%
                    'type'        => 'PRODUCT',
                    'status'      => 1,
                    'content'     => $content,
                    'has_image'   => 0,
                ))->add();

                $hasUseUids[ $randUid ] = 1; //标记 已使用过

                break; //结束 while
            }

        } while (TRUE); //则重新随机 另一个用户

    }

    echo "$pCount\t 商品ID：$pid\t 新增销量 $incSalesNum \t件，新增评论 $incCommNum \t条\r\n";


}

echo "\r\n未在comments文件夹文案中 找到 catgId 的评论列表 统计：Cid => Count \r\n";
print_r($notFoundCountMap);