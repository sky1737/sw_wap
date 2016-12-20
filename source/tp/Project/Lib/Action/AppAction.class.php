<?php

class AppAction extends BaseAction
{
    public function z()
    {
        $agent = M('AppZ');
        $agent_count = $agent->count('zid');
        import('@.ORG.system_page');
        $page = new Page($agent_count, 20);
        $list = $agent->order('`sort` asc,`zid` DESC')->limit($page->firstRow, $page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $page->show());
        $this->display();
    }

    public function z_edit()
    {
        if (IS_POST) {
            //var_dump($_POST);exit;
            if (empty($_POST['image']) && $_FILES['pic']['error'] == 4) {
                $this->frame_submit_tips(0, '请上传众筹封面图片！');
            }

            if ($_FILES['pic']['error'] != 4) {
                //上传图片
                $rand_num = date('Y/m', $_SERVER['REQUEST_TIME']);
                $upload_dir = './upload/app/z/' . $rand_num . '/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();
                $upload->maxSize = 10 * 1024 * 1024;
                $upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
                $upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
                $upload->savePath = $upload_dir;
                $upload->saveRule = 'uniqid';
                if ($upload->upload()) {
                    $uploadList = $upload->getUploadFileInfo();
                    // 上传到又拍云服务器
//                    $attachment_upload_type = C('config.attachment_upload_type');
//                    if ($attachment_upload_type == '1') {
//                        import('upyunUser', './source/class/upload/');
//                        upyunUser::upload('./upload/category/' . $rand_num . '/' . $uploadList[0]['savename'], '/category/' . $rand_num . '/' . $uploadList[0]['savename']);
//                    }

                    if ($_POST['image'] && file_exists('./upload/' . $_POST['image'])) {
                        @unlink('./upload/' . $_POST['image']);
                    }
                    $_POST['image'] = 'app/z/' . $rand_num . '/' . $uploadList[0]['savename'];
                } else {
                    $this->frame_submit_tips(0, $upload->getErrorMsg());
                }
            }

            $db_z = D('AppZ');
            if ($_POST['zid']) {
                $bool = $db_z->data($_POST)->save();
            } else {
                $_POST['add_time'] = $_SERVER['REQUEST_TIME'];
                $_POST['zid'] = $db_z->data($_POST)->add();
                $bool = $_POST['zid'] > 0;
            }

            //if (!$bool) $this->frame_submit_tips(0, ($_POST['zid'] ? '修改' : '添加') . '失败！请重试~'.$db_z->getLastSql());

            if ($_POST['zid']) {
                $db_z_item = D('AppZItem');
                for ($i = 0; $i < count($_POST['item_id']); $i++) {
                    $data = array('item_id' => $_POST['item_id'][$i], 'zid' => $_POST['zid'], 'agent_id' => $_POST['agent_id'][$i], 'minimum' => $_POST['minimum'][$i], 'maximum' => $_POST['maximum'][$i], 'amount' => $_POST['amount'][$i], 'note' => $_POST['note'][$i]);

                    if ($data['item_id']) {
                        $result = $db_z_item->data($data)->save();
                    } else {
                        $result = $db_z_item->data($data)->add();
                    }
                    //if (!$result) {
                    //    $this->frame_submit_tips(0, ($data['item_id'] ? '修改' : '添加') . '众筹项失败！请重试~'.$db_z_item->getLastSql());
                    //    break;
                    //}
                }
            }

            $this->frame_submit_tips(1, ($_POST['zid'] ? '修改' : '添加') . '成功！');
        }
        $zid = intval($_GET['zid']);
        $item = array();
        if ($zid) {
            $item = D('AppZ')->field(true)->where(array('zid' => $zid))->find();
            $item['items'] = D('AppZItem')->where(array('zid' => $zid))->select();
        }
        $this->assign('item', $item);

        $this->assign('agents', D('Agent')->where(array('status' => 1, 'price' => array('gt', 0)))->select());
        $this->display();
    }

    public function z_del()
    {
        if (IS_POST) {
            $condition['zid'] = intval($_POST['zid']);
            D('AppZItem')->where($condition)->delete();
            if (D('AppZ')->where($condition)->delete()) {
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }
}

