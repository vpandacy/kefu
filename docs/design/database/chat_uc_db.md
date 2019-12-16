### uc_db表结构
由chat库迁移过来
```
CREATE TABLE `merchant` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '默认主键',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商户或公司名',
  `sn` varchar(255) NOT NULL DEFAULT '' COMMENT '商户编号',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '商户图标',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '商户简介',
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '商户联系方式',
  `app_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用ID,详细请查看uc下的常量',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态,-2,待审核,-1,审核失败,0,禁用,1,审核成功',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8mb4_general_ci CHARSET=utf8mb4 COMMENT='商户表';

CREATE TABLE `merchant_setting` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '商户配置表',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `auto_disconnect` int(11) NOT NULL DEFAULT '0' COMMENT '自动断开时长',
  `greetings` varchar(255) NOT NULL DEFAULT '' COMMENT '问候语',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='商户企业配置表';

CREATE TABLE `department` (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '部门名',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '所属商户',
  `app_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用ID,详细请查看uc下的常量',
  `status` bigint(20) NOT NULL DEFAULT '0' COMMENT '状态,0已删除,1正常',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='部门表';

CREATE TABLE `staff` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sn` varchar(255) NOT NULL DEFAULT '' COMMENT '员工sn',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱帐号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',
  `department_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '部门ID',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(255) NOT NULL DEFAULT '' COMMENT '盐值',
  `listen_nums` varchar(255) NOT NULL DEFAULT '0' COMMENT '接听数',
  `app_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '应用ID,逗号间隔,详细请查看uc下的常量',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,禁用,1启用',
  `is_root` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是超级管理员,0不是,1是',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='员工表';

CREATE TABLE `role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名',
  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `app_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用ID,详细请查看uc下的常量',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态,0禁用,1启用',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色表';

CREATE TABLE `staff_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `staff_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '员工ID',
  `role_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `app_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用ID,详细请查看uc下的常量',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '状态,0异常,1正常',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='员工角色表';

CREATE TABLE `action` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `level1_name` varchar(50) NOT NULL DEFAULT '' COMMENT '一级菜单名称',
  `level2_name` varchar(50) NOT NULL DEFAULT '' COMMENT '二级菜单名称',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '操作名称',
  `urls` varchar(255) NOT NULL DEFAULT '0' COMMENT '操作链接',
  `weight` tinyint(4) NOT NULL DEFAULT '0' COMMENT '权重',
  `level1_weight` tinyint(3) NOT NULL DEFAULT '0' COMMENT '一级菜单排序权重',
  `level2_weight` tinyint(3) NOT NULL DEFAULT '0' COMMENT '一级菜单排序权重',
  `app_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用ID,详细请查看uc下的常量',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1:启用 0:禁用',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='权限表';

CREATE TABLE `role_action` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `action_id` int(11) NOT NULL DEFAULT '0' COMMENT '权限ID',
  `app_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用ID,详细请查看uc下的常量',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '状态,0异常,1正常',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色权限表';
```
### 20191213
```
ALTER TABLE `staff`
    ADD `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '用户昵称' AFTER `merchant_id`,
    ADD `is_online` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在线,0不在线,1在线' AFTER `listen_nums`;
```
### 20191216
```
CREATE TABLE `queue_captcha` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型 1：邮件 2：手机',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '账号 可能是邮件地址或者手机号码',
  `captcha` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT '客户端ip',
  `expires_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '过期时间 一般有效期5分钟',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '使用状态 0：未使用 1：已使用',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '插入时间',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最近一次更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_account_type` (`account`,`type`),
  KEY `idx_created_time` (`created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='验证码表';

CREATE TABLE `queue_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(256) NOT NULL DEFAULT '' COMMENT '手机号码',
  `sign` varchar(10) NOT NULL DEFAULT '' COMMENT '签名',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '发送手机内容',
  `channel` varchar(30) NOT NULL DEFAULT '' COMMENT '发送渠道名称',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '发送状态 1成功 0 失败 -2 等待发送 -1 发送中',
  `return_msg` varchar(500) NOT NULL DEFAULT '' COMMENT '返回信息',
  `taskid` varchar(60) NOT NULL DEFAULT '' COMMENT '任务id',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '客户端发送ip',
  `send_number` int(11) NOT NULL DEFAULT '1' COMMENT '发送条数，默认1',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最近一次更新时间',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '插入时间',
  PRIMARY KEY (`id`),
  KEY `idx_status_mobile` (`status`,`mobile`),
  KEY `idx_ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信发送队列表';
```