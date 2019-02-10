/*
 Navicat Premium Data Transfer

 Source Server         : 菜菜本地
 Source Server Type    : MySQL
 Source Server Version : 50719
 Source Host           : localhost:3306
 Source Schema         : yb_leave_school

 Target Server Type    : MySQL
 Target Server Version : 50719
 File Encoding         : 65001

 Date: 10/02/2019 16:42:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for leave_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `leave_admin_user`;
CREATE TABLE `leave_admin_user`  (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `collegename` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '所属学院',
  `if_super_admin` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '是否是超级管理员',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_admin_user
-- ----------------------------
INSERT INTO `leave_admin_user` VALUES (1, '信息学院', 'root', '信息学院', '否');
INSERT INTO `leave_admin_user` VALUES (3, 'admin', 'root', '', '是');

-- ----------------------------
-- Table structure for leave_app_info
-- ----------------------------
DROP TABLE IF EXISTS `leave_app_info`;
CREATE TABLE `leave_app_info`  (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `yb_info` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '前台简介',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '前台信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_app_info
-- ----------------------------
INSERT INTO `leave_app_info` VALUES (1, '五一放假|5月1号-5月5号|5月5号晚上|4月29号晚上|大家玩得开心|有问题找易班长解决');

-- ----------------------------
-- Table structure for leave_app_user
-- ----------------------------
DROP TABLE IF EXISTS `leave_app_user`;
CREATE TABLE `leave_app_user`  (
  `yb_userid` int(20) NOT NULL COMMENT '用户id',
  `yb_username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `yb_usertoken` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户授权',
  `yb_userhead` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户头像',
  `yb_schoolname` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户学校',
  PRIMARY KEY (`yb_userid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_app_user
-- ----------------------------
INSERT INTO `leave_app_user` VALUES (8507345, '忆梦', '70fc21848c23de5538a907a5a63d9daef71eeba0', 'http://img02.fs.yiban.cn/8507345/avatar/user/200', '广东财经大学');

-- ----------------------------
-- Table structure for leave_collegename
-- ----------------------------
DROP TABLE IF EXISTS `leave_collegename`;
CREATE TABLE `leave_collegename`  (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `yb_collegename` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '学院',
  `yb_classname` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '专业',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `yb_collegename`(`yb_collegename`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '更新前台学院信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_collegename
-- ----------------------------
INSERT INTO `leave_collegename` VALUES (1, '信息学院', '计算机科学与技术|电子商务|软件工程|信息管理与信息系统');

-- ----------------------------
-- Table structure for leave_datalist
-- ----------------------------
DROP TABLE IF EXISTS `leave_datalist`;
CREATE TABLE `leave_datalist`  (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `yb_userid` int(20) NOT NULL COMMENT '用户id',
  `yb_username` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '姓名',
  `yb_enteryear` int(20) NOT NULL COMMENT '用户年级',
  `yb_collegename` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户学院',
  `yb_classname` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户班级',
  `yb_if_leave` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户是否离校',
  `yb_other_info` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户填写其他信息',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `yb_userid`(`yb_userid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户填写信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_datalist
-- ----------------------------
INSERT INTO `leave_datalist` VALUES (1, 8507345, '蔡坚鑫', 2016, '信息学院', '计算机科学与技术+1班', '是', '2019-02-10|2019-02-11|回家|15802090624');

SET FOREIGN_KEY_CHECKS = 1;
