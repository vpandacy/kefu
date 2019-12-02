### mysql表改动记录
#### 20191102
	CREATE TABLE `merchants` (
	  `id` bigint NOT NULL COMMENT '默认主键' AUTO_INCREMENT PRIMARY KEY,
	  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',
	  `merchant_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商户名',
	  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
	  `salt` varchar(255) NOT NULL DEFAULT '' COMMENT '盐值',
	  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态,0启用,-1禁用',
	  `last_login_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后一次登录时间',
	  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
	  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
	) COMMENT='商户表' COLLATE 'utf8mb4_general_ci';

	CREATE TABLE `departments` (
	  `id` bigint(20) NOT NULL COMMENT '主键',
	  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '部门名',
	  `merchant_id` bigint NOT NULL DEFAULT '0' COMMENT '所属商户',
	  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
	  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
	) COMMENT='部门表' COLLATE 'utf8mb4_general_ci';
		
	ALTER TABLE `merchants`
	ADD `avatar` varchar(255) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '' COMMENT '头像' AFTER `mobile`,
	CHANGE `merchant_name` `merchant_name` varchar(255) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '' COMMENT '商户或公司名' AFTER `avatar`;

	CREATE TABLE `company_info` (
	  `id` bigint(20) NOT NULL COMMENT '主键' AUTO_INCREMENT PRIMARY KEY,
	  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
	  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '展示标题',
	  `company_name` varchar(255) NOT NULL DEFAULT '' COMMENT '公司名字',
	  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '公司简介',
	  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '公司联系方式',
	  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
	  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
	) COMMENT='公司信息表' COLLATE 'utf8mb4_general_ci';

	ALTER TABLE `company_info`
	ADD `auto_disconnect` int NOT NULL DEFAULT '0' COMMENT '自动断开时长' AFTER `merchant_id`;

	CREATE TABLE `employees` (
	  `id` bigint(20) NOT NULL COMMENT '主键' AUTO_INCREMENT PRIMARY KEY,
	  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
	  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
	  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '手机号',
	  `department_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '部门ID',
	  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
	  `salt` varchar(255) NOT NULL DEFAULT '' COMMENT '盐值',
	  `listen_nums` varchar(255) NOT NULL DEFAULT '0' COMMENT '接听数',
	  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,启用,1禁用',
	  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
	  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
	) COMMENT='员工表' COLLATE 'utf8mb4_general_ci';

	ALTER TABLE `employees`
	ADD `merchant_id` bigint NOT NULL DEFAULT '0' COMMENT '商户ID' AFTER `id`;

	CREATE TABLE `role` (
	  `id` bigint(20) NOT NULL COMMENT '主键' AUTO_INCREMENT PRIMARY KEY,
	  `role_name` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名',
	  `department_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '父级ID',
	  `status` tinyint NOT NULL DEFAULT '0' COMMENT '状态,0启用,1禁用',
	  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
	  `udated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
	) COMMENT='角色表' COLLATE 'utf8mb4_general_ci';

	CREATE TABLE `role_permission` (
	  `id` bigint(20) NOT NULL COMMENT '主键' AUTO_INCREMENT PRIMARY KEY,
	  `role_id` bigint NOT NULL DEFAULT '0' COMMENT '角色ID',
	  `perm_id` int NOT NULL DEFAULT '0' COMMENT '权限ID',
	  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
	  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
	) COMMENT='角色权限表' COLLATE 'utf8mb4_general_ci';

	CREATE TABLE `black_list` (
	  `id` bigint NOT NULL COMMENT '主键' AUTO_INCREMENT PRIMARY KEY,
	  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'ip地址',
	  `visitor_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '访客ID',
	  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
	  `employee_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '接待员工ID',
	  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未删除,1删除',
	  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
	  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
	) COMMENT='黑名单' COLLATE 'utf8mb4_general_ci';

	ALTER TABLE `role`
	CHANGE `department_id` `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID' AFTER `role_name`;

	CREATE TABLE `common_words` (
	  `id` bigint(20) NOT NULL COMMENT '主键' AUTO_INCREMENT PRIMARY KEY,
	  `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
	  `words` varchar(255) NOT NULL DEFAULT '' COMMENT '常用语',
	  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,未删除,1删除',
	  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
	  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
	) COMMENT='常用语' COLLATE 'utf8mb4_general_ci';
	
	ALTER TABLE `employees`
    ADD `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱帐号' AFTER `merchant_id`;
### 20191103