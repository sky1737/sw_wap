<?php
/**
 *  新零售
 */
require_once dirname(__FILE__).'/global.php';
require_once dirname(__FILE__).'/uploadfile.php';
//@unlink('./upload/images/2017/06/13/593f5ebbabba1.gif');
function ajax_upload_pic(&$pic,$key,$file)
{
    if($file['error'] != 4) {
        $upload_dir = "./upload/branchcompany/".date('Y/m/d',time())."/";
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $upload = new UploadFile();
        $upload->maxSize = 3 * 1024 * 1024;
        $upload->allowExts = array('jpg', 'jpeg', 'png', 'gif','bmp');
        $upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif','image/bmp');
        $upload->savePath = $upload_dir;
        $upload->saveRule = 'uniqid';
        $upload->files = $file;
        if($upload->upload()) {
            $uploadList = $upload->getUploadFileInfo();
            $pic[$key]= $uploadList[0]['savepath'].$uploadList[0]['savename'];
        }
        else {
            $pic[$key]='message:'.$upload->getErrorMsg();
        }
    }
    else {
        $pic[$key]='message:没有选择图片';
    }
}

//$sellmsg = M("yws_newzerosell")->select();
//dump($sellmsg);
$post = $_POST;
if($post){
    $title = $_POST['yws_title'];
    //dump($config['site_url']);
    $url = $config['site_url'];
    //dump($wap_user);
    $data['wap_uid'] = $wap_user['uid'];
    $data['wap_nickname'] = $wap_user['nickname'];
    $data['wap_avatar'] = $wap_user['avatar'];
    $data['wap_openid'] = $wap_user['openid'];
    $data['wap_unionid'] = $wap_user['wechat_unionid'];
    $data['area'] = I('post.area');
    $data['linkman'] = I('post.linkman');
    $data['phone'] = I('post.phone');

    //var_dump($_FILES);
    //个人身份证
    $files['idcard_pic'] = $_FILES['idcard_pic'];
    //个人营业执照license
    $files['license_pic'] = $_FILES['license_pic'];
    //银行信息bank
    $files['bank_pic'] = $_FILES['bank_pic'];
    //个人照片persioanal1
    $files['peo_pic'] = $_FILES['peo_pic'];
    //个人照片persioanal2
    $files['tax_pic'] = $_FILES['tax_pic'];
    $pic='';
    foreach ($files as $key=>$val){
        ajax_upload_pic($pic,$key,$val,$url);
    }
    //dump($pic);
    $data = array_merge($data,$pic);
    $res=implode('',$pic);
    //dump(strpos($res,'message'));
    if(strpos($res,'message')===false){
        //图片上传成功执行插入
        $sellmsg = M("Yws_branchcompany");
        $data['created_time'] = date('Y-m-d H:i:s',time());
        //dump($data);
        $insert = $sellmsg->add($data);
        //dump($insert);
        if($insert){
            $resoult =  '申请已提交,等待审核....';
        }else{
            foreach ($pic as $k=>$v){
                if(strpos($v,'message:')===false){
                    //dump($v);
                    @unlink($v);
                }
            }
            $resoult =  "出错了，请刷新后重试";
        }
        include display('yws_result');
        echo ob_get_clean();
    }else{
        foreach ($pic as $k=>$v){
            if(strpos($v,'message')!==false){
                switch ($k){
                    case 'idcard_pic':
                        $error[]="您的身份证".str_replace('message:','',$v);
                        break;
                    case 'license_pic':
                        $error[]="个人营业执照".str_replace('message:','',$v);
                        break;
                    case 'bank_pic':
                        $error[]="银行信息".str_replace('message:','',$v);
                        break;
                    case 'peo_pic':
                        $error[]="个人照片".str_replace('message:','',$v);
                        break;
                    case 'tak_pic':
                        $error[]="税务照片".str_replace('message:','',$v);
                        break;
                }
            }else{
                @unlink($v);
            }
        }
        include display('yws_result');
        echo ob_get_clean();
    }

}else{
    include display('yws_fgssq');
    echo ob_get_clean();
}