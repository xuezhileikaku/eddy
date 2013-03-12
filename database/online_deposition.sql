--
-- Table structure for table `mt4_deposition`
--
drop database if exists `mt4_0312`;
create database `mt4_0312` default charset utf8 COLLATE utf8_general_ci;

use `mt4_0312`;

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='��������¼';

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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='�ڲ�ת�˼�¼';

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='�˻������¼';

