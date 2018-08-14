USE guanjia16_new;

-- 修改列结构及创建新数据表

ALTER TABLE achievement ADD COLUMN `status` TINYINT COMMENT '状态' DEFAULT 1;
ALTER TABLE achievement ADD COLUMN `admin_id` BIGINT COMMENT '业务员ID' ;
ALTER TABLE achievement ADD COLUMN `service_id` BIGINT COMMENT '业务员ID' ;

ALTER TABLE admins CHANGE `pic` `avatar` BIGINT COMMENT '头像';
ALTER TABLE admins ADD COLUMN `branch_id` BIGINT COMMENT '分公司ID' DEFAULT NULL ;
ALTER TABLE admins ADD COLUMN `department_id` BIGINT COMMENT '部门ID' DEFAULT NULL ;
ALTER TABLE admins ADD COLUMN `is_probation` TINYINT COMMENT '是否试用期' DEFAULT 0;
ALTER TABLE admins ADD COLUMN `on_job` TINYINT COMMENT '是否在职' DEFAULT 1;
ALTER TABLE admins ADD COLUMN `status` TINYINT COMMENT '基本状态' DEFAULT 1;
ALTER TABLE admins ADD COLUMN `is_lock` TINYINT COMMENT '是否锁定' DEFAULT 0;
ALTER TABLE admins ADD COLUMN `update_at` DATETIME COMMENT '更新时间' DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE admins ADD COLUMN `created_by` BIGINT COMMENT '创建人ID';
ALTER TABLE admins ADD COLUMN `entry_time` DATETIME COMMENT '入职时间' DEFAULT NULL ;
ALTER TABLE admins ADD COLUMN `formal_time` DATETIME COMMENT '转正时间' DEFAULT NULL ;
ALTER TABLE admins ADD COLUMN `created_at` TIMESTAMP COMMENT '创建时间' DEFAULT CURRENT_TIMESTAMP;

create table admin_log (
  id varchar(128) not null primary key,
  name varchar(200) default '' not null,
  description varchar(1024) null,
  created_at timestamp default CURRENT_TIMESTAMP not null,
  class varchar(128) default '' not null,
  action varchar(128) default '' not null,
  parameters varchar(128) null,
  admin_id bigint not null
);

create table branchs_commission (
  id int auto_increment
    primary key,
  amount double(20,2) not null,
  ratio double(20,2) not null,
  `time` DATETIME default CURRENT_TIMESTAMP NOT NULL ,
  type int not null
);

create table branchs_commission_list (
  id int auto_increment
    primary key,
  name varchar(255) not null,
  type int not null comment '状态',
  ratio double(20,2) not null comment '比率',
  `time` DATETIME default CURRENT_TIMESTAMP NOT NULL ,
  branchs_id int default '0' not null comment '分公司ID'
);

create table branchs_levels (
  id int auto_increment
    primary key,
  level_name varchar(255) null comment '等级名称',
  level_status tinyint(3) default '0' null comment '等级状态'
) comment '分公司等级表';

-- ----------------------------
-- Table structure for `bonus_system`
-- ----------------------------
DROP TABLE IF EXISTS `bonus_system`;
CREATE TABLE `bonus_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recommend` tinyint(4) NOT NULL DEFAULT '1' COMMENT '推荐人  1合伙人  2普惠',
  `customer` tinyint(4) NOT NULL DEFAULT '1' COMMENT '新客户  1合伙人  2普惠',
  `first` decimal(10,2) DEFAULT '0.00',
  `second` decimal(10,2) DEFAULT '0.00',
  `third` decimal(10,2) DEFAULT '0.00',
  `fourth` decimal(10,2) DEFAULT '0.00',
  `fifth` decimal(10,2) DEFAULT '0.00',
  `sixth` decimal(10,2) DEFAULT '0.00',
  `seventh` decimal(10,2) DEFAULT '0.00',
  `eighth` decimal(10,2) DEFAULT '0.00',
  `ninth` decimal(10,2) DEFAULT '0.00',
  `tenth` decimal(10,2) DEFAULT '0.00',
  `eleventh` decimal(10,2) DEFAULT '0.00',
  `twelfth` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bonus_system
-- ----------------------------
INSERT INTO `bonus_system` VALUES ('3', '1', '1', '800.00', '1400.00', '2000.00', '2600.00', '3200.00', '3800.00', '4400.00', '5000.00', '5200.00', '5400.00', '5600.00', '5800.00');
INSERT INTO `bonus_system` VALUES ('2', '2', '2', '200.00', '350.00', '500.00', '650.00', '800.00', '950.00', '1100.00', '1250.00', '1400.00', '1550.00', '1700.00', '1850.00');
INSERT INTO `bonus_system` VALUES ('6', '1', '2', '400.00', '700.00', '1000.00', '1300.00', '1600.00', '1900.00', '2200.00', '2500.00', '2800.00', '3100.00', '3400.00', '3700.00');
INSERT INTO `bonus_system` VALUES ('7', '2', '1', '320.00', '560.00', '800.00', '1040.00', '1280.00', '1520.00', '1760.00', '2000.00', '2240.00', '2480.00', '2720.00', '2960.00');


-- ----------------------------
-- Table structure for `bonus_list`
-- ----------------------------
DROP TABLE IF EXISTS `bonus_list`;
CREATE TABLE `bonus_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(255) DEFAULT NULL COMMENT '业务员ID',
  `admin_id` int(11) DEFAULT NULL COMMENT '业务员ID',
  `branch_id` int(11) DEFAULT NULL,
  `contract_num` varchar(255) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT '0.00',
  `time` int(11) DEFAULT NULL,
  `recommender` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `commission` decimal(10,2) DEFAULT '0.00' COMMENT '业务员奖金',
  `bonus` decimal(10,2) DEFAULT '0.00' COMMENT '客户奖金',
  `company_id` int(11) DEFAULT '0' COMMENT '新客户ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `commission` ADD COLUMN `branch_id` BIGINT COMMENT '分公司ID' DEFAULT NULL ;
ALTER TABLE `commission` ADD COLUMN `created_by` BIGINT COMMENT '创建人ID' DEFAULT NULL ;

ALTER TABLE `commission_list` ADD COLUMN `branch_id` BIGINT COMMENT '分公司ID' DEFAULT NULL ;
ALTER TABLE `commission_list` ADD COLUMN `status` BIGINT COMMENT '状态  试用期   非试用期   1 / 0' DEFAULT NULL ;

ALTER TABLE `company_payment` ADD COLUMN `level_id` BIGINT COMMENT '级别ID' DEFAULT NULL ;
ALTER TABLE `company_payment` ADD COLUMN `service_id` BIGINT COMMENT '级别ID' DEFAULT NULL ;

ALTER TABLE `company_recommend` ADD COLUMN `status` TINYINT COMMENT '推荐关系' DEFAULT 1;

ALTER TABLE `company_report` ADD COLUMN `service_id` BIGINT COMMENT '订阅服务ID' ;

ALTER TABLE `company_service` ADD COLUMN `bill_id` BIGINT COMMENT '票据ID' ;
# ALTER TABLE `company_service` ADD COLUMN `payment_id` BIGINT COMMENT '付款ID' ;
# ALTER TABLE `company_service` ADD COLUMN `contract_id` BIGINT COMMENT '合同ID' ;
ALTER TABLE `company_service` ADD COLUMN `level_id` BIGINT COMMENT '级别ID' ;
ALTER TABLE `company_service` ADD COLUMN `bill_status` TINYINT COMMENT '票据状态' ;
ALTER TABLE `company_service` ADD COLUMN `payment_status` TINYINT COMMENT '支付状态' ;
ALTER TABLE `company_service` ADD COLUMN `report_status` TINYINT COMMENT '征信状态' ;
ALTER TABLE `company_service` ADD COLUMN `service_status` TINYINT COMMENT '服务状态' ;
ALTER TABLE `company_service` ADD COLUMN `type` TINYINT COMMENT '客户类型 1合伙人 2普惠' ;

ALTER TABLE `contracts` ADD COLUMN `service_id` BIGINT COMMENT '订阅服务ID';

ALTER TABLE `companys` ADD COLUMN `is_bad` TINYINT COMMENT '是否恶意' DEFAULT 0;
ALTER TABLE `companys` ADD COLUMN `is_rask` TINYINT COMMENT '是否高风险客户' DEFAULT 0;

ALTER TABLE `finances` ADD COLUMN `status` TINYINT COMMENT '报表数据状态  1正常 2票据异常 3征信异常 4企业信息异常 5服务期异常' DEFAULT 1;
ALTER TABLE `finances` ADD COLUMN `info` VARCHAR(255) COMMENT '异常原因的备注' DEFAULT NULL ;

ALTER TABLE `levels` ADD COLUMN `info` VARCHAR(255) COMMENT '报销详细信息' DEFAULT NULL ;

ALTER TABLE `loan` ADD COLUMN `payment_id` BIGINT COMMENT '缴费ID';

ALTER TABLE `manages` ADD COLUMN `status` TINYINT COMMENT '状体' DEFAULT 1;
ALTER TABLE `manages` ADD COLUMN `info` VARCHAR(255) COMMENT '异常原因的备注' DEFAULT NULL ;

ALTER TABLE `recommends` ADD COLUMN `status` TINYINT COMMENT '报表数据状态  1正常 2票据异常 3征信异常 4企业信息异常 5服务期异常' DEFAULT 1;
ALTER TABLE `recommends` ADD COLUMN `info` VARCHAR(255) COMMENT '异常原因的备注' DEFAULT NULL ;

CREATE TABLE `refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info` varchar(255) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `time` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL ,
  `type` TINYINT(4) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT '退费表' DEFAULT CHARSET=utf8;

CREATE TABLE `black_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info` varchar(255) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `time` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL ,
  `company_name` int(11) DEFAULT NULL,
  `type` TINYINT(4) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT '退费表' DEFAULT CHARSET=utf8;

# ALTER TABLE `companys` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `company_info` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `company_payment` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `company_bank` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `company_service` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `company_report` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `company_bill` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `company_bill_info` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `company_recommend` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `contracts` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `contract_file` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `users` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;
# ALTER TABLE `refund` ADD COLUMN `is_delete` TINYINT NOT NULL DEFAULT 0;

-- 数据迁移
UPDATE `admins` SET `status` = if(`active` = 'Y', 1, 0) ;
UPDATE `achievement` SET `admin_id` = (SELECT `admins`.`id` FROM `admins` WHERE `admins`.`name` = `achievement`.`admin_name`);
UPDATE `achievement` SET `service_id` = (SELECT `company_service`.`id` FROM `company_service` JOIN `companys` on `companys`.`id` = `company_service`.`company_id` WHERE `companys`.`name` = `achievement`.`company_name` LIMIT 1);

-- 默认认为当前企业订阅服务没有重复数据
DELETE FROM `company_service` WHERE `company_id` IN (SELECT a.`company_id` FROM (SELECT `company_id` FROM `company_service` GROUP BY `company_id` HAVING COUNT(`company_id`) > 1) a) AND `id` NOT IN (SELECT b.id FROM (SELECT MIN(`id`) `id` FROM `company_service` GROUP BY `company_id` HAVING COUNT(`company_id`) > 1) b);

-- 更新企业订阅服务数据
UPDATE `company_service` SET `type` = (SELECT if(`type` = 4, 2, 1) FROM `company_payment` WHERE `company_service`.`company_id` = `company_payment`.`company_id` and `company_payment`.`status` = 2 LIMIT 1);
UPDATE `company_service` SET `bill_id` = (SELECT `bill_id` FROM `companys` WHERE `company_service`.`company_id` = `companys`.`id`);
# UPDATE `company_service` SET `payment_id` = (SELECT `payment_id` FROM `companys` WHERE `company_service`.`company_id` = `companys`.`id`);
UPDATE `company_service` SET `level_id` = (SELECT `level_id` FROM `companys` WHERE `company_service`.`company_id` = `companys`.`id`);
UPDATE `company_service` SET `payment_status` = (SELECT `payment` FROM `companys` WHERE `company_service`.`company_id` = `companys`.`id`);
UPDATE `company_service` SET `service_status` = (SELECT `status` FROM `companys` WHERE `company_service`.`company_id` = `companys`.`id`);
UPDATE `company_service` SET `bill_status` = (SELECT if(`amount` > 0, 1, 0) FROM `company_bill` WHERE `company_service`.`company_id` = `company_bill`.`company_id` LIMIT 1);
UPDATE `company_service` SET `report_status` = (SELECT `status` FROM `company_report` WHERE `company_service`.`company_id` = `company_report`.`company_id` ORDER BY `status` DESC LIMIT 1);

UPDATE `company_report` SET `service_id` = (SELECT `id` FROM `company_service` WHERE `company_service`.`company_id` = company_report.`company_id` ORDER BY `company_service`.`id` DESC LIMIT 1);
UPDATE `contracts` SET `service_id` = (SELECT `id` FROM `company_service` WHERE `company_service`.`company_id` = contracts.`company_id` ORDER BY `company_service`.`id` DESC LIMIT 1);

UPDATE `companys` SET `is_bad` = (SELECT `out_status`);

UPDATE `company_payment` SET `level_id` = (SELECT `level_id` FROM `companys` where `company_payment`.`company_id` = `companys`.`id`);
UPDATE `company_payment` SET `service_id` = (SELECT `id` FROM `company_service` WHERE `company_service`.`company_id` = company_payment.`company_id` ORDER BY `company_service`.`id` DESC LIMIT 1);

UPDATE `loan` SET `payment_id` = (SELECT `companys`.`payment_id` FROM `companys` WHERE `companys`.`id` = `loan`.company_id);

INSERT INTO `access_list` VALUES
  ('Admins', 'Companys', 'info', 1),
  ('Admins', 'Companys', 'payment', 1),
  ('Admins', 'Companys', 'refund', 1),
  ('Admins', 'Companys', 'user', 1),
  ('Admins', 'Companys', 'setting', 1),
  ('Admins', 'Companys', 'new_list', 1),
#   ('Admins', 'apply', 'payment', 1),
#   ('Admins', 'loan', 'new', 1),
  ('Admins', 'loan', 'edit_list', 1),
  ('Admins', 'loan', 'edit_info', 1),
  ('Admins', 'companys', 'edit_list', 1),
  ('Admins', 'companys', 'edit_info', 1),
  ('Admins', 'companys', 'verify_list', 1),
#   ('Admins', 'companys', 'edit_auditing', 1),
#   ('Admins', 'apply', 'info', 1),
  ('Admins', 'Tools', 'search', 1),
  ('Admins', 'Finance', 'branch_achievement_export', 1),
  ('Admins', 'Companys', 'business', 1);

-- 删除废弃的列及数据表

ALTER TABLE `admins` DROP COLUMN `mustChangePassword`;
ALTER TABLE `admins` DROP COLUMN `banned`;
ALTER TABLE `admins` DROP COLUMN `suspended`;
ALTER TABLE `admins` DROP COLUMN `active`;

ALTER TABLE `company_payment` DROP COLUMN `verify_id`;

ALTER TABLE `company_info` DROP COLUMN `company_id`;
ALTER TABLE `company_info` DROP COLUMN `bankcard_photo`;
ALTER TABLE `company_info` DROP COLUMN `bankcard`;
ALTER TABLE `company_info` DROP COLUMN `bank_province`;
ALTER TABLE `company_info` DROP COLUMN `bank_city`;
ALTER TABLE `company_info` DROP COLUMN `bank_name`;
ALTER TABLE `company_info` DROP COLUMN `bank`;
ALTER TABLE `company_info` DROP COLUMN `bank_type`;
ALTER TABLE `company_info` DROP COLUMN `bank_account`;
ALTER TABLE `company_info` DROP COLUMN `account_holder`;
ALTER TABLE `company_info` DROP COLUMN `category`;

ALTER TABLE `company_info` DROP COLUMN `work_bank`;
ALTER TABLE `company_info` DROP COLUMN `work_bankcard`;
ALTER TABLE `company_info` DROP COLUMN `work_bank_city`;
ALTER TABLE `company_info` DROP COLUMN `work_bank_name`;
ALTER TABLE `company_info` DROP COLUMN `work_bank_province`;
ALTER TABLE `company_info` DROP COLUMN `work_bankcard_photo`;
ALTER TABLE `company_info` DROP COLUMN `work_account_holder`;

ALTER TABLE `company_info` DROP COLUMN `sign_photo`;
ALTER TABLE `company_info` DROP COLUMN `contract`;
ALTER TABLE `company_info` DROP COLUMN `contract_num`;

ALTER TABLE `company_info` DROP COLUMN `verify_id`;

ALTER TABLE `companys` DROP COLUMN `type`;
ALTER TABLE `companys` DROP COLUMN `payment_id`;
ALTER TABLE `companys` DROP COLUMN `bill_id`;
ALTER TABLE `companys` DROP COLUMN `report_id`;
ALTER TABLE `companys` DROP COLUMN `level_id`;
ALTER TABLE `companys` DROP COLUMN `payment`;
ALTER TABLE `companys` DROP COLUMN `out_info`;
ALTER TABLE `companys` DROP COLUMN `out_status`;

ALTER TABLE `contracts` DROP COLUMN `contract_id`;
ALTER TABLE `contracts` DROP COLUMN `sign_id`;

DROP TABLE `admin_menus`;
DROP TABLE `company_benefit`;
DROP TABLE `person`;
DROP TABLE `provinces`;
DROP TABLE `cities`;
DROP TABLE `areas`;
DROP TABLE `version_copy`;
