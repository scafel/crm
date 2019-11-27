/*
Navicat MySQL Data Transfer

Source Server         : 本地Mysql
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : crm.yqthyy

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-11-27 15:17:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for scafel_admin
-- ----------------------------
DROP TABLE IF EXISTS `scafel_admin`;
CREATE TABLE `scafel_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `safe` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL,
  `tel` varchar(255) NOT NULL COMMENT '手机号',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `model_id` varchar(255) NOT NULL DEFAULT '0',
  `role_id` varchar(255) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Table structure for scafel_banner
-- ----------------------------
DROP TABLE IF EXISTS `scafel_banner`;
CREATE TABLE `scafel_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(255) NOT NULL DEFAULT 'img' COMMENT '图片位置',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `url` varchar(255) NOT NULL DEFAULT '#' COMMENT '跳转地址',
  `title` varchar(255) DEFAULT 'title' COMMENT '标题',
  `content` text COMMENT '描述',
  `type` varchar(255) DEFAULT 'type',
  `sort` int(11) DEFAULT '0' COMMENT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='轮播图';

-- ----------------------------
-- Table structure for scafel_channel
-- ----------------------------
DROP TABLE IF EXISTS `scafel_channel`;
CREATE TABLE `scafel_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='来源渠道';

-- ----------------------------
-- Table structure for scafel_class
-- ----------------------------
DROP TABLE IF EXISTS `scafel_class`;
CREATE TABLE `scafel_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='客服服务等级表';

-- ----------------------------
-- Table structure for scafel_custom
-- ----------------------------
DROP TABLE IF EXISTS `scafel_custom`;
CREATE TABLE `scafel_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `addtime` varchar(255) NOT NULL DEFAULT '0',
  `remarks` text,
  `username` varchar(255) NOT NULL DEFAULT '0',
  `hisnumber` varchar(255) NOT NULL DEFAULT '0' COMMENT '住院号',
  `bednumber` varchar(255) NOT NULL DEFAULT '0',
  `nexttime` varchar(255) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '回访还是住院  0回访 1住院',
  `question_one` text,
  `question_two` text,
  `lastremarks` text,
  `isin` tinyint(4) DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  `class_id` int(11) DEFAULT '0',
  `intime` varchar(255) DEFAULT '0',
  `outtime` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2584 DEFAULT CHARSET=utf8 COMMENT='客服服务记录表';

-- ----------------------------
-- Table structure for scafel_department
-- ----------------------------
DROP TABLE IF EXISTS `scafel_department`;
CREATE TABLE `scafel_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='就诊科室表';

-- ----------------------------
-- Table structure for scafel_link
-- ----------------------------
DROP TABLE IF EXISTS `scafel_link`;
CREATE TABLE `scafel_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `sort` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for scafel_model
-- ----------------------------
DROP TABLE IF EXISTS `scafel_model`;
CREATE TABLE `scafel_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `model_name` varchar(255) NOT NULL COMMENT '模块名',
  `url` varchar(255) NOT NULL COMMENT '地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COMMENT='模块表';

-- ----------------------------
-- Table structure for scafel_notepad
-- ----------------------------
DROP TABLE IF EXISTS `scafel_notepad`;
CREATE TABLE `scafel_notepad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` int(11) NOT NULL DEFAULT '0' COMMENT '来源',
  `toid` int(11) NOT NULL DEFAULT '0' COMMENT '接收方',
  `message` text NOT NULL COMMENT '内容',
  `addtime` varchar(255) NOT NULL COMMENT '添加时间',
  `isread` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否阅读',
  `isrun` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否执行',
  `runtime` varchar(255) NOT NULL COMMENT '执行时间',
  `custom_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) DEFAULT '1',
  `isedit` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3259 DEFAULT CHARSET=utf8 COMMENT='消息表';

-- ----------------------------
-- Table structure for scafel_role
-- ----------------------------
DROP TABLE IF EXISTS `scafel_role`;
CREATE TABLE `scafel_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='用户权限对照表';

-- ----------------------------
-- Table structure for scafel_user
-- ----------------------------
DROP TABLE IF EXISTS `scafel_user`;
CREATE TABLE `scafel_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_id` int(11) NOT NULL DEFAULT '1',
  `username` varchar(255) NOT NULL DEFAULT '未记录',
  `gander` tinyint(4) NOT NULL DEFAULT '0',
  `age` int(11) NOT NULL DEFAULT '0',
  `tel` varchar(255) NOT NULL DEFAULT '未记录',
  `addr` varchar(255) NOT NULL DEFAULT '未记录',
  `channel_id` int(11) NOT NULL DEFAULT '1',
  `department_id` int(11) NOT NULL DEFAULT '1',
  `remarks` varchar(255) NOT NULL DEFAULT '未添加备注',
  `addtime` varchar(255) NOT NULL DEFAULT '0',
  `admin_id` int(11) DEFAULT '1',
  `class_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10778 DEFAULT CHARSET=utf8mb4 COMMENT='登记的客户表';

-- ----------------------------
-- Table structure for scafel_user_custom
-- ----------------------------
DROP TABLE IF EXISTS `scafel_user_custom`;
CREATE TABLE `scafel_user_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time_id` int(11) NOT NULL DEFAULT '1',
  `username` varchar(255) NOT NULL DEFAULT '未记录',
  `gander` tinyint(4) NOT NULL DEFAULT '0',
  `age` int(11) NOT NULL DEFAULT '0',
  `tel` varchar(255) NOT NULL DEFAULT '未记录',
  `addr` varchar(255) NOT NULL DEFAULT '未记录',
  `department_id` int(11) NOT NULL DEFAULT '1',
  `remarks` varchar(255) NOT NULL DEFAULT '未添加备注',
  `addtime` varchar(255) NOT NULL DEFAULT '0',
  `admin_id` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `cometime` varchar(255) DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COMMENT='客服预约登记的客户表';

-- ----------------------------
-- Table structure for scafel_web
-- ----------------------------
DROP TABLE IF EXISTS `scafel_web`;
CREATE TABLE `scafel_web` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stitle` varchar(255) NOT NULL DEFAULT 'scafel',
  `s_address` varchar(255) NOT NULL DEFAULT 'scafel',
  `s_b_logo` varchar(255) NOT NULL DEFAULT 'scafel',
  `s_tel` varchar(255) NOT NULL DEFAULT 'scafel',
  `type` varchar(255) NOT NULL DEFAULT 'scafel',
  `surl` varchar(255) DEFAULT 'scafel',
  `skeywords` varchar(255) DEFAULT 'scafel',
  `sdescription` varchar(255) DEFAULT 'scafel',
  `s_name` varchar(255) DEFAULT 'scafel',
  `s_phone` varchar(255) DEFAULT 'scafel',
  `s_qq` varchar(255) DEFAULT 'scafel',
  `s_email` varchar(255) DEFAULT 'scafel',
  `scopyright` varchar(255) DEFAULT 'scafel',
  `sentitle` varchar(255) DEFAULT 'scafel',
  `s_postcode` varchar(255) DEFAULT '030000',
  `s_icp` varchar(255) DEFAULT '1',
  `s_record` varchar(255) DEFAULT '1',
  `s_s_logo` varchar(255) DEFAULT 'scafel',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站信息表';

-- ----------------------------
-- Table structure for scafel_wechat
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat`;
CREATE TABLE `scafel_wechat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` varchar(255) DEFAULT '0',
  `appsecret` varchar(255) DEFAULT '0',
  `wechatname` varchar(255) DEFAULT 'scafel',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `admin_id` int(11) DEFAULT '1' COMMENT '管理员账号id',
  `token` varchar(255) DEFAULT 'scafel',
  `encodingaeskey` varchar(255) DEFAULT 'scafel',
  `type` tinyint(4) DEFAULT '0' COMMENT '公众号类型',
  `addtime` varchar(255) DEFAULT '0',
  `isbang` tinyint(4) DEFAULT '0',
  `url` varchar(255) DEFAULT 'scafel',
  `remarks` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for scafel_wechat_game
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_game`;
CREATE TABLE `scafel_wechat_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL DEFAULT '0',
  `answer` text NOT NULL,
  `game_key_word` varchar(255) NOT NULL,
  `game_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信公众号游戏表\r\n';

-- ----------------------------
-- Table structure for scafel_wechat_game_answer
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_game_answer`;
CREATE TABLE `scafel_wechat_game_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `answer` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for scafel_wechat_key
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_key`;
CREATE TABLE `scafel_wechat_key` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyname` varchar(255) NOT NULL DEFAULT 'scafel',
  `returntype` int(11) NOT NULL DEFAULT '0' COMMENT '0 文字  1图文  2图片  3链接 ',
  `returnid` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `wechat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for scafel_wechat_keywords
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_keywords`;
CREATE TABLE `scafel_wechat_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyname` varchar(255) NOT NULL DEFAULT 'scafel',
  `returntype` int(11) NOT NULL DEFAULT '0' COMMENT '0 文字  1图文  2图片  3链接 ',
  `returnid` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `wechat_id` int(11) NOT NULL,
  `returnname` varchar(255) NOT NULL DEFAULT 'scafel',
  `message` text NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT 'scafel',
  `img` varchar(255) NOT NULL DEFAULT 'scafel',
  `addtime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='微信公众号关键词记录表';

-- ----------------------------
-- Table structure for scafel_wechat_log
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_log`;
CREATE TABLE `scafel_wechat_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for scafel_wechat_message
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_message`;
CREATE TABLE `scafel_wechat_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  `addtime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for scafel_wechat_qrcode
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_qrcode`;
CREATE TABLE `scafel_wechat_qrcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  `image` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for scafel_wechat_question
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_question`;
CREATE TABLE `scafel_wechat_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` varchar(255) NOT NULL DEFAULT '0',
  `answer` text NOT NULL,
  `question_key_word` varchar(255) NOT NULL DEFAULT '0',
  `question_name` varchar(255) NOT NULL DEFAULT '0',
  `wechat_id` int(11) NOT NULL DEFAULT '0',
  `isshow` tinyint(4) NOT NULL DEFAULT '1',
  `addtime` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='微信公众号问卷表\r\n';

-- ----------------------------
-- Table structure for scafel_wechat_question_answer
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_question_answer`;
CREATE TABLE `scafel_wechat_question_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` varchar(255) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `answer` varchar(255) DEFAULT '0',
  `addtime` varchar(255) DEFAULT NULL,
  `wechat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信公众号问卷用户答题记录表';

-- ----------------------------
-- Table structure for scafel_wechat_question_code_list
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_question_code_list`;
CREATE TABLE `scafel_wechat_question_code_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` varchar(255) NOT NULL DEFAULT '0',
  `title` text NOT NULL,
  `answer` text NOT NULL,
  `code` int(11) NOT NULL DEFAULT '0',
  `range` varchar(255) NOT NULL,
  `wechat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COMMENT='微信公众号问卷分值结果对照对照表\r\n';

-- ----------------------------
-- Table structure for scafel_wechat_question_tips
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_question_tips`;
CREATE TABLE `scafel_wechat_question_tips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` varchar(255) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `wechat_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='微信公众号问卷友情提示信息表\r\n';

-- ----------------------------
-- Table structure for scafel_wechat_token
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_token`;
CREATE TABLE `scafel_wechat_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for scafel_wechat_user
-- ----------------------------
DROP TABLE IF EXISTS `scafel_wechat_user`;
CREATE TABLE `scafel_wechat_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父id',
  `nickname` text COMMENT '用户的昵称',
  `openid` varchar(255) CHARACTER SET utf8 DEFAULT '0' COMMENT '用户的标识，对当前公众号唯一',
  `sex` tinyint(4) DEFAULT '0' COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `province` varchar(255) CHARACTER SET utf8 DEFAULT '0' COMMENT '用户所在省份',
  `city` varchar(255) CHARACTER SET utf8 DEFAULT '0' COMMENT '用户所在城市',
  `country` varchar(255) CHARACTER SET utf8 DEFAULT '0' COMMENT '用户所在国家',
  `headimgurl` varchar(255) CHARACTER SET utf8 DEFAULT '0' COMMENT '用户头像',
  `privilege` varchar(255) CHARACTER SET utf8 DEFAULT '0' COMMENT '用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）',
  `unionid` varchar(255) CHARACTER SET utf8 DEFAULT '0' COMMENT '只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户是否能使用',
  `subscribe` tinyint(4) DEFAULT '0' COMMENT '用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。',
  `language` varchar(255) DEFAULT '0' COMMENT '用户的语言，简体中文为zh_CN',
  `subscribe_time` varchar(255) DEFAULT '0' COMMENT '用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间',
  `remark` varchar(255) DEFAULT '0' COMMENT '公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注',
  `groupid` varchar(255) DEFAULT '0' COMMENT '用户所在的分组ID',
  `tagid_list` varchar(255) DEFAULT '0' COMMENT '用户被打上的标签ID列表',
  `subscribe_scene` varchar(255) DEFAULT '0' COMMENT '返回用户关注的渠道来源',
  `qr_scene` varchar(255) DEFAULT '0' COMMENT '二维码扫码场景',
  `qr_scene_str` varchar(255) DEFAULT '0' COMMENT '二维码扫码场景描述',
  `scor` int(11) DEFAULT '0' COMMENT '积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
