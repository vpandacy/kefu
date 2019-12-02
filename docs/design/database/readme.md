    ### mysql表改动记录
    #### 20191102
    CREATE TABLE `black_list` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
      `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'ip地址',
      `visitor_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '访客ID',
      `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
      `staff_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '接待员工ID',
      `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未删除,1删除',
      `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='黑名单';
    
    CREATE TABLE `common_word` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
      `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
      `words` varchar(255) NOT NULL DEFAULT '' COMMENT '常用语',
      `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,未删除,1删除',
      `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='常用语';	
    
    CREATE TABLE `department` (
      `id` bigint(20) NOT NULL COMMENT '主键',
      `name` varchar(255) NOT NULL DEFAULT '' COMMENT '部门名',
      `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '所属商户',
      `is_deleted` bigint(20) NOT NULL DEFAULT '0' COMMENT '是否删除,0未删除,1删除',
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
      `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,启用,1禁用',
      `is_root` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是超级管理员,0不是,1是',
      `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='员工表';
    
    CREATE TABLE `staff_role` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
      `employees_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '员工ID',
      `role_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '角色ID',
      `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='员工角色表';
    
    
    CREATE TABLE `staff_style` (
      `id` bigint(20) NOT NULL COMMENT 'ID',
      `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
      `staff_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '员工ID',
      `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='员工风格关系表';
    
    
    CREATE TABLE `merchant` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '默认主键',
      `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商户或公司名',
      `sn` varchar(255) NOT NULL DEFAULT '' COMMENT '商户编号',
      `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '商户图标',
      `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '商户简介',
      `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '商户联系方式',
      `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态,0启用,-1禁用',
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
    
    
    CREATE TABLE `action` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
      `action_name` varchar(255) NOT NULL DEFAULT '' COMMENT '操作名称',
      `action_url` varchar(255) NOT NULL DEFAULT '0' COMMENT '操作链接',
      `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='权限表';
    
    
    CREATE TABLE `role` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
      `role_name` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名',
      `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
      `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态,0启用,1禁用',
      `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `udated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色表';
    
    
    CREATE TABLE `role_action` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
      `role_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '角色ID',
      `action_id` int(11) NOT NULL DEFAULT '0' COMMENT '权限ID',
      `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色权限表';
    
    
    CREATE TABLE `style` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
      `merchant_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商户ID',
      `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
      `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
      `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
      `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='风格表';