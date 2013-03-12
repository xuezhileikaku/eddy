--
-- Table structure for table `mt4_deposition`
--
drop database if exists `mt4_0312`;
create database `mt4_0312` default charset utf8 COLLATE utf8_general_ci;

use `mt4_0312`;

DROP TABLE IF EXISTS `mt4_deposition`;
CREATE TABLE `mt4_deposition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(45) NOT NULL DEFAULT '' COMMENT '入金交易帐号',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '入金金额',
  `order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '快钱交易号',
  `time` datetime NOT NULL COMMENT '入金日期时间',
  `is_success` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` varchar(255) DEFAULT '' COMMENT '备用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id_UNIQUE` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='在线入金记录';

--
-- Table structure for table `mt4_transfer`
--

DROP TABLE IF EXISTS `mt4_transfer`;
CREATE TABLE `mt4_transfer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transfer_from` varchar(45) NOT NULL DEFAULT '' COMMENT '转出帐号',
  `transfer_to` varchar(45) NOT NULL DEFAULT '' COMMENT '转入帐号',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '转账金额',
  `transfer_time` datetime NOT NULL COMMENT '转账时间',
  `is_success` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否成功',
  `params` varchar(255) DEFAULT '' COMMENT '备用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='内部转账记录';

--
-- Table structure for table `mt4_withdraw`
--

DROP TABLE IF EXISTS `mt4_withdraw`;
CREATE TABLE `mt4_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(45) NOT NULL DEFAULT '' COMMENT '出金交易帐号',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '出金金额$',
  `name` varchar(45) NOT NULL DEFAULT '' COMMENT '收款人姓名',
  `bank_code` varchar(45) NOT NULL DEFAULT '' COMMENT '收款人银行帐号',
  `bank_name` varchar(255) NOT NULL DEFAULT '' COMMENT '收款账户开户行名称',
  `time` datetime NOT NULL COMMENT '时间',
  `is_success` tinyint(4) NOT NULL DEFAULT '0' COMMENT '在线下账是否成功',
  `params` varchar(255) DEFAULT '' COMMENT '备用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='账户出金记录';

