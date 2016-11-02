<?php

/*
 * 修改站点配置
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/06 15:07
 * 
 */

class ConfigAction extends BaseAction
{
	public function index()
    {
        $database_config_group = D('Config_group');
        $condition_config_group['status'] = '1';
        $group_list = $database_config_group->where($condition_config_group)->order('`gsort` DESC,`gid` ASC')->select();
        $this->assign('group_list', $group_list);

        $gid = $this->_get('gid');
        if (empty($gid)) $gid = $group_list[0]['gid'];
        $this->assign('gid', $gid);

        $database_config = D('Config');
        $condition_config['gid'] = $gid;
        $condition_config['status'] = '1';
        $tmp_config_list = $database_config->where($condition_config)->order('`sort` ASC,`id` asc')->select();

        foreach ($tmp_config_list as $key => $value) {
            $config_list[$value['tab_id']]['name'] = $value['tab_name'];
            $config_list[$value['tab_id']]['list'][] = $value;
        }
        $this->assign($this->build_html($config_list));

        $this->display();
    }

	public function amend()
    {
        if (IS_POST) {
            $database_config = D('Config');
            foreach ($_POST as $key => $value) {
                $data['name'] = $key;
                $data['value'] = trim(stripslashes(htmlspecialchars_decode($value)));
                $database_config->data($data)->where(array("name" => $key))->save();
                if ($key == 'wechat_sourceid') {
                    $data['name'] = 'wechat_token';
                    $data['value'] = md5('pigcms_wechat_token' . $data['value']);
                    $database_config->data($data)->where(array("name" => 'wechat_token'))->save();
                }
            }
            import('ORG.Util.Dir');
            Dir::delDirnotself('./cache');
            $this->success('修改成功!');
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function show()
    {
        $config = D('Config')->get_config();
        $this->display();
    }

	protected function build_html($config_list)
    {
        if (is_array($config_list)) {
            $config_html = '';
            if (count($config_list) > 1) $has_tab = true;
            if ($has_tab) $config_tab_html = '<ul class="tab_ul">';
            $pigcms_auto_key = 1;

            $has_image_btn = false;
            $has_page_btn = false;
            foreach ($config_list as $pigcms_key => $pigcms_value) {
                if ($has_tab) $config_tab_html .= '<li ' . ($pigcms_auto_key == 1 ? 'class="active"' : '') . '><a data-toggle="tab" href="#tab_' . $pigcms_key . '">' . $pigcms_value['name'] . '</a></li>';

                $config_html .= '<table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_' . $pigcms_key . '">';
                foreach ($pigcms_value['list'] as $key => $value) {
                    $tmp_type_arr = explode('&', $value['type']);
                    $type_arr = array();
                    foreach ($tmp_type_arr as $k => $v) {
                        $tmp_value = explode('=', $v);
                        $type_arr[$tmp_value[0]] = $tmp_value[1];
                    }

                    $config_html .= '<tr>';
                    $config_html .= '<th width="160">' . $value['info'] . '：</th>';
                    $config_html .= '<td>';
                    if ($type_arr['type'] == 'text') {
                        $size = !empty($type_arr['size']) ? $type_arr['size'] : '60';
                        $config_html .= '<input type="text" class="input-text" name="' . $value['name'] . '" id="config_' . $value['name'] . '" value="' . $value['value'] . '" size="' . $size . '" validate="' . $type_arr['validate'] . '" tips="' . $value['desc'] . '"/>';
                    } else if ($type_arr['type'] == 'textarea') {
                        $rows = !empty($type_arr['rows']) ? $type_arr['rows'] : '4';
                        $cols = !empty($type_arr['cols']) ? $type_arr['cols'] : '80';
                        $config_html .= '<textarea rows="' . $rows . '" cols="' . $cols . '" name="' . $value['name'] . '" id="config_' . $value['name'] . '" validate="' . $type_arr['validate'] . '" tips="' . $value['desc'] . '">' . $value['value'] . '</textarea>';
                    } else if ($type_arr['type'] == 'radio') {
                        $radio_option = explode('|', $type_arr['value']);
                        foreach ($radio_option as $radio_k => $radio_v) {
                            $radio_one = explode(':', $radio_v);
                            if ($radio_k == 0) {
                                $config_html .= '<span class="cb-enable"><label class="cb-enable ' . ($value['value'] == $radio_one[0] ? 'selected' : '') . '"><span>' . $radio_one[1] . '</span><input type="radio" name="' . $value['name'] . '" value="' . $radio_one[0] . '" ' . ($value['value'] == $radio_one[0] ? 'checked="checked"' : '') . '/></label></span>';
                            } else if ($radio_k == 1) {
                                $config_html .= '<span class="cb-disable"><label class="cb-disable ' . ($value['value'] == $radio_one[0] ? 'selected' : '') . '"><span>' . $radio_one[1] . '</span><input type="radio" name="' . $value['name'] . '" value="' . $radio_one[0] . '" ' . ($value['value'] == $radio_one[0] ? 'checked="checked"' : '') . '/></label></span>';
                            }
                        }
                        if ($value['desc']) {
                            $config_html .= '<em tips="' . $value['desc'] . '" class="notice_tips"></em>';
                        }
                    } else if ($type_arr['type'] == 'image') {
                        $config_html .= '<span class="config_upload_image_btn"><input type="button" value="上传图片" class="button" style="margin-left:0px;margin-right:10px;"/></span><input type="text" class="input-text input-image" name="' . $value['name'] . '" id="config_' . $value['name'] . '" value="' . $value['value'] . '" size="48" validate="' . $type_arr['validate'] . '" tips="' . $value['desc'] . '"/> ';
                    } else if ($type_arr['type'] == 'file') {
                        $config_html .= '<span class="config_upload_file_btn" file_validate="' . $type_arr['file'] . '"><input type="button" value="上传文件" class="button" style="margin-left:0px;margin-right:10px;"/></span><input type="text" class="input-text input-file" name="' . $value['name'] . '" id="config_' . $value['name'] . '" value="' . $value['value'] . '" size="48" readonly="readonly" validate="' . $type_arr['validate'] . '" tips="' . $value['desc'] . '"/> ';
                    } else if ($type_arr['type'] == 'select') {
                        $radio_option = explode('|', $type_arr['value']);
                        $config_html .= '<select name="' . $value['name'] . '">';
                        foreach ($radio_option as $radio_k => $radio_v) {
                            $radio_one = explode(':', $radio_v);
                            $config_html .= '<option value="' . $radio_one[0] . '" ' . ($value['value'] == $radio_one[0] ? 'selected="selected"' : '') . '>' . $radio_one[1] . '</option>';
                        }
                        $config_html .= '</select>';
                        if ($value['desc']) {
                            $config_html .= '<em tips="' . $value['desc'] . '" class="notice_tips"></em>';
                        }
                    } else if ($type_arr['type'] == 'page') {
                        $config_html .= '<span class="config_select_page_btn"><input type="button" value="选择微杂志" class="button" style="margin-left:0px;margin-right:10px;"/></span><input type="text" class="input-text input-widget-page" name="' . $value['name'] . '" id="config_' . $value['name'] . '" value="' . $value['value'] . '" size="10" validate="' . $type_arr['validate'] . '" tips="' . $value['desc'] . '"/> ';
                        $has_page_btn = true;
                    } else if ($type_arr['type'] == 'salt') {
                        $config_html .= '<span class="config_generate_salt_btn"><input type="button" value="生成KEY" class="button generate-salt" style="margin-left:0px;margin-right:10px;"/></span><input type="text" class="input-text input-image" name="' . $value['name'] . '" id="config_' . $value['name'] . '" value="' . $value['value'] . '" size="48" validate="' . $type_arr['validate'] . '" tips="' . $value['desc'] . '"/> ';
                        $has_salt_btn = true;
                    }
                    $config_html .= '</td>';
                    $config_html .= '</tr>';
                }
                $config_html .= '</table>';
                $pigcms_auto_key++;
            }
            if ($has_tab) $config_tab_html .= '</ul>';

            $return_config['config_html'] = $config_html;
            if ($has_tab) $return_config['config_tab_html'] = $config_tab_html;
            $return_config['has_image_btn'] = $has_image_btn;
            $return_config['has_page_btn'] = $has_page_btn;
            $return_config['has_sale_btn'] = $has_salt_btn;
            return $return_config;
        }
    }

	public function ajax_upload_pic()
	{
		if($_FILES['imgFile']['error'] != 4) {
			$img_admin_id = sprintf("%09d", $this->system_session['id']);
			$rand_num = substr($img_admin_id, 0, 3) . '/' . substr($img_admin_id, 3, 3) . '/' . substr($img_admin_id, 6, 3);
			$upload_dir = "./upload/images/{$rand_num}/";
			if(!is_dir($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = 3 * 1024 * 1024;
			$upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');
			$upload->allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
			$upload->savePath = $upload_dir;
			$upload->saveRule = 'uniqid';
			if($upload->upload()) {
				$uploadList = $upload->getUploadFileInfo();
				$title = $rand_num . ',' . $uploadList[0]['savename'];
				exit(json_encode(array('error'                            => 0, 'url' => $this->config['site_url'] .
					'/upload/images/' .
					$rand_num . '/' . $uploadList[0]['savename'], 'title' => $title)));
			}
			else {
				exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
			}
		}
		else {
			exit(json_encode(array('error' => 1, 'message' => '没有选择图片')));
		}
	}

	public function ajax_upload_file()
	{
		if(empty($_GET['name'])) {
			exit(json_encode(array('error' => 1, 'message' => '不知道您要上传到哪个配置项，请重试。')));
		}
		$now_config = D('Config')->field('`name`,`type`')->where(array('name' => $_GET['name']))->find();
		if(empty($now_config)) {
			exit(json_encode(array('error' => 1, 'message' => '您正在上传的配置项不存在，请重试。')));
		}
		$tmp_type_arr = explode('&', $now_config['type']);
		$type_arr = array();
		foreach ($tmp_type_arr as $k => $v) {
			$tmp_value = explode('=', $v);
			$type_arr[$tmp_value[0]] = $tmp_value[1];
		}
		$allowExts = array_key_exists('file', $type_arr) ? explode(',', $type_arr['file']) : array();
		if($_FILES['imgFile']['error'] != 4) {
			$img_admin_id = sprintf("%09d", $this->system_session['id']);
			$rand_num =
				substr($img_admin_id, 0, 3) . '/' . substr($img_admin_id, 3, 3) . '/' . substr($img_admin_id, 6, 3);
			$upload_dir = "./upload/files/{$rand_num}/";
			if(!is_dir($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = 10 * 1024 * 1024;
			$upload->allowExts = $allowExts;
			$upload->savePath = $upload_dir;
			$upload->saveRule = 'uniqid';
			if($upload->upload()) {
				$uploadList = $upload->getUploadFileInfo();
				$title = $rand_num . ',' . $uploadList[0]['savename'];
				exit(json_encode(array('error' => 0,
				                       'url'   => './upload/files/' . $rand_num . '/' . $uploadList[0]['savename'],
				                       'title' => $title)));
			}
			else {
				exit(json_encode(array('error' => 1, 'message' => $upload->getErrorMsg())));
			}
		}
		else {
			exit(json_encode(array('error' => 1, 'message' => '没有选择文件')));
		}
	}

	public function group()
	{
		$list = D('Config_group')->order('`sort` ASC,`id` ASC')->select();
		$this->assign('list', $list);
		$this->display();
	}

	public function group_edit()
	{
		$db = D('Config_group');
		if(IS_POST) {
			$data['id'] = $this->_post('id', 'trim,intval');
			$data['name'] = $this->_post('name');
			if(empty($data['name']))
				$this->frame_submit_tips(0, '请填写分组名称！');

			$data['action'] = $this->_post('action');
			$data['status'] = $this->_post('status');
			if($data['id']) {
				if($db->where(array('id' => $data['id']))->save($data)) {
					$this->frame_submit_tips(1, '修改成功！');
				}
				else {
					$this->frame_submit_tips(0, '修改失败！请重试~');
				}
			}
			else {
				if($data['id'] = $db->add($data)) {
					$this->frame_submit_tips(1, '添加成功！');
				}
				else {
					$this->frame_submit_tips(0, '添加失败！请重试~');
				}
			}
		}

		$gid = I('get.id', 0, 'trim,intval');
		$group = array('sort' => 0, 'status' => 1);
		if($gid) {
			$group = $db->where(array('id' => $gid))->find();
			if(empty($group)) {
				$this->frame_submit_tips(0, '记录不存在或已被删除！');
			}
		}
		$this->assign('group', $group);

		$this->display();
	}

	public function group_del()
	{
		$menu_id = $this->_get('id', 'trim,intval');
		if(!$menu_id)
			$this->error('参数错误！');

		D('Config_group')->where(array('id' => $menu_id))->delete();
		$this->success('删除成功！');
	}

	public function config()
	{
		$gid = I('get.group');
		if(!$gid)
			$this->error_tips('参数错误！');

		$group = D('Config_group')->where(array('id' => $gid))->find();
		if(empty($group))
			$this->error_tips('该分组不存在！');

		$this->assign('group', $group);

		$list = D('Config')->where(array('gid' => $gid))->select();
		$this->assign('list', $list);

		$this->display();
	}

	public function config_edit()
	{
		$db = D('Config');
		if(IS_POST) {
			// SELECT `id`, `name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `gid`, `sort`, `status` FROM `tp_config` WHERE 1
			$data['id'] = $this->_post('id', 'trim,intval');
			$data['gid'] = $this->_post('gid', 'trim,intval');
			if(!$data['gid'])
				$this->frame_submit_tips(0, '请选择配置分组！');

			$data['info'] = $this->_post('info');
			$data['name'] = $this->_post('name');
			if(empty($data['info']) || empty($data['name']))
				$this->frame_submit_tips(0, '配置ID和名称不能为空！');

			$data['type'] = $this->_post('type');
			$data['value'] = $this->_post('value');
			if(empty($data['type']) || empty($data['value']))
				$this->frame_submit_tips(0, '配置类型和值不能为空！');
			$data['desc'] = $this->_post('desc', 'trim');

			$data['tab_id'] = $this->_post('tab_id', 'trim');
			$data['tab_name'] = $this->_post('tab_name', 'trim');

			$data['status'] = $this->_post('status', 'trim,intval');
			$data['sort'] = $this->_post('sort', 'trim,intval');
			if($data['id']) {
				if($db->where(array('id' => $data['id']))->save($data)) {
					$this->frame_submit_tips(1, '修改成功！');
				}
				else {
					$this->frame_submit_tips(0, '修改失败！请重试~');
				}
			}
			else {
				if($data['id'] = $db->add($data)) {
					$this->frame_submit_tips(1, '添加成功！');
				}
				else {
					$this->frame_submit_tips(0, '添加失败！请重试~');
				}
			}
		}
		$gid = I('get.group', 0, 'trim,intval');
		$config_id = I('get.id', 0, 'trim,intval');
		$cfg = array('gid' => $gid, 'sort' => 0, 'status' => 1);
		if($config_id) {
			$cfg = $db->where(array('id' => $config_id))->find();
			if(empty($cfg)) {
				$this->frame_submit_tips(0, '记录不存在或已被删除！');
			}
		}
		$this->assign('cfg', $cfg);

		$this->assign('groups', D('Config_group')->order('`sort` ASC,`id` DESC')->select());

		$this->display();
	}

	public function config_del()
	{
		$menu_id = $this->_get('id', 'trim,intval');
		if(!$menu_id)
			$this->error('参数错误！');

		D('Config')->where(array('id' => $menu_id))->delete();
		$this->success('删除成功！');
	}

	private function menu_tree($list, $parent_id, $str = '')
	{
		$data = array();
		foreach ($list as $item) {
			if($item['fid'] != $parent_id)
				continue;

			$item['prefix'] = $str;

			$data[] = $item;

			$children = $this->menu_tree($list, $item['id'], $str . '|——');
			if(!empty($children))
				$data = array_merge($data, $children);
		}

		return $data;
	}

	public function menu()
	{
		$list = D('System_menu')->order('`sort` ASC, `id` DESC')->select();
		$this->assign('list', $this->menu_tree($list, 0, ''));
		$this->display();
	}

	public function menu_edit()
	{
		$db = D('System_menu');
		if(IS_POST) {
			$data['id'] = $this->_post('id', 'trim,intval');
			$data['fid'] = $this->_post('fid', 'trim,intval');
			$data['name'] = $this->_post('name');
			if(empty($data['name']))
				$this->frame_submit_tips(0, '请填写菜单名称！');

			$data['module'] = $this->_post('module');
			$data['action'] = $this->_post('action');
			$data['status'] = $this->_post('status', 'trim,intval');
			$data['sort'] = $this->_post('sort', 'trim,intval');
			if($data['id']) {
				if($db->where(array('id' => $data['id']))->save($data)) {
					$this->frame_submit_tips(1, '修改成功！');
				}
				else {
					$this->frame_submit_tips(0, '修改失败！请重试~');
				}
			}
			else {
				if($data['id'] = $db->add($data)) {
					$this->frame_submit_tips(1, '添加成功！');
				}
				else {
					$this->frame_submit_tips(0, '添加失败！请重试~');
				}
			}
		}

		$parents = $db->where(array('fid' => 0))->order('`sort` ASC, `id` DESC')->select();
		$this->assign('parents', $parents);

		$menu = array('sort' => 0, 'status' => 1);
		$id = $this->_get('id', 'trim,intval');
		if($id) {
			$menu = $db->where(array('id' => $id))->find();
			if(empty($menu))
				$this->frame_submit_tips(0, '记录不存在或已被删除！');
		}
		$this->assign('menu', $menu);

		$this->display();
	}

	public function menu_del()
	{
		$menu_id = $this->_get('id', 'trim,intval');
		if(!$menu_id)
			$this->error('参数错误！');

		D('System_menu')->where(array('id' => $menu_id))->delete();
		$this->success('删除成功！');
	}

	//修改分类状态
	public function menu_status()
	{
		$db = M('System_menu');
		$id = $this->_post('id', 'trim,intval');
		$status = $this->_post('status', 'trim,intval');
		$db->where(array('id' => $id))->save(array('status' => $status));
	}

//	public function im()
//	{
//		if(empty($this->config['site_url'])) {
//			exit(json_encode(array('error_code' => true, 'msg' => '先填写您网站的域名')));
//		}
//		if(empty($this->config['wechat_appid']) || empty($this->config['wechat_appsecret'])) {
//			exit(json_encode(array('error_code' => true, 'msg' => '先设置站点的微信公众号信息')));
//		}
//
//		$array = parse_url($this->config['site_url']);
//		$host = $array['host'];
//		import('ORG.Net.Http');
//		$http = new Http();
//		$data = array(
//			'domain'        => $host,
//			'label'         => '',
//			'from'          => '3',
//			'wx_app_id'     => $this->config['wechat_appid'],
//			'wx_app_secret' => $this->config['wechat_appsecret'],
//			'activity_url'  => $this->config['site_url'] . '/admin.php?c=Imapi&a=activity',        //活动链接
//			'my_url'        => $this->config['site_url'] . '/admin.php?c=Imapi&a=my',        //活动链接
//			'msg_tip_url'   => $this->config['site_url'] . '/admin.php?c=Imapi&a=index',        //消息提醒链接
//		);
//
//
//		$return = Http::curlPost('http://im-link.meihua.com/api/app_create.php', $data);
//		$return = json_decode($return, true);
//		if($return['err_code']) {
//			exit(json_encode(array('error_code' => true, 'msg' => $return['err_msg'])));
//		}
//		else {
//			if(D('Config')->where("`name`='im_appid'")->find()) {
//				D('Config')->where("`name`='im_appid'")->save(array('value' => $return['app_id']));
//			}
//			else {
//				D('Config')->add(array('name' => 'im_appid', 'value' => $return['app_id'], 'gid' => 0, 'status' => 1));
//			}
//			if(D('Config')->where("`name`='im_appkey'")->find()) {
//				D('Config')->where("`name`='im_appkey'")->save(array('value' => $return['app_key']));
//			}
//			else {
//				D('Config')->add(array('name'   => 'im_appkey', 'value' => $return['app_key'], 'gid' => 0,
//				                       'status' => 1));
//			}
//			S(C('now_city') . 'config', null);
//			exit(json_encode(array('error_code' => false, 'msg' => '获取成功')));
//		}
//	}
}