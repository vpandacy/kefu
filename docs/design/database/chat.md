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