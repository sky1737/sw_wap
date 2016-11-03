<?php

class goods_controller extends base_controller
{
    public function __construct()
    {
        parent::__construct();
        if (empty($this->store_session)) redirect(url('index:index'));

        if (!$this->store_session['agent_id']) redirect(url('index:index'));
    }

    public function index()
    {
        if (IS_POST) {
            $product = M('Product');
            $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();
            $result = $product->putaway($this->store_session['store_id'], $product_id);
            if ($result) {
                json_return(0, '商品上架成功');
            } else {
                json_return(1001, '商品上架失败');
            }
            exit;
        } else {
            $weixin_bind = M('Weixin_bind')->get_account_type($this->store_session['store_id']);
            if ($weixin_bind['errcode'] == 0 && $weixin_bind['code'] == 3) {

            }
        }
        $this->display();
    }

    //商品分类列表
    public function category()
    {
        $this->display();
    }

    public function goods_category_add()
    {
        if (IS_POST) {
            $database_product_group = D('Product_group');
            $data_product_group['store_id'] = $this->store_session['store_id'];
            $data_product_group['group_name'] = mysql_real_escape_string($_POST['cat_name']);
            $data_product_group['is_show_name'] = $_POST['show_tag_title'];
            $data_product_group['first_sort'] = $_POST['first_sort'];
            $data_product_group['second_sort'] = $_POST['second_sort'];
            $data_product_group['list_style_size'] = $_POST['size'];
            $data_product_group['list_style_type'] = $_POST['size_type'];
            $data_product_group['is_show_price'] = $_POST['price'];
            $data_product_group['is_show_product_name'] = $_POST['show_title'];
            $data_product_group['is_show_buy_button'] = $_POST['buy_btn'];
            $data_product_group['buy_button_style'] = $_POST['buy_btn_type'];
            $data_product_group['group_label'] = mysql_real_escape_string($_POST['cat_desc']);
            $data_product_group['has_custom'] = !empty($_POST['custom']) ? 1 : 0;
            $data_product_group['add_time'] = $_SERVER['REQUEST_TIME'];
            $group_id = $database_product_group->data($data_product_group)->add();
            if (empty($group_id)) {
                json_return(1000, '分组添加失败');
            }

            if ($_POST['custom']) {
                $field_result =
                    M('Custom_field')->add_field($this->store_session['store_id'], $_POST['custom'], 'good_cat',
                        $group_id);
                if (empty($field_result)) {
                    json_return(1001, '分组添加成功！自定义内容添加失败。请刷新页面重试');
                } else {
                    json_return(0, '添加成功');
                }
            } else {
                json_return(0, '添加成功');
            }
        } else {
            pigcms_tips('非法访问');
        }
    }

    public function goods_category_edit()
    {
        if (IS_POST) {
            $database_product_group = D('Product_group');
            $condition_product_group['group_id'] = $_POST['cat_id'];
            $condition_product_group['store_id'] = $this->store_session['store_id'];
            $data_product_group['group_name'] = $_POST['cat_name'];
            $data_product_group['is_show_name'] = $_POST['show_tag_title'];
            $data_product_group['first_sort'] = $_POST['first_sort'];
            $data_product_group['second_sort'] = $_POST['second_sort'];
            $data_product_group['list_style_size'] = $_POST['size'];
            $data_product_group['list_style_type'] = $_POST['size_type'];
            $data_product_group['is_show_price'] = $_POST['price'];
            $data_product_group['is_show_product_name'] = $_POST['show_title'];
            $data_product_group['is_show_buy_button'] = $_POST['buy_btn'];
            $data_product_group['buy_button_style'] = $_POST['buy_btn_type'];
            $data_product_group['group_label'] = $_POST['cat_desc'];
            $data_product_group['has_custom'] = !empty($_POST['custom']) ? 1 : 0;
            $data_product_group['add_time'] = $_SERVER['REQUEST_TIME'];
            if (!$database_product_group->where($condition_product_group)->data($data_product_group)->save()) {
                json_return(1000, '分类编辑失败，请重试');
            }

            M('Custom_field')->delete_field($this->store_session['store_id'], 'good_cat', $_POST['cat_id']);
            if ($_POST['custom']) {
                $field_result =
                    M('Custom_field')->add_field($this->store_session['store_id'], $_POST['custom'], 'good_cat',
                        $_POST['cat_id']);
                if (empty($field_result)) {
                    json_return(1001, '分组编辑成功！自定义内容添加失败。请刷新页面重试');
                } else {
                    json_return(0, '编辑成功');
                }
            } else {
                json_return(0, '编辑成功');
            }
        } else {
            pigcms_tips('非法访问');
        }
    }

    //商品分类删除
    public function goods_category_delete()
    {
        $database_product_group = D('Product_group');
        $condition_product_group['store_id'] = $this->store_session['store_id'];
        $condition_product_group['group_id'] = $_POST['group_id'];
        $now_group =
            $database_product_group->field('`group_id`,`store_id`,`has_custom`')->where($condition_product_group)
                ->find();
        if (empty($now_group)) {
            json_return(1004, '页面不存在');
        }

        if ($now_group['has_custom']) {
            M('Custom_field')->delete_field($now_group['store_id'], 'good_cat', $now_group['group_id']);
        }
        if ($database_product_group->where($condition_product_group)->delete()) {
            json_return(0, '删除成功');
        } else {
            json_return(1005, '删除失败，请重试');
        }
    }

    //ajax得到商品分组
    public function get_goodsCategory()
    {
        $group_list = M('Product_group')->get_all_list($this->store_session['store_id']);
        if (empty($group_list)) {
            json_return(1000, '没有分类');
        } else {
            json_return(0, $group_list);
        }
    }

    //添加商品
    public function create()
    {
        if (IS_POST) {
//			if ($_POST['group_ids']) {
//				$tmp_arr = explode(',', $_POST['group_ids']);
//				$group_ids_arr = array();
//				foreach ($tmp_arr as $value) {
//					if (!empty($value)) {
//						$group_ids_arr[] = $value;
//					}
//				}
//			}

            $product = M('Product');
            $product_category = M('Product_category');
            $product_image = M('Product_image');
            $product_sku = M('Product_sku');
            $product_to_property = M('Product_to_property');
            $product_to_property_value = M('Product_to_property_value');
            $system_product_to_property_value = M('System_product_to_property_value');
            $product_custom_field = M('Product_custom_field');
            $common_data = M('Common_data');

            $data = array();

            $data['uid'] = isset($_POST['supplier_uid']) ? intval(trim($_POST['supplier_uid'])) : 0; //供应商UID
            $data['store_id'] = isset($_POST['supplier_store_id']) ? intval(trim($_POST['supplier_store_id'])) : 0; //供应商 商店id
            $data['create_uid'] = $this->user_session['uid'];

            $data['is_import'] = isset($_POST['is_import']) ? intval(trim($_POST['is_import'])) : 0; //是否是海外商品
            $data['category_id'] = isset($_POST['category_id']) ? intval(trim($_POST['category_id'])) : 0; //分类
            $data['buy_way'] = 1; // isset($_POST['buy_way']) ? intval(trim($_POST['buy_way'])) : 0; //购买方式
            $data['buy_url'] = ''; // isset($_POST['buy_url']) ? trim($_POST['buy_url']) : ''; //购买地址
            $data['quantity'] = isset($_POST['quantity']) ? intval(trim($_POST['quantity'])) : 0; //数量
            $data['show_sku'] = isset($_POST['show_stock']) ? intval(trim($_POST['show_stock'])) : 0; //是否显示库存数量
            $data['code'] = isset($_POST['code']) ? trim($_POST['code']) : ''; //商家编码
            $data['name'] = isset($_POST['name']) ? mysql_real_escape_string(trim($_POST['name'])) : ''; //名称
            $data['price'] = isset($_POST['price']) ? floatval(trim($_POST['price'])) : 0; //价格
            $data['market_price'] = isset($_POST['market_price']) ? floatval(trim($_POST['market_price'])) : 0; //原价
            $data['cost_price'] = isset($_POST['cost_price']) ? floatval(trim($_POST['cost_price'])) : 0; //原价
            $data['factory_price'] = isset($_POST['factory_price']) ? floatval(trim($_POST['factory_price'])) : 0; //原价
            $data['discount'] =
                isset($_POST['discountpre']) ? floatval(trim($_POST['discountpre'])) : 10; //折扣率
            $data['weight'] = isset($_POST['weight']) ? floatval(trim($_POST['weight'])) : 0; // 重量
            $data['postage_type'] = isset($_POST['postage_type']) ? intval(trim($_POST['postage_type'])) : 0; //邮费类型
            $data['postage'] = isset($_POST['postage']) ? floatval(trim($_POST['postage'])) : 0; //固定邮费
            $data['postage_template_id'] =
                isset($_POST['postage_tpl_id']) ? intval(trim($_POST['postage_tpl_id'])) : 0; //邮费模板
            $data['buyer_quota'] = isset($_POST['buyer_quota']) ? intval(trim($_POST['buyer_quota'])) : 0; //每人限购
            $data['sold_time'] = isset($_POST['sold_time']) ? trim($_POST['sold_time']) : 0; //开售时间
            $data['allow_discount'] = isset($_POST['discount']) ? intval(trim($_POST['discount'])) : 0; //会员折扣
            $data['invoice'] = isset($_POST['invoice']) ? intval(trim($_POST['invoice'])) : 0; //发票
            $data['warranty'] = isset($_POST['warranty']) ? intval(trim($_POST['warranty'])) : 0; //保修
            $data['date_added'] = time();
            $data['status'] = $_POST['status']; //销售状态 下架 上架
            $data['intro'] = isset($_POST['intro']) ? mysql_real_escape_string(trim($_POST['intro'])) : ''; //商品简介
            $data['info'] = isset($_POST['info']) ? mysql_real_escape_string(trim($_POST['info'])) : ''; //商品描述
            $data['has_custom'] = !empty($_POST['custom']) ? 1 : 0;
            //$data['has_category'] = !empty($group_ids_arr) ? 1 : 0;
            $data['is_recommend'] = !empty($_POST['is_recommend']) ? -1 : 0;
            $data['is_hot'] = !empty($_POST['is_hot']) ? -1 : 0;
            $data['is_fx'] = !empty($_POST['is_fx']) ? -1 : 0;
            if (empty($data['buy_way'])) {
                $data['quantity'] = 1;
            }

            $images = isset($_POST['images']) ? $_POST['images'] : array(); //图片
            $preview = isset($_POST['preview']) ? $_POST['preview'] : 0; //是否跳转到前台预览
            $tmp_skus = isset($_POST['skus']) ? $_POST['skus'] : array(); //商品库存信息
            $fields = isset($_POST['fields']) ? $_POST['fields'] : array(); //商品自定义字段
            $sys_fields2 = isset($_POST['sys_fields']) ? $_POST['sys_fields'] : array(); //商品栏目筛选属性

            if (!empty($tmp_skus)) {
                $data['has_property'] = 1;
            }

            if (!empty($images)) {
                foreach ($images as &$image) {
                    $image = getAttachment($image);
                }
                $data['image'] = $images[0]; //商品主图
            }

            $category = $product_category->getCategory($data['category_id']);
            if (!empty($category['cat_fid'])) {
                $data['category_fid'] = $category['cat_fid'];
            } else {
                $data['category_fid'] = $data['category_id'];
                $data['category_id'] = 0;
            }

            //商品添加
            $product_id = $product->add($data);
            if ($product_id) {
                $common_data->setProductQty();

                if (!empty($images)) {
                    $images = $product_image->add($product_id, $images);
                }

                //添加栏目商品性值关联
                foreach ($sys_fields2 as $v) {
                    $system_product_to_property_value->add(array('product_id' => $product_id,
                        'pid' => $v['sys_property_id'],
                        'vid' => $v['sys_property_value_id']));
                }

                if (!empty($tmp_skus)) {
                    $skus = array();
                    $props_vid = array();
                    $props_pid = array();
                    foreach ($tmp_skus as $sku) {
                        $props_arr = explode(';', $sku['props_str']);
                        foreach ($props_arr as $prop) {
                            $prop_arr = explode(':', $prop);
                            $pid = $prop_arr[0];
                            $vid = $prop_arr[1];
                            $props_vid[$pid . ';' . $vid] = array(
                                'pid' => $pid,
                                'vid' => $vid
                            );
                            $props_pid[$pid] = $pid;
                        }
                        $skus[] = array(
                            'properties' => $sku['props_str'],
                            'price' => $sku['price'],
                            'market' => $sku['market'],
                            'cost' => $sku['cost'],
                            'factory' => $sku['factory'],
                            'quantity' => $sku['quantity'],
                            'weight' => $sku['weight'],
                            'code' => $sku['code']
                        );
                    }
                    $skus = $product_sku->add($product_id, $skus);

                    //添加商品属性关联
                    $i = 1;
                    foreach ($props_pid as $prop_pid) {
                        $product_to_property->add(array('store_id' => $this->store_session['store_id'],
                            'product_id' => $product_id, 'pid' => $prop_pid,
                            'order_by' => $i));
                        $i++;
                    }

                    //添加商品性值关联
                    $i = 1;
                    foreach ($props_vid as $prop_vid) {
                        $product_to_property_value->add(array('store_id' => $this->store_session['store_id'],
                            'product_id' => $product_id, 'pid' => $prop_vid['pid'],
                            'vid' => $prop_vid['vid'], 'order_by' => $i));
                        $i++;
                    }

                    //修改自定义字段
                    if ($product_custom_field->delete($product_id)) {
                        $product_custom_field->add($product_id, $fields);
                    }
                }

                //商品分组
//				if (!empty($group_ids_arr)) {
//					$database_product_group = D('Product_group');
//					$database_product_to_group = D('Product_to_group');
//					$data_product_to_group['product_id'] = $product_id;
//					foreach ($group_ids_arr as $value) {
//						$data_product_to_group['group_id'] = $value;
//						if ($database_product_to_group->data($data_product_to_group)->add()) {
//							$database_product_group->where(array('group_id' => $value))->setInc('product_count');
//						}
//					}
//				}
                if ($_POST['custom']) {
                    $field_result =
                        M('Custom_field')->add_field($this->store_session['store_id'], $_POST['custom'], 'good',
                            $product_id);
                }

                if ($preview) {
                    echo json_return(0, option('config.wap_site_url') . '/good.php?id=' . $product_id); //预览
                } else {
                    echo json_return(0, url('index'));
                }
            } else {
                json_return('1010', '商品添加失败');
            }
            exit;
        }
        $this->display();
    }

    //修改商品
    public function edit()
    {

        if (IS_POST) {
//			if ($_POST['group_ids']) {
//				$tmp_arr = explode(',', $_POST['group_ids']);
//				$group_ids_arr = array();
//				foreach ($tmp_arr as $value) {
//					if (!empty($value)) {
//						$group_ids_arr[] = $value;
//					}
//				}
//			}

            /**
             * @var $product product_model
             */
            $product = M('Product');
            $product_category = M('Product_category');
            $product_image = M('Product_image');
            $product_custom_field = M('Product_custom_field');
            /**
             * @var $product_sku product_sku_model
             */
            $product_sku = M('Product_sku');
            //$system_product_to_property = M('System_product_to_property');
            $system_product_to_property_value = M('System_product_to_property_value');

            $data['uid'] = isset($_POST['supplier_uid']) ? intval(trim($_POST['supplier_uid'])) : 0; //供应商UID
            $store_id = isset($_POST['supplier_store_id']) ? intval(trim($_POST['supplier_store_id'])) : 0; //供应商 商店id
            $product_id = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;

            $data['store_id'] = $store_id;
            $data['category_id'] = isset($_POST['category_id']) ? intval(trim($_POST['category_id'])) : 0; //分类
            $data['buy_way'] = 1;// isset($_POST['buy_way']) ? intval(trim($_POST['buy_way'])) : 0; //购买方式
            $data['buy_url'] = '';// isset($_POST['buy_url']) ? trim($_POST['buy_url']) : ''; //购买地址
            $data['quantity'] = isset($_POST['quantity']) ? intval(trim($_POST['quantity'])) : 0; //数量
            $data['show_sku'] = isset($_POST['show_stock']) ? intval(trim($_POST['show_stock'])) : 0; //是否显示库存数量
            $data['code'] = isset($_POST['code']) ? trim($_POST['code']) : ''; //商家编码
            $data['name'] = isset($_POST['name']) ? mysql_real_escape_string(trim($_POST['name'])) : ''; //名称
            $data['price'] = isset($_POST['price']) ? floatval(trim($_POST['price'])) : 0; //销售价格
            $data['is_import'] = isset($_POST['is_import']) ? intval(trim($_POST['is_import'])) : 0; //是否是海外商品
            //var_dump($data['is_overseas']);exit;
            $data['market_price'] =
                isset($_POST['market_price']) ? floatval(trim($_POST['market_price'])) : 0; //市场价
            $data['cost_price'] =
                isset($_POST['cost_price']) ? floatval(trim($_POST['cost_price'])) : 0; //成本价
            $data['factory_price'] =
                isset($_POST['factory_price']) ? floatval(trim($_POST['factory_price'])) : 0; //成本价
            $data['discount'] =
                isset($_POST['discountpre']) ? floatval(trim($_POST['discountpre'])) : 10; //折扣率
            $data['weight'] = isset($_POST['weight']) ? floatval(trim($_POST['weight'])) : 0;
            if (isset($_POST['postage_type'])) {
                $data['postage_type'] =
                    !empty($_POST['postage_type']) ? intval(trim($_POST['postage_type'])) : 0; //邮费类型
            }
            if (isset($_POST['postage'])) {
                $data['postage'] = !empty($_POST['postage']) ? floatval(trim($_POST['postage'])) : 0; //固定邮费
            }
            if (isset($_POST['postage_tpl_id'])) {
                $data['postage_template_id'] =
                    !empty($_POST['postage_tpl_id']) ? intval(trim($_POST['postage_tpl_id'])) : 0; //邮费模板
            }
            $data['buyer_quota'] = isset($_POST['buyer_quota']) ? intval(trim($_POST['buyer_quota'])) : 0; //每人限购
            $data['sold_time'] = isset($_POST['sold_time']) ? intval(strtotime(trim($_POST['sold_time']))) : 0; //开售时间
            $data['allow_discount'] = isset($_POST['discount']) ? intval(trim($_POST['discount'])) : 0; //会员折扣
            $data['invoice'] = isset($_POST['invoice']) ? intval(trim($_POST['invoice'])) : 0; //发票
            $data['warranty'] = isset($_POST['warranty']) ? intval(trim($_POST['warranty'])) : 0; //保修
            $data['status'] = $_POST['status']; //销售状态 下架 上架
            $data['intro'] = isset($_POST['intro']) ? mysql_real_escape_string(trim($_POST['intro'])) : ''; //商品简介
            $data['info'] = isset($_POST['info']) ? mysql_real_escape_string(trim($_POST['info'])) : ''; //商品描述
            $data['has_custom'] = !empty($_POST['custom']) ? 1 : 0;
            //$data['has_category'] = !empty($group_ids_arr) ? 1 : 0;
            $data['is_recommend'] = !empty($_POST['is_recommend']) ? -1 : 0;
            $data['is_hot'] = !empty($_POST['is_hot']) ? -1 : 0;
            $data['is_fx'] = !empty($_POST['is_fx']) ? -1 : 0;
            if (empty($data['buy_way'])) {
                $data['quantity'] = 1;
            }
            $preview = isset($_POST['preview']) ? $_POST['preview'] : 0; //是否跳转到前台预览
            $images = isset($_POST['images']) ? $_POST['images'] : array(); //图片
            $sys_fields2 = isset($_POST['sys_fields']) ? $_POST['sys_fields'] : array(); //商品栏目筛选属性

            if (!empty($images)) {
                foreach ($images as &$image) {
                    $image = getAttachment($image);
                }

                $data['image'] = $images[0]; //商品主图
            }
            $fields = isset($_POST['fields']) ? $_POST['fields'] : array(); //自定义字段
            $skus = isset($_POST['skus']) ? $_POST['skus'] : array(); //库存信息
            if (!empty($skus)) {
                $data['has_property'] = 1;
            }
            $category = $product_category->getCategory($data['category_id']);
            if (!empty($category['cat_fid'])) {
                $data['category_fid'] = $category['cat_fid'];
            } else {
                $data['category_fid'] = $data['category_id'];
                $data['category_id'] = 0;
            }
            //var_dump($store_id,$product_id,$data);die();
            //var_dump($product_id,$data);exit;
            //修改商品
            $asl_store_id = addslashes($store_id);
            $asl_create_uid = addslashes($this->user_session['uid']);
            $product->edit(array(
                //'_string'=> "(`store_id` = '$asl_store_id' OR create_uid = '$asl_create_uid')",
                'product_id' => $product_id,
            ), $data);
            //print_r($product->db->last_sql);die();

            //$product_detail = $product->get(array('product_id' => $product_id, 'store_id' => $store_id));

            //添加栏目商品性值关联
            //if ($product_detail['supplier_id'] == 0) {
            //  foreach ($sys_fields2 as $v) {
            //    $system_product_to_property_value->edit(array('product_id' => $product_id), array('pid' => $v['sys_property_id'], 'vid' => $v['sys_property_value_id']));
            //  }
            //}

            // 筛选的属性
            //if($product_detail['supplier_id'] == 0) {
            $system_product_to_property_value->delete(array('product_id' => $product_id));
            foreach ($sys_fields2 as $v) {
                $system_product_to_property_value->add(array('product_id' => $product_id,
                    'pid' => $v['sys_property_id'],
                    'vid' => $v['sys_property_value_id']));
            }
            //}
            /**
             * @var $product_to_property product_to_property_model
             * @var $product_to_property_value product_to_property_value_model
             */
            $product_to_property = M('Product_to_property');
            $product_to_property_value = M('Product_to_property_value');

            // 规格信息
            if (!empty($skus)) {
                //$skus = $product_sku->edit($product_id, $skus);
                $product_sku->edit($product_id, $skus);

                // 添加关联
                $prop_vids = array();
                $prop_pids = array();
                foreach ($skus as $sku) {
                    foreach (explode(';', $sku['properties']) as $prop) {
                        $prop_arr = explode(':', $prop);
                        $pid = $prop_arr[0];
                        $vid = $prop_arr[1];
                        $prop_vids[$pid . ';' . $vid] = array(
                            'pid' => $pid,
                            'vid' => $vid
                        );
                        $prop_pids[$pid] = $pid;
                    }
                }

                $product_to_property->edit($store_id, $product_id, $prop_pids);
                $product_to_property_value->edit($store_id, $product_id, $prop_vids);
            } else {
                $product_sku->db->where("`product_id` = {$product_id}")->delete();
                $product_to_property->db->where("`product_id` = {$product_id}")->delete();
                $product_to_property_value->db->where("`product_id` = {$product_id}")->delete();
            }

            //修改图片，不删除源文件
            if ($product_image->delete($product_id)) {
                $images = $product_image->add($product_id, $images);
            }

            //修改自定义字段
            if ($product_custom_field->delete($product_id)) {
                $product_custom_field->add($product_id, $fields);
            }

//			//商品分组
//			$database_product_group = D('Product_group');
//			$database_product_to_group = D('Product_to_group');
//			$condition_product_to_group['product_id'] = $product_id;
//			$tmp_group_ids = $database_product_to_group->field('`group_id`')->where($condition_product_to_group)->select();
//			$group_ids = array();
//			foreach ($tmp_group_ids as $key => $value) {
//				$group_ids[] = $value['group_id'];
//			}
//
//			//删除以前的关系
//			if ($group_ids) {
//				$database_product_to_group->where($condition_product_to_group)->delete();
//				$condition_product_group['group_id'] = array('in', $group_ids);
//				$database_product_group->where($condition_product_group)->setDec('product_count');
//			}
//
//			if (!empty($group_ids_arr)) {
//				$data_product_to_group['product_id'] = $product_id;
//				foreach ($group_ids_arr as $value) {
//					$data_product_to_group['group_id'] = $value;
//					if ($database_product_to_group->data($data_product_to_group)->add()) {
//						$database_product_group->where(array('group_id' => $value))->setInc('product_count');
//					}
//				}
//			}

            M('Custom_field')->delete_field($this->store_session['store_id'], 'good', $product_id);
            if ($_POST['custom']) {
                $field_result = M('Custom_field')->add_field($this->store_session['store_id'], $_POST['custom'], 'good',
                    $product_id);
            }

            //同步微页面商品
            $fields = D('Custom_field')
                ->where(array('store_id' => $this->store_session['store_id'], 'field_type' => 'goods'))
                ->select();
            if ($fields) {
                foreach ($fields as $field) {
                    $products = unserialize($field['content']);
                    if (!empty($products) && !empty($products['goods'])) {
                        $new_products = array();
                        foreach ($products['goods'] as $product) {
                            if ($product['id'] == $product_id) {
                                $product['title'] = htmlspecialchars($data['name'], ENT_QUOTES);
                                $product['price'] = $data['price'];
                            }
                            $new_products[] = $product;
                        }
                        $products['goods'] = $new_products;
                        $content = serialize($products);
                        D('Custom_field')->where(array('field_id' => $field['field_id']))
                            ->data(array('content' => $content))->save();
                    }
                }
            }

            if (!empty($_POST['referer']) && strtolower($_POST['referer']) == 'fx_goods') {
                echo json_return(0, url('fx:goods'));
            }

            if ($preview) {
                echo json_return(0, option('config.wap_site_url') . '/good.php?id=' . $product_id); //预览
            } else {
                echo json_return(0, url('index'));
            }
        }
        $this->display();
    }

    //批量修改供应商
    public function movesupplier()
    {
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : array();
        $supplierUid = isset($_POST['supplierUid']) ? intval(trim($_POST['supplierUid'])) : 0;
        $product = D('Product');
        if ($product_id && $supplierUid) {
            $product->where(array('product_id' => array('in', $product_id)))->data(array('uid' => $supplierUid))->save();
        }
        json_return(0, '移动成功');
    }

    public function goods_load()
    {
        if (empty($_POST['page']))
            pigcms_tips('非法访问！', 'none');

        if ($_POST['page'] == 'create_content') {
            $cat_list = M('Product_category')->getAllCategory(0, true);

            $agent = D('Agent');
            $allOpenSelfAgents = $agent->where('open_self <> 0 and status=1')->select();
            $allOpenSelfAgentIds = array();
            foreach ($allOpenSelfAgents as $a) $allOpenSelfAgentIds[] = $a['agent_id'];

            $store = D('Store');
            $supplierStoreInfo = $store->where(array('agent_id' => array('in', $allOpenSelfAgentIds)))->select();

            //var_dump($storeInfo);exit;

            $this->assign('supplierStoreInfo', $supplierStoreInfo);

            $this->assign('cat_list', $cat_list);
        }
        if ($_POST['page'] == 'edit_content') {
            $this->_edit_content($_GET['id']);
        }
//		//商品分组列表
//		if($_POST['page'] == 'category_content') {
//			$group_list = M('Product_group')->get_list($this->store_session['store_id']);
//			$this->assign('group_list', $group_list);
//		}
//		//商品分组编辑
//		if($_POST['page'] == 'category_edit') {
//			$now_group = M('Product_group')->get_group($this->store_session['store_id'], $_POST['group_id']);
//			if(!empty($now_group)) {
//				if($now_group['has_custom']) {
//					$customField = M('Custom_field')->get_field($this->store_session['store_id'], 'good_cat',
//						$now_group['group_id']);
//					$this->assign('customField', json_encode($customField));
//				}
//				$this->assign('now_group', $now_group);
//			}
//			else {
//				exit('当前分组不存在！');
//			}
//		}
        if ($_POST['page'] == 'selling_content') {

            $agent = D('Agent');
            $allOpenSelfAgents = $agent->where('open_self <> 0 and status=1')->select();
            $allOpenSelfAgentIds = array();
            foreach ($allOpenSelfAgents as $a) $allOpenSelfAgentIds[] = $a['agent_id'];

            $store = D('Store');
            $supplierStoreInfo = $store->where(array('agent_id' => array('in', $allOpenSelfAgentIds)))->select();

            $supplierUid = isset($_POST['supplierUid']) ? trim($_POST['supplierUid']) : '';
            $this->_selling_goods_list($supplierUid);

            $this->assign('supplierUid', $supplierUid);
            $this->assign('supplierStoreInfo', $supplierStoreInfo);
        }
        if ($_POST['page'] == 'stockout_content') {
            $this->_stockout_goods_list();
        }
        if ($_POST['page'] == 'soldout_content') {
            $this->_soldout_goods_list();
        }

        $this->display($_POST['page']);
    }

    public function get_product_property_list()
    {
        $list = M('Product_property')->get_list();
        if (empty($list))
            json_return(999, '管理员没有添加规格项目');
        else
            json_return(0, $list);
    }

    public function get_propertyvaluebyid()
    {
        $id = intval($_POST['propertypid']);
        if (!$id) {
            die('err');
        }
        exit(json_encode(array('counts' => 0, 'fields' => null)));
        // {counts:0,fields:{arr:[{vid:'',value:''}]}}
        //$list = D('Product_property_value')->where(array('pid' => $id))->select();
        //exit(json_encode(array('counts' => count($list), 'fields' => $list)));
    }

//    public function get_system_property_list() {
//
//        // 加载商品所属类别的属性
//        $catid_str = $_GET['catid'];
//        if (empty($catid_str))
//            return false;
//
//        $catid = end(explode("-", $catid_str));
//        $cat_arr = M('Product_category')->getCategory($catid);
//        if (empty($cat_arr['filter_attr'])) {
//            json_return(998, '该商品栏目缺少筛选属性，请联系系统管理员添加该属性后再操作！');
//        }
//
//        $filter_attr = explode(',', $cat_arr['filter_attr']);
//        $return_arr = M('System_product_property')->get_list_to_value($filter_attr);
//        json_return(0, $return_arr);
//    }

    public function get_system_property_list()
    {
        // 加载商品所属类别的属性
        $catid_str = $_GET['catid'];
        if (empty($catid_str))
            return false;

        //判断是否为二级菜单
        $cat_num = count(explode("-", $catid_str));
        if ($cat_num == 1) {
            $catid = $catid_str;
            $cat_arr = M('Product_category')->getCategory($catid);
            if (empty($cat_arr['filter_attr'])) {
                json_return(998, '该商品栏目缺少筛选属性，请联系系统管理员添加该属性后再操作！');
            }

            $filter_attr = explode(',', $cat_arr['filter_attr']);
            $return_arr = M('System_product_property')->get_list_to_value($filter_attr);
        } else if ($cat_num == 2) {
            $catidArr = explode('-', $catid_str);
            $parent_cat = M('Product_category')->getCategory(reset($catidArr));

            $parent_filter_attr = explode(',', $parent_cat['filter_attr']);
            $parent_cat_arr = M('System_product_property')->get_list_to_value($parent_filter_attr);
            if (empty($parent_cat['filter_attr'])) {
                $parent_cat_arr['error_code'] = 998;
            }

            //$parent_cat_arr['cat_name'] = $parent_cat['cat_name'];
            $son_cat = M('Product_category')->getCategory(end($catidArr));

            $son_filter_attr = explode(',', $son_cat['filter_attr']);
            $son_cat_arr = M('System_product_property')->get_list_to_value($son_filter_attr);
            if (empty($son_cat['filter_attr'])) {
                $son_cat_arr['error_code'] = 998;
            }
            //$son_cat_arr['cat_name'] = $son_cat['cat_name'];

            $data = array_merge($parent_cat_arr, $son_cat_arr);
            // $return_arr[] = $parent_cat_arr;
            // $return_arr[] = $son_cat_arr;
        }
        //json_return(0, $return_arr);
        json_return(0, $data);
    }

    //读取该商品的栏目属性 edit专用
//    public function get_system_product_property_list() {
//        // 加载商品所属类别的属性
//        $catid_str = $_GET['catid'];
//        $pid = $_GET['pid'];
//        if (empty($catid_str))
//            return false;
//
//        $catid = end(explode("-", $catid_str));
//        $cat_arr = M('Product_category')->getCategory($catid);
//        if (empty($cat_arr['filter_attr'])) {
//            json_return(998, '该商品栏目缺少筛选属性，请联系系统管理员添加该属性后再操作！');
//        }
//        $filter_attr = explode(',', $cat_arr['filter_attr']);
//        $arr1 = M('System_product_property')->get_list_to_value($filter_attr);
//        $arr2 = D('System_product_to_property_value')->where(array('product_id' => $pid))->field('vid')->select();
//        foreach ($arr2 as $k => $v) {
//            $arr3[$k] = $v['vid'];
//        }
//
//        foreach ($arr1 as $k => $v) {
//            foreach ($v['property_value'] as $k1 => $v1) {
//                if (in_array($v1['vid'], $arr3)) {
//                    $v1['selected'] = 'selected';
//                }
//                $v['property_value'][$k1] = $v1;
//            }
//            $return_arr[$k] = $v;
//        }
//
//        json_return(0, $return_arr);
//    }

    public function get_system_product_property_list()
    {
        // 加载商品所属类别的属性
        $catid_str = $_GET['catid'];
        $pid = $_GET['pid'];
        if (empty($catid_str))
            return false;

        $catid = end(explode("-", $catid_str));

        $cat_arr = $this->searchParent($catid);
        foreach ($cat_arr as $k => $v) {
            if (empty($k)) {
                unset($cat_arr[$k]);
            }
        }

        $filter_attr = array_keys($cat_arr);
        $arr1 = M('System_product_property')->get_list_to_value($filter_attr);
        $arr2 = D('System_product_to_property_value')->where(array('product_id' => $pid))->field('vid')->select();
        foreach ($arr2 as $k => $v) {
            $arr3[$k] = $v['vid'];
        }

        foreach ($arr1 as $k => $v) {
            foreach ($v['property_value'] as $k1 => $v1) {
                if (is_array($arr3) && in_array($v1['vid'], $arr3)) {
                    $v1['selected'] = 'selected';
                }
                $v['property_value'][$k1] = $v1;
            }
            if (!($cat_arr[$v['pid'] - 1] == $cat_arr[$v['pid']]) && ($v['pid'] - 1 > 0)) {
                //$v['cat_name'] = $cat_arr[$v['pid']];
            }
            $return_arr[$k] = $v;
        }

        json_return(0, $return_arr);
    }

    public function get_property_value()
    {
        $database = D('Product_property_value');
        $condition['pid'] = $_POST['pid'];
        $condition['value'] = $_POST['txt'];
        $value = $database->field('`svid`')->where($condition)->find();
        if (empty($value)) {
            $data['pid'] = $_POST['pid'];
            $data['value'] = $_POST['txt'];
            $vid = $database->data($condition)->add();
            if (empty($vid)) {
                json_return(1001, '添加规格属性失败，请重试');
            }
            $value['vid'] = $vid;
        }
        json_return(0, $value['vid']);
    }

    public function get_trade_delivery()
    {
        $tpl_list = M('Postage_template')->get_all_list($this->store_session['store_id']);
        if (!empty($tpl_list)) {
            return json_return(0, $tpl_list);
        } else {
            return json_return(1002, '没有运费模板');
        }
    }

    public function attachment()
    {
        $this->display();
    }

    public function attchment_amend_name()
    {
        $condition_attachment['id'] = $_POST['id'];
        $condition_attachment['store_id'] = $this->store_session['store_id'];
        $data_attachment['name'] = $_POST['name'];
        if (D('Attachment')->where($condition_attachment)->data($data_attachment)->save()) {
            json_return(0, '保存成功');
        } else {
            json_return(1001, '保存文件名失败');
        }
    }

    public function attchment_delete()
    {
        $condition_attachment['id'] = $_POST['id'];
        $condition_attachment['store_id'] = $this->store_session['store_id'];
        $data_attachment['status'] = 0;
        if (D('Attachment')->where($condition_attachment)->data($data_attachment)->save()) {
            json_return(0, '删除成功');
        } else {
            json_return(1002, '删除失败');
        }
    }

    public function attchment_delete_more()
    {
        if (empty($_POST['id']))
            json_return(1003, '请选中一些值');

        $condition_attachment['id'] = array('in', $_POST['id']);
        $condition_attachment['store_id'] = $this->store_session['store_id'];
        $data_attachment['status'] = 0;
        if (D('Attachment')->where($condition_attachment)->data($data_attachment)->save()) {
            json_return(0, '批量删除成功');
        } else {
            json_return(1004, '批量删除失败');
        }
    }

    /**
     * 商品下架
     */
    public function soldout()
    {
        if (IS_POST) {
            /**
             * @var $product product_model
             */
            $product = M('Product');
            $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();

            if (!empty($product_id)) {
                foreach ($product_id as $id) {
                    $product_info =
                        D('Product')->field('product_id,is_fx,fx_type,source_product_id,original_product_id')
                            ->where(array('product_id' => $id))->find();
                    if ($product->soldout($this->store_session['store_id'], array($id))) {
                        if (!empty($product_info['is_fx']) && empty($product_info['original_product_id'])) { //供货商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        } else if (!empty($product_info['source_product_id'])) { //分销商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        }
                    }
                }
                $this->_sync_wei_page_goods($product_id); //同步微页面商品
                json_return(0, '商品下架成功');
            } else {
                json_return(1001, '商品下架失败');
            }
            exit;
        }

        $this->display();
    }

    /**
     * 商品售完
     */
    public function stockout()
    {
        $this->display();
    }

    /**
     * 商品删除
     */
    public function remove()
    {
        $product = M('Product');

        $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();
        //参数兼容数组和数字
        if (!is_array($product_id)) {
            $product_id = array(intval(trim($product_id)));
        }
    }

    /**
     * 参与会员折扣
     */
    public function allow_discount()
    {
        $product = M('Product');

        $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();
        $discount = isset($_REQUEST['discount']) ? intval(trim($_REQUEST['discount'])) : 0;
        $result = $product->allowDiscount($this->store_session['store_id'], $discount, $product_id);
        if ($result) {
            json_return(0, '商品参与会员折扣成功');
        } else {
            json_return(1001, '商品参与会员折扣失败');
        }
        exit;
    }

    /**
     * 商品分组
     */
    public function edit_group()
    {
        $product_to_group = M('Product_to_group');

        $data = isset($_POST['data']) ? $_POST['data'] : array();
        if (!empty($data)) {
            foreach ($data as $value) {
                $product_id = $value['product_id'];
                $product_to_group->delete($product_id);
                $group_ids = explode(',', $value['group_id']);
                $flag = false;
                foreach ($group_ids as $group_id) {
                    if ($product_to_group->add(array('product_id' => $product_id, 'group_id' => $group_id))) {
                        $flag = true;
                        D('Product_group')->where(array('group_id' => $group_id))
                            ->setInc('product_count', 1); //商品分组的商品数量
                    }
                }
                if ($flag) {
                    D('Product')->where(array('product_id' => $product_id))->data(array('has_category' => 1))->save();
                }
            }
            json_return(0, '修改成功');
        } else {
            json_return(1001, '修改失败');
        }

        return false;
    }

    /**
     * 出售中的商品列表
     */
    private function _selling_goods_list($supplierUid)
    {
        /**
         * @var $product product_model
         */
        $product = M('Product');
//		$product_group = M('Product_group');
//		$product_to_group = M('Product_to_group');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $group_id = isset($_POST['group_id']) ? trim($_POST['group_id']) : '';

        $where = array();
        //$where['store_id'] = $this->store_session['store_id'];
        $where['quantity'] = array('>', 0);
        $where['soldout'] = 0;
        if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        if ($supplierUid) {
            $where['uid'] = $supplierUid;
        }
        if ($group_id) {
            $products = $product_to_group->getProducts($group_id);
            $product_ids = array();
            if (!empty($products)) {
                foreach ($products as $item) {
                    $product_ids[] = $item['product_id'];
                }
            }
            $where['product_id'] = array('in', $product_ids);
        }
        $product_total = $product->getSellingTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        $products = $product->getSelling($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);
        //var_dump($product->db->last_sql);exit;
        //$product_groups = $product_group->get_all_list($this->store_session['store_id']);

        //$this->assign('product_groups', $product_groups);
        //$this->assign('product_groups_json', json_encode($product_groups));
        $this->assign('page', $page->show());
        $this->assign('products', $products);
    }

    /**
     * 已售罄的商品列表
     */
    private function _stockout_goods_list()
    {
        $product = M('Product');
        $product_group = M('Product_group');
        $product_to_group = M('Product_to_group');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $group_id = isset($_POST['group_id']) ? trim($_POST['group_id']) : '';

        $where = array();
        //$where['store_id'] = $this->store_session['store_id'];
        if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        if ($group_id) {
            $products = $product_to_group->getProducts($group_id);
            $product_ids = array();
            if (!empty($products)) {
                foreach ($products as $item) {
                    $product_ids[] = $item['product_id'];
                }
            }
            $where['product_id'] = array('in', $product_ids);
        }
        $product_total = $product->getStockoutTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        $products = $product->getStockout($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);

        $product_groups = $product_group->get_all_list($this->store_session['store_id']);

        $this->assign('product_groups', $product_groups);
        $this->assign('product_groups_json', json_encode($product_groups));
        $this->assign('page', $page->show());
        $this->assign('products', $products);
    }

    /**
     * 仓库中的商品
     */
    private function _soldout_goods_list()
    {
        $product = M('Product');
        $product_group = M('Product_group');
        $product_to_group = M('Product_to_group');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
        $group_id = isset($_POST['group_id']) ? trim($_POST['group_id']) : '';

        $where = array();
        //$where['store_id'] = $this->store_session['store_id'];
        if ($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        if ($group_id) {
            $products = $product_to_group->getProducts($group_id);
            $product_ids = array();
            if (!empty($products)) {
                foreach ($products as $item) {
                    $product_ids[] = $item['product_id'];
                }
            }
            $where['product_id'] = array('in', $product_ids);
        }
        $product_total = $product->getSoldoutTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        $products = $product->getSoldout($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);

        $product_groups = $product_group->get_all_list($this->store_session['store_id']);

        $this->assign('product_groups', $product_groups);
        $this->assign('product_groups_json', json_encode($product_groups));
        $this->assign('page', $page->show());
        $this->assign('products', $products);
    }

    /**
     * 修改商品详细
     */
    private function _edit_content($id)
    {
        $cat_list = M('Product_category')->getAllCategory(0, true);
        $this->assign('cat_list', $cat_list);

        /**
         * @var $product_sku product_sku_model
         * @var $product_property product_property_model
         * @var $product_property_value product_property_value_model
         * @var $product_to_property product_to_property_model
         * @var $product_to_property_value product_to_property_value_model
         */
        $product = M('Product');
        $product_image = M('Product_image');
        $product_sku = M('Product_sku');
        $product_property = M('Product_property');
        $product_property_value = M('Product_property_value');
        $product_to_property = M('Product_to_property');
        $product_to_property_value = M('Product_to_property_value');
        $postage_template = M('Postage_template');
        $product_custom_field = M('Product_custom_field');

        /**
         * @var product_category_model $product_category
         */
        $product_category = M('Product_category');

        $agent = D('Agent');
        $allOpenSelfAgents = $agent->where('open_self <> 0 and status=1')->select();
        $allOpenSelfAgentIds = array();
        foreach ($allOpenSelfAgents as $a) $allOpenSelfAgentIds[] = $a['agent_id'];

        $store = D('Store');
        $supplierStoreInfo = $store->where(array('agent_id' => array('in', $allOpenSelfAgentIds)))->select();

        //var_dump($storeInfo);exit;

        $this->assign('supplierStoreInfo', $supplierStoreInfo);

        //var_dump($allOpenSelfAgents);exit;

        $product = $product->get(array('product_id' => $id/*, 'store_id' => $this->store_session['store_id']*/));
        $this->assign('product', $product);
        if (empty($product)) {
            exit('当前商品不存在！');
        }

        //商品分类
        if (!empty($product['category_id'])) {
            $category = $product_category->getCategory($product['category_id']);
            if ($category['cat_fid']) {
                $parent_category = $product_category->getCategory($category['cat_fid']);
            } else {
                $parent_category = array();
            }
            if (!empty($parent_category)) {
                $this->assign('category_name', $parent_category['cat_name'] . ' - ' . $category['cat_name']);
            } else {
                $this->assign('category_name', $category['cat_name']);
            }
        } else if (!empty($product['category_fid'])) {
            $parent_category = $product_category->getCategory($product['category_fid']);
            $this->assign('category_name', $parent_category['cat_name']);
            $category = $parent_category;
        }
        if (!empty($product['category_id'])) {
            $this->assign('category_id', $product['category_id']);
        } else {
            $this->assign('category_id', $product['category_fid']);
        }
        $this->assign('category', $category);
        $this->assign('parent_category', $parent_category);

        // 商品分组
        if ($product['has_category']) {
            $group_groups = D('')->field('`pg`.`group_id`,`pg`.`group_name`')->table(array('Product_group' => 'pg',
                'Product_to_group' => 'ptg'))
                ->where("`pg`.`group_id`=`ptg`.`group_id` AND `product_id`='$id'")->select();
            $this->assign('group_groups', $group_groups);
        }

        if ($product['has_custom']) {
            $customField =
                M('Custom_field')->get_field($this->store_session['store_id'], 'good', $product['product_id']);
            $this->assign('customField', json_encode($customField));
        }

        //商品图片
        $tmp_images = $product_image->getImages($id);
        $images = array();
        foreach ($tmp_images as $tmp_image) {
            $images[] = array(
                'image_id' => $tmp_image['image_id'],
                'image' => $tmp_image['image'],
            );
        }
        $this->assign('images', $images);

        // 运费模板
        if (!empty($product['postage_template_id'])) {
            $postage_template =
                $postage_template->get_tpl($product['postage_template_id'], $this->store_session['store_id']);
            $this->assign('postage_template', $postage_template);
        }

        // 自定义字段
        $fields = $product_custom_field->getFields($id);
        $this->assign('fields', $fields);

        //商品库存信息
        $skus = $product_sku->getSkus($id);
        $skuData = array();
        foreach ($skus as $k) {
            $skuData[str_replace(';', '_', str_replace(':', '_', $k['properties']))] = $k;
        }
        $this->assign('sku_data', json_encode($skuData));

        $skuSubGroup = "";
        $html = "";

        if ($product['supplier_id']) { //分销商品
            //$id = $product['source_product_id'];
            //$pids = $product_to_property->getPids($product['supplier_id'], $id);
            //$pids2 = $product_to_property->getPids($this->store_session['store_id'], $id);
            //if(!empty($pids[0]['pid'])) {
            //	$pid = $pids[0]['pid'];
            //	$name = $product_property->getName($pid);
            //	$vids = $product_to_property_value->getVids($product['supplier_id'], $id, $pid);
            //	if(!empty($pids[1]['pid']) && !empty($pids[2]['pid'])) {
            //		$pid1 = $pids[1]['pid'];
            //		$name1 = $product_property->getName($pid1);
            //		$vids1 = $product_to_property_value->getVids($product['supplier_id'], $id, $pid1);
            //		$pid2 = $pids[2]['pid'];
            //		$name2 = $product_property->getName($pid2);
            //		$vids2 = $product_to_property_value->getVids($product['supplier_id'], $id, $pid2);
            //		$html = '<thead>';
            //		$html .= '    <tr>';
            //		$html .= '        <th class="text-center">' . $name . '</th>';
            //		$html .= '        <th class="text-center">' . $name1 . '</th>';
            //		$html .= '        <th class="text-center">' . $name2 . '</th>';
            //		$html .= '        <th class="th-price text-right">价格（元）</th>';
            //		$html .= '        <th class="th-stock text-right">成本（元）</th>';
            //		$html .= '        <th class="text-right">建议售价（元）</th>';
            //		$html .= '    </tr>';
            //		$html .= '</thead>';
            //		$html .= '<tbody>';
            //		foreach ($vids as $key => $vid) {
            //			$value = $product_property_value->getValue($pid, $vid['vid']);
            //			foreach ($vids1 as $key1 => $vid1) {
            //				$value1 = $product_property_value->getValue($pid1, $vid1['vid']);
            //				foreach ($vids2 as $key2 => $vid2) {
            //					$properties =
            //						$pid . ':' . $vid['vid'] . ';' . $pid1 . ':' . $vid1['vid'] . ';' . $pid2 . ':' .
            //						$vid2['vid'];
            //					$sku = $product_sku->getSku($id, $properties);
            //					$sku2 = $product_sku->getSku($product['product_id'], $properties);
            //					$html .= '    <tr class="sku" sku-id="' . $sku2['sku_id'] . '" properties="' .
            //						$sku['properties'] . '">';
            //					$value2 = $product_property_value->getValue($pid2, $vid2['vid']);
            //					if($key1 == 0 && $key2 == 0) {
            //						$html .=
            //							'    <td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' .
            //							$value . '</td>';
            //					}
            //					if($key2 == 0) {
            //						$html .= '    <td class="text-center" rowspan="' . count($vids2) . '">' . $value1 .
            //							'</td>';
            //					}
            //					$html .= '        <td class="text-center">' . $value2 . '</td>';
            //					if(!empty($product['unified_price_setting'])) { //供货商统一定价
            //						$html .= '        <td class="text-right">' . $sku2['price'] .
            //							'<input type="hidden" name="sku_price" class="js-price input-mini" value="' .
            //							$sku2['price'] . '" /></td>';
            //					}
            //					else {
            //						$html .=
            //							'        <td class="text-right"><input type="text" name="sku_price" class="js-price input-mini" data-min-price="' .
            //							$sku['min_fx_price'] . '" data-max-price="' . $sku['max_fx_price'] .
            //							'" value="' . $sku2['price'] . '" maxlength="10"></td>';
            //					}
            //					$html .= '        <td class="text-right">' . $sku['cost_price'] .
            //						'<input type="hidden" name="stock_num" class="js-stock-num input-mini" value="' .
            //						$sku2['quantity'] . '" /></td>';
            //
            //					$html .= '        <td class="text-right">' . $sku['min_fx_price'] . ' - ' .
            //						$sku['max_fx_price'] .
            //						'<input type="hidden" name="code" class="js-code input-small" value="' .
            //						$sku2['code'] . '" /></td>';
            //					$html .= '    </tr>';
            //				}
            //			}
            //		}
            //	}
            //	else if(!empty($pids[1]['pid'])) {
            //		$pid1 = $pids[1]['pid'];
            //		$name1 = $product_property->getName($pid1);
            //		$vids1 = $product_to_property_value->getVids($product['supplier_id'], $id, $pid1);
            //		$html = '<thead>';
            //		$html .= '    <tr>';
            //		$html .= '        <th class="text-center">' . $name . '</th>';
            //		$html .= '        <th class="text-center">' . $name1 . '</th>';
            //		$html .= '        <th class="th-price text-right">价格（元）</th>';
            //		$html .= '        <th class="th-stock text-right">成本（元）</th>';
            //		$html .= '        <th class="text-right">建议售价（元）</th>';
            //		$html .= '    </tr>';
            //		$html .= '</thead>';
            //		$html .= '<tbody>';
            //		foreach ($vids as $key => $vid) {
            //			$value = $product_property_value->getValue($pid, $vid['vid']);
            //			foreach ($vids1 as $key1 => $vid1) {
            //				$properties = $pid . ':' . $vid['vid'] . ';' . $pid1 . ':' . $vid1['vid'];
            //				$sku = $product_sku->getSku($id, $properties);
            //				$sku2 = $product_sku->getSku($product['product_id'], $properties);
            //				$html .= '    <tr class="sku" sku-id="' . $sku2['sku_id'] . '" properties="' .
            //					$sku['properties'] . '">';
            //				$value1 = $product_property_value->getValue($pid1, $vid1['vid']);
            //				if($key1 == 0) {
            //					$html .= '    <td class="text-center" rowspan="' . count($vids1) . '">' . $value .
            //						'</td>';
            //				}
            //				$html .= '        <td class="text-center">' . $value1 . '</td>';
            //				if(!empty($product['unified_price_setting'])) { //供货商统一定价
            //					$html .= '        <td class="text-right">' . $sku2['price'] .
            //						'<input type="hidden" name="sku_price" class="js-price input-mini" value="' .
            //						$sku2['price'] . '" /></td>';
            //				}
            //				else {
            //					$html .=
            //						'        <td class="text-right"><input type="text" name="sku_price" class="js-price input-mini" data-min-price="' .
            //						$sku['min_fx_price'] . '" data-max-price="' . $sku['max_fx_price'] . '" value="' .
            //						$sku2['price'] . '" maxlength="10"></td>';
            //				}
            //				$html .= '        <td class="text-right">' . $sku['cost_price'] .
            //					'<input type="hidden" name="stock_num" class="js-stock-num input-mini" value="' .
            //					$sku2['quantity'] . '" /></td>';
            //				$html .= '        <td class="text-right">' . $sku['min_fx_price'] . ' - ' .
            //					$sku['max_fx_price'] .
            //					'<input type="hidden" name="code" class="js-code input-small" value="' . $sku2['code'] .
            //					'" /></td>';
            //				$html .= '    </tr>';
            //			}
            //		}
            //	}
            //	else {
            //		$html = '<thead>';
            //		$html .= '    <tr>';
            //		$html .= '        <th class="text-center">' . $name . '</th>';
            //		$html .= '        <th class="th-price text-right">价格（元）</th>';
            //		$html .= '        <th class="th-stock text-right">成本（元）</th>';
            //		$html .= '        <th class="text-right">建议售价（元）</th>';
            //		$html .= '    </tr>';
            //		$html .= '</thead>';
            //		$html .= '<tbody>';
            //		foreach ($vids as $key => $vid) {
            //			$value = $product_property_value->getValue($pid, $vid['vid']);
            //			$properties = $pid . ':' . $vid['vid'];
            //			$sku = $product_sku->getSku($id, $properties);
            //			$sku2 = $product_sku->getSku($product['product_id'], $properties);
            //			$html .=
            //				'<tr class="sku" sku-id="' . $sku2['sku_id'] . '" properties="' . $sku['properties'] .
            //				'">';
            //			$value = $product_property_value->getValue($pid, $vid['vid']);
            //			$html .= '<td class="text-center">' . $value . '</td>';
            //			if(!empty($product['unified_price_setting'])) { //供货商统一定价
            //				$html .= '<td class="text-right">' . $sku2['price'] .
            //					'<input type="hidden" name="sku_price" class="js-price input-mini" value="' .
            //					$sku2['price'] . '" /></td>';
            //			}
            //			else {
            //				$html .=
            //					'<td class="text-right"><input type="text" name="sku_price" class="js-price input-mini" data-min-price="' .
            //					$sku['min_fx_price'] . '" data-max-price="' . $sku['max_fx_price'] . '" value="' .
            //					$sku2['price'] . '" maxlength="10"></td>';
            //			}
            //			$html .= '<td class="text-right">' . $sku['cost_price'] .
            //				'<input type="hidden" name="stock_num" class="js-stock-num input-mini" value="' .
            //				$sku2['quantity'] . '" /></td>';
            //			$html .= '<td class="text-right">' . $sku['min_fx_price'] . ' - ' . $sku['max_fx_price'] .
            //				'<input type="hidden" name="code" class="js-code input-small" value="' . $sku2['code'] .
            //				'" /></td>';
            //			$html .= '    </tr>';
            //		}
            //	}
            //	$html .= '</tbody>';
            //	if(empty($product['unified_price_setting'])) { //供货商统一定价
            //		$html .= '<tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-price" href="javascript:;">价格</a></span><span class="js-batch-form" style="display:none;"><input type="text" class="js-batch-txt input-mini" placeholder=""> <a class="js-batch-save fx-product" href="javascript:;">保存</a> <a class="js-batch-cancel" href="javascript:;">取消</a><p class="help-desc"></p></span></div></td></tr></tfoot>';
            //	}
            //}
        } else {
            $props = $product_to_property->getProperties($this->store_session['store_id'], $id);

            if (count($props) > 0) {
                foreach ($props as $k => $v) {
                    $skuSubGroup .= '<div class="sku-sub-group">';
                    $skuSubGroup .= '<h3 class="sku-group-title">';
                    $skuSubGroup .=
                        '<div class="select2-container js-sku-name" style="width:180px;" data-id="' . $v['pid'] . '">';
                    $skuSubGroup .=
                        '<a href="javascript:void(0)" onclick="return false;" class="select2-choice" tabindex="-1"> <span class="select2-chosen">' .
                        $v['name'] . '</span>';
                    $skuSubGroup .= '<abbr class="select2-search-choice-close"></abbr> <span class="select2-arrow"> <b></b> </span> </a> </div>';
                    $skuSubGroup .= '<a class="js-remove-sku-group remove-sku-group">×</a> </h3>';
                    $skuSubGroup .= '<div class="js-sku-atom-container sku-group-cont">';
                    $skuSubGroup .= '<div>';
                    $skuSubGroup .= '<div data-cid="" class="js-sku-atom-list sku-atom-list">';

                    $values = $product_to_property_value->getValues($this->store_session['store_id'], $id, $v['pid']);
                    foreach ($values as $key => $val) {
                        $skuSubGroup .= '<div class="sku-atom" style="width:50px;" id="r_' . $v['pid'] . '' .
                            $val['vid'] . '" rid="' . $v['pid'] . '' . $val['vid'] . '" atom-id="' . $val['vid'] .
                            '"> <span>' . $val['value'] . '</span>';
                        $skuSubGroup .= '<div class="close-modal small js-remove-sku-atom">×</div>';
                        $skuSubGroup .=
                            '<div class="property_value_img" style="cursor:pointer;width:48px; height:48px; line-height:48px; border:1px solid gray;" data-vid="' .
                            $val['vid'] . '">';
                        $skuSubGroup .= empty($val['image']) ? '添加图片'
                            : '<img src="' . getAttachmentUrl($val['image']) . '" width="48" height="48">';
                        $skuSubGroup .= '</div></div>';
                    }
                    $skuSubGroup .= '</div><a href="javascript:;" class="js-add-sku-atom add-sku">+添加</a> </div></div></div>';

                }
                //var_dump($props);
            }

            $pids = $product_to_property->getPids($this->store_session['store_id'], $id, '*');
            if (!empty($pids[0]['pid'])) {
                $html = '<thead><tr>';

                $thCode = '<th class="th-price text-right">价格（元）</th>' .
                    '<th class="th-market text-right">市场（元）</th>' .
                    '<th class="th-cost text-right">成本（元）</th>' .
                    '<th class="th-factory text-right">厂价（元）</th>' .
                    '<th class="th-stock text-right">库存</th>' .
                    '<th class="th-stock text-right">重量（克）</th>' .
                    '<th class="th-code">商家编码</th>' .
                    '<th class="text-right">销量</th>' .
                    '</tr>' .
                    '</thead>' .
                    '<tbody>';

                function tdCode($sku)
                {
                    return
                        '<td class="text-right"><input type="text" name="sku_price" class="js-price input-mini" value="' .
                        $sku['price'] . '" maxlength="10"></td>' .
                        '<td class="text-right"><input type="text" name="sku_market" class="js-market input-mini" value="' .
                        $sku['market_price'] . '" maxlength="10"></td>' .
                        '<td class="text-right"><input type="text" name="sku_cost" class="js-cost input-mini" value="' .
                        $sku['cost_price'] . '" maxlength="10"></td>' .
                        '<td class="text-right"><input type="text" name="sku_factory" class="js-factory input-mini" value="' .
                        $sku['factory_price'] . '" maxlength="10"></td>' .
                        '<td class="text-right"><input type="text" name="stock_num" class="js-stock-num input-mini" value="' .
                        $sku['quantity'] . '" maxlength="9"></td>' .
                        '<td class="text-right"><input type="text" name="sku_weight" class="js-sku_weight input-mini" value="' .
                        $sku['weight'] . '" maxlength="9"></td>' .
                        '<td><input type="text" name="code" class="js-code input-mini" value="' .
                        $sku['code'] . '"></td>' .
                        '<td class="text-right">' . $sku['sales'] . '</td>' .
                        '</tr>';
                }

                $pid = $pids[0]['pid'];
                $name = $product_property->getName($pid);
                $vids = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid);

                if (!empty($pids[1]['pid']) && !empty($pids[2]['pid'])) {
                    $pid1 = $pids[1]['pid'];
                    $name1 = $product_property->getName($pid1);
                    $vids1 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid1);
                    $pid2 = $pids[2]['pid'];
                    $name2 = $product_property->getName($pid2);
                    $vids2 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid2);

                    $html .= '<th class="text-center">' . $name . '</th>';
                    $html .= '<th class="text-center">' . $name1 . '</th>';
                    $html .= '<th class="text-center">' . $name2 . '</th>';

                    $html .= $thCode;

                    foreach ($vids as $key => $vid) {
                        $value = $product_property_value->getValue($pid, $vid['vid']);
                        foreach ($vids1 as $key1 => $vid1) {
                            $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                            foreach ($vids2 as $key2 => $vid2) {
                                $properties =
                                    $pid . ':' . $vid['vid'] . ';' . $pid1 . ':' . $vid1['vid'] . ';' . $pid2 . ':' .
                                    $vid2['vid'];
                                $sku = $product_sku->getSku($id, $properties);
                                $html .= '<tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' .
                                    $sku['properties'] . '">';
                                $value2 = $product_property_value->getValue($pid2, $vid2['vid']);
                                if ($key1 == 0 && $key2 == 0) {
                                    $html .=
                                        '<td class="text-center" rowspan="' . count($vids1) * count($vids2) . '">' .
                                        $value . '</td>';
                                }
                                if ($key2 == 0) {
                                    $html .= '<td class="text-center" rowspan="' . count($vids2) . '">' . $value1 .
                                        '</td>';
                                }
                                $html .= '        <td class="text-center">' . $value2 . '</td>';

                                $html .= tdCode($sku);
                            }
                        }
                    }
                    $html .= '</tbody><tfoot><tr><td colspan="6"><div class="batch-opts">批量设置： <span class="js-batch-type"><a class="js-batch-price" href="javascript:;">价格</a>&nbsp;<a class="js-batch-market" href="javascript:;">市场</a>&nbsp;<a class="js-batch-cost" href="javascript:;">成本</a>&nbsp;<a class="js-batch-factory" href="javascript:;">厂价</a>&nbsp;<a class="js-batch-stock" href="javascript:;">库存</a>&nbsp;<a class="js-batch-weight" href="javascript:;">重量</a></span><span class="js-batch-form" style="display:none;"><input type="text" class="js-batch-txt input-mini" placeholder=""><a class="js-batch-save" href="javascript:;">保存</a> <a class="js-batch-cancel" href="javascript:;">取消</a><p class="help-desc"></p></span></div></td></tr></tfoot>';
                } else if (!empty($pids[1]['pid'])) {
                    $pid1 = $pids[1]['pid'];
                    $name1 = $product_property->getName($pid1);
                    $vids1 = $product_to_property_value->getVids($this->store_session['store_id'], $id, $pid1);
                    $html .= '<th class="text-center">' . $name . '</th>' .
                        '<th class="text-center">' . $name1 . '</th>';

                    $html .= $thCode;

                    foreach ($vids as $key => $vid) {
                        $value = $product_property_value->getValue($pid, $vid['vid']);
                        foreach ($vids1 as $key1 => $vid1) {
                            $properties = $pid . ':' . $vid['vid'] . ';' . $pid1 . ':' . $vid1['vid'];
                            //echo  $properties;
                            //echo '</br>';
                            $sku = $product_sku->getSku($id, $properties);
                            //var_dump($sku);
                            $html .= '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' .
                                $sku['properties'] . '">';
                            $value1 = $product_property_value->getValue($pid1, $vid1['vid']);
                            if ($key1 == 0) {
                                $html .= '<td class="text-center" rowspan="' . count($vids1) . '">' . $value . '</td>';
                            }
                            $html .= '<td class="text-center">' . $value1 . '</td>';

                            $html .= tdCode($sku);
                        }
                    }
                } else {
                    $html .= '<th class="text-center">' . $name . '</th>';

                    $html .= $thCode;

                    foreach ($vids as $key => $vid) {
                        //$value = $product_property_value->getValue($pid, $vid['vid']);
                        $properties = $pid . ':' . $vid['vid'];
                        $sku = $product_sku->getSku($id, $properties);
                        $html .=
                            '    <tr class="sku" sku-id="' . $sku['sku_id'] . '" properties="' . $sku['properties'] .
                            '">';
                        $value = $product_property_value->getValue($pid, $vid['vid']);
                        $html .= '        <td class="text-center">' . $value . '</td>';

                        $html .= tdCode($sku);
                    }
                }
            }

            $html .= '</tbody>' .
                '<tfoot>' .
                '<tr>' .
                '<td colspan="6">' .
                '<div class="batch-opts">批量设置：' .
                '<span class="js-batch-type">' .
                '<a class="js-batch-price" href="javascript:;">价格</a>&nbsp;' .
                '<a class="js-batch-market" href="javascript:;">市场</a>&nbsp;' .
                '<a class="js-batch-cost" href="javascript:;">成本</a>&nbsp;' .
                '<a class="js-batch-factory" href="javascript:;">厂价</a>&nbsp;' .
                '<a class="js-batch-stock" href="javascript:;">库存</a>&nbsp;' .
                '<a class="js-batch-weight" href="javascript:;">重量</a>' .
                '</span>' .
                '<span class="js-batch-form" style="display:none;">' .
                '<input type="text" class="js-batch-txt input-mini" placeholder=""> <a class="js-batch-save" href="javascript:;">保存</a> ' .
                '<a class="js-batch-cancel" href="javascript:;">取消</a>' .
                '<p class="help-desc"></p>' .
                '</span>' .
                '</div>' .
                '</td>' .
                '</tr>' .
                '</tfoot>';
        }
        $this->assign('prop_content', $skuSubGroup);
        $this->assign('sku_content', $html);
    }

    //保存扫码活动
    public function save_qrcode_activity()
    {
        $activity = M('Product_qrcode_activity');

        $data = array();
        $data['buy_type'] = isset($_POST['buy_type']) ? intval($_POST['buy_type']) : 0;
        $data['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
        $data['discount'] = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
        $data['price'] = isset($_POST['price']) ? floatval($_POST['price']) : 0;
        if (isset($_POST['activity_id'])) {
            $where = array();
            $where['store_id'] = $this->store_session['store_id'];
            $where['product_id'] = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
            $where['id'] = isset($_POST['activity_id']) ? intval(trim($_POST['activity_id'])) : 0;
            if ($activity->save($where, $data)) {
                $activity = $activity->getActivity($where['store_id'], $where['product_id'], $where['id']);
                echo json_encode(array('code' => 1, 'type' => 'edit', 'msg' => '保存成功', 'data' => $activity));
            } else {
                echo json_encode(array('code' => 0, 'type' => 'edit', 'msg' => '保存失败', 'data' => array()));
            }
        } else {
            $data['store_id'] = $this->store_session['store_id'];
            $data['product_id'] = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
            if ($activity_id = $activity->add($data)) {
                $activity = $activity->getActivity($data['store_id'], $data['product_id'], $activity_id);
                echo json_encode(array('code' => 1, 'type' => 'add', 'msg' => '保存成功', 'data' => $activity));
            } else {
                echo json_encode(array('code' => 0, 'type' => 'add', 'msg' => '保存失败', 'data' => array()));
            }
        }
        exit;
    }

    //获取扫码活动
    public function get_qrcode_activity()
    {
        $activity = M('Product_qrcode_activity');

        $product_id = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;

        $activities = $activity->getActivities($this->store_session['store_id'], $product_id);
        if (!empty($activities)) {
            echo json_encode($activities);
        } else {
            echo false;
        }
        exit;
    }

    //删除扫码活动
    public function del_qrcode_activity()
    {
        $activity = M('Product_qrcode_activity');

        $product_id = isset($_POST['product_id']) ? intval(trim($_POST['product_id'])) : 0;
        $activity_id = isset($_POST['activity_id']) ? intval(trim($_POST['activity_id'])) : 0;

        if ($activity->delete($this->store_session['store_id'], $product_id, $activity_id)) {
            json_return(0, '删除成功');
        } else {
            json_return(1001, '删除失败');
        }
    }

    //删除商品
    public function del_product()
    {
        /**
         * @var $product product_model
         */
        $product = M('Product');

        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
        if (!empty($product_id) && !is_array($product_id)) {
            $product_id = array($product_id);
        }
        if (!empty($product_id)) {
            foreach ($product_id as $id) {
                $product_info = D('Product')->field('product_id,is_fx,fx_type,source_product_id,original_product_id')
                    ->where(array('product_id' => $id))->find();
                if ($product->delete($this->store_session['store_id'], $product_id)) {
                    if (!empty($product_info['is_fx']) && empty($product_info['original_product_id'])) { //供货商
                        //$product->delFxProduct(array('original_product_id' => $product_info['product_id']));
                        $this->_delFxProduct($product_info['product_id']);
                    } else if (!empty($product_info['source_product_id'])) { //分销商
                        $this->_delFxProduct($product_info['product_id']);
                    }
                }
            }
            $this->_sync_wei_page_goods($product_id); //同步微页面商品
            json_return(0, '删除成功');
        } else {
            json_return(1001, '删除失败');
        }
    }

    //设置商品排序
    public function set_sort()
    {
        if (IS_POST) {
            $id = isset($_POST['id']) ? intval(trim($_POST['id'])) : 0;
            $sort = isset($_POST['sort']) ? intval(trim($_POST['sort'])) : 0;
            if (D('Product')->where(array('product_id' => $id, 'store_id' => $this->store_session['store_id']))
                ->data(array('sort' => $sort))->save()
            ) {
                json_return(0, '排序成功');
            } else {
                json_return(1001, '排序失败');
            }
        }
    }

    //同步微页面商品
    private function _sync_wei_page_goods($product_id, $store_id = '')
    {
        $product_id = !is_array($product_id) ? array($product_id) : $product_id;
        //删除微页面的商品
        if (empty($store_id)) {
            $store_id = $this->store_session['store_id'];
        }
        $fields = D('Custom_field')->where(array('store_id' => $store_id, 'field_type' => 'goods'))->select();
        if ($fields) {
            foreach ($fields as $field) {
                $products = unserialize($field['content']);
                if (!empty($products) && !empty($products['goods'])) {
                    $new_products = array();
                    foreach ($products['goods'] as $product) {
                        if (!in_array($product['id'], $product_id)) {
                            $new_products[] = $product;
                        }
                    }
                    $products['goods'] = $new_products;
                    $content = serialize($products);
                    D('Custom_field')->where(array('field_id' => $field['field_id']))
                        ->data(array('content' => $content))->save();
                }
            }
        }
    }

    //删除分销商品
    private function _delFxProduct($product_id)
    {
        $products = D('Product')->where(array('source_product_id' => $product_id))->select();
        if (!empty($products)) {
            foreach ($products as $product) {
                D('Product')->where(array('product_id' => $product['product_id']))->data(array('status' => 2))->save();
                $this->_sync_wei_page_goods($product['product_id'], $product['store_id']);
                $this->_delFxProduct($product['product_id']);
            }
        }
    }

    //下架分销商品
    private function _soldoutFxProduct($product_id)
    {
        $products = D('Product')->where(array('source_product_id' => $product_id))->select();
        if (!empty($products)) {
            foreach ($products as $product) {
                D('Product')->where(array('product_id' => $product['product_id']))->data(array('status' => 2))->save();
                $this->_sync_wei_page_goods($product['product_id'], $product['store_id']);
                $this->_soldoutFxProduct($product['product_id']);
            }
        }
    }

    //递归处理查找父元素
    private function searchParent($cat_id)
    {
        static $arr = array();
        $res = D('Product_category')->where(array('cat_id' => $cat_id))->find();

        if (!empty($res['filter_attr'])) {
            $arr[$res['filter_attr']] = $res['cat_name'];
        }

        if (stripos($res['filter_attr'], ',') !== false) {
            foreach (explode(',', $res['filter_attr']) as $v) {
                $arr[$v] = $res['cat_name'];
            }
            unset($arr[$res['filter_attr']]);
        }

        if ($res['cat_fid'] != 0) {
            $this->searchParent($res['cat_fid']);
        }

        return $arr;
    }

    // 属性值上传图片
    public function property_value_img()
    {
        $vid = $_GET['vid'];
        $image = $_GET['image'];

        if (empty($vid)) {
            json_return(1001, '缺少参数');
        }

        if (empty($image)) {
            json_return(1001, '请上传图片');
        }

        $image = getAttachment($image);

        $product_property_value = D('Product_property_value')->where(array('vid' => $vid))->find();
        if (empty($product_property_value)) {
            json_return(1001, '未找到相应的属性值');
        }

        D('Product_property_value')->where(array('vid' => $vid))->data(array('image' => $image))->save();

        json_return(0, '上传完成');
    }

    /*
           if (IS_POST) {
                $product = M('Product');
                $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();

                if (!empty($product_id)) {
                    foreach ($product_id as $id) {
                        $product_info = D('Product')->field('product_id,is_fx,fx_type,source_product_id,original_product_id')->where(array('product_id' => $id))->find();
                        if ($product->soldout($this->store_session['store_id'], array($id))) {
                            if (!empty($product_info['is_fx']) && empty($product_info['original_product_id'])) { //供货商
                                $this->_soldoutFxProduct($product_info['product_id']);
                            } else if (!empty($product_info['source_product_id'])) { //分销商
                                $this->_soldoutFxProduct($product_info['product_id']);
                            }
                        }
                    }
                    $this->_sync_wei_page_goods($product_id); //同步微页面商品
                    json_return(0, '商品下架成功');
                } else {
                    json_return(1001, '商品下架失败');
                }
                exit;
            }

    */
    public function comments_load()
    {
        if (empty($_POST['page']))
            pigcms_tips('非法访问！', 'none');
        if ($_POST['page'] == 'create_content') {
            $cat_list = M('Product_category')->getAllCategory();
            $this->assign('cat_list', $cat_list);
        }
        if ($_POST['page'] == 'edit_content') {
            $this->_edit_content($_GET['id']);
        }
        //商品分组列表
        if ($_POST['page'] == 'category_content') {
            $group_list = M('Product_group')->get_list($this->store_session['store_id']);
            $this->assign('group_list', $group_list);
        }
        //商品分组编辑
        if ($_POST['page'] == 'category_edit') {
            $now_group = M('Product_group')->get_group($this->store_session['store_id'], $_POST['group_id']);
            if (!empty($now_group)) {
                if ($now_group['has_custom']) {
                    $customField = M('Custom_field')->get_field($this->store_session['store_id'], 'good_cat',
                        $now_group['group_id']);
                    $this->assign('customField', json_encode($customField));
                }
                $this->assign('now_group', $now_group);
            } else {
                exit('当前分组不存在！');
            }
        }


        if ($_POST['page'] == 'comment_goods_content') {
            $this->_comment_goods_list();
        }
        if ($_POST['page'] == 'comment_store_content') {
            $this->_comment_store_list();
        }
        $this->display($_POST['page']);
    }

    //商品评论
    public function product_comment()
    {
        if (IS_POST) {
            $product = M('Product');
            $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();

            if (!empty($product_id)) {
                foreach ($product_id as $id) {
                    $product_info =
                        D('Product')->field('product_id,is_fx,fx_type,source_product_id,original_product_id')
                            ->where(array('product_id' => $id))->find();
                    if ($product->soldout($this->store_session['store_id'], array($id))) {
                        if (!empty($product_info['is_fx']) && empty($product_info['original_product_id'])) { //供货商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        } else if (!empty($product_info['source_product_id'])) { //分销商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        }
                    }
                }
                $this->_sync_wei_page_goods($product_id); //同步微页面商品
                json_return(0, '商品下架成功');
            } else {
                json_return(1001, '商品下架失败');
            }
            exit;
        }


        $this->display();
    }


    //店铺评论
    public function store_comment()
    {
        if (IS_POST) {
            $product = M('Product');
            $product_id = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array();

            if (!empty($product_id)) {
                foreach ($product_id as $id) {
                    $product_info =
                        D('Product')->field('product_id,is_fx,fx_type,source_product_id,original_product_id')
                            ->where(array('product_id' => $id))->find();
                    if ($product->soldout($this->store_session['store_id'], array($id))) {
                        if (!empty($product_info['is_fx']) && empty($product_info['original_product_id'])) { //供货商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        } else if (!empty($product_info['source_product_id'])) { //分销商
                            $this->_soldoutFxProduct($product_info['product_id']);
                        }
                    }
                }
                $this->_sync_wei_page_goods($product_id); //同步微页面商品
                json_return(0, '商品下架成功');
            } else {
                json_return(1001, '商品下架失败');
            }
            exit;
        }


        $this->display();
    }

    /**
     * 已评论的商品列表
     */
    private function _comment_goods_list()
    {
        $where['store_id'] = $this->store_session['store_id'];
        $where['delete_flg'] = 0;
        $where['type'] = 'PRODUCT';
        $comment_model = M('Comment');
        $count = $comment_model->getCount($where);

        if ($count > 0) {
            $limit = 15;
            import('source.class.user_page');
            $page = new Page($count, $limit);

            $comment_list = $comment_model->getList($where, 'id desc', $page->listRows, $page->firstRow, true);

            foreach ($comment_list['comment_list'] as $k => $v) {
                $product_new_arr[] = $v['relation_id'];
            }

            $in_array = $product_new_arr;

            $produdcts = D('Product')->where(array('product_id' => array('in', $in_array)))->select();
            if (is_array($produdcts)) {
                foreach ($produdcts as $k => $v) {
                    $product_arr[$v['product_id']] = $v;
                }
            }
            $this->assign('page', $page->show());
            $this->assign('comments', $comment_list);
            $this->assign('product_arr', $product_arr);
        }


    }


    /**
     * 已评论的店铺列表
     */
    private function _comment_store_list()
    {
        $where['store_id'] = $this->store_session['store_id'];
        $where['delete_flg'] = 0;
        $where['type'] = 'STORE';
        $comment_model = M('Comment');
        $count = $comment_model->getCount($where);

        if ($count > 0) {
            $limit = 15;
            import('source.class.user_page');
            $page = new Page($count, $limit);

            $comment_list = $comment_model->getList($where, 'id desc', $page->listRows, $page->firstRow, true);

            foreach ($comment_list['comment_list'] as $k => $v) {
                $product_new_arr[] = $v['relation_id'];
            }

            $in_array = $product_new_arr;

            $stores = D('Store')->where(array('Store_id' => array('in', $in_array)))->select();
            if (is_array($stores)) {
                foreach ($stores as $k => $v) {
                    $store_arr[$v['store_id']] = $v;
                }
            }
            $this->assign('page', $page->show());
            $this->assign('comments', $comment_list);
            $this->assign('store_arr', $store_arr);
        }


    }


    //删除评论
    public function del_comment()
    {
        $comment = M('Comment');
        $comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : 0;
        if (!empty($comment_id) && !is_array($comment_id)) {
            $comment_id = array($comment_id);
        }
        if (!empty($comment_id)) {

            foreach ($comment_id as $id) {
                $comment->delete(array('id' => $id));
            }
            json_return(0, '删除成功');
        } else {
            json_return(1001, '删除失败');
        }
    }

    //修改审核状态
    public function set_comment_status()
    {
        $comment = M('Comment');
        $comment_id = isset($_POST['id']) ? $_POST['id'] : 0;
        $status = $_POST['status'];


        if (empty($status) || empty($comment_id)) {
            json_return(1001, '修改失败');

        }

        $data = array('status' => $status);
        $where = array(
            'id' => $comment_id,
            'store_id' => $this->store_session['store_id']
        );

        $comment->save($data, $where);

        json_return(0, '修改成功');


    }

    public function view_supplier_order()
    {
        $this->display();
    }


    public function load()
    {
        $action = strtolower(trim($_POST['page']));

        $store_id = $this->store_session['store_id'];

        //var_dump($action);exit;

        if (empty($action)) pigcms_tips('非法访问！', 'none');
        switch ($action) {
            case 'supplier_order_content':

                $status = isset($_POST['status']) ? trim($_POST['status']) : ''; //订单状态
                $shipping_method = isset($_POST['shipping_method']) ? strtolower(trim($_POST['shipping_method'])) : ''; //运送方式 快递发货 上门自提
                $payment_method = isset($_POST['payment_method']) ? strtolower(trim($_POST['payment_method'])) : ''; //支付方式
                $type = isset($_POST['type']) ? $_POST['type'] : '*'; //订单类型 普通订单 代付订单
                $orderbyfield = isset($_POST['orderbyfield']) ? trim($_POST['orderbyfield']) : '';
                $orderbymethod = isset($_POST['orderbymethod']) ? trim($_POST['orderbymethod']) : '';
                $page = isset($_POST['p']) ? intval(trim($_POST['p'])) : 1;
                $order_no = isset($_POST['order_no']) ? trim($_POST['order_no']) : '';
                $trade_no = isset($_POST['trade_no']) ? trim($_POST['trade_no']) : '';
                $user = isset($_POST['user']) ? trim($_POST['user']) : ''; //收货人
                $tel = isset($_POST['tel']) ? trim($_POST['tel']) : ''; //收货人手机
                $time_type = isset($_POST['time_type']) ? trim($_POST['time_type']) : '';
                $start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '';
                $stop_time = isset($_POST['stop_time']) ? trim($_POST['stop_time']) : '';
                $weixin_user = isset($_POST['weixin_user']) ? trim($_POST['weixin_user']) : '';

                $data = array(
                    'status' => $status,
                    'orderbyfield' => $orderbyfield,
                    'orderbymethod' => $orderbymethod,
                    'page' => $page,
                    'order_no' => $order_no,
                    'trade_no' => $trade_no,
                    'user' => $user,
                    'tel' => $tel,
                    'time_type' => $time_type,
                    'start_time' => $start_time,
                    'stop_time' => $stop_time,
                    'weixin_user' => $weixin_user,
                    'type' => $type,
                    'payment_method' => $payment_method,
                    'shipping_method' => $shipping_method
                );

                $this->_supplier_order_content($data);
                break;

            case 'detail_content':
                $this->_view_detail_content();
                $action = 'view_order_detail_content';
                break;

            default:
                break;
        }
        $this->display($action);
    }


    private function _supplier_order_content($data)
    {
        $order = M('Order');
        $order_product = M('Order_product');
        $user = M('User');

        $where = array();
        if ($data['status'] != '*') {
            $where['status'] = intval($data['status']);
        } else { // 所有订单（不包含临时订单）
            $where['status'] = array('>=', 2);
        }
        if ($data['order_no']) {
            $where['order_no'] = $data['order_no'];
        }
        if (is_numeric($data['type'])) {
            $where['type'] = $data['type'];
        }
        if (!empty($data['user'])) {
            $where['address_user'] = $data['user'];
        }
        if (!empty($data['tel'])) {
            $where['address_tel'] = $data['tel'];
        }
        if (!empty($data['payment_method'])) {
            $where['payment_method'] = $data['payment_method'];
        }
        if (!empty($data['shipping_method'])) {
            $where['shipping_method'] = $data['shipping_method'];
        }
        $field = '';
        if (!empty($data['time_type'])) {
            $field = $data['time_type'];
        }
        if (!empty($data['start_time']) && !empty($data['stop_time']) && !empty($field)) {
            $where['_string'] = "`" . $field . "` >= " . strtotime($data['start_time']) . " AND `" . $field . "` <= " .
                strtotime($data['stop_time']);
        } else if (!empty($data['start_time']) && !empty($field)) {
            $where[$field] = array('>=', strtotime($data['start_time']));
        } else if (!empty($data['stop_time']) && !empty($field)) {
            $where[$field] = array('<=', strtotime($data['stop_time']));
        }
        //排序
        if (!empty($data['orderbyfield']) && !empty($data['orderbymethod'])) {
            $orderby = "`{$data['orderbyfield']}` " . $data['orderbymethod'];
        } else {
            $orderby = '`order_id` DESC';
        }
        $order_total = $order->getOrderTotal($where);
        import('source.class.user_page');
        $page = new Page($order_total, 15);
        $tmp_orders = $order->getOrders($where, $orderby, $page->firstRow, $page->listRows);

        $orders = array();
        foreach ($tmp_orders as $tmp_order) {
            $products = $order_product->getProducts($tmp_order['order_id']);
            $tmp_order['products'] = $products;
            if (empty($tmp_order['uid'])) {
                $tmp_order['is_fans'] = false;
                $tmp_order['buyer'] = '';
            } else {
                //$tmp_order['is_fans'] = $user->isWeixinFans($tmp_order['uid']);
                $tmp_order['is_fans'] = true;
                $user_info = $user->checkUser(array('uid' => $tmp_order['uid']));
                $tmp_order['buyer'] = $user_info['nickname'];
            }
            $is_supplier = false;
            if (!empty($tmp_order['suppliers'])) { //订单供货商
                $suppliers = explode(',', $tmp_order['suppliers']);
                if (in_array($this->store_session['store_id'], $suppliers)) {
                    $is_supplier = true;
                }
            }
            $tmp_order['is_supplier'] = $is_supplier;
            $has_my_product = false;
            foreach ($products as &$product) {
                $product['image'] = getAttachmentUrl($product['image']);
                if (empty($product['is_fx'])) {
                    $has_my_product = true;
                }
            }

            $tmp_order['products'] = $products;
            $tmp_order['has_my_product'] = $has_my_product;
            if (!empty($tmp_order['user_order_id'])) {
                $order_info =
                    D('Order')->field('store_id')->where(array('order_id' => $tmp_order['user_order_id']))->find();
                $seller = D('Store')->field('name')->where(array('store_id' => $order_info['store_id']))->find();
                $tmp_order['seller'] = $seller['name'];
            }
            $orders[] = $tmp_order;
        }

        //订单状态
        $order_status = $order->status();
        $this->assign('order_status', $order_status);

        //支付方式
        $payment_method = $order->getPaymentMethod();
        $this->assign('payment_method', $payment_method);

        $this->assign('status', $data['status']);
        $this->assign('orders', $orders);
        $this->assign('page', $page->show());
    }


    //订单详细
    public function view_order_detail()
    {
        $this->display();
    }

    //订单详细页面
    private function _view_detail_content()
    {
        $order = M('Order');
        $order_product = M('Order_product');
        $user = M('User');
        $package = M('Order_package');

        $order_id = isset($_POST['order_id']) ? intval(trim($_POST['order_id'])) : 0;
        $order = $order->getOrder($this->store_session['store_id'], $order_id);
        $products = $order_product->getProducts($order_id);
        if (empty($order['uid'])) {
            $order['is_fans'] = false;
            $is_fans = false;
            $order['buyer'] = '';
        } else {
            //$tmp_order['is_fans'] = $user->isWeixinFans($tmp_order['uid']);
            $order['is_fans'] = true;
            $is_fans = true;
            $user_info = $user->checkUser(array('uid' => $order['uid']));
            $order['buyer'] = $user_info['nickname'];
        }

        //		$is_supplier = false;
        //		if (!empty($order['suppliers'])) { //订单供货商
        //			$suppliers = explode(',', $order['suppliers']);
        //			if (in_array($this->store_session['store_id'], $suppliers)) {
        //				$is_supplier = true;
        //			}
        //		}
        //		$order['is_supplier'] = $is_supplier;

        $comment_count = 0;
        $product_count = 0;
        $tmp_products = array();
        foreach ($products as $product) {
            if (!empty($product['comment'])) {
                $comment_count++;
            }
            $product_count++;

            $tmp_products[] = $product['product_id']; //
        }

        $status = M('Order')->status();
        $payment_method = M('Order')->getPaymentMethod();

        if (empty($order['address'])) {
            $status[0] = '未填收货地址';
        } else {
            $status[1] = '已填收货地址';
        }
        if (!empty($order['user_order_id'])) {
            $user_order_id = $order['user_order_id'];
        } else {
            $user_order_id = $order['order_id'];
        }
        $where = array();
        $where['user_order_id'] = $user_order_id;
        $tmp_packages = $package->getPackages($where);
        $packages = array();
        foreach ($tmp_packages as $package) {
            $package_products = explode(',', $package['products']);
            if (array_intersect($package_products, $tmp_products)) {
                $packages[] = $package;
            }
        }

        // 查看满减送
        $order_ward_list = M('Order_reward')->getByOrderId($order['order_id']);
        // 使用优惠券
        $order_coupon = M('Order_coupon')->getByOrderId($order['order_id']);

        //应付款金额
        $package_info = D('Order_package')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id'], 'status' => 1))->find();
        $express_money = empty($package_info) ? 0 : $package_info['express_money'];
        //商家应退回的钱
        $return_money = $order['sub_total'] - $order['profit'] - $express_money;
        $cost = $order['sub_total'] - $order['profit'];
        $this->assign('is_fans', $is_fans);
        $this->assign('order', $order);
        $this->assign('products', $products);
        $this->assign('rows', $comment_count + $product_count);
        $this->assign('comment_count', $comment_count);
        $this->assign('status', $status);
        $this->assign('payment_method', $payment_method);
        $this->assign('packages', $packages);
        $this->assign('order_ward_list', $order_ward_list);
        $this->assign('order_coupon', $order_coupon);
        $this->assign('return_money', $return_money);
        $this->assign('cost', $cost);
    }

}