INSERT INTO  `pigcms_product_category` (  `cat_name` ,  `cat_desc` ,  `cat_fid` ,  `cat_pic` ,  `cat_pc_pic` ,  `cat_status` ,  `cat_sort` ,  `cat_path` ,  `cat_level` ,  `filter_attr` ,  `tag_str` , `cat_parent_status` ,  `_id` ,  `_pid` ) 
SELECT  `name` ,  `des` , 0,  `logourl` ,  `logourl` , 1,  `sort` ,  '0',  '0',  '',  '', 1,  `id` ,  `parentid` 
FROM  `tp_product_cat` 
WHERE  `token` =  'emynao1445223957'

UPDATE pigcms_product_category
SET `cat_fid` = (SELECT `cat_id` FROM pigcms_product_category2 WHERE _id = pigcms_product_category._pid)

update pigcms_product_category set cat_level = 1 where cat_fid = 0;
update pigcms_product_category set cat_level = 2 where cat_fid <> 0;

UPDATE  `pigcms_product_category` SET  `cat_path` = CONCAT(  '0,', cat_fid,  ',', cat_id );
UPDATE `pigcms_product_category` SET `cat_path`= replace(`cat_path`,'0,0,','0,') where cat_path like '0,0,%';