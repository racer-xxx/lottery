-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2012 年 11 月 22 日 12:24
-- 服务器版本: 5.5.23
-- PHP 版本: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_ssqiu`
--

-- --------------------------------------------------------

--
-- 表的结构 `advices`
--

CREATE TABLE IF NOT EXISTS `advices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `ip` varchar(20) NOT NULL,
  `level` char(1) DEFAULT NULL COMMENT '评分等级满分5颗星.1,2,3,4,5',
  `advices` text,
  `created_time` datetime DEFAULT NULL,
  `source` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `api_count`
--

CREATE TABLE IF NOT EXISTS `api_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api` varchar(50) NOT NULL,
  `caller` varchar(200) DEFAULT NULL COMMENT '调用者新浪微博id',
  `call_time` datetime NOT NULL,
  `call_ip` varchar(20) NOT NULL,
  `error_code` int(7) DEFAULT NULL COMMENT '0表示成功',
  `note` text,
  `left_count` text,
  `source` varchar(10) NOT NULL COMMENT '统计来源。如ssq,3Q等',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `bet_sim`
--

CREATE TABLE IF NOT EXISTS `bet_sim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `bets` varchar(200) NOT NULL,
  `source` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `integral`
--

CREATE TABLE IF NOT EXISTS `integral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL COMMENT '微博id或注册用户id',
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  `integral_sum` int(11) NOT NULL COMMENT '积分总数',
  `source` char(4) NOT NULL COMMENT '新浪，腾讯，网易，搜狐，人人等',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `integral_detail`
--

CREATE TABLE IF NOT EXISTS `integral_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL COMMENT '微博id或注册用户id',
  `created_time` datetime NOT NULL,
  `ip` varchar(20) NOT NULL,
  `integral` varchar(5) NOT NULL,
  `source` varchar(10) NOT NULL COMMENT '新浪，腾讯，网易，搜狐，人人等',
  `type` char(1) NOT NULL COMMENT '积分获得途径（1登录2签到3答题得分4反馈5抽奖6其他7关注8分享到微博9邀请好友）\r\n积分计划\r\n登录10\r\n签到5//暂不开放，免去统计签到之烦\r\n答题得分（按具体来。总分10，分0,2,4,6,8,10六档）\r\n反馈建议50/次\r\n抽奖//不定0-10000暂不开放\r\n分享到微博50\r\n其他待定\r\n加关注100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `kaokaoni`
--

CREATE TABLE IF NOT EXISTS `kaokaoni` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `weibo_id` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `tihao` varchar(50) NOT NULL,
  `remark` tinyint(3) NOT NULL COMMENT '得分，满分100',
  `lasted_time` int(3) NOT NULL COMMENT '测试耗时，以秒为单位',
  `submit_time` datetime NOT NULL,
  `source` varchar(10) NOT NULL COMMENT '比如Sina_SSQ,Tenc_3Q等',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `kaokaoni_shiti`
--

CREATE TABLE IF NOT EXISTS `kaokaoni_shiti` (
  `tihao` smallint(5) NOT NULL AUTO_INCREMENT,
  `q` varchar(200) NOT NULL,
  `a` varchar(100) DEFAULT NULL,
  `b` varchar(100) DEFAULT NULL,
  `c` varchar(100) DEFAULT NULL,
  `d` varchar(100) DEFAULT NULL,
  `e` varchar(100) DEFAULT NULL,
  `answer` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1单选2多选3填空4判断5简答6备用',
  `level` tinyint(2) NOT NULL COMMENT '难度类型.1易2中3难',
  `source` varchar(5) NOT NULL COMMENT '比如SSQ,3Q等',
  PRIMARY KEY (`tihao`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbl_ssq`
--

CREATE TABLE IF NOT EXISTS `tbl_ssq` (
  `qihao` int(7) NOT NULL,
  `date` date DEFAULT NULL,
  `red_1` int(2) DEFAULT NULL,
  `red_2` int(2) DEFAULT NULL,
  `red_3` int(2) DEFAULT NULL,
  `red_4` int(2) DEFAULT NULL,
  `red_5` int(2) DEFAULT NULL,
  `red_6` int(2) DEFAULT NULL,
  `blue` int(2) DEFAULT NULL,
  `red_oder_1` int(2) DEFAULT NULL,
  `red_oder_2` int(2) DEFAULT NULL,
  `red_oder_3` int(2) DEFAULT NULL,
  `red_oder_4` int(2) DEFAULT NULL,
  `red_oder_5` int(2) DEFAULT NULL,
  `red_oder_6` int(2) DEFAULT NULL,
  `touzhu_sum` int(9) DEFAULT NULL,
  `jiangchi_sum` int(9) DEFAULT NULL,
  `1_zhushu` int(3) DEFAULT NULL,
  `1_sum` int(8) DEFAULT NULL,
  `2_zhushu` int(4) DEFAULT NULL,
  `2_sum` int(7) DEFAULT NULL,
  `3_zhushu` int(4) DEFAULT NULL,
  `3_sum` int(4) DEFAULT NULL,
  `4_zhushu` int(6) DEFAULT NULL,
  `4_sum` int(3) DEFAULT NULL,
  `5_zhushu` int(7) DEFAULT NULL,
  `5_sum` int(2) DEFAULT NULL,
  `6_zhushu` int(8) DEFAULT NULL,
  `6_sum` int(1) DEFAULT NULL,
  PRIMARY KEY (`qihao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tbl_ssq_nums_blue`
--

CREATE TABLE IF NOT EXISTS `tbl_ssq_nums_blue` (
  `blue_num_id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `nums` smallint(3) NOT NULL,
  PRIMARY KEY (`blue_num_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbl_ssq_nums_red`
--

CREATE TABLE IF NOT EXISTS `tbl_ssq_nums_red` (
  `red_num_id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `nums` smallint(3) NOT NULL,
  PRIMARY KEY (`red_num_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbl_ssq_nums_yearly`
--

CREATE TABLE IF NOT EXISTS `tbl_ssq_nums_yearly` (
  `year` smallint(4) NOT NULL DEFAULT '0',
  `r1` tinyint(4) NOT NULL,
  `r2` tinyint(4) NOT NULL,
  `r3` tinyint(4) NOT NULL,
  `r4` tinyint(4) NOT NULL,
  `r5` tinyint(4) NOT NULL,
  `r6` tinyint(4) NOT NULL,
  `r7` tinyint(4) NOT NULL,
  `r8` tinyint(4) NOT NULL,
  `r9` tinyint(4) NOT NULL,
  `r10` tinyint(4) NOT NULL,
  `r11` tinyint(4) NOT NULL,
  `r12` tinyint(4) NOT NULL,
  `r13` tinyint(4) NOT NULL,
  `r14` tinyint(4) NOT NULL,
  `r15` tinyint(4) NOT NULL,
  `r16` tinyint(4) NOT NULL,
  `r17` tinyint(4) NOT NULL,
  `r18` tinyint(4) NOT NULL,
  `r19` tinyint(4) NOT NULL,
  `r20` tinyint(4) NOT NULL,
  `r21` tinyint(4) NOT NULL,
  `r22` tinyint(4) NOT NULL,
  `r23` tinyint(4) NOT NULL,
  `r24` tinyint(4) NOT NULL,
  `r25` tinyint(4) NOT NULL,
  `r26` tinyint(4) NOT NULL,
  `r27` tinyint(4) NOT NULL,
  `r28` tinyint(4) NOT NULL,
  `r29` tinyint(4) NOT NULL,
  `r30` tinyint(4) NOT NULL,
  `r31` tinyint(4) NOT NULL,
  `r32` tinyint(4) NOT NULL,
  `r33` tinyint(4) NOT NULL,
  `b1` tinyint(4) NOT NULL,
  `b2` tinyint(4) NOT NULL,
  `b3` tinyint(4) NOT NULL,
  `b4` tinyint(4) NOT NULL,
  `b5` tinyint(4) NOT NULL,
  `b6` tinyint(4) NOT NULL,
  `b7` tinyint(4) NOT NULL,
  `b8` tinyint(4) NOT NULL,
  `b9` tinyint(4) NOT NULL,
  `b10` tinyint(4) NOT NULL,
  `b11` tinyint(4) NOT NULL,
  `b12` tinyint(4) NOT NULL,
  `b13` tinyint(4) NOT NULL,
  `b14` tinyint(4) NOT NULL,
  `b15` tinyint(4) NOT NULL,
  `b16` tinyint(4) NOT NULL,
  PRIMARY KEY (`year`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `touzhu_ssq`
--

CREATE TABLE IF NOT EXISTS `touzhu_ssq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `qihao` char(7) NOT NULL,
  `r1` char(2) NOT NULL,
  `r2` char(2) NOT NULL,
  `r3` char(2) NOT NULL,
  `r4` char(2) NOT NULL,
  `r5` char(2) NOT NULL,
  `r6` char(2) NOT NULL,
  `b` char(2) NOT NULL,
  `source` varchar(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `bet_time` datetime NOT NULL,
  PRIMARY KEY (`id`,`user_id`,`qihao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `weibo_status`
--

CREATE TABLE IF NOT EXISTS `weibo_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` datetime NOT NULL,
  `status` varchar(420) NOT NULL,
  `source` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `weibo_users`
--

CREATE TABLE IF NOT EXISTS `weibo_users` (
  `weibo_id` varchar(50) NOT NULL,
  `created_time` datetime NOT NULL,
  `last_access_time` datetime NOT NULL,
  `last_access_ip` varchar(20) NOT NULL,
  `access_times` varchar(10) NOT NULL,
  `screen_name` varchar(100) DEFAULT NULL COMMENT '用户昵称 ',
  `name` varchar(100) DEFAULT NULL COMMENT '友好显示名称 ',
  `province` varchar(10) DEFAULT NULL,
  `city` varchar(10) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL COMMENT '用户所在地 ',
  `description` varchar(200) DEFAULT NULL COMMENT '用户描述',
  `url` varchar(100) DEFAULT NULL COMMENT '用户博客地址 ',
  `profile_image_url` varchar(100) DEFAULT NULL COMMENT '用户头像地址 ',
  `cover_image` varchar(100) DEFAULT NULL,
  `profile_url` varchar(50) DEFAULT NULL,
  `domain` varchar(50) DEFAULT NULL COMMENT '用户的个性化域名 ',
  `weihao` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL COMMENT '性别，m：男、f：女、n：未知 ',
  `followers_count` varchar(8) NOT NULL COMMENT '粉丝数',
  `friends_count` varchar(8) NOT NULL COMMENT '关注数 ',
  `statuses_count` varchar(8) NOT NULL COMMENT '微博数 ',
  `favourites_count` varchar(8) DEFAULT NULL COMMENT '收藏数 ',
  `created_at` varchar(100) DEFAULT NULL COMMENT '创建时间 ',
  `following` varchar(5) DEFAULT NULL COMMENT '当前登录用户是否已关注该用户 （是否我的关注）',
  `allow_all_act_msg` varchar(5) DEFAULT NULL COMMENT '是否允许所有人给我发私信 ',
  `geo_enabled` varchar(5) DEFAULT NULL COMMENT '是否允许带有地理信息 ',
  `verified` varchar(5) DEFAULT NULL COMMENT '是否是微博认证用户，即带V用户 ',
  `verified_type` varchar(30) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `status` text COMMENT '用户的最近一条微博信息字段 ',
  `allow_all_comment` varchar(5) DEFAULT NULL COMMENT '是否允许所有人对我的微博进行评论 ',
  `avatar_large` varchar(150) DEFAULT NULL COMMENT '用户大头像地址 ',
  `verified_reason` varchar(200) DEFAULT NULL COMMENT '认证原因',
  `follow_me` varchar(5) DEFAULT NULL COMMENT '该用户是否关注当前登录用户 （是否我的粉丝）',
  `online_status` varchar(5) DEFAULT NULL COMMENT '用户的在线状态，0：不在线、1：在线 ',
  `bi_followers_count` varchar(8) DEFAULT NULL COMMENT '用户的互粉数 ',
  `lang` varchar(100) DEFAULT NULL,
  `source` varchar(10) NOT NULL DEFAULT '' COMMENT '统计来源，如3Q,ssq等',
  `star` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`weibo_id`,`source`),
  UNIQUE KEY `weibo_id` (`weibo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
