### mysql表改动记录
#### 20191102
```
CREATE TABLE `group_chat` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `sn` varchar(255) NOT NULL DEFAULT '' COMMENT '风格编号',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态,0异常,1正常',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='风格表';

CREATE TABLE `group_chat_staff` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_chat_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '风格ID',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `staff_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '员工ID',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态,0异常,1正常',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='风格员工关系表';

CREATE TABLE `black_list` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'ip地址',
  `visitor_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '访客ID',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `staff_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '接待员工ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '已删除,1正常',
  `expired_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '失效时间',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='黑名单';

CREATE TABLE `common_word` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `words` varchar(255) NOT NULL DEFAULT '' COMMENT '常用语',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0已删除,1正常',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='常用语';	    
```
#### 20191210
```
CREATE TABLE `leave_message` (
  `id` bigint(20) NOT NULL COMMENT '主键' AUTO_INCREMENT PRIMARY KEY,
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',
  `wechat` varchar(255) NOT NULL DEFAULT '' COMMENT '微信号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '留言信息',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  KEY `merchant_id` (`merchant_id`)
) ENGINE='InnoDB' DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='留言表';


CREATE TABLE `reception_rule` (
  `id` bigint NOT NULL COMMENT '主键' AUTO_INCREMENT,
  `group_chat_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '风格分组ID',
  `merchant_id` bigint NOT NULL DEFAULT '0' COMMENT '商户ID',
  `distribution_mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分配方式',
  `reception_rule` tinyint(1) NOT NULL DEFAULT '0' COMMENT '接待规则,0人工客服优先',
  `reception_strategy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '接待策略,0风格分组优先,1管理员优先',
  `shunt_mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分流规则,0指定客服,1区域分流,2搜索引擎',
  `shunt_data` varchar(5000) NOT NULL DEFAULT '' COMMENT '分流接待规则数据',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_merchant_group` (`merchant_id`,`group_chat_id`)
) ENGINE='InnoDB' DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='接待规则表';


CREATE TABLE `group_chat_setting` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_chat_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '风格ID',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `company_name` varchar(255) NOT NULL DEFAULT '' COMMENT '公司名称',
  `company_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '公司简介',
  `company_logo` varchar(255) NOT NULL DEFAULT '' COMMENT '公司logo',
  `is_history` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否展示消息记录,0展示,1不展示',
  `province_id` int(10) NOT NULL DEFAULT '0' COMMENT '所在省份,该省外不能使用客服',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否主动发起会话,0主动,1不主动',
  `windows_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '浮动窗口展示状态,0最小化,1展示',
  `is_force` tinyint(4) NOT NULL DEFAULT '0' COMMENT '新消息是否强制弹窗,0强制,1不强制',
  `lazy_time` int(10) NOT NULL DEFAULT '0' COMMENT '首次发起时间,单位s',
  `is_show_num` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否展示消息数量,0展示,1不展示',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_merchant_group` (`merchant_id`,`group_chat_id`)
) ENGINE='InnoDB' DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='风格设置表';
```