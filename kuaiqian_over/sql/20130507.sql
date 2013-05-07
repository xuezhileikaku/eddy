CREATE DATABASE  IF NOT EXISTS `cb_bibao` 
USE `cb_bibao`;
-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: yeepay
-- ------------------------------------------------------
-- Server version	5.5.30-log

--
-- Table structure for table `mt4_logs`
--

DROP TABLE IF EXISTS `mt4_logs`;


CREATE TABLE `mt4_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2702 DEFAULT CHARSET=utf8 COMMENT='系统日志';


--
-- Table structure for table `mt4_transfer`
--

DROP TABLE IF EXISTS `mt4_transfer`;


CREATE TABLE `mt4_transfer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transfer_from` varchar(45) NOT NULL DEFAULT '' COMMENT 'ת���ʺ�',
  `transfer_to` varchar(45) NOT NULL DEFAULT '' COMMENT 'ת���ʺ�',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'ת�˽��',
  `transfer_time` datetime NOT NULL COMMENT 'ת��ʱ��',
  `is_success` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '�Ƿ�ɹ�',
  `params` varchar(255) DEFAULT '' COMMENT '����',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1089 DEFAULT CHARSET=utf8 COMMENT='�ڲ�ת�˼�¼';


--
-- Table structure for table `mt4_withdraw`
--

DROP TABLE IF EXISTS `mt4_withdraw`;


CREATE TABLE `mt4_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(45) NOT NULL DEFAULT '' COMMENT '�������ʺ�',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '������$',
  `name` varchar(45) NOT NULL DEFAULT '' COMMENT '�տ�������',
  `bank_code` varchar(45) NOT NULL DEFAULT '' COMMENT '�տ��������ʺ�',
  `bank_name` varchar(255) NOT NULL DEFAULT '' COMMENT '�տ��˻�����������',
  `time` datetime NOT NULL COMMENT 'ʱ��',
  `is_success` tinyint(4) NOT NULL DEFAULT '0' COMMENT '���������Ƿ�ɹ�',
  `params` varchar(255) DEFAULT '' COMMENT '����',
  `cjhl` decimal(11,4) unsigned DEFAULT '6.0000',
  `rmb` decimal(11,2) unsigned DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=567 DEFAULT CHARSET=utf8 COMMENT='�˻������¼';


--
-- Table structure for table `mt4_deposition`
--

DROP TABLE IF EXISTS `mt4_deposition`;


CREATE TABLE `mt4_deposition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(45) NOT NULL DEFAULT '' COMMENT '������ʺ�',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '�����',
  `order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '��Ǯ���׺�',
  `time` datetime NOT NULL COMMENT '�������ʱ��',
  `is_success` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` varchar(255) DEFAULT '' COMMENT '����',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id_UNIQUE` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=437 DEFAULT CHARSET=utf8 COMMENT='��������¼';


-- Dump completed on 2013-05-07  9:21:57
