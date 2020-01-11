### 日志数据库
```
CREATE DATABASE `chat_logs_db` DEFAULT CHARACTER SET = `utf8mb4` COLLATE=utf8mb4_general_ci;

CREATE TABLE `app_access_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(10) NOT NULL DEFAULT '0' COMMENT '商户id',
  `staff_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户表id',
  `staff_name` varchar(20) NOT NULL DEFAULT '' COMMENT '员工名字',
  `referer_url` varchar(1000) NOT NULL DEFAULT '' COMMENT '当前访问的refer',
  `target_url` varchar(1000) NOT NULL DEFAULT '' COMMENT '访问的url',
  `query_params` varchar(1000) NOT NULL DEFAULT '' COMMENT 'get和post参数',
  `ua` varchar(1000) NOT NULL DEFAULT '' COMMENT '访问ua',
  `ip` varchar(32) NOT NULL DEFAULT '' COMMENT '访问ip',
  `ip_desc` varchar(30) NOT NULL DEFAULT '' COMMENT 'ip对应城市信息',
  `note` varchar(1000) NOT NULL DEFAULT '' COMMENT 'json格式备注字段',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '插入日期',
  PRIMARY KEY (`id`),
  KEY `idx_staff_id` (`staff_id`),
  KEY `idx_created_time_merchant` (`created_time`,`merchant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户访问日志记录表';

CREATE TABLE `app_err_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(30) NOT NULL DEFAULT '' COMMENT 'app 名字',
  `request_uri` varchar(255) NOT NULL DEFAULT '' COMMENT '请求uri',
  `referer` varchar(500) NOT NULL DEFAULT '' COMMENT '来源url',
  `content` varchar(3000) NOT NULL DEFAULT '' COMMENT '日志内容',
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip',
  `ua` varchar(1000) NOT NULL DEFAULT '' COMMENT 'ua信息',
  `cookies` varchar(1000) NOT NULL DEFAULT '' COMMENT 'cookie信息。如果有的话',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '插入时间',
  PRIMARY KEY (`id`),
  KEY `idx_created_time_app_name` (`created_time`,`app_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='app错误日表';
```
### 20200109
``` 
CREATE TABLE `cs_login_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `staff_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '员工ID',
  `ua` varchar(255) NOT NULL DEFAULT '' COMMENT 'ua',
  `source` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登录终端',
  `login_ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登录IP',
  `login_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登录时间',
  `logout_time` datetime DEFAULT NULL COMMENT '退出时间',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '登录时间',
  PRIMARY KEY (`id`),
  KEY `merchant_id_staff_id` (`merchant_id`,`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='客服登录日志'
```