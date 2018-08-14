/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : wdxr

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-05-18 16:54:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `company_report`
-- ----------------------------
DROP TABLE IF EXISTS `company_report`;
CREATE TABLE `company_report` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) NOT NULL COMMENT '公司ID',
  `report` varchar(255) DEFAULT '' COMMENT '征信报告',
  `verify_id` bigint(20) DEFAULT NULL COMMENT '审核ID',
  `end_time` int(11) DEFAULT NULL COMMENT '审核的截止时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_report
-- ----------------------------
INSERT INTO `company_report` VALUES ('2', '22', '236', '7', '0');
INSERT INTO `company_report` VALUES ('3', '23', '273,274', '68', '0');
INSERT INTO `company_report` VALUES ('4', '23', '275', '69', '0');
INSERT INTO `company_report` VALUES ('5', '24', '295', '74', '0');
INSERT INTO `company_report` VALUES ('6', '27', '346,347,348', '83', '0');
INSERT INTO `company_report` VALUES ('7', '30', '383', '97', null);
INSERT INTO `company_report` VALUES ('8', '29', '384', '98', null);
INSERT INTO `company_report` VALUES ('9', '22', '', null, '1497715200');
