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