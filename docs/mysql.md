## 数据库变更记录

#### 20200303 郭威
```
ALTER TABLE `common_word` ADD `title` VARCHAR(50)  NOT NULL  DEFAULT ''  COMMENT '常用语标题'  AFTER `merchant_id`;
```