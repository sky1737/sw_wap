update tp_config set status = 0 where id = 108;
update `yunws`.`tp_config` set `status`=0 where `id`=96;
insert `tp_config`(`name`,`type`,`value`,`info`,`desc`,`tab_id`,`gid`,`sort`,`status`) values('agent_ratio_3','type=text&size=3&validate=required:true,number:true,maxlength:2','15','物流费用','','0',12,108,1);
update tp_config set `sort`  = `id`  where `gid`  = 12;



ALTER TABLE  `tp_agent` ADD  `is_agent` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否代理' AFTER  `open_self` ,
ADD  `is_editor` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否产品专员' AFTER  `is_agent`;


DROP TABLE IF EXISTS `tp_refund_package`;
CREATE TABLE `tp_refund_package` (
  `package_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `express_code` varchar(50) NOT NULL,
  `express_company` varchar(50) NOT NULL DEFAULT '' COMMENT '快递公司',
  `express_no` varchar(50) NOT NULL DEFAULT '' COMMENT '快递单号',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态  -1绝收 0未签收 1签收',
  `refund_reason` varchar(200)  DEFAULT '' COMMENT '退款原因',
  `is_take` int(1) NOT NULL DEFAULT '1' COMMENT '0 未收货 1 已收货',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `handle_name` varchar(30) DEFAULT '' COMMENT '处理人',
  `handle_time` int(11) DEFAULT '0' COMMENT '处理时间',
  `products` varchar(500) NOT NULL DEFAULT '' COMMENT '商品集合',
  PRIMARY KEY (`package_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='退货订单包裹';
#店铺 供应商编码
ALTER TABLE `tp_store`
ADD COLUMN `supplier_code`  varchar(255) NULL AFTER `agent_id`;

ALTER TABLE  `tp_refund_package` ADD  `refuse_sign_reason` varchar(200)  DEFAULT  '' COMMENT  '商家拒签理由';


ALTER TABLE `tp_product` ADD COLUMN `create_uid` int(11) DEFAULT 0 NOT NULL COMMENT  '创建商品的uid' AFTER `store_id` ;

ALTER TABLE `tp_order_package` ADD COLUMN `express_money` decimal(10,2)	 DEFAULT 0  COMMENT  '物流费'  ;

ALTER TABLE `tp_product` ADD COLUMN `is_experience` int(1) NOT NULL DEFAULT '0' COMMENT '付邮免费体验';

INSERT INTO `yunws`.`tp_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`) VALUES (NULL, '4', '收支记录', 'Order', 'income', '0', '1', '1');

INSERT INTO `yunws`.`tp_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`) VALUES (NULL, '4', '红包开店收支明细', 'Order', 'payfor_log', '0', '1', '1');

INSERT INTO `yunws`.`tp_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`) VALUES (NULL, '4', '购物收支明细', 'Order', 'buy_log', '0', '1', '1');

INSERT INTO `yunws`.`tp_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`) VALUES (NULL, '3', '品牌管理', 'Product', 'brand', '0', '1', '1');

CREATE TABLE IF NOT EXISTS `tp_product_brand` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL COMMENT '商铺品牌名',
  `pic` varchar(200) NOT NULL COMMENT '品牌图片',
  `order_by` int(100) NOT NULL DEFAULT '0' COMMENT '排序，越小越前面',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用（1：启用；  0：禁用）',
  PRIMARY KEY (`brand_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商铺品牌表' AUTO_INCREMENT=70 ;

INSERT INTO `tp_product_brand` (`brand_id`, `name`, `pic`, `order_by`, `status`) VALUES
(17, '梦妆', 'brand/2015/12/567a15a016bae.png', 0, 1),
(19, '妮维雅', 'brand/2015/12/567a14599171f.png', 0, 1),
(20, '花印', 'brand/2015/12/567a146851a20.png', 0, 1),
(21, '迪奥', 'brand/2015/12/567a14a8b02b2.png', 0, 1),
(22, '资生堂', 'brand/2015/12/567a14bad1fe4.png', 0, 1),
(23, '欧莱雅', 'brand/2015/12/567a14cd7ad47.png', 0, 1),
(24, '泊美', 'brand/2015/12/567a15dd557bc.png', 0, 1),
(25, '新面孔', 'brand/2015/12/567a15f9d7bd5.png', 0, 1),
(26, '相宜本草', 'brand/2015/12/567a160fae42b.png', 0, 1),
(27, '金珀莱', 'brand/2015/12/567a162107a42.png', 0, 1),
(29, '玛丽黛佳', 'brand/2015/12/567a5227a6dc5.png', 0, 1),
(30, '韩蓓丽', 'brand/2015/12/567a5292df2c0.png', 0, 1),
(31, '火烈鸟', 'brand/2015/12/567a52b0b8fca.png', 0, 1),
(32, '查明一猫', 'brand/2015/12/567a52d1c8f08.png', 0, 1),
(33, 'UOUO', 'brand/2015/12/567a52e754c20.png', 0, 1),
(34, '梦妆', 'brand/2015/12/567a530aef5fa.png', 0, 1),
(35, '欧莱雅', 'brand/2015/12/567a532482dc5.png', 0, 1),
(36, '资生堂', 'brand/2015/12/567a5341854b4.png', 0, 1),
(37, '迪奥', 'brand/2015/12/567a5358bd203.png', 0, 1),
(38, '妮维雅', 'brand/2015/12/567a536c41fab.png', 0, 1),
(39, '海飞丝', 'brand/2015/12/567a589e42fb5.png', 0, 1),
(40, '美涛', 'brand/2015/12/567a58adce7f1.png', 0, 1),
(41, '欧莱雅', 'brand/2015/12/567a58bbaf7f9.png', 0, 1),
(42, '飘柔', 'brand/2015/12/567a58cf77e4f.png', 0, 1),
(43, '施华蔻', 'brand/2015/12/567a58df1392a.png', 0, 1),
(44, '舒蕾', 'brand/2015/12/567a58f156c5e.png', 0, 1),
(45, '丝蕴', 'brand/2015/12/567a58ffeaf71.png', 0, 1),
(46, '清扬', 'brand/2015/12/567a590c23d6f.png', 0, 1),
(47, '潘婷', 'brand/2015/12/567a59d7f2532.jpg', 0, 1),
(48, '沙宣', 'brand/2015/12/567a5927858d1.jpg', 0, 1),
(49, '飘柔', 'brand/2015/12/567a59e10f849.png', 0, 1),
(60, '梦仙奴.', 'brand/2015/12/567a62db73ef7.png', 0, 1),
(61, '雅顿', 'brand/2015/12/567a62eab9235.png', 0, 1),
(62, '香奈儿', 'brand/2015/12/567a62f8e619b.png', 0, 1),
(63, '宝格丽', 'brand/2015/12/567a6308b32d4.png', 0, 1),
(64, '安娜苏', 'brand/2015/12/567a631a92668.png', 0, 1),
(65, '小雏菊', 'brand/2015/12/567a632a2331c.png', 0, 1),
(66, 'ck凯文克莱', 'brand/2015/12/567a633ebdd96.png', 0, 1),
(67, '兰蔻', 'brand/2015/12/567a634b71248.png', 0, 1),
(68, '娇兰', 'brand/2015/12/567a63571e5af.png', 0, 1),
(69, '马克贾克', 'brand/2015/12/567a636aab535.png', 0, 1);