UPDATE  `tp_system_menu` SET  `name` =  '开店订单', `action` =  'payfor' WHERE  `tp_system_menu`.`id` =36;
UPDATE  `tp_system_menu` SET  `name` =  '红包订单', `action` =  'redpack' WHERE  `tp_system_menu`.`id` =35;
UPDATE  `tp_system_menu` SET  `name` =  '充值订单', `action` =  'recharge' WHERE  `tp_system_menu`.`id` =34;

UPDATE `tp_user` SET `balance` = 0.00, `point` =0, `withdrawal` = 0.00, `exchanged` =0;
TRUNCATE TABLE  `tp_user_income`;
TRUNCATE TABLE  `tp_user_exch`;
TRUNCATE TABLE  `tp_user_collect`;
TRUNCATE TABLE  `tp_user_cash`;
TRUNCATE TABLE  `tp_user_cart`;
TRUNCATE TABLE  `tp_user_attention`;
TRUNCATE TABLE  `tp_user_address`;


DROP TABLE IF EXISTS `tp_payfor_redpack`;
CREATE TABLE IF NOT EXISTS `tp_payfor_redpack` (
  `id` int(11) NOT NULL auto_increment,
  `order_no` varchar(100) NOT NULL COMMENT 'payfor_order_no',
  `trade_no` varchar(100) NOT NULL default '' COMMENT '微信发红包交易号',
  `uid` int(11) NOT NULL default '0' COMMENT '用户id',
  `openid` varchar(36) NOT NULL,
  `add_time` varchar(20) NOT NULL default '' COMMENT '申请时间',
  `status` tinyint(2) NOT NULL default '0' COMMENT '状态 -1失败 0申请 1成功',
  `amount` decimal(10,2) unsigned NOT NULL default '0.00' COMMENT '提现金额',
  `complate_time` varchar(20) NOT NULL default '' COMMENT '完成时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户红包记录' AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `tp_payfor_order`;
CREATE TABLE IF NOT EXISTS `tp_payfor_order` (
  `order_id` int(10) unsigned NOT NULL auto_increment COMMENT '订单id',
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `trade_no` varchar(100) NOT NULL COMMENT '交易号',
  `third_id` varchar(100) NOT NULL COMMENT '支付完成后返回',
  `uid` int(11) NOT NULL default '0' COMMENT '买家id',
  `total` decimal(10,2) NOT NULL default '0.00' COMMENT '订单金额（含邮费）',
  `profit_status` int(2) NOT NULL COMMENT '分佣状态 1一级分销返现 2二级分销返现 3三级分销返现',
  `agent_status` int(2) NOT NULL COMMENT '代理状态 1一级分销返现 2二级分销返现 3三级分销返现',
  `pay_type` varchar(50) NOT NULL default '' COMMENT '支付方式',
  `status` tinyint(1) NOT NULL default '0' COMMENT '订单状态 0临时订单 1未支付 2已支付 ',
  `add_time` int(11) NOT NULL default '0' COMMENT '订单时间',
  `paid_time` int(11) NOT NULL default '0' COMMENT '付款时间',
  `complate_time` int(11) NOT NULL,
  `remarks` varchar(500) NOT NULL default '' COMMENT '备注',
  `pay_money` decimal(10,2) NOT NULL COMMENT '实际付款金额',
  `is_check` tinyint(1) NOT NULL default '1' COMMENT '是否对账，1：未对账，2：已对账',
  PRIMARY KEY  (`order_id`),
  UNIQUE KEY `order_no` (`order_no`),
  UNIQUE KEY `trade_no` (`trade_no`),
  KEY `uid` USING BTREE (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='充值订单' AUTO_INCREMENT=1;

INSERT INTO `tp_config` (`id`, `name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `gid`, `sort`, `status`) VALUES (NULL, 'payfor_store', 'type=text&size=3&validate=required:true,number:true', '100', '开店费用', '需支付设置金额才可开店', '0', '', '12', '0', '1');

DROP TABLE  `tp_agent_qrcode`;

ALTER TABLE  `tp_order_merge` CHANGE  `merge_no`  `order_no` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '订单号';

INSERT INTO `tp_config` (`id`, `name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `gid`, `sort`, `status`) VALUES (NULL, 'mergeid_prefix', 'type=text&size=20', 'MEG', '跨代理订单号前缀', '用户看到的订单号 = 订单号前缀+订单号，不能与订单号前缀相同', '0', '', '1', '0', '1');

DROP TABLE IF EXISTS `tp_order`;
CREATE TABLE IF NOT EXISTS `tp_order` (
  `order_id` int(10) unsigned NOT NULL auto_increment COMMENT '订单id',
  `merge_id` int(11) NOT NULL COMMENT '合并付款记录id',
  `agent_id` int(11) NOT NULL COMMENT '代理商id',
  `store_id` int(10) NOT NULL default '0' COMMENT '店铺id',
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `trade_no` varchar(100) NOT NULL COMMENT '交易号',
  `pay_type` varchar(10) NOT NULL COMMENT '支付方式',
  `third_id` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL default '0' COMMENT '买家id',
  `session_id` varchar(32) NOT NULL,
  `postage` decimal(10,2) NOT NULL default '0.00' COMMENT '邮费',
  `sub_total` decimal(10,2) NOT NULL default '0.00' COMMENT '商品金额（不含邮费）',
  `balance` decimal(10,2) NOT NULL default '0.00' COMMENT '使用账户余额数',
  `point` int(11) NOT NULL default '0' COMMENT '使用积分数量',
  `total` decimal(10,2) NOT NULL default '0.00' COMMENT '订单金额（含邮费）',
  `profit` decimal(10,2) NOT NULL default '0.00' COMMENT '订单利润',
  `profit_status` tinyint(1) NOT NULL default '0' COMMENT '0无,1买家已返,2一级分销,3二级分销,4一级代理,5二级代理',
  `pro_count` int(11) NOT NULL COMMENT '商品的个数',
  `pro_num` int(10) NOT NULL default '0' COMMENT '商品数量',
  `address` text NOT NULL COMMENT '收货地址',
  `address_user` varchar(30) NOT NULL default '' COMMENT '收货人',
  `address_tel` varchar(20) NOT NULL default '' COMMENT '收货人电话',
  `payment_method` varchar(50) NOT NULL default '' COMMENT '支付方式',
  `shipping_method` varchar(50) NOT NULL default '' COMMENT '物流方式 express快递发货 selffetch上门自提',
  `type` tinyint(1) NOT NULL default '0' COMMENT '订单类型 0普通 1代付 2送礼 3分销',
  `status` tinyint(1) NOT NULL default '0' COMMENT '订单状态 0临时订单 1未支付 2未发货 3已发货 4已完成 5已取消 6退款中 ',
  `add_time` int(11) NOT NULL default '0' COMMENT '订单时间',
  `paid_time` int(11) NOT NULL default '0' COMMENT '付款时间',
  `sent_time` int(11) NOT NULL default '0' COMMENT '发货时间',
  `cancel_time` int(11) NOT NULL default '0' COMMENT '取消时间',
  `complate_time` int(11) NOT NULL,
  `refund_time` int(11) NOT NULL COMMENT '退款时间',
  `comment` varchar(500) NOT NULL default '' COMMENT '买家留言',
  `bak` varchar(500) NOT NULL default '' COMMENT '备注',
  `star` tinyint(1) NOT NULL default '0' COMMENT '加星订单 1|2|3|4|5 默认0',
  `pay_money` decimal(10,2) NOT NULL COMMENT '实际付款金额',
  `cancel_method` tinyint(1) NOT NULL default '0' COMMENT '订单取消方式 0过期自动取消 1卖家手动取消 2买家手动取消',
  `float_amount` decimal(10,2) NOT NULL default '0.00' COMMENT '订单浮动金额',
  `is_fx` tinyint(1) NOT NULL default '0' COMMENT '是否包含分销商品 0 否 1是',
  `fx_order_id` int(11) unsigned NOT NULL default '0' COMMENT '分销订单id',
  `user_order_id` int(11) unsigned NOT NULL default '0' COMMENT '用户订单id,统一分销订单',
  `suppliers` varchar(500) NOT NULL default '' COMMENT '商品供货商',
  `packaging` tinyint(1) unsigned NOT NULL default '0' COMMENT '打包中',
  `fx_postage` varchar(500) NOT NULL default '' COMMENT '分销运费详细 supplier_id=>postage',
  `useStorePay` tinyint(1) NOT NULL default '0',
  `storeOpenid` varchar(100) NOT NULL,
  `sales_ratio` decimal(10,2) NOT NULL COMMENT '商家销售分成比例,按照所填百分比进行扣除',
  `is_check` tinyint(1) NOT NULL default '1' COMMENT '是否对账，1：未对账，2：已对账',
  PRIMARY KEY  (`order_id`),
  UNIQUE KEY `order_no` (`order_no`),
  UNIQUE KEY `trade_no` (`trade_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='订单' AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `tp_order_merge`;
CREATE TABLE IF NOT EXISTS `tp_order_merge` (
  `merge_id` int(10) unsigned NOT NULL auto_increment COMMENT '订单id',
  `uid` int(11) NOT NULL default '0' COMMENT '买家id',
  `merge_no` varchar(100) NOT NULL COMMENT '订单号',
  `trade_no` varchar(100) NOT NULL COMMENT '交易号',
  `pay_type` varchar(10) NOT NULL COMMENT '支付方式',
  `third_id` varchar(100) NOT NULL,
  `balance` decimal(10,2) NOT NULL default '0.00' COMMENT '使用账户余额数',
  `point` int(11) NOT NULL default '0' COMMENT '使用积分数量',
  `total` decimal(10,2) NOT NULL default '0.00' COMMENT '订单金额（含邮费）',
  `payment_method` varchar(50) NOT NULL default '' COMMENT '支付方式',
  `status` tinyint(1) NOT NULL default '0' COMMENT '订单状态 0临时订单 1未支付 2未发货 3已发货 4已完成 5已取消 6退款中 ',
  `add_time` int(11) NOT NULL default '0' COMMENT '订单时间',
  `paid_time` int(11) NOT NULL default '0' COMMENT '付款时间',
  `sent_time` int(11) NOT NULL default '0' COMMENT '发货时间',
  `cancel_time` int(11) NOT NULL default '0' COMMENT '取消时间',
  `complate_time` int(11) NOT NULL,
  `pay_money` decimal(10,2) NOT NULL COMMENT '实际付款金额',
  `float_amount` decimal(10,2) NOT NULL default '0.00' COMMENT '订单浮动金额',
  `is_check` tinyint(1) NOT NULL default '1' COMMENT '是否对账，1：未对账，2：已对账',
  PRIMARY KEY  (`merge_id`),
  UNIQUE KEY `order_no` (`merge_no`),
  UNIQUE KEY `trade_no` (`trade_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='订单合并付款记录' AUTO_INCREMENT=1 ;

ALTER TABLE  `tp_user_cart` ADD  `agent_id` INT NOT NULL COMMENT  '代理商id' AFTER  `store_id`;

DROP TABLE IF EXISTS `tp_agent_qrcode`;
CREATE TABLE IF NOT EXISTS `tp_agent_qrcode` (
  `id` int(11) NOT NULL auto_increment,
  `num` int(11) NOT NULL,
  `store_id` int(11) NOT NULL COMMENT '店铺id',
  `url` varchar(100) NOT NULL COMMENT '二维码路径',
  `ticket` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `tp_user_exch`;
CREATE TABLE IF NOT EXISTS `tp_user_exch` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0' COMMENT '用户id',
  `point` int(11) NOT NULL default '0' COMMENT '积分数量',
  `amount` decimal(10,2) unsigned NOT NULL default '0.00' COMMENT '兑换金额',
  `add_time` varchar(20) NOT NULL default '' COMMENT '申请时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='积分兑换' AUTO_INCREMENT=1;

ALTER TABLE  `tp_user` ADD  `exchanged` INT NOT NULL COMMENT  '已兑换积分数量';

ALTER TABLE  `tp_store` CHANGE  `agent_approve`  `agent_id` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '代理代理级别';

INSERT INTO `tp_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`) VALUES (NULL, '12', '代理级别', 'Agent', 'index', '0', '1', '1');

DROP TABLE IF EXISTS `tp_agent`;
CREATE TABLE IF NOT EXISTS `tp_agent` (
  `agent_id` int(11) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL COMMENT '代理名称',
  `open_self` tinyint(1) NOT NULL default '0' COMMENT '开启自营',
  `max_products` int(11) NOT NULL default '0' COMMENT '自营区产品数量限制',
  `remarks` varchar(256) NOT NULL COMMENT '代理说明',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` tinyint(1) NOT NULL default '0' COMMENT '启用状态',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY  (`agent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `tp_agent` (`agent_id`, `name`, `open_self`, `max_products`, `remarks`, `sort`, `status`, `add_time`) VALUES
(1, '分销代理', 0, 0, '基础代理商，无自营区域。', 0, 0, 1457682213);

DROP TABLE IF EXISTS `tp_user_cash`;
CREATE TABLE IF NOT EXISTS `tp_user_cash` (
  `id` int(11) NOT NULL auto_increment,
  `trade_no` varchar(100) NOT NULL default '' COMMENT '交易号',
  `uid` int(11) NOT NULL default '0' COMMENT '用户id',
  `truename` varchar(20) NOT NULL COMMENT '店主姓名',
  `add_time` varchar(20) NOT NULL default '' COMMENT '申请时间',
  `status` tinyint(2) NOT NULL default '0' COMMENT '状态 -1失败 0申请 1成功',
  `amount` decimal(10,2) unsigned NOT NULL default '0.00' COMMENT '提现金额',
  `complate_time` varchar(20) NOT NULL default '' COMMENT '完成时间',
  PRIMARY KEY  (`id`),
  KEY `bank_id` USING BTREE (`truename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户提现' AUTO_INCREMENT=1 ;

-- tp_system_menu
INSERT INTO `tp_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`) VALUES (NULL, '2', '充值活动', 'Recharge', 'index', '0', '1', '1');

-- tp_recharge
DROP TABLE IF EXISTS `tp_recharge`;
CREATE TABLE IF NOT EXISTS `tp_recharge` (
  `recharge_id` int(11) NOT NULL auto_increment,
  `amount` decimal(10,2) NOT NULL COMMENT '充值金额',
  `point` int(11) NOT NULL COMMENT '奖励积分',
  `profit` decimal(10,2) NOT NULL COMMENT '分销金额',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `status` tinyint(1) NOT NULL COMMENT '状态,0无效1有效',
  `sort` int(11) NOT NULL default '0' COMMENT '排序，正顺。',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY  (`recharge_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='充值规则' AUTO_INCREMENT=5 ;

-- tp_recharge_order
DROP TABLE IF EXISTS `tp_recharge_order`;
CREATE TABLE IF NOT EXISTS `tp_recharge_order` (
  `order_id` int(10) unsigned NOT NULL auto_increment COMMENT '订单id',
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `trade_no` varchar(100) NOT NULL COMMENT '交易号',
  `third_id` varchar(100) NOT NULL COMMENT '支付完成后返回',
  `uid` int(11) NOT NULL default '0' COMMENT '买家id',
  `total` decimal(10,2) NOT NULL default '0.00' COMMENT '订单金额（含邮费）',
  `point` int(11) NOT NULL default '0' COMMENT '使用积分数量',
  `profit` decimal(10,2) NOT NULL COMMENT '分成金额',
  `profit_status` int(2) NOT NULL COMMENT '分成状态 1一级分销返现 2二级分销返现 3三级分销返现',
  `pay_type` varchar(50) NOT NULL default '' COMMENT '支付方式',
  `status` tinyint(1) NOT NULL default '0' COMMENT '订单状态 0临时订单 1未支付 2已支付 ',
  `add_time` int(11) NOT NULL default '0' COMMENT '订单时间',
  `paid_time` int(11) NOT NULL default '0' COMMENT '付款时间',
  `complate_time` int(11) NOT NULL,
  `remarks` varchar(500) NOT NULL default '' COMMENT '备注',
  `pay_money` decimal(10,2) NOT NULL COMMENT '实际付款金额',
  `is_check` tinyint(1) NOT NULL default '1' COMMENT '是否对账，1：未对账，2：已对账',
  PRIMARY KEY  (`order_id`),
  UNIQUE KEY `order_no` (`order_no`),
  UNIQUE KEY `trade_no` (`trade_no`),
  KEY `uid` USING BTREE (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='充值订单' AUTO_INCREMENT=4 ;

-- tp_store_auth
DROP TABLE IF EXISTS `tp_store_auth`;
CREATE TABLE IF NOT EXISTS `tp_store_auth` (
  `auth_id` int(11) NOT NULL auto_increment,
  `store_id` int(11) NOT NULL COMMENT '店铺id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `auths` varchar(128) NOT NULL COMMENT '授权信息',
  `status` tinyint(11) NOT NULL COMMENT '状态',
  `add_time` int(11) NOT NULL COMMENT '授权时间',
  PRIMARY KEY  (`auth_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺授权' AUTO_INCREMENT=1 ;

-- config
INSERT INTO `tp_config` (`id`, `name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `gid`, `sort`, `status`) VALUES (NULL, 'auto_create', 'type=radio&value=1:启用|0:关闭', '1', '自动创建店铺', '用户注册时自动创建店铺。', '0', '', '12', '0', '1');

-- tp_user_income
DROP TABLE IF EXISTS `tp_user_income`;
CREATE TABLE IF NOT EXISTS `tp_user_income` (
  `income_id` int(11) NOT NULL auto_increment,
  `uid` tinyint(1) NOT NULL default '1' COMMENT '用户ID',
  `order_no` varchar(20) NOT NULL default '0' COMMENT '订单id',
  `income` decimal(10,2) NOT NULL default '0.00' COMMENT '收入 负值为支出',
  `point` int(11) NOT NULL default '0' COMMENT '积分',
  `type` tinyint(2) NOT NULL COMMENT '-3 => ''提现'', -2 => ''活动使用'', -1 => ''购物使用'', 1 => ''购物立返'', 2 => ''一级分销返佣'', 3 => ''二级分销返佣'', 4 => ''一级代理利润'', 5 => ''二级代理利润'', 6 => ''物流费用'', 7 => ''推荐奖励'', 8 => ''活动奖励'', 9 => ''退货返还'', 10 => ''管理员充值''',
  `add_time` int(11) NOT NULL default '0' COMMENT '时间',
  `status` tinyint(1) NOT NULL default '1' COMMENT '状态 0进行中 1成功 2失败',
  `remarks` varchar(128) NOT NULL COMMENT '备注',
  PRIMARY KEY  (`income_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户收支记录' AUTO_INCREMENT=1 ;

INSERT INTO `tp_config` (`id`, `name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `gid`, `sort`, `status`) VALUES
(NULL, 'promote_reward', 'type=text&size=3&validate=required:true,number:true', '10', '推广奖励', '推广下线用户奖励积分或现金。', '0', '', 12, 0, 1);
INSERT INTO `tp_config` (`id`, `name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `gid`, `sort`, `status`) VALUES (NULL, 'withdrawal_min', 'type=text&size=3&validate=required:true,number:true', '100', '最低提现金额', '', '0', '', '12', '0', '1');

INSERT INTO `tp_config` (`id`, `name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `gid`, `sort`, `status`) VALUES (NULL, 'free_postage', 'type=text&size=3&validate=required:true,number:true', '69', '满额免邮', '订单总额达到设置数量将减去邮费', '0', '', '2', '0', '1');
UPDATE  `tp_config_group` SET  `status` =  '0' WHERE  `tp_config_group`.`gid` =13;

ALTER TABLE `tp_user`
  DROP `bank_id`,
  DROP `bank_no`,
  DROP `bank_username`,
  DROP `bank_addr`;

CREATE TABLE IF NOT EXISTS `tp_user_cash` (
  `id` int(11) NOT NULL auto_increment,
  `trade_no` varchar(100) NOT NULL default '' COMMENT '交易号',
  `uid` int(11) NOT NULL default '0' COMMENT '用户id',
  `card_id` int(11) NOT NULL default '0' COMMENT '用户银行卡id',
  `type` tinyint(1) NOT NULL default '0' COMMENT '提现方式 0对私 1对公',
  `add_time` varchar(20) NOT NULL default '' COMMENT '申请时间',
  `status` tinyint(1) NOT NULL default '1' COMMENT '状态 1申请中 2银行处理中 3提现成功 4提现失败',
  `amount` decimal(10,2) unsigned NOT NULL default '0.00' COMMENT '提现金额',
  `complate_time` varchar(20) NOT NULL default '' COMMENT '完成时间',
  PRIMARY KEY  (`id`),
  KEY `bank_id` USING BTREE (`card_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户提现' AUTO_INCREMENT=1 ;

RENAME TABLE  `tp_store_withdrawal` TO  `tp_user_card` ;
DROP TABLE IF EXISTS `tp_user_card`;
CREATE TABLE IF NOT EXISTS `tp_user_card` (
  `card_id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0' COMMENT '用户id',
  `bank_id` int(11) NOT NULL default '0' COMMENT '银行id',
  `bank_name` varchar(30) NOT NULL default '' COMMENT '开户行',
  `card_no` varchar(30) NOT NULL default '' COMMENT '卡号',
  `card_user` varchar(30) NOT NULL default '' COMMENT '持卡人',
  `add_time` varchar(20) NOT NULL default '' COMMENT '申请时间',
  PRIMARY KEY  (`card_id`),
  KEY `bank_id` USING BTREE (`bank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户银行卡' AUTO_INCREMENT=1 ;

-- tp_order
ALTER TABLE  `tp_order` ADD  `balance` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00' COMMENT  '使用账户余额数' AFTER  `pay_money` ,
	ADD  `point` INT NOT NULL DEFAULT  '0' COMMENT  '使用积分数量' AFTER  `balance`,
	ADD  `agent_id` INT NOT NULL COMMENT  '代理商ID' AFTER  `order_id`,
	ADD  `profit` decimal(10,2) NOT NULL DEFAULT  '0.00' COMMENT  '订单利润' AFTER  `total`,
	ADD  `profit_status` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '0无,1买家已返,2一级分销,3二级分销,4一级代理,5二级代理' AFTER  `profit`,
	ADD  `profit` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00' COMMENT  '全部商品利润' AFTER  `pro_num`;
ALTER TABLE  `tp_order` CHANGE  `balance`  `balance` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00' COMMENT  '使用账户余额数'  AFTER  `sub_total`,
	CHANGE  `point`  `point` INT( 11 ) NOT NULL DEFAULT  '0' COMMENT  '使用积分数量'  AFTER  `balance`;

-- tp_store
ALTER TABLE  `tp_store` ADD  `agent_approve` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '代理审核状态';
ALTER TABLE  `tp_store` CHANGE  `open_logistics`  `open_logistics` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '启用物流配送',
	CHANGE  `offline_payment`  `offline_payment` TINYINT( 1 ) NULL DEFAULT  '0' COMMENT  '线下支付',
	CHANGE  `open_friend`  `open_friend` TINYINT( 1 ) NULL DEFAULT  '0' COMMENT  '开启送朋友需启用物流配送';

-- tp_access_token_expires
ALTER TABLE  `tp_access_token_expires` ADD  `time` INT NOT NULL DEFAULT  '1' COMMENT  '更新次数';
RENAME TABLE  `tp_access_token_expires` TO  `tp_access_token` ;

-- tp_adver
ALTER TABLE  `tp_adver` ADD  `qrcode` VARCHAR( 50 ) NOT NULL COMMENT  '活动二维码' AFTER  `pic`;

-- tp_recognition
ALTER TABLE  `tp_recognition` ADD  `store_id` INT NOT NULL DEFAULT  '0' COMMENT  '扫码自动注册为当前商城的下线' AFTER  `third_id`;

-- tp_product
ALTER TABLE  `tp_product` CHANGE  `original_price`  `market_price` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00' COMMENT  '市场价';

-- tp_product_sku
ALTER TABLE  `tp_product_sku` ADD  `market_price` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00' COMMENT  '市场价格' AFTER  `sales`;
ALTER TABLE  `tp_product_sku` ADD  `weight` INT NOT NULL DEFAULT  '0' COMMENT  '重量（克）' AFTER  `quantity`;

-- tp_user
-- ALTER TABLE  `tp_user` ADD  `is_agent` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否代理' AFTER  `city`;
ALTER TABLE  `tp_user` ADD  `is_twiker` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否为推客',
	ADD  `twiker_id` VARCHAR( 10 ) NOT NULL COMMENT  '推广码',
	ADD  `parent_uid` INT NOT NULL COMMENT  '上级Uid';
ALTER TABLE  `tp_user` 
	ADD  `sex` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '性别',
	ADD  `country` VARCHAR( 50 ) NOT NULL COMMENT  '国家',
	ADD  `province` VARCHAR( 50 ) NOT NULL COMMENT  '省份',
	ADD  `city` VARCHAR( 50 ) NOT NULL COMMENT  '城市';
ALTER TABLE  `tp_user` ADD  `balance` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00' COMMENT  '可用余额' AFTER  `status` ,
	ADD  `point` INT NOT NULL DEFAULT  '0' COMMENT  '可用积分' AFTER  `balance`;

ALTER TABLE `tp_user` CHANGE `uid` `uid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
	CHANGE `nickname` `nickname` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	CHANGE `password` `password` CHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, 
	CHANGE `phone` `phone` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '手机号', 
	CHANGE `openid` `openid` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '微信唯一标识', 
	CHANGE `reg_time` `reg_time` INT(10) UNSIGNED NOT NULL DEFAULT '0', 
	CHANGE `reg_ip` `reg_ip` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', 
	CHANGE `last_time` `last_time` INT(10) UNSIGNED NOT NULL DEFAULT '0', 
	CHANGE `last_ip` `last_ip` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', 
	CHANGE `check_phone` `check_phone` TINYINT(1) NOT NULL DEFAULT '0', 
	CHANGE `login_count` `login_count` INT(11) NOT NULL DEFAULT '1', 
	CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '1', 
	CHANGE `intro` `intro` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '个人签名', 
	CHANGE `avatar` `avatar` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '头像', 
	CHANGE `is_weixin` `is_weixin` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否是微信用户 0否 1是', 
	CHANGE `stores` `stores` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '店铺数量', 
	CHANGE `token` `token` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '微信token', 
	CHANGE `session_id` `session_id` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'session id', 
	CHANGE `server_key` `server_key` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, 
	CHANGE `source_site_url` `source_site_url` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '来源网站', 
	CHANGE `payment_url` `payment_url` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '站外支付地址', 
	CHANGE `notify_url` `notify_url` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '通知地址', 
	CHANGE `oauth_url` `oauth_url` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '对接网站用户认证地址', 
	CHANGE `is_seller` `is_seller` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '是否是卖家', 
	CHANGE `third_id` `third_id` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '第三方id', 
	CHANGE `drp_store_id` `drp_store_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户所属店铺', 
	CHANGE `app_id` `app_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '对接应用id', 
	CHANGE `admin_id` `admin_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '后台ID', 
	CHANGE `sex` `sex` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '性别', 
	CHANGE `country` `country` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '国家', 
	CHANGE `province` `province` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '省份',
	CHANGE `city` `city` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '城市', 
	CHANGE `twiker_id` `twiker_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '推客ID', 
	CHANGE `parent_uid` `parent_uid` INT(11) NOT NULL DEFAULT '0' COMMENT '上级Uid';

ALTER TABLE  `tp_user` CHANGE  `twiker_id`  `twid` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '推客码';
ALTER TABLE  `tp_user` ADD `withdrawal` decimal(10,2) NOT NULL default '0.00' COMMENT '已提现金额',
	ADD `bank_id` int(5) NOT NULL default '0' COMMENT '开户银行',
	ADD `bank_no` varchar(30) NOT NULL default '' COMMENT '银行卡号',
	ADD `bank_username` varchar(30) NOT NULL default '' COMMENT '开卡用户姓名',
	ADD `bank_addr` varchar(30) NOT NULL default '' COMMENT '开户行';



DROP TABLE IF EXISTS `tp_api_count`;
CREATE TABLE IF NOT EXISTS `tp_api_count` (
  `id` int(11) NOT NULL auto_increment,
  `key` varchar(20) NOT NULL COMMENT '接口名称',
  `count` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


INSERT INTO `tp_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`) VALUES (NULL, '0', '帮助管理', '', '', '3', '1', '1');
INSERT INTO `tp_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`) VALUES (NULL, '63', '帮助列表', 'help', 'index', '3', '1', '1');
INSERT INTO `tp_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`) VALUES (NULL, '63', '分类管理', 'help', 'category', '3', '1', '1');

DROP TABLE IF EXISTS `tp_help`;
CREATE TABLE IF NOT EXISTS `tp_help` (
  `help_id` int(10) NOT NULL auto_increment COMMENT '帮助id',
  `category_fid` int(11) NOT NULL,
  `category_id` int(10) NOT NULL default '0' COMMENT '分类id',
  `title` varchar(128) NOT NULL COMMENT '帮助名称',
  `content` text NOT NULL COMMENT '帮助描述',
  `sort` int(11) unsigned NOT NULL default '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL default '0' COMMENT '帮助浏览量',
  `seo_title` varchar(128) NOT NULL COMMENT '帮助SEO标题',
  `seo_key` varchar(256) NOT NULL COMMENT '帮助SEO关键字',
  `seo_des` varchar(256) NOT NULL COMMENT '帮助SEO介绍',
  `date_added` int(11) NOT NULL default '0' COMMENT '添加时间',
  PRIMARY KEY  (`help_id`),
  KEY `category_id` USING BTREE (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='帮助' AUTO_INCREMENT=2 ;

INSERT INTO `tp_help` (`help_id`, `category_fid`, `category_id`, `title`, `content`, `sort`, `status`, `seo_title`, `seo_key`, `seo_des`, `date_added`) VALUES
(1, 102, 105, '爱微爱', '<p>\r\n	爱微爱\r\n</p>\r\n<p>\r\n	魂牵梦萦\r\n</p>', 0, 1, '魂牵梦萦', '魂牵梦萦', '魂牵梦萦', 1451890606);

DROP TABLE IF EXISTS `tp_help_category`;
CREATE TABLE IF NOT EXISTS `tp_help_category` (
  `cat_id` int(10) NOT NULL auto_increment COMMENT '分类id',
  `cat_name` varchar(50) NOT NULL COMMENT '分类名称',
  `cat_desc` varchar(1000) NOT NULL COMMENT '描述',
  `cat_fid` int(10) NOT NULL default '0' COMMENT '父类id',
  `cat_status` tinyint(1) NOT NULL default '1' COMMENT '状态',
  `cat_sort` int(10) NOT NULL default '0' COMMENT '排序，值越大优前',
  `cat_path` varchar(1000) NOT NULL,
  `cat_level` tinyint(1) NOT NULL default '1' COMMENT '级别',
  `cat_parent_status` tinyint(1) NOT NULL default '1' COMMENT '父类状态',
  PRIMARY KEY  (`cat_id`),
  KEY `parent_category_id` USING BTREE (`cat_fid`),
  KEY `cat_sort` USING BTREE (`cat_sort`),
  KEY `cat_name` USING BTREE (`cat_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='帮助分类' AUTO_INCREMENT=110 ;

INSERT INTO `tp_help_category` (`cat_id`, `cat_name`, `cat_desc`, `cat_fid`, `cat_status`, `cat_sort`, `cat_path`, `cat_level`, `cat_parent_status`) VALUES
(102, '帮助主题', '', 0, 1, 0, '0,102', 1, 1),
(103, '自助服务', '', 0, 1, 0, '0,103', 1, 1),
(104, '最新公告', '', 0, 1, 0, '0,104', 1, 1),
(105, '会员中心', '', 102, 1, 0, '0,102,105', 2, 1),
(106, '新手指南', '', 102, 1, 0, '0,102,106', 2, 1),
(107, '支付方式', '', 102, 1, 0, '0,102,107', 2, 1),
(108, '配送服务', '', 102, 1, 0, '0,102,108', 2, 1),
(109, '售后服务', '', 102, 1, 0, '0,102,109', 2, 1);

DROP TABLE IF EXISTS `tp_store_user`;
CREATE TABLE IF NOT EXISTS `tp_store_user` (
  `id` int(11) NOT NULL auto_increment,
  `store_id` int(11) NOT NULL COMMENT '店铺id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `status` tinyint(1) NOT NULL default '0' COMMENT '授权状态0待授权,1已授权',
  `auth` varchar(128) NOT NULL COMMENT '权限',
  `remarks` varchar(128) NOT NULL COMMENT '备注',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺授权管理' AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `tp_app_million`;
CREATE TABLE IF NOT EXISTS `tp_app_million` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `issue` int(11) NOT NULL COMMENT '投资期数',
  `point` int(11) NOT NULL COMMENT '积分',
  `income` int(11) NOT NULL COMMENT '收益积分',
  `time` int(11) NOT NULL COMMENT '投资时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='百万大奖' AUTO_INCREMENT=5 ;

INSERT INTO `tp_app_million` (`id`, `uid`, `issue`, `point`, `income`, `time`) VALUES
(1, 58, 1, 1000, 0, 1456131615),
(2, 58, 2, 100, 0, 1456135232),
(3, 58, 3, 100, 0, 1456135300),
(4, 58, 4, 100, 0, 1456135361);

DROP TABLE IF EXISTS `tp_app_million_income`;
CREATE TABLE IF NOT EXISTS `tp_app_million_income` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `issue` int(11) NOT NULL COMMENT '期数',
  `point` int(11) NOT NULL COMMENT '投资积分',
  `income` int(11) NOT NULL COMMENT '投资收益',
  `parent_uid` int(11) NOT NULL COMMENT '上级用户id',
  `time` int(11) NOT NULL COMMENT '时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='百万大奖收益' AUTO_INCREMENT=1 ;


-- update config

--INSERT INTO `tp_config` (`name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `gid`, `sort`, `status`) VALUES
--('default_point', 'type=radio&value=1:积分|0:佣金', '1', '默认返佣返积分', '', '0', '', 12, 0, 1),
--('point_exchange', 'type=text&size=3&validate=required:true,number:true,maxlength:2', '100', '积分兑换比例', '1元人民币兑换积分数量', '0', '', 12, 0, 1),
--('commission_rate_1', 'type=text&size=3&validate=required:true,number:true,maxlength:2', '30', '购买返佣', '用户购买商品返现', '0', '', 12, 0, 1),
--('commission_rate_2', 'type=text&size=3&validate=required:true,number:true,maxlength:2', '15', '一级推客返佣', '', '0', '', 12, 0, 1),
--('commission_rate_3', 'type=text&size=3&validate=required:true,number:true,maxlength:2', '15', '二级推客返佣', '', '0', '', 12, 0, 1),
--('commission_rate_4', 'type=text&size=3&validate=required:true,number:true,maxlength:2', '15', '一级代理利润', '', '0', '', 12, 0, 1),
--('commission_rate_5', 'type=text&size=3&validate=required:true,number:true,maxlength:2', '15', '二级代理利润', '', '0', '', 12, 0, 1),
--('commission_rate_6', 'type=text&size=3&validate=required:true,number:true,maxlength:2', '10', '物流费用', '物流配送费用', '0', '', 12, 0, 1);
UPDATE  `tp_config` SET  `name` =  'buyer_ratio' WHERE  `id` =103;
UPDATE  `tp_config` SET  `name` =  'promoter_ratio_1' WHERE  `id` =104;
UPDATE  `tp_config` SET  `name` =  'promoter_ratio_2' WHERE  `id` =105;
UPDATE  `tp_config` SET  `name` =  'agent_ratio_1' WHERE  `id` =106;
UPDATE  `tp_config` SET  `name` =  'agent_ratio_2' WHERE  `id` =107;
UPDATE  `tp_config` SET  `name` =  'logistics_ratio' WHERE  `id` =108;
UPDATE  `tp_config` SET  `status` =  '0' WHERE  `id` =59;
UPDATE  `tp_config` SET  `status` =  '0' WHERE  `id` =60;
UPDATE  `tp_config` SET  `type` =  'type=image&validate=required:true,url:true',`info` =  '微信用户中心顶部背景',`gid` =  '1',`status` =  '1' WHERE  `id` =20;



-- 
TRUNCATE TABLE  `tp_order`;
TRUNCATE TABLE  `tp_order_product`;
TRUNCATE TABLE `tp_order_package`;

TRUNCATE TABLE tp_access_token_expires;
TRUNCATE TABLE tp_recognition;
TRUNCATE TABLE tp_login_qrcode;
TRUNCATE TABLE tp_location_qrcode;
delete from tp_user where openid != '';


-- 清理数据
UPDATE  `tp_user` SET stores = ( SELECT COUNT( 1 ) FROM tp_store WHERE tp_user.uid = tp_store.uid AND STATUS =1 );
delete FROM  `tp_store` WHERE STATUS !=1;
UPDATE  `tp_store` SET logo = ( SELECT avatar FROM tp_user WHERE tp_store.uid = uid );
SELECT COUNT(1) num, SUM(order_count) order_count, SUM(order_total) order_total, SUM(pro_count) pro_count FROM (SELECT u.uid, s.store_id, (SELECT COUNT(1) FROM tp_order WHERE store_id = s.store_id AND STATUS IN (2, 3, 4)) AS order_count, (SELECT SUM(total) FROM tp_order WHERE store_id = s.store_id AND STATUS IN (2, 3, 4)) AS order_total, (SELECT SUM(pro_count) FROM tp_order WHERE store_id = s.store_id AND STATUS IN (2, 3, 4)) AS pro_count FROM tp_user u LEFT JOIN tp_store s ON u.uid = s.uid WHERE u.parent_uid =58 AND u.status =1 AND s.status =1) AS t

DELETE FROM  `tp_order` WHERE uid NOT IN (SELECT uid FROM tp_user)

-- 统计用户店铺数量
UPDATE  `tp_user` SET stores = ( SELECT COUNT( 1 ) FROM  `tp_store` WHERE uid =  `tp_user`.uid AND STATUS =1 )

UPDATE  `tp_store` SET logo = ( SELECT avatar FROM tp_user WHERE tp_store.uid = uid ) WHERE logo =  '';


-- 清理用户
delete FROM `tp_user` where admin_id = 0;
delete FROM `tp_product`  WHERE uid NOT IN (SELECT uid FROM tp_user);

delete FROM `tp_product_image` where product_id not in (select product_id from tp_product);
delete FROM `tp_store_analytics` where store_id not in (select store_id from tp_store);
DELETE FROM  `tp_store_contact` WHERE store_id NOT IN (SELECT store_id FROM tp_store);
delete FROM `tp_store_physical` WHERE store_id NOT IN (SELECT store_id FROM tp_store);

UPDATE  `tp_user` SET stores = ( SELECT COUNT( 1 ) FROM tp_store WHERE uid = tp_user.uid );
DELETE FROM  `tp_store` WHERE uid NOT IN (SELECT uid FROM tp_user);

UPDATE `tp_user` SET `balance`=0,`point`=0,`withdrawal`=0 WHERE 1

