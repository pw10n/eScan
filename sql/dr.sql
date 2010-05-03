

-- phpMyAdmin SQL Dump
-- version 2.11.3deb1ubuntu1.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 29, 2010 at 03:23 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `dr`
--

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `mode` tinyint(4) NOT NULL,
  `adminUser` text,
  `actorUid` int(11) default NULL,
  `actorBid` int(11) default NULL,
  `targetUid` int(11) default NULL,
  `targetBid` int(11) default NULL,
  `targetBid2` int(11) default NULL,
  `targetTid` int(11) default NULL,
  `targetPoints` tinyint(4) default NULL,
  `targetEid` int(11) default NULL,
  `action` int(11) NOT NULL,
  `comment` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2110 ;

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE IF NOT EXISTS `majors` (
  `code` varchar(5) NOT NULL COMMENT 'major code',
  `name` varchar(50) NOT NULL COMMENT 'full major name',
  PRIMARY KEY  (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE IF NOT EXISTS `score` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `eid` smallint(6) NOT NULL,
  `ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `act` smallint(6) NOT NULL,
  `pts` mediumint(9) NOT NULL,
  `comment` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1322 ;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `tid` int(11) NOT NULL auto_increment,
  `name` varchar(25) NOT NULL,
  `cid` int(11) NOT NULL,
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `team_annotated`
--
CREATE TABLE IF NOT EXISTS `team_annotated` (
`tid` int(11)
,`name` varchar(25)
,`cid` int(11)
,`pts` decimal(29,0)
);
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(10) unsigned NOT NULL auto_increment COMMENT 'user id',
  `bid` smallint(5) unsigned NOT NULL COMMENT 'barcode id',
  `pin` smallint(5) unsigned NOT NULL COMMENT '4-digit pin',
  `fn` varchar(50) NOT NULL COMMENT 'first name',
  `ln` varchar(70) NOT NULL COMMENT 'last name',
  `em` varchar(150) NOT NULL COMMENT 'email',
  `ma` varchar(30) NOT NULL COMMENT 'Major',
  `tid` bigint(20) NOT NULL COMMENT 'Team ID',
  `opt` tinyint(4) NOT NULL default '0' COMMENT 'opt-out mail',
  `s` tinyint(4) NOT NULL COMMENT 'state',
  `elig` tinyint(1) NOT NULL default '1' COMMENT 'prize eligible',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1438 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `users_annotated`
--
CREATE TABLE IF NOT EXISTS `users_annotated` (
`uid` int(10) unsigned
,`bid` smallint(5) unsigned
,`pin` smallint(5) unsigned
,`fn` varchar(50)
,`ln` varchar(70)
,`em` varchar(150)
,`ma` varchar(30)
,`tid` bigint(20)
,`opt` tinyint(4)
,`s` tinyint(4)
,`elig` tinyint(1)
,`pts` decimal(29,0)
,`evts` bigint(21)
);
-- --------------------------------------------------------

--
-- Structure for view `team_annotated`
--
DROP TABLE IF EXISTS `team_annotated`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dr`.`team_annotated` AS select `dr`.`team`.`tid` AS `tid`,`dr`.`team`.`name` AS `name`,`dr`.`team`.`cid` AS `cid`,(select sum(`dr`.`score`.`pts`) AS `sum(``pts``)` from (`dr`.`users` join `dr`.`score`) where ((`dr`.`users`.`uid` = `dr`.`score`.`uid`) and (`dr`.`users`.`tid` = `dr`.`team`.`tid`))) AS `pts` from `dr`.`team`;

-- --------------------------------------------------------

--
-- Structure for view `users_annotated`
--
DROP TABLE IF EXISTS `users_annotated`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dr`.`users_annotated` AS select `dr`.`users`.`uid` AS `uid`,`dr`.`users`.`bid` AS `bid`,`dr`.`users`.`pin` AS `pin`,`dr`.`users`.`fn` AS `fn`,`dr`.`users`.`ln` AS `ln`,`dr`.`users`.`em` AS `em`,`dr`.`users`.`ma` AS `ma`,`dr`.`users`.`tid` AS `tid`,`dr`.`users`.`opt` AS `opt`,`dr`.`users`.`s` AS `s`,`dr`.`users`.`elig` AS `elig`,(select sum(`dr`.`score`.`pts`) AS `sum(``pts``)` from `dr`.`score` where (`dr`.`score`.`uid` = `dr`.`users`.`uid`)) AS `pts`,(select count(0) AS `count(*)` from `dr`.`score` where ((`dr`.`score`.`uid` = `dr`.`users`.`uid`) and (`dr`.`score`.`act` = 0))) AS `evts` from `dr`.`users`;

