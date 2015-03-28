-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 28, 2015 at 09:50 AM
-- Server version: 5.5.42
-- PHP Version: 5.4.39

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `x`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_action_log`
--

CREATE TABLE IF NOT EXISTS `admin_action_log` (
  `id` bigint(20) unsigned NOT NULL,
  `author_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` char(2) NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `insertdate` datetime NOT NULL,
  `query_that_is_executed` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `record_id` bigint(20) DEFAULT NULL,
  `query_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6154 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_action_log`
--

INSERT INTO `admin_action_log` (`id`, `author_id`, `name`, `module_name`, `action`, `ip`, `url`, `insertdate`, `query_that_is_executed`, `record_id`, `query_status`) VALUES
(6131, 240, 'Paresh Behede', '', 'lo', '127.0.0.1', '/x/gateway/login/auth.php', '2015-03-24 04:19:31', 'UPDATE `author` SET lastvisit=''2015-03-24 04:19:31'' where id=''240''', 240, '1'),
(6132, 240, 'Paresh Behede', '', 'lo', '127.0.0.1', '/x/gateway/login/auth.php', '2015-03-24 04:24:25', 'UPDATE `author` SET lastvisit=''2015-03-24 04:24:25'' where id=''240''', 240, '1'),
(6133, 240, 'Paresh Behede', '', 'lo', '127.0.0.1', '/x/gateway/login/auth.php', '2015-03-25 03:33:57', 'UPDATE `author` SET lastvisit=''2015-03-25 03:33:57'' where id=''240''', 240, '1'),
(6134, 240, 'Paresh Behede', '', 'lo', '127.0.0.1', '/x/gateway/login/auth.php', '2015-03-27 03:37:11', 'UPDATE `author` SET lastvisit=''2015-03-27 03:37:11'' where id=''240''', 240, '1'),
(6135, 240, 'Paresh Behede', '', 'lo', '127.0.0.1', '/x/gateway/login/auth.php', '2015-03-27 04:03:25', 'UPDATE `author` SET lastvisit=''2015-03-27 04:03:25'' where id=''240''', 240, '1'),
(6136, 240, 'Paresh Behede', 'author', 'm', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-27 04:04:37', 'UPDATE `author` SET thumbnail='''', rights=''1'', rightsmod=''11111'', cmsmodules_id=''1'', page_id='''', block_ids='''', email=''pares2711@gmail.com'', name=''Paresh Behede'', username=''paresh'', biodata='''', by_line=''3'', password=''2799823d27c5a4fde3a6468831bf2b384e962f5b'' where id=''240''', 240, ''),
(6137, 240, 'Paresh Behede', '', 'lo', '127.0.0.1', '/x/gateway/login/auth.php', '2015-03-27 04:13:11', 'UPDATE `author` SET lastvisit=''2015-03-27 04:13:11'' where id=''240''', 240, '1'),
(6138, 240, 'Paresh Behede', '', 'lo', '127.0.0.1', '/x/gateway/login/auth.php', '2015-03-28 04:46:44', 'UPDATE `author` SET lastvisit=''2015-03-28 04:46:44'' where id=''240''', 240, '1'),
(6139, 240, 'Paresh Behede', '', 'lo', '127.0.0.1', '/x/gateway/login/auth.php', '2015-03-28 04:49:18', 'UPDATE `author` SET lastvisit=''2015-03-28 04:49:18'' where id=''240''', 240, '1'),
(6140, 240, 'Paresh Behede', 'author', 'm', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 04:52:36', 'UPDATE `author` SET thumbnail='''', rights=''1'', rightsmod=''11111'', cmsmodules_id=''1'', page_id='''', block_ids='''', email=''pares2711@gmail.com'', name=''Paresh Behede'', username=''paresh'', biodata='''', designation=''CEO'', by_line=''3'', password=''2799823d27c5a4fde3a6468831bf2b384e962f5b'' where id=''240''', 240, ''),
(6141, 240, 'Paresh Behede', 'author', 'm', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:05:17', 'UPDATE `author` SET thumbnail='''', rights=''1'', rightsmod=''11111'', cmsmodules_id=''1'', page_id='''', block_ids='''', email=''pares2711@gmail.com'', name=''Paresh Behede'', username=''paresh'', biodata='''', designation='''', by_line=''3'', password=''2799823d27c5a4fde3a6468831bf2b384e962f5b'' where id=''240''', 240, ''),
(6142, 240, 'Paresh Behede', 'author', 'm', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:05:29', 'UPDATE `author` SET thumbnail='''', rights=''1'', rightsmod=''11111'', cmsmodules_id=''1'', page_id='''', block_ids='''', email=''pares2711@gmail.com'', name=''Paresh Behede'', username=''paresh'', biodata='''', designation='''', by_line=''3'', password=''2799823d27c5a4fde3a6468831bf2b384e962f5b'' where id=''240''', 240, ''),
(6143, 240, 'Paresh Behede', 'author', 'm', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:07:24', 'UPDATE `author` SET thumbnail='''', rights=''1'', rightsmod=''11111'', cmsmodules_id=''1'', page_id='''', block_ids='''', email=''pares2711@gmail.com'', name=''Paresh Behede'', username=''paresh'', biodata='''', designation='''', by_line=''3'', password=''2799823d27c5a4fde3a6468831bf2b384e962f5b'' where id=''240''', 240, ''),
(6144, 240, 'Paresh Behede', 'author', 'm', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:13:16', 'UPDATE `author` SET thumbnail='''', rights=''1'', rightsmod=''11111'', cmsmodules_id=''1'', email=''pares2711@gmail.com'', name=''Paresh Behede'', username=''paresh'', biodata='''', designation=''CEO'', by_line=''3'', password=''2799823d27c5a4fde3a6468831bf2b384e962f5b'' where id=''240''', 240, '1'),
(6145, 240, 'Paresh Behede', 'author', 'm', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:13:34', 'UPDATE `author` SET thumbnail='''', rights=''1'', rightsmod=''11111'', cmsmodules_id=''1'', email=''pares2711@gmail.com'', name=''Paresh Behede'', username=''paresh'', biodata=''This is BIO'', designation=''CEO'', by_line=''3'', password=''2799823d27c5a4fde3a6468831bf2b384e962f5b'' where id=''240''', 240, '1'),
(6146, 240, 'Paresh Behede', 'author', 'm', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:17:13', 'UPDATE `author` SET thumbnail='''', rights=''1'', rightsmod=''11111'', cmsmodules_id=''1'', email=''pares2711@gmail.com'', name=''Paresh Behede'', username=''paresh'', biodata=''This is BIO'', designation=''CEO'', by_line=''3'', password=''2799823d27c5a4fde3a6468831bf2b384e962f5b'' where id=''240''', 240, '1'),
(6147, 240, 'Paresh Behede', 'author', 'a', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:25:46', 'INSERT INTO `author` SET id='''', by_line=''3'', name=''appa'', email=''appa@checkfees.in'', username=''appa'', password=''45da9cf5a2da1ee000756d9111714fe505d41229'', cmsmodules_id=''1'', rights=''1'', designation=''CTO'', biodata='''', rightsmod=''11111'', status=''1'', insertdate=''2015-03-28 05:25:46'', lastvisit=''2015-03-28 05:25:46''', 241, '1'),
(6148, 240, 'Paresh Behede', 'author', 'd', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:30:42', 'UPDATE `author` SET status=''-1'' where id=''241''', 241, '1'),
(6149, 240, 'Paresh Behede', 'author', 'r', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:31:16', 'UPDATE `author` SET status=''1'' where id=''241''', 241, '1'),
(6150, 240, 'Paresh Behede', 'author', 'd', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:31:57', 'UPDATE `author` SET status=''-1'' where id=''241''', 241, '1'),
(6151, 240, 'Paresh Behede', 'author', 'r', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 05:34:20', 'UPDATE `author` SET status=''1'' where id=''241''', 241, '1'),
(6152, 240, 'Paresh Behede', 'author', 'm', '127.0.0.1', '/x/gateway/author/getauthor.php', '2015-03-28 06:01:15', 'UPDATE `author` SET thumbnail='''', rights=''1'', rightsmod=''11111'', cmsmodules_id=''1018,1'', email=''pares2711@gmail.com'', name=''Paresh Behede'', username=''paresh'', biodata=''This is BIO'', designation=''CEO'', by_line=''3'', password=''2799823d27c5a4fde3a6468831bf2b384e962f5b'' where id=''240''', 240, '1'),
(6153, 240, 'Paresh Behede', '', 'lo', '127.0.0.1', '/x/gateway/login/auth.php', '2015-03-28 09:43:31', 'UPDATE `author` SET lastvisit=''2015-03-28 09:43:31'' where id=''240''', 240, '1');

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE IF NOT EXISTS `area` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `rights` tinyint(1) NOT NULL COMMENT '''Read Only''=>0, ''Admin''=>1 ,''Author''=>2,''Develo[er''=>3',
  `rightsmod` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '00000',
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '''Active/Publish''=>1,''Inactive/Unpublish''=>0,''Deleted''=>-1',
  `insertdate` datetime DEFAULT NULL,
  `lastvisit` datetime DEFAULT NULL,
  `cmsmodules_id` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `biodata` text COLLATE utf8_unicode_ci,
  `by_line` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1=byline, 2=cms user, 3=both'
) ENGINE=MyISAM AUTO_INCREMENT=242 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This table is for storing all the Author in CMS.Not related';

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id`, `name`, `email`, `username`, `password`, `rights`, `rightsmod`, `thumbnail`, `status`, `insertdate`, `lastvisit`, `cmsmodules_id`, `designation`, `biodata`, `by_line`) VALUES
(240, 'Paresh Behede', 'pares2711@gmail.com', 'paresh', '2799823d27c5a4fde3a6468831bf2b384e962f5b', 1, '11111', '', 1, NULL, '2015-03-28 09:43:31', '1018,1', 'CEO', 'This is BIO', 3),
(241, 'appa', 'appa@checkfees.in', 'appa', '45da9cf5a2da1ee000756d9111714fe505d41229', 1, '11111', '', 1, '2015-03-28 05:25:46', '2015-03-28 05:25:46', '1', 'CTO', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` tinyint(3) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `parentid` tinyint(3) NOT NULL DEFAULT '0',
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `metatitle` varchar(600) COLLATE utf8_unicode_ci NOT NULL,
  `metakeyword` varchar(600) COLLATE utf8_unicode_ci NOT NULL,
  `metadescription` varchar(600) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '''Active/Publish''=>1,''Inactive/Unpublish''=>0,''Deleted''=>-1',
  `insertdate` datetime NOT NULL,
  `updatedate` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='this table contains all categories';

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_modules`
--

CREATE TABLE IF NOT EXISTS `cms_modules` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `headingname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `displayname` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_pid` tinyint(4) NOT NULL DEFAULT '0',
  `display_order` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '''Active/Publish''=>1,''Inactive/Unpublish''=>0,''Deleted''=>-1'
) ENGINE=MyISAM AUTO_INCREMENT=1019 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cms_modules`
--

INSERT INTO `cms_modules` (`id`, `name`, `headingname`, `displayname`, `module_pid`, `display_order`, `status`) VALUES
(1, 'Author', 'Author Management', 'Author', 0, 1, 1),
(1018, 'adminlog', 'AdminLog Managment', 'Admin Logs', 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `degree` varchar(255) NOT NULL,
  `firm_name` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `area_id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `city_id` varchar(255) NOT NULL,
  `city_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `phone` varchar(500) NOT NULL,
  `timing` varchar(255) NOT NULL,
  `experiance` int(11) NOT NULL,
  `fees` varchar(255) NOT NULL,
  `weeklyoff` varchar(10) NOT NULL,
  `by_line` varchar(100) NOT NULL,
  `lattitude` varchar(100) NOT NULL,
  `langitude` varchar(100) NOT NULL,
  `author_id` int(11) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1 => Published, 0 => UnPublished, -1 => Deleted',
  `insertdate` date NOT NULL,
  `updatedate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_action_log`
--
ALTER TABLE `admin_action_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`), ADD KEY `status` (`status`), ADD KEY `name` (`name`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_modules`
--
ALTER TABLE `cms_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_action_log`
--
ALTER TABLE `admin_action_log`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6154;
--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=242;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cms_modules`
--
ALTER TABLE `cms_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1019;
--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
