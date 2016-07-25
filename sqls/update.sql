ALTER TABLE  `tp_agent` ADD  `is_agent` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否代理' AFTER  `open_self` ,
ADD  `is_editor` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否产品专员' AFTER  `is_agent`;

#店铺 供应商编码
ALTER TABLE `tp_store`
ADD COLUMN `supplier_code`  varchar(255) NULL AFTER `agent_id`;