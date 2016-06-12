ALTER TABLE  `tp_user` ADD  `_uid` INT NOT NULL COMMENT  '导入用户id',
ADD  `_pid` INT NOT NULL COMMENT  '导入用户pid';

DELETE FROM  `tp_userinfo` WHERE token <>  'emynao1445223957'

UPDATE  `tp_twitter_count` SET total = ( SELECT total
FROM (
SELECT ROUND( SUM( price ) , 2 ) total, twid
FROM  `tp_twitter_log` 
WHERE token =  'emynao1445223957'
GROUP BY twid ) a
WHERE twid = tp_twitter_count.twid
)
WHERE token =  'emynao1445223957'

update `tp_twitter_count` set total = total - remove where token = 'emynao1445223957' and total > 0;

update `tp_userinfo` set `balance` = (select total from `tp_twiter_count` where twid = tp_userinfo.twid  where token = 'emynao1445223957' and total > 0) where token = 'emynao1445223957';

INSERT INTO `tp_user` (`nickname`, `password`, `phone`, `openid`, 
`reg_time`, `reg_ip`, `last_time`, `last_ip`, `check_phone`, `login_count`, `status`, 
`balance`, `point`, 
`intro`, `avatar`, `is_weixin`, `stores`, 
`token`, `session_id`, `server_key`, `source_site_url`, `payment_url`, `notify_url`, `oauth_url`, `is_seller`, 
`third_id`, `drp_store_id`, `app_id`, `admin_id`, 
`sex`, `country`, `province`, `city`, `parent_uid`, `twid`, `_uid`, `_pid`) 
SELECT  `wechaname`, `password`, `tel`, `wecha_id`, 
`time`, 0, `time`, 0, 0, 1, 1,
`balance`, 0,
'', `portrait`, 1, `isfx`, 
'', '', '', '', '', '', '', `isfx`,
'', 0, 0, 0, 
`sex`, `address`, '', '', 0, `twid`, `id`, `pid` FROM `tp_userinfo` WHERE 1
