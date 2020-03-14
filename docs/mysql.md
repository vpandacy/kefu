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