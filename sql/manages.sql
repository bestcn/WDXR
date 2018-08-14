/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : wdxr

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-05-18 16:54:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `manages`
-- ----------------------------
DROP TABLE IF EXISTS `manages`;
CREATE TABLE `manages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `makecoll` varchar(255) NOT NULL,
  `company_id` varchar(255) NOT NULL,
  `money` varchar(255) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of manages
-- ----------------------------
INSERT INTO `manages` VALUES ('2', '456456456456', '测试', '3', '管理费', '1495093318');
