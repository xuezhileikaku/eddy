delimiter $$

CREATE DATABASE `yeepay` /*!40100 DEFAULT CHARACTER SET gbk */$$

delimiter $$

CREATE TABLE `jyzbpme` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderNum` varchar(115) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=gbk$$

delimiter $$

CREATE TABLE `baisheng` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderNum` varchar(115) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=gbk$$