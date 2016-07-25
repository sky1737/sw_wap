ALTER TABLE  `tp_agent` ADD  `is_agent` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否代理' AFTER  `open_self` ,
ADD  `is_editor` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '是否产品专员' AFTER  `is_agent`;