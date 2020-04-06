## chat_logs_db

```
CREATE TABLE `app_err_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(30) NOT NULL DEFAULT '' COMMENT 'app 名字',
  `request_uri` varchar(255) NOT NULL DEFAULT '' COMMENT '请求uri',
  `referer` varchar(500) NOT NULL DEFAULT '' COMMENT '来源url',
  `content` varchar(5000) NOT NULL DEFAULT '' COMMENT '日志内容',
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip',
  `ua` varchar(1000) NOT NULL DEFAULT '' COMMENT 'ua信息',
  `cookies` varchar(1000) NOT NULL DEFAULT '' COMMENT 'cookie信息。如果有的话',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '插入时间',
  PRIMARY KEY (`id`),
  KEY `idx_created_time_app_name` (`created_time`,`app_name`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='app错误日表';
```