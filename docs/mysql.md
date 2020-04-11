## 数据库变更记录

#### 20200303 郭威
```
use chat_db;
ALTER TABLE `common_word` ADD `title` VARCHAR(50)  NOT NULL  DEFAULT ''  COMMENT '常用语标题'  AFTER `merchant_id`;
```

#### 20200314 郭威
```
ALTER TABLE `guest_history_log` ADD `has_talked` TINYINT(3)  NOT NULL  DEFAULT '0'  COMMENT '访客是否说话 1： 有 0：没有'  AFTER `chat_stype_id`;

ALTER TABLE `guest_history_log` ADD `has_mobile` TINYINT(3)  NOT NULL  DEFAULT '0'  COMMENT '是否有手机号码号码 1 有 0 无'  AFTER `has_talked`;

ALTER TABLE `guest_history_log` ADD `has_email` TINYINT(3)  NOT NULL  DEFAULT '0'  COMMENT '是否有邮箱 1：有 0：无'  AFTER `has_mobile`;

```

#### 20200405 郭威
```
use chat_logs;
CREATE TABLE `app_guest_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cookie` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'cookie',
  `uuid` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'uuid',
  `referer` varchar(500) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'referer',
  `ip` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `ua` varchar(400) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'ua',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '插入时间',
  PRIMARY KEY (`id`),
  KEY `idx_created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='游客访问日志';
```

#### 20200411 郭威
```
use chat_db;
ALTER TABLE `leave_message` ADD `client_ip` VARCHAR(25)  NOT NULL  DEFAULT ''  COMMENT '客户端ip'  AFTER `message`;
ALTER TABLE `leave_message` ADD `land_url` VARCHAR(500)  NOT NULL  DEFAULT ''  COMMENT '落地页'  AFTER `client_ip`;

```
