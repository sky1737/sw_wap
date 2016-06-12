INSERT INTO `pigcms_product` (`uid`, `store_id`, `name`, `sale_way`, `buy_way`, `type`, `quantity`, `price`, `original_price`, `code`, `image`, `image_size`, `status`, `date_added`, `intro`, `info`, `properties`, `cost_price`, `sort`,`_id`,`_cid`) 
select 58,91, `name`,0,1,0,1000,vprice,price,id,logourl,'',1,1449734113,'',`intro`,'',oprice,`sort`,id,catid from tp_product where token = 'emynao1445223957'
;



UPDATE  `pigcms_product` SET category_id = ( SELECT cat_id
FROM pigcms_product_category
WHERE _id = pigcms_product._cid );
;


UPDATE  `pigcms_product` SET category_fid = ( SELECT cat_fid
FROM pigcms_product_category
WHERE _id = pigcms_product._cid );


INSERT INTO `pigcms_product_image`(`product_id`, `image`, `sort`) 
SELECT `product_id`, `image`, 1 FROM `pigcms_product` WHERE product_id > 22;


update pigcms_product set info = replace(info,'&lt;','<');
update pigcms_product set info = replace(info,'&gt;','>');
update pigcms_product set info = replace(info,'&quot;','"');
update pigcms_product set info = replace(info,'"/uploads/','"http://mp.91sws.com/uploads/');
