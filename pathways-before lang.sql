-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 12, 2017 at 10:01 PM
-- Server version: 5.6.26
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pathways`
--
CREATE DATABASE IF NOT EXISTS `pathways` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `pathways`;

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

DROP TABLE IF EXISTS `audit`;
CREATE TABLE `audit` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user` int(11) NOT NULL,
  `event` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `audit`
--

INSERT INTO `audit` (`id`, `date`, `user`, `event`) VALUES
(2, '2017-06-05 21:22:30', 2, 'Updated class: CIS-72A - Introduction to Web Page Creation'),
(3, '2017-06-05 21:34:29', 2, 'Created class: CIS-5 - Programming Concepts and Methodology I: C++'),
(12, '2017-06-06 01:08:26', 2, 'Created class: CSC-61 - Intro to Database Theory'),
(30, '2017-06-06 02:21:58', 2, 'Created class: CSC-17A - Programming Concepts and	3 units Methodology II'),
(31, '2017-06-06 02:22:57', 2, 'Created class: MAT-1A - Calculus I'),
(32, '2017-06-06 02:23:40', 2, 'Created class: MAT-1B - Calculus II'),
(33, '2017-06-06 02:26:02', 2, 'Created class: CSC-11 - Computer Architecture and Organization Assembly'),
(34, '2017-06-06 02:37:46', 2, 'Updated class: MAT-1B - Calculus II'),
(35, '2017-06-06 02:45:42', 2, 'Updated class: MAT-1B - Calculus II'),
(36, '2017-06-06 02:46:03', 2, 'Updated class: MAT-1B - Calculus II'),
(37, '2017-06-06 02:49:13', 2, 'Updated class: MAT-1B - Calculus II'),
(38, '2017-06-06 02:53:32', 2, 'Created class: PHY-4A - Mechanics'),
(39, '2017-06-06 02:54:27', 2, 'Created class: PHY-4B - Electricity and Magnetism'),
(40, '2017-06-06 02:59:52', 2, 'Updated class: PHY-4A - Mechanics'),
(41, '2017-06-06 03:00:18', 2, 'Updated class: PHY-4B - Electricity and Magnetism'),
(42, '2017-06-06 03:08:53', 2, 'Created class: CSC-2 - Fundamentals of Systems Analysis'),
(43, '2017-06-06 03:12:44', 2, 'Created class: CSC-17B - C++ Programming: Advanced Objects'),
(44, '2017-06-06 03:13:34', 2, 'Created class: CSC-18A - Java Programming: Objects'),
(45, '2017-06-06 03:14:23', 2, 'Created class: CSC-18B - Java Programming: Advanced Objects'),
(46, '2017-06-06 03:15:43', 2, 'Created class: CSC-18C - Java Programming: Data Structures'),
(47, '2017-06-06 03:17:31', 2, 'Updated class: CIS-72A - Introduction to Web Page Creation'),
(48, '2017-06-06 03:19:00', 2, 'Updated class: CIS-72A - Introduction to Web Page Creation'),
(49, '2017-06-06 03:20:28', 2, 'Created class: CSC-17C - C++ Programming: Data Structures'),
(50, '2017-06-06 03:24:23', 2, 'Created class: CSC-20 - Systems Analysis and Design'),
(51, '2017-06-06 03:25:27', 2, 'Created class: CIS-62 - Microsoft Access DBMS: Comprehensive'),
(52, '2017-06-06 03:26:17', 2, 'Created class: CIS-91 - Microsoft Project'),
(53, '2017-06-06 03:26:45', 2, 'Created class: CSC-63 - Introduction to Structured Query Language (SQL)'),
(54, '2017-06-06 03:27:53', 2, 'Created class: CSC-28A - MS Access Programming'),
(55, '2017-06-06 03:33:57', 2, 'Created class: CIS-26A - Cisco Networking Academy 1A'),
(56, '2017-06-06 03:34:10', 2, 'Updated class: CIS-26A - Cisco Networking Academy 1A'),
(57, '2017-06-06 03:34:55', 2, 'Created class: CIS-23 - Software End User Support'),
(58, '2017-06-06 03:38:15', 2, 'Updated class: CIS-23 - Software End User Support'),
(59, '2017-06-06 03:39:40', 2, 'Updated class: CIS-23 - Software End User Support'),
(60, '2017-06-06 04:39:33', 2, 'Updated class: CIS-23 - Software End User Support'),
(61, '2017-06-06 04:40:23', 2, 'Updated class: CIS-26A - Cisco Networking Academy 1A'),
(63, '2017-06-06 04:45:04', 2, 'Logged into administration'),
(64, '2017-06-06 05:09:35', 2, 'Logged into administration'),
(65, '2017-06-06 05:59:46', 2, 'Logged into administration'),
(66, '2017-06-06 06:00:01', 2, 'Logged into administration'),
(67, '2017-06-06 18:03:41', 2, 'Logged into administration'),
(68, '2017-06-06 18:04:57', 2, 'Created class: CIS-26B - Cisco Networking Academy 1B'),
(69, '2017-06-06 18:05:54', 2, 'Deleted class: CIS-26B - Cisco Networking Academy 1B'),
(70, '2017-06-06 18:06:21', 2, 'Created class: CIS-26B - Cisco Networking Academy 1B'),
(71, '2017-06-06 18:06:58', 2, 'Created class: CIS-26C - Cisco Networking Academy 1C'),
(72, '2017-06-06 18:07:35', 2, 'Created class: CIS-26D - Cisco Networking Academy 1D'),
(73, '2017-06-06 18:08:59', 2, 'Created class: CIS-25 - Information and Communication Technology Essentials'),
(74, '2017-06-06 18:10:02', 2, 'Created class: CIS-27 - Information and Network Security'),
(75, '2017-06-06 18:11:01', 2, 'Created class: CIS-27A - Computer Forensics Fundamentals'),
(76, '2017-06-06 18:11:26', 2, 'Created class: tester'),
(77, '2017-06-06 18:13:28', 2, 'Deleted class: tester'),
(78, '2017-06-06 18:14:50', 2, 'Created class: CSC-21A - Linux Operating System Administration'),
(79, '2017-06-06 18:22:45', 2, 'Created class: CIS-95A - Introduction to the Internet'),
(80, '2017-06-06 18:23:27', 2, 'Created class: CIS-76B - Introduction to Dreamweaver'),
(81, '2017-06-06 18:23:56', 2, 'Created class: CIS-78A - Introduction to Adobe Photoshop'),
(82, '2017-06-06 18:25:01', 2, 'Created class: CIS-72B - Intermediate Web Page Creation using Cascading Style Sheets (CSS)'),
(83, '2017-06-06 18:26:19', 2, 'Created class: CSC-12 - PHP Dynamic Web Site Programming'),
(84, '2017-06-06 18:27:21', 2, 'Created class: CSC-14A - Web Programming: JavaScript'),
(85, '2017-06-06 18:27:48', 2, 'Created class: CAT-79 - Introduction to Adobe Illustrator'),
(86, '2017-06-06 18:31:15', 2, 'Updated class: CSC-14A - Web Programming: JavaScript'),
(87, '2017-06-06 18:31:55', 2, 'Updated class: CSC-12 - PHP Dynamic Web Site Programming'),
(88, '2017-06-06 18:34:46', 2, 'Updated class: CSC-12 - PHP Dynamic Web Site Programming'),
(89, '2017-06-06 18:36:30', 2, 'Updated class: CIS-72B - Intermediate Web Page Creation using Cascading Style Sheets (CSS)'),
(90, '2017-06-06 18:38:45', 2, 'Created class: CIS-81 - Introduction to Desktop Publishing using Adobe InDesign'),
(91, '2017-06-06 18:39:55', 2, 'Created class: CAT-80 - Word Processing: Microsoft Word for Windows'),
(92, '2017-06-06 18:41:12', 2, 'Created class: CIS-98A - Introduction to Excel'),
(93, '2017-06-06 18:42:06', 2, 'Created class: CIS-98B - Advanced Excel'),
(94, '2017-06-06 18:42:45', 2, 'Updated class: CIS-5 - Programming Concepts and Methodology I: C++'),
(95, '2017-06-06 18:47:38', 2, 'Updated class: CIS-72A - Introduction to Web Page Creation'),
(96, '2017-06-06 18:49:12', 2, 'Updated class: CIS-27 - Information and Network Security'),
(97, '2017-06-06 18:51:22', 2, 'Updated class: CIS-27 - Information and Network Security'),
(98, '2017-06-06 18:52:22', 2, 'Updated class: CIS-27 - Information and Network Security'),
(99, '2017-06-06 18:53:21', 2, 'Updated class: CIS-27 - Information and Network Security'),
(100, '2017-06-06 18:58:40', 2, 'Created class: CSC-7 - Discrete Structures'),
(101, '2017-06-06 18:59:20', 2, 'Updated class: MAT-1B - Calculus II'),
(102, '2017-06-08 17:51:41', 2, 'Logged into administration'),
(103, '2017-06-08 18:26:40', 2, 'Logged into administration'),
(104, '2017-06-19 18:25:29', 2, 'Logged into administration'),
(105, '2017-06-19 18:40:22', 2, 'Logged into administration'),
(106, '2017-06-19 18:54:15', 2, 'Updated cert: super secret test'),
(107, '2017-06-19 19:28:39', 2, 'Updated cert: super secret test'),
(108, '2017-06-19 19:33:37', 2, 'Updated cert: super secret test'),
(109, '2017-06-19 20:25:34', 2, 'Logged into administration'),
(110, '2017-06-19 20:26:40', 2, 'Updated cert: Computer Science AD-T'),
(111, '2017-06-19 20:36:39', 2, 'Updated cert: Computer Science AD-T'),
(112, '2017-06-19 20:49:21', 2, 'Logged into administration'),
(113, '2017-06-19 20:53:49', 2, 'Updated cert: Computer Science AD-T'),
(114, '2017-06-19 20:54:24', 2, 'Updated cert: Computer Science AD-T'),
(115, '2017-06-19 21:02:42', 2, 'Updated cert: Computer Science AD-T'),
(116, '2017-06-19 21:02:45', 2, 'Updated cert: Computer Science AD-T'),
(117, '2017-06-19 21:11:58', 2, 'Updated cert: Computer Science AD-T'),
(118, '2017-06-19 21:15:31', 2, 'Updated cert: Computer Science AD-T'),
(119, '2017-06-19 22:43:05', 2, 'Logged into administration'),
(120, '2017-06-19 22:47:35', 2, 'Updated cert: super secret test'),
(121, '2017-06-19 22:51:11', 2, 'Updated cert: super secret test'),
(122, '2017-06-19 22:52:29', 2, 'Updated cert: super secret test'),
(123, '2017-06-19 23:02:06', 2, 'Updated cert: super secret test'),
(124, '2017-06-19 23:22:30', 2, 'Updated cert: new'),
(125, '2017-06-19 23:29:28', 2, 'Deleted certificate: '),
(126, '2017-06-19 23:29:29', 2, 'Deleted certificate: '),
(127, '2017-06-19 23:42:37', 2, 'Updated cert: Computer Programming'),
(128, '2017-06-19 23:43:14', 2, 'Updated cert: Computer Programming'),
(129, '2017-06-19 23:43:46', 2, 'Updated cert: Computer Programming'),
(130, '2017-06-19 23:44:13', 2, 'Updated cert: Computer Programming'),
(131, '2017-06-19 23:48:07', 2, 'Updated cert: C++ Programming'),
(132, '2017-06-19 23:48:33', 2, 'Updated class: CSC-5 - Programming Concepts and Methodology I: C++'),
(133, '2017-06-19 23:49:06', 2, 'Updated cert: C++ Programming'),
(134, '2017-06-19 23:49:07', 2, 'Updated cert: C++ Programming'),
(135, '2017-06-19 23:50:13', 2, 'Updated cert: C++ Programming'),
(136, '2017-06-19 23:54:02', 2, 'Updated cert: Java Programming'),
(137, '2017-06-19 23:56:06', 2, 'Updated cert: Java Programming'),
(138, '2017-06-20 00:07:44', 2, 'Logged into administration'),
(139, '2017-06-20 00:09:38', 2, 'Updated cert: Relational Database Technology'),
(140, '2017-06-20 18:52:19', 2, 'Logged into administration'),
(141, '2017-06-20 18:54:55', 2, 'Updated cert: Systems Development'),
(142, '2017-06-20 18:55:31', 2, 'Updated cert: Systems Development'),
(143, '2017-06-20 19:06:31', 2, 'Updated cert: CISCO Networking'),
(144, '2017-06-20 19:11:23', 2, 'Updated cert: Information Security'),
(145, '2017-06-20 20:03:41', 2, 'Updated cert: Web Developer'),
(146, '2017-06-20 20:06:51', 2, 'Updated cert: Web Developer'),
(147, '2017-06-20 20:59:39', 2, 'Updated cert: Web Developer'),
(148, '2017-06-20 21:12:25', 2, 'Updated cert: Web Developer'),
(149, '2017-06-20 21:12:51', 2, 'Updated cert: Web Developer'),
(150, '2017-06-20 21:13:13', 2, 'Updated cert: Web Developer'),
(151, '2017-06-21 00:13:23', 2, 'Logged into administration'),
(152, '2017-06-21 00:20:00', 2, 'Updated cert: Web Developer'),
(153, '2017-06-21 00:24:39', 2, 'Updated cert: Web Designer'),
(154, '2017-06-21 00:33:32', 2, 'Updated cert: Computer Applications'),
(155, '2017-06-21 00:34:26', 2, 'Updated cert: Computer Applications'),
(156, '2017-06-21 00:35:41', 2, 'Updated cert: Computer Applications'),
(157, '2017-06-21 00:38:15', 2, 'Updated cert: Relational Database Technology'),
(158, '2017-06-21 00:38:34', 2, 'Updated cert: Relational Database Technology'),
(159, '2017-06-21 00:42:11', 2, 'Updated cert: Systems Development'),
(160, '2017-06-21 00:44:29', 2, 'Updated cert: CISCO Networking'),
(161, '2017-06-21 00:46:09', 2, 'Updated cert: Information Security'),
(162, '2017-06-21 01:12:12', 2, 'Updated cert: tester'),
(163, '2017-06-21 01:37:23', 2, 'Logged into administration'),
(164, '2017-06-21 01:38:35', 2, 'Deleted certificate: '),
(165, '2017-06-21 01:38:36', 2, 'Deleted certificate: '),
(166, '2017-06-21 01:44:23', 2, 'Updated cert: c0put'),
(167, '2017-06-21 01:57:48', 2, 'Logged into administration'),
(168, '2017-06-21 02:02:53', 2, 'Deleted certificate: c0put'),
(169, '2017-06-21 02:04:03', 2, 'Updated cert: asd'),
(170, '2017-06-21 02:05:36', 2, 'Updated cert: asd'),
(171, '2017-06-21 02:06:10', 2, 'Deleted certificate: asd'),
(172, '2017-06-21 02:17:05', 2, 'Updated cert: C++ Programming'),
(173, '2017-06-21 02:17:42', 2, 'Updated cert: C++ Programming'),
(174, '2017-06-21 02:18:04', 2, 'Updated cert: C++ Programming'),
(175, '2017-06-21 18:38:45', 2, 'Logged into administration'),
(176, '2017-06-21 18:50:47', 2, 'Logged into administration'),
(177, '2017-06-21 19:51:28', 2, 'Updated User: joe'),
(178, '2017-06-21 19:52:57', 2, 'Updated User: joe'),
(179, '2017-06-21 19:53:54', 2, 'Updated User: joe'),
(180, '2017-06-21 19:55:14', 2, 'Updated User: joe'),
(181, '2017-06-21 19:55:20', 2, 'Updated User: joe'),
(182, '2017-06-21 19:57:19', 2, 'Deleted user: joe'),
(183, '2017-06-22 20:31:56', 2, 'Logged into administration'),
(184, '2017-06-23 07:09:31', 2, 'Logged into administration'),
(185, '2017-06-23 07:09:52', 2, 'Deleted user: joe'),
(186, '2017-06-23 07:10:09', 2, 'Updated User: joe'),
(187, '2017-06-23 07:10:29', 2, 'Updated User: joe'),
(188, '2017-06-23 07:20:51', 2, 'Logged into administration'),
(189, '2017-06-23 07:21:03', 2, 'Updated User: joe'),
(190, '2017-06-23 07:23:23', 2, 'Updated User: joe'),
(191, '2017-06-23 07:24:25', 2, 'Reset User: joe made by tester'),
(192, '2017-06-23 07:26:27', 2, 'Deleted user: joe'),
(193, '2017-06-23 07:30:42', 2, 'Reset password for user: joe'),
(194, '2017-06-23 07:30:42', 2, 'Created user: joe'),
(195, '2017-06-23 07:32:10', 6, 'Logged into administration'),
(196, '2017-06-23 07:33:07', 2, 'Logged into administration'),
(197, '2017-06-23 07:33:20', 2, 'Deleted user: joe'),
(198, '2017-06-23 07:35:16', 2, 'Reset password for user: js'),
(199, '2017-06-23 07:35:17', 2, 'Created user: js'),
(200, '2017-06-23 07:36:20', 2, 'Reset password for user: js'),
(201, '2017-06-23 07:36:20', 2, 'Created user: js'),
(202, '2017-06-23 07:38:12', 2, 'Reset password for user: joe'),
(203, '2017-06-23 07:38:12', 2, 'Created user: joe'),
(204, '2017-06-26 18:46:43', 2, 'Logged into administration'),
(205, '2017-06-26 18:57:10', 2, 'Logged into administration'),
(206, '2017-06-26 19:01:18', 2, 'Updated class: CIS-1A - Introduction to Computer Information System'),
(207, '2017-06-26 19:10:47', 2, 'Updated class: CIS-1A - Introduction to Computer Information System'),
(208, '2017-06-26 19:53:44', 2, 'Logged into administration'),
(209, '2017-06-26 20:08:45', 2, 'Logged into administration'),
(210, '2017-06-26 20:08:52', 2, 'Deleted certificate: super secret test'),
(211, '2017-06-27 18:50:18', 2, 'Logged into administration'),
(212, '2017-06-27 21:18:27', 2, 'Updated cert: Computer Science AD-T'),
(213, '2017-06-27 21:19:57', 2, 'Updated class: PHY-4B - Electricity and Magnetism'),
(214, '2017-06-28 08:49:38', 2, 'Logged into administration'),
(215, '2017-06-28 08:50:15', 2, 'Updated cert: Computer Science AD-T'),
(216, '2017-06-28 20:40:11', 2, 'Logged into administration'),
(217, '2017-06-28 20:46:02', 2, 'Logged into administration'),
(218, '2017-06-28 20:48:25', 2, 'Updated user: joe'),
(219, '2017-06-28 20:48:53', 2, 'Updated user: joe'),
(220, '2017-06-28 23:44:43', 2, 'Logged into administration'),
(221, '2017-06-28 23:44:50', 2, 'Reset password for user: '),
(222, '2017-06-28 23:54:20', 2, 'Reset password for user: '),
(223, '2017-06-29 00:09:32', 2, 'Logged into administration'),
(224, '2017-06-29 18:48:58', 2, 'Logged into administration'),
(225, '2017-06-29 20:25:34', 2, 'Created class: TES-1 - tester'),
(226, '2017-06-29 20:28:11', 2, 'Deleted class: TES-1 - tester'),
(227, '2017-07-03 18:56:40', 2, 'Logged into administration'),
(228, '2017-07-03 19:18:43', 2, 'Logged into administration'),
(229, '2017-07-03 20:42:35', 2, 'Deleted user: joe'),
(230, '2017-07-03 20:50:43', 2, 'Reset password for user: joe'),
(231, '2017-07-03 20:50:43', 2, 'Created user: joe'),
(232, '2017-07-05 19:00:24', 2, 'Logged into administration'),
(233, '2017-07-05 19:01:28', 2, 'Updated cert: tester'),
(234, '2017-07-05 19:02:13', 2, 'Deleted certificate: tester'),
(235, '2017-07-05 19:04:28', 2, 'Updated cert: tester'),
(236, '2017-07-05 19:05:24', 2, 'Updated cert: tester'),
(237, '2017-07-05 19:19:50', 2, 'Updated cert: tester'),
(238, '2017-07-05 19:20:34', 2, 'Updated cert: tester'),
(239, '2017-07-05 19:57:38', 2, 'Created class: EXA-1 - Example class a simple test'),
(240, '2017-07-05 19:58:29', 2, 'Updated cert: tester'),
(241, '2017-07-06 18:44:55', 2, 'Logged into administration'),
(242, '2017-07-06 21:03:25', 2, 'Logged into administration'),
(243, '2017-07-12 19:14:38', 2, 'Logged into administration'),
(244, '2017-07-12 19:19:23', 2, 'Logged into administration'),
(245, '2017-07-12 19:23:05', 2, 'Logged into administration'),
(246, '2017-07-12 19:40:36', 2, 'Logged into administration');

-- --------------------------------------------------------

--
-- Table structure for table `certificateData`
--

DROP TABLE IF EXISTS `certificateData`;
CREATE TABLE `certificateData` (
  `id` int(11) NOT NULL,
  `cert` int(11) NOT NULL,
  `language` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `elo` text COLLATE utf8_unicode_ci NOT NULL,
  `schedule` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `certificateData`
--

INSERT INTO `certificateData` (`id`, `cert`, `language`, `description`, `elo`, `schedule`) VALUES
(1, 1, 0, '<p style=\"text-align: justify;\"><span style=\"font-family: \'Times New Roman\';\"><span style=\"font-size: 16px;\">The Associate in Science in Computer Science for Transfer degree provides a solid preparation for transfer majors in computer science including an emphasis on object oriented programming logic in C++, computer architecture, calculus and calculus based physics . The intent of this degree is to assist students in seamlessly transferring to a CSU . With this degree the student will be prepared for transfer to the university upper division level in preparation for the eventual conferral of the Bachelor\'s Degree in Computer Science . The degree aligns with the approved Transfer Model Curriculum (TMC) in Computer Science.</span></span></p>', '<ul>\n<li>Write programs utilizing the following data structures: arrays, records, strings, linked lists, stacks, queues, and hash tables</li>\n<li>Write and execute programs in assembly language illustrating typical mathematical and business applications .</li>\n<li>Demonstrate different traversal methods of trees and graphs</li>\n</ul>', '<p style=\"text-align: center;\">Full Time Completion in Two Years</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>\n<p>[class id=\"c1a\" text=\"CIS-1A*\" /]</p>\n<p>[class id=\"c5\" text=\"CIS-5\" /]</p>\n<p>[class id=\"m1a\" text=\"MAT-1A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c17a\" text=\"CSC-17\" /]</p>\n<p>[class id=\"m1b\" text=\"MAT-1B\" /]</p>\n</td>\n</tr>\n<tr>\n<td>Year 2</td>\n<td>\n<p>[class id=\"c11\" text=\"CSC-11\" /]</p>\n<p>[class id=\"p4a\" text=\"PHY-4A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c7\" text=\"CSC-7\" /]</p>\n<p>[class id=\"p4b\" text=\"PHY-4B\" /]</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p>*CIS 1A - Not required but recommended before or concurrent with CSC/CIS 5</p>'),
(2, 2, 0, '<p>This program focuses on the general writing and implementation of generic and customized programs to drive operating systems that generally prepare individuals to apply the methods and procedures of software design and programming to software installation and maintenance. This includes instruction in software design; low and high level languages and program writing; program customization and linking; prototype testing; troubleshooting; and related aspects of operating systems and networks</p>', '<ul>\n<li>Design structured programs using C++, Javascript, or Java.</li>\n<li>Design and use object oriented programs in one of these languages C++, Java or PHP.</li>\n<li>Design and use advanced programming techniques in C++ or Java.</li>\n</ul>', '<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c1a\" text=\"CIS-1A\" /]</p>\n<p>[class id=\"c21\" text=\"CSC/CIS-21\" /]</p>\n<p>[class id=\"c72a\" text=\"CIS-72A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c2\" text=\"CSC/CIS-2\" /]</p>\n<p>[class id=\"c5\" text=\"CSC/CIS-5\" /]</p>\n</td>\n</tr>\n<tr>\n<td>Year 2</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c11\" text=\"CSC/CIS-11\" /]</p>\n<p>[class id=\"c17a\" text=\"CSC/CIS-17A\" /]</p>\n<p>and/or</p>\n<p>[class id=\"c18a\" text=\"CSC/CIS-18A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c17b\" text=\"CSC/CIS-17B\" /]</p>\n<p>and/or</p>\n<p>[class id=\"c18b\" text=\"CSC/CIS-18B\" /]</p>\n<p>[class id=\"c17c\" text=\"CSC/CIS-17C*\" /]</p>\n<p>and/or</p>\n<p>[class id=\"c18c\" text=\"CSC/CIS-18C*\" /]</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p>*Completion of 17C or 18C class is not required but gives student the additional certificate in C++ or Java certificates</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>\n<p>[class id=\"c1a\" text=\"CIS-1A\" /]</p>\n<p>[class id=\"c72a\" text=\"CIS-72A\" /]</p>\n</td>\n<td>\n<p>[class id=\"c21\" text=\"CSC/CIS-21\" /]</p>\n<p>[class id=\"c11\" text=\"CSC/CIS-11\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c2\" text=\"CSC/CIS-2\" /]</p>\n<p>[class id=\"c5\" text=\"CSC/CIS-5\" /]</p>\n</td>\n</tr>\n<tr>\n<td>Year 2</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c17a\" text=\"CSC/CIS-17A\" /]</p>\n<p>and/or</p>\n<p>[class id=\"c18a\" text=\"CSC/CIS-18A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c17b\" text=\"CSC/CIS-17B\" /]</p>\n<p>and/or</p>\n<p>[class id=\"c18b\" text=\"CSC/CIS-18B\" /]</p>\n<p>[class id=\"c17c\" text=\"CSC/CIS-17C*\" /]</p>\n<p>and/or</p>\n<p>[class id=\"c18c\" text=\"CSC/CIS-18C*\" /]</p>\n<p>&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p>*Completion of 17C or 18C class is not required but gives student the additional certificate in C++ or Java certificates</p>'),
(3, 3, 0, '<p>Create structured and Object code in C++ for business, gaming, mathematical and scientific problems by identifying the information input requirements, synthesizing the algorithmic steps needed to transform the data input into the required output information, and organizing the output format to facilitate user communication.</p>', '<ul>\n<li>Create structured and Object code in C++ for business, gaming, mathematical and scientific problems by identifying the information input requirements, synthesizing the algorithmic steps needed to transform the data input into the required output information, and organizing the output format to facilitate user communication.</li>\n<li>Using C++ libraries create and run C++ programs that incorporate the following:\n<ul>\n<li>Multiprocessors</li>\n<li>Multimedia</li>\n<li>ODBC</li>\n<li>SQL</li>\n</ul>\n</li>\n<li>Establish client/server relationship</li>\n<li>OR Using C++ libraries create and run C++ programs that incorporate data structures.</li>\n</ul>', '<p style=\"text-align: center;\">Full Time Completion in 2 Years</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c1a\" text=\"CIS-1A*\" /]</p>\n<p>[class id=\"c5\" text=\"CSC/CIS-5\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>[class id=\"c17a\" text=\"CSC-17A\" /]</td>\n</tr>\n<tr>\n<td>Year 2</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c17b\" text=\"CSC-17B\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>[class id=\"c17c\" text=\"CSC-17C\" /]</td>\n</tr>\n</tbody>\n</table>\n<p>*CIS 1A - Not required but recommended before or concurrent with CSC/CIS 5</p>\n<p>&nbsp;</p>\n<p style=\"text-align: center;\">One Year Completion</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>[class id=\"c1a\" text=\"CIS-1A*\" /]\n<p>[class id=\"c5\" text=\"CSC/CIS-5\" /]</p>\n</td>\n<td>\n<p>[class id=\"c17a\" text=\"CSC-17A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c17b\" text=\"CSC-17B\" /]</p>\n<p>[class id=\"c17c\" text=\"CSC-17C\" /]</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p>*CIS 1A - Not required but recommended before or concurrent with CSC/CIS 5</p>'),
(4, 4, 0, '<p>Completion of this certificate provides the student with skills a new programmer would need to obtain employment programming Java applications.</p>', '<ul>\n<li>Create structured and Object code in Java for business, gaming, mathematical and scientific problems by identifying the information input requirements, synthesizing the algorithmic steps needed to transform the data input into the required output information, and organizing the output format to facilitate user communication.</li>\n<li>Using Java libraries create and run Java programs that incorporate the following:\n<ul>\n<li>Multiprocessors</li>\n<li>Multimedia</li>\n<li>JDBC</li>\n<li>SQL</li>\n</ul>\n</li>\n<li>Establish client/server relationship</li>\n<li>Using Java libraries create and run Java programs that incorporate data structures.</li>\n</ul>', '<p style=\"text-align: center;\">Full Time Completion in 2 Years</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c1a\" text=\"CIS-1A*\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>[class id=\"c5\" text=\"CSC/CIS-5\" /]</td>\n</tr>\n<tr>\n<td>Year 2</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c18a\" text=\"CSC-18A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c18b\" text=\"CSC-18B\" /]</p>\n<p>[class id=\"c18c\" text=\"CSC-18C\" /]</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p>*CIS 1A - Not required but recommended before or concurrent with CSC/CIS 5</p>\n<p>&nbsp;</p>\n<p style=\"text-align: center;\">One Year Completion</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>[class id=\"c1a\" text=\"CIS-1A*\" /]\n<p>[class id=\"c5\" text=\"CSC/CIS-5\" /]</p>\n</td>\n<td>\n<p>[class id=\"c18a\" text=\"CSC-18A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c18b\" text=\"CSC-18B\" /]</p>\n<p>[class id=\"c18c\" text=\"CSC-18C\" /]</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p>*CIS 1A - Not required but recommended before or concurrent with CSC/CIS 5</p>'),
(5, 5, 0, '<p>Provides the skills necessary to present a view of data as a collection of rows and columns and manage these relational databases based on a variety of data models.</p>', '<ul>\n<li>Present the data to the user as a set of relations.</li>\n<li>Provide relational operators to manipulate the data in tabular form.</li>\n<li>Use a modeling language to define the schema of each database hosted in the DBMS, according to the DBMS data model.</li>\n<li>Optimize data structures (fields, records, files and objects) to deal with very large amounts of data stored on a permanent data storage device.</li>\n<li>Create a database query language and report writer to allow users to interactively interrogate the database, analyze its data and update it according to the users privileges on data.</li>\n<li>Develop a transaction mechanism, that would guarantee the ACID properties, in order to ensure data integrity, despite concurrent user accesses and faults.</li>\n</ul>', '<p style=\"text-align: center;\">One Year Completion - Online</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c61\" text=\"CSC-61\" /]</p>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c2\" text=\"CSC-2\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c20\" text=\"CSC-20\" /]</td>\n</tr>\n<tr>\n<td>[class id=\"c62\" text=\"CIS-62\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c91\" text=\"CIS-91\" /]</td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: left;\">&nbsp;</p>\n</td>\n<td>&nbsp;</td>\n<td>[class id=\"c28a\" text=\"CSC-28A\" /][class id=\"c63\" text=\"CSC-63\" /]</td>\n</tr>\n</tbody>\n</table>'),
(6, 6, 0, '<p>The Systems Development mini certificate gives students the skills necessary to analyze, design, and develop an information system in any business environment that is involved in keeping data about various entities up-to-date and/or processing daily transactions.</p>', '<ul>\n<li>Demonstrate an understanding of systems analysis as applied to the effective use of computers in business operations.</li>\n<li>Analyze user requirements in business operations applying structured analysis tools like Data Flow Diagrams, Data Dictionary and Process Description.</li>\n<li>Design various system components like output, input and user interface screens, reports, and normalized files.</li>\n<li>Demonstrate an understanding of various developmental methodologies including the use of CASE tools.</li>\n<li>Design relational database tables, queries, forms, reports, macros, validation rules in MS Access.</li>\n<li>Demonstrate how to document a database and how MS Access can interface with the Web, demonstrate error trapping, database security, and automating ActiveX Controls with VBA.</li>\n<li>Demonstrate an understanding of System Architecture, Implementation, Operations, Support and Security plus various tools for cost benefit analysis and project management.</li>\n</ul>', '<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c61\" text=\"CSC-61\" /]</p>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c2\" text=\"CSC-2\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c62\" text=\"CIS-62\" /]</td>\n</tr>\n<tr>\n<td>[class id=\"c20\" text=\"CSC-20\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c91\" text=\"CIS-91\" /]</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n</td>\n<td>&nbsp;</td>\n<td>&nbsp;</td>\n</tr>\n</tbody>\n</table>'),
(7, 7, 0, '<p>Cisco Certified Network Associate (CCNA) certificate validates the ability to install, configure, operate, and troubleshoot medium-size router and switched networks, including implementation and verification of connections to remote sites in a WAN. CCNA curriculum includes basic mitigation of security threats, introduction to wireless networking concepts and terminology, and performance-based skills. This includes (but is not limited to) the use of these protocols: IP, Enhanced Interior Gateway Routing Protocol (EIGRP), Serial Line Interface Protocol Frame Relay, Routing Information Protocol Version 2 (RIPv2),VLANs, Ethernet, access control lists (ACLs). This certificate is designed for students with advanced problem solving and analytical skills. The curriculum offers a comprehensive and theoretical learning experience for analytical students, and uses language that aligns well with engineering concepts. Interactive activities are embedded in the curriculum, along with detailed, theoretical labs.</p>', '<ul>\n<li>Demonstrate an understanding of routing fundamentals, subnets and IP addressing schemes.</li>\n<li>Explain the command and steps required to configure router host tables, and interfaces within the RIP, EIGRP and OSPF protocols.</li>\n<li>Demonstrate an understanding of switching concepts and LAN design to include the use of Virtual LANs with LAN trunking configured by the Spanning Tree Protocol.</li>\n<li>Define and demonstrate the concepts of Cisco\'s implementation of ISDN networking including WAN link options.</li>\n</ul>', '<p style=\"text-align: center;\">One Year Completion</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>&nbsp;</td>\n<td>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c26a\" text=\"CIS-26A\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c26b\" text=\"CIS-26B\" /]</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n</td>\n<td>&nbsp;</td>\n<td style=\"text-align: center;\">\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c26c\" text=\"CIS-26C\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c26d\" text=\"CIS-26D\" /]</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>'),
(8, 8, 0, '<p>certification description</p>', '<ul>\n<li>learn stuff</li>\n</ul>', '<p style=\"text-align: center;\">Completion in One Year</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c21\" text=\"CIS-21\" /]</p>\n<p>[class id=\"c25\" text=\"CIS-25\" /]</p>\n<p>[class id=\"c27\" text=\"CIS-27\" /]</p>\n<p>[class id=\"c27a\" text=\"CIS-27A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>\n<p>&nbsp;[class id=\"c21a\" text=\"CSC-21A\" /]</p>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c26a\" text=\"CIS-26A\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c26b\" text=\"CIS-26B\" /]</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<p style=\"text-align: center;\">Information Security &amp; CISCO Certificates</p>\n<p style=\"text-align: center;\">Completion in Two Years</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>&nbsp;</td>\n<td>\n<p>[class id=\"c21\" text=\"CIS-21\" /]</p>\n<p>[class id=\"c25\" text=\"CIS-25\" /]</p>\n<p>[class id=\"c27\" text=\"CIS-27\" /]</p>\n<p>[class id=\"c27a\" text=\"CIS-27A\" /]</p>\n</td>\n<td>&nbsp;</td>\n<td>\n<p>&nbsp;[class id=\"c21a\" text=\"CSC-21A\" /]</p>\n<p>&nbsp;</p>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c26a\" text=\"CIS-26A\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c26b\" text=\"CIS-26B\" /]<br /><br /></td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n<tr>\n<td>Year 2</td>\n<td>&nbsp;</td>\n<td>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c26c\" text=\"CIS-26C\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c26d\" text=\"CIS-26D\" /]</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n</td>\n<td>&nbsp;</td>\n<td>&nbsp;</td>\n</tr>\n</tbody>\n</table>'),
(9, 9, 0, '<p>&nbsp;</p>\n<p>&nbsp;</p>', '<ul>\n<li>Apply programming principles to develop a fully functioning and customized web site experience for both the site user and site administrator.</li>\n<li>Use JavaScript to enhance a web site\'s interactivity using the DOM.</li>\n<li>Use PHP to enhance a web site\'s capabilities by creating data driven web page content, custom form validation and processing, and database manipulation.</li>\n</ul>', '<p style=\"text-align: center;\">Full Time Completion in One Year</p>\n<table>\n<tbody>\n<tr>\n<td style=\"width: 44px;\">Year</td>\n<td style=\"width: 51.8px;\">Summer</td>\n<td style=\"width: 501.2px;\">Fall</td>\n<td style=\"width: 46px;\">Winter</td>\n<td style=\"width: 46px;\">Spring</td>\n</tr>\n<tr>\n<td style=\"width: 44px;\">Year 1</td>\n<td style=\"width: 51.8px;\">&nbsp;</td>\n<td style=\"width: 501.2px;\">\n<p>[class id=\"c76b\" text=\"CIS-76B\" /]</p>\n<p>[class id=\"c78a\" text=\"CIS-78A\" /]</p>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c72a\" text=\"CIS-72A\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c72b\" text=\"CIS-72B\" /]</td>\n</tr>\n</tbody>\n</table>\n<p><br /><br /></p>\n</td>\n<td style=\"width: 46px;\">&nbsp;</td>\n<td style=\"width: 46px;\">\n<p>[class id=\"a67\" text=\"ADM-67\" /]</p>\n<p>[class id=\"c12\" text=\"CSC-12\" /]</p>\n<p>[class id=\"c14a\" text=\"CSC-14A\" /]</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\">Webmaster Certificate - Designer (CE 820) And Webmaster Certificate - Developer (CE 843)</p>\n<p style=\"text-align: center;\">Completion in One Year</p>\n<table>\n<tbody>\n<tr>\n<td style=\"width: 44px;\">Year</td>\n<td style=\"width: 51.8px;\">Summer</td>\n<td style=\"width: 501.2px;\">Fall</td>\n<td style=\"width: 46px;\">Winter</td>\n<td style=\"width: 46px;\">Spring</td>\n</tr>\n<tr>\n<td style=\"width: 44px;\">Year 1</td>\n<td style=\"width: 51.8px;\">&nbsp;[class id=\"c79\" text=\"CAT-79\" /]</td>\n<td style=\"width: 501.2px;\">\n<p>[class id=\"c76b\" text=\"CIS-76B\" /]</p>\n<p>[class id=\"c78a\" text=\"CIS-78A\" /]</p>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c72a\" text=\"CIS-72A\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c72b\" text=\"CIS-72B\" /]</td>\n</tr>\n</tbody>\n</table>\n<p><br /><br /></p>\n</td>\n<td style=\"width: 46px;\">&nbsp;[class id=\"c81\" text=\"CIS-81\" /]</td>\n<td style=\"width: 46px;\">\n<p>[class id=\"a67\" text=\"ADM-67\" /]</p>\n<p>[class id=\"c12\" text=\"CSC-12\" /]</p>\n<p>[class id=\"c14a\" text=\"CSC-14A\" /]</p>\n<p>&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>'),
(10, 10, 0, '<p>&nbsp;&nbsp;</p>', '<ul>\n<li>Apply design and visual communication principles to web site, page, and interface design.</li>\n<li>Use Photoshop to create and edit images for use on the web, including photographs, logos, navigation buttons, background images, image maps, and web page design mockups (tracing images).</li>\n<li>Use Flash to create web animations and interactive web sites.</li>\n</ul>', '<p style=\"text-align: center;\">Full Time Completion in One Year</p>\n<table>\n<tbody>\n<tr>\n<td style=\"width: 44px;\">Year</td>\n<td style=\"width: 51.8px;\">Summer</td>\n<td style=\"width: 501.2px;\">Fall</td>\n<td style=\"width: 46px;\">Winter</td>\n<td style=\"width: 46px;\">Spring</td>\n</tr>\n<tr>\n<td style=\"width: 44px;\">Year 1</td>\n<td style=\"width: 51.8px;\">&nbsp;[class id=\"c79\" text=\"CAT-79\" /]</td>\n<td style=\"width: 501.2px;\">\n<p>[class id=\"c76b\" text=\"CIS-76B\" /]</p>\n<p>[class id=\"c78a\" text=\"CIS-78A\" /]</p>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c72a\" text=\"CIS-72A\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c72b\" text=\"CIS-72B\" /]</td>\n</tr>\n</tbody>\n</table>\n<p><br /><br /></p>\n</td>\n<td style=\"width: 46px;\">&nbsp;[class id=\"c81\" text=\"CIS-81\" /]</td>\n<td style=\"width: 46px;\">\n<p>[class id=\"a67\" text=\"ADM-67\" /]</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\">Webmaster Certificate - Designer (CE 820) And Webmaster Certificate - Developer (CE 843)</p>\n<p style=\"text-align: center;\">Completion in One Year</p>\n<table>\n<tbody>\n<tr>\n<td style=\"width: 44px;\">Year</td>\n<td style=\"width: 51.8px;\">Summer</td>\n<td style=\"width: 501.2px;\">Fall</td>\n<td style=\"width: 46px;\">Winter</td>\n<td style=\"width: 46px;\">Spring</td>\n</tr>\n<tr>\n<td style=\"width: 44px;\">Year 1</td>\n<td style=\"width: 51.8px;\">&nbsp;[class id=\"c79\" text=\"CAT-79\" /]</td>\n<td style=\"width: 501.2px;\">\n<p>[class id=\"c76b\" text=\"CIS-76B\" /]</p>\n<p>[class id=\"c78a\" text=\"CIS-78A\" /]</p>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c72a\" text=\"CIS-72A\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c72b\" text=\"CIS-72B\" /]</td>\n</tr>\n</tbody>\n</table>\n<p><br /><br /></p>\n</td>\n<td style=\"width: 46px;\">&nbsp;[class id=\"c81\" text=\"CIS-81\" /]</td>\n<td style=\"width: 46px;\">\n<p>[class id=\"a67\" text=\"ADM-67\" /]</p>\n<p>[class id=\"c12\" text=\"CSC-12\" /]</p>\n<p>[class id=\"c14a\" text=\"CSC-14A\" /]</p>\n<p>&nbsp;</p>\n</td>\n</tr>\n</tbody>\n</table>'),
(11, 11, 0, '<p>This program prepares individuals to perform basic data and text entry using standard and customized software products. This includes instruction in keyboarding skills, personal computer and work station operation, reading draft texts and raw data forms, and various interactive software programs used for tasks such as word processing, spreadsheets, databases, and others.</p>', '<ul>\n<li>Describe and use operating system software.</li>\n<li>Describe and use Word processing software.</li>\n<li>Write structured programs using C++, or Java.</li>\n<li>Describe and use graphics software to manipulate digital images.</li>\n<li>Describe and use database software to construct 3NF databases.</li>\n<li>Construct a visually appealing web site including database structures within the design.</li>\n<li>Design and use spreadsheets that have embedded equations/formulas utilizing different data types.</li>\n</ul>', '<p style=\"text-align: center;\">Full Time Completion in One Year</p>\n<table>\n<tbody>\n<tr>\n<td>Year</td>\n<td>Summer</td>\n<td>Fall</td>\n<td>Winter</td>\n<td>Spring</td>\n</tr>\n<tr>\n<td>Year 1</td>\n<td>\n<p>[class id=\"c79\" text=\"CAT-79\" /]</p>\n<p>[class id=\"c95a\" text=\"CIS-95A\" /]</p>\n</td>\n<td>&nbsp;\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c1a\" text=\"CIS-1A\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c1b\" text=\"CIS-1B\" /]</td>\n</tr>\n<tr>\n<td>[class id=\"c21\" text=\"CIS-21\" /]</td>\n<td>&nbsp;</td>\n</tr>\n<tr>\n<td>[class id=\"c72a\" text=\"CIS-72A\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c72b\" text=\"CIS-72B\" /]</td>\n</tr>\n</tbody>\n</table>\n</td>\n<td>\n<p>[class id=\"c95a\" text=\"CIS-95A\" /]</p>\n<p>[class id=\"c81\" text=\"CIS-81\" /]</p>\n</td>\n<td>\n<p>[class id=\"c5\" text=\"CSC-5\" /]</p>\n<p>Or</p>\n<p>[class id=\"c28a\" text=\"CSC-28A\" /]</p>\n<p>[class id=\"c80\" text=\"CAT-80\" /]</p>\n<table>\n<tbody>\n<tr>\n<td style=\"text-align: center;\" colspan=\"2\">8 Week Classes</td>\n</tr>\n<tr>\n<td>[class id=\"c2\" text=\"CSC-2\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c61\" text=\"CSC-61\" /]</td>\n</tr>\n<tr>\n<td>[class id=\"c98a\" text=\"CIS-98A*\" /]</td>\n<td style=\"text-align: right;\">[class id=\"c98b\" text=\"CIS-98B\" /]</td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n<p>&nbsp;</p>\n<p>* 98B is the required course but has 98A as a prerequisite</p>\n<p><sup>1</sup> can be taken in of CIS 98A and CIS 98B?? what is this from</p>\n<p>&nbsp;</p>'),
(18, 14, 0, '<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; font-family: \'Open Sans\', Arial, sans-serif;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam dapibus aliquet ante at fringilla. Nunc a sapien et est facilisis hendrerit porta at dui. Nullam finibus, dolor quis porttitor finibus.</p>', '<ul>\n<li>\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; font-family: \'Open Sans\', Arial, sans-serif;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam dapibus aliquet ante at fringilla. Nunc a sapien et est facilisis hendrerit porta at dui.</p>\n</li>\n</ul>', '<table>\n<tbody>\n<tr>\n<td style=\"width: 87.2px;\">Year</td>\n<td style=\"width: 156px;\">Summer</td>\n<td style=\"width: 156.8px;\">Fall</td>\n<td style=\"width: 156.8px;\">Winter</td>\n<td style=\"width: 156.8px;\">Spring</td>\n</tr>\n<tr>\n<td style=\"width: 87.2px;\">Year 1</td>\n<td style=\"width: 156px;\">&nbsp;[class id=\"e1\" text=\"EXA-1\" /]</td>\n<td style=\"width: 156.8px;\">[class id=\"e1\" text=\"EXA-1\" /]</td>\n<td style=\"width: 156.8px;\">&nbsp;[class id=\"e1\" text=\"EXA-1\" /]</td>\n<td style=\"width: 156.8px;\">&nbsp;[class id=\"e1\" text=\"EXA-1\" /]</td>\n</tr>\n</tbody>\n</table>');

-- --------------------------------------------------------

--
-- Table structure for table `certificateList`
--

DROP TABLE IF EXISTS `certificateList`;
CREATE TABLE `certificateList` (
  `id` int(11) NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `hasAs` tinyint(1) NOT NULL DEFAULT '0',
  `hasCe` tinyint(1) NOT NULL DEFAULT '1',
  `units` float NOT NULL,
  `category` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `certificateList`
--

INSERT INTO `certificateList` (`id`, `code`, `hasAs`, `hasCe`, `units`, `category`, `description`, `sort`) VALUES
(1, '650', 1, 0, 29, 1, 'Computer Science AD-T', 0),
(2, '728', 1, 1, 26, 1, 'Computer Programming', 1),
(3, '803', 0, 1, 13, 1, 'C++ Programming', 2),
(4, '809', 0, 1, 13, 1, 'Java Programming', 3),
(5, '816', 0, 1, 12, 1, 'Relational Database Technology', 4),
(6, '806', 0, 1, 12, 1, 'Systems Development', 5),
(7, '810', 0, 1, 16, 2, 'CISCO Networking', 0),
(8, 'XXX', 0, 1, 17, 2, 'Information Security', 1),
(9, '843', 0, 1, 17, 3, 'Web Developer', 0),
(10, '820', 0, 1, 17, 3, 'Web Designer', 1),
(11, '726', 1, 1, 32, 3, 'Computer Applications', 2),
(14, '123', 0, 1, 50, 1, 'tester', 0);

-- --------------------------------------------------------

--
-- Table structure for table `classData`
--

DROP TABLE IF EXISTS `classData`;
CREATE TABLE `classData` (
  `id` int(11) NOT NULL,
  `class` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `language` int(11) NOT NULL DEFAULT '0',
  `prereq` text COLLATE utf8_unicode_ci,
  `coreq` text COLLATE utf8_unicode_ci,
  `advisory` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `units` float NOT NULL,
  `transfer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prereq` text COLLATE utf8_unicode_ci,
  `coreq` text COLLATE utf8_unicode_ci NOT NULL,
  `advisory` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `sort`, `title`, `units`, `transfer`, `prereq`, `coreq`, `advisory`, `description`) VALUES
('a67', 67, 'ADM-67 - Multimedia Animation', 3, 'CSU', 'None', '', '', 'A comprehensive course exploring the digital techniques, methods and software tools used by the industry to develop digital animation suitable for motion graphics, web design, film, video and multimedia platforms. 36 hours lecture and 72 hours laboratory.'),
('c11', 11, 'CSC-11 - Computer Architecture and Organization Assembly', 3, 'CSU', '', '', '[class id=\"c5\" text=\"CIS-5\" /]', 'An introduction to microprocessor architecture and assembly language programming. The relationship between the hardware and the software will be studied in order to understand the interaction between a program and the total system. Mapping of statements and constructs in a high-level language onto sequences of machine instructions is studied as well as the internal representation of simple data types and structures. Numerical computation is performed, noting the various data representation errors and potential procedural errors. 54 hours lecture and 18 hours laboratory. (TBA option)'),
('c12', 12, 'CSC-12 - PHP Dynamic Web Site Programming', 3, 'CSU', '', '', '[class id=\"c5\" text=\"CIS-5\" /] and [class id=\"c14a\" text=\"CSC-14A\" /] or [class id=\"c72a\" text=\"CIS-72A\" /]', 'Dynamic web site programming using PHP. Fundamentals of server-side web programming. Introduction to database-driven web sites, using PHP to access a database such as MySQL. Web applications such as user registration, content management, and e- commerce. This course is for students already familiar with the fundamentals of programming and HTML. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c14a', 14, 'CSC-14A - Web Programming: JavaScript', 3, 'CSU', '', '', '[class id=\"c5\" text=\"CIS-5\" /] or [class id=\"c72a\" text=\"CIS-72A\" /]', 'Dynamic web site programming using PHP. Fundamentals of server-side web programming. Introduction to database-driven web sites, using PHP to access a database such as MySQL. Web applications such as user registration, content management, and e- commerce. This course is for students already familiar with the fundamentals of programming and HTML. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c17a', 17, 'CSC-17A - Programming Concepts and	3 units Methodology II', 3, 'UC, CSU (C-ID COMP 132)', '[class id=\"c5\" text=\"CIS-5\" /]', '', '', 'Examination of information systems and their role in business. Focus on information systems, database management systems, networking, e-commerce, ethics and security, computer systems hardware and software components. Application of these concepts and methods through hands-on projects developing computer-based solutions to business problems. Utilizing a systems approach students will use databases, spreadsheets, word processors, presentation graphics, and the Internet to solve business problems and communicate solutions. 54 hours lecture and 18 hours laboratory. (TBA option)'),
('c17b', 17, 'CSC-17B - C++ Programming: Advanced Objects', 3, 'UC, CSU', '[class id=\"c17a\" text=\"CSC-17A\" /]', '', '', 'This is an advanced C++ programming course for students familiar with object-oriented programming and utilization of basic graphical interface techniques. An emphasis will be placed on advanced concepts associated with complex Business and Gaming applications that utilize exception handling, multithreading, multimedia, and database connectivity. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c17c', 17, 'CSC-17C - C++ Programming: Data Structures', 3, 'UC, CSU', '', '', '[class id=\"c17a\" text=\"CSC-17A\" /]', 'This course offers a thorough presentation of the essential principles and practices of data structures using the C++ programming language. The course emphasizes abstract data types, software engineering principles, lists, stacks, queues, trees, graphs and the comparative analysis of algorithms. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c18a', 18, 'CSC-18A - Java Programming: Objects', 3, 'UC, CSU', '', '', '[class id=\"c5\" text=\"CIS-5\" /]', 'An introduction to Java programming for students already experienced in the fundamentals of programming. An emphasis will be placed upon object-oriented programming. Other topics include graphical interface design and typical swing GUI components. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c18b', 18, 'CSC-18B - Java Programming: Advanced Objects', 3, 'UC, CSU', '', '', '[class id=\"c18a\" text=\"CSC-18A\" /]', 'This is an advanced JAVA programming course for students familiar with object-oriented programming and utilization of basic graphical interface techniques. An emphasis will be placed on advanced concepts associated with Business, E-Commerce and Gaming applications that utilize exception handling, multithreading, multimedia, and database connectivity. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c18c', 18, 'CSC-18C - Java Programming: Data Structures', 3, 'UC, CSU', '', '', '[class id=\"c18a\" text=\"CSC-18A\" /]', 'This course is designed to be an advanced Java programming course for students familiar with object-oriented programming and database concepts. The major emphasis will be related to concepts of storing and retrieving data efficiently, which are the essential principles and practices of data structures. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c1a', 1, 'CIS-1A - Introduction to Computer Information System', 3, 'UC, CSU (C-ID ITIS 120)', 'none', '', '[class id=\"c1a\" text=\"CIS-1A\" /]', 'Examination of information systems and their role in business. Focus on information systems, database management systems, networking, e-commerce, ethics and security, computer systems hardware and software components. Application of these concepts and methods through hands-on projects developing computer-based solutions to business problems. Utilizing a systems approach students will use databases, spreadsheets, word processors, presentation graphics, and the Internet to solve business problems and communicate solutions. 54 hours lecture and 18 hours laboratory. (TBA option)'),
('c1b', 1, 'CIS-1B - Advanced Concepts in Computer Information Systems', 3, 'CSU', '[class id=\"c1a\" text=\"CIS-1A\" /]', '', '', 'Advanced computer applications. Advanced concepts and skills of word processing, spreadsheets, presentation graphics, the Internet and databases with an emphasis on multitasking, integrating applications, linking and embedding are covered. 54 hours lecture and 18 hours laboratory. (TBA option)'),
('c2', 2, 'CSC-2 - Fundamentals of Systems Analysis', 3, 'CSU', '', '', '', 'The course presents a systematic methodology for analyzing a business problem or opportunity, determining what role, if any, computer-based technologies can play in addressing the business need, articulating business requirements for the technology solution, specifying alternative approaches to acquiring the technology capabilities needed to address the business requirements, and specifying the requirements for the information systems solution in particular, in-house development, development from third-party providers, or purchased commercial-off-the-shelf packages. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c20', 20, 'CSC-20 - Systems Analysis and Design', 3, 'CSU', '[class id=\"c2\" text=\"CSC-2\" /]', '', 'Students should have a working knowledge of MS Access', 'Structured design techniques for the development and implementation of computerized business applications. Course includes project planning, analysis of current system, design of a new system, implementation, consideration of data base design and development, file organization, and modular programming techniques. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c21', 21, 'CIS-21 - Introduction to Operating Systems', 3, 'CSU', '[class id=\"c1a\" text=\"CIS-1A\" /]', '', '', 'An introduction to operating concepts, structure, functions, performance and management is covered. A current operating system, such as Windows, Linux, or UNIX is used as a case study. File multi-processing, system security, device management, network operating systems, and utilities are introduced. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c21a', 21, 'CSC-21A - Linux Operating System Administration', 3, 'CSU', '', '', '', 'This course covers the fundamentals of the Linux operating system, system architecture, installation, command line functions, performance, and file systems. All major administrative responsibilities associated with this operating system are performed. These tasks shall include but not be limited to system installation, configuration, security, and backups for both client and server which might be found in a small business environment. This course aligns with the Linux Professional, LPI.org LPIC-1 Certification exam. 54 hours lecture. (Letter Grade, or Pass/No Pass option.)'),
('c23', 23, 'CIS-23 - Software End User Support', 3, '', '', '', '[class id=\"c1a\" text=\"CIS-1A\" /] and [class id=\"c1b\" text=\"CIS-1B\" /]', 'This course provides a comprehensive introduction to the implementation of database management systems using Microsoft Access. The student will be provided hands-on experience in modeling work problems and transforming them to a relational data model. The student will design data tables to efficiently store data. The student will be shown techniques for entering, changing and deleting data using datasheets and forms. The student will learn to filter and modify data using queries and to output data using both forms and reports. Access macros will be applied to forms and reports. The student will be presented with database projects to reinforce their lectures. 54 hours lecture and 18 hours laboratory. (TBA option)'),
('c25', 25, 'CIS-25 - Information and Communication Technology Essentials', 3, '', '', '', '[class id=\"c1a\" text=\"CIS-1A\" /]', 'This course provides an introduction to the computer hardware and software skills needed to help meet the growing demand for entry- level ICT professionals. The fundamentals of computer hardware and software as well as advanced concepts such as security, networking, and the responsibilities of an ICT professional will be introduced. Preparation for the CompTIA A+ certification exams. 54 hours lecture. (Letter Grade, or Pass/No Pass option.)'),
('c26a', 26, 'CIS-26A - Cisco Networking Academy 1A', 4, 'CSU', '', '', '[class id=\"c1a\" text=\"CIS-1A\" /], [class id=\"c21\" text=\"CIS-21\" /] or [class id=\"c23\" text=\"CIS-23\" /]', 'This course introduces the architecture, structure, functions, components, and models of the Internet and other computer networks. The principles and structure of IP (Internet Protocol) addressing and the fundamentals of Ethernet concepts, media, and operations are introduced to provide a foundation for further study of computer networks. It uses the OSI (Open Systems Interconnection) and TCP (Transmission Control Protocol) layered models to examine the nature and roles of protocols and services at the application, network, data link, and physical layers. Preparation for the CompTIA Network+ certification exam. 72 hours lecture. (Letter Grade, or Pass/No Pass option)'),
('c26b', 26, 'CIS-26B - Cisco Networking Academy 1B', 4, 'CSU', '[class id=\"c26a\" text=\"CIS-26A\" /]', '', '', 'This course describes the architecture, components, and operations of routers and switches in a small network. Students learn how to configure a router and a switch for basic functionality. By the end of this course students will be able to configure and troubleshoot routers and switches and resolve common issues with RIPv1, RIPv2, single-area and multi-area OSPF, virtual LANs, and inter- VLAN routing in both IPv4 and IPv6 networks. This course is one of four Cisco-related curricula designed to prepare students for Cisco Certified Network Associate (CCNA) certification examination. 72 hours lecture. (Letter Grade, or Pass/No Pass option.)'),
('c26c', 26, 'CIS-26C - Cisco Networking Academy 1C', 4, 'CSU', '[class id=\"c26b\" text=\"CIS-26B\" /]', '', '', 'This course introduces students to troubleshooting common network problems, using a layered model approach, interpret network diagrams, LAN segmentation using switches, routers, full- duplex Ethernet operations, dynamic routing, and the network administrator\'s role. Students will learn to configure, verify, and troubleshoot VLANs, inter VLAN routing, VTP trunking on Cisco switches, and RSTP operation. Students will identify the basic parameters to configure a wireless network and common implementation issues. This course is one of four Cisco-related curricula designed to prepare students for Cisco Certified Network Associate (CCNA) certification examination. 72 hours lecture. (Letter Grade, or Pass/No Pass option.)'),
('c26d', 26, 'CIS-26D - Cisco Networking Academy 1D', 4, 'CSU', '[class id=\"c26c\" text=\"CIS-26C\" /]', '', '', 'This course introduces students to configure WAN services, frame relay, WAN serial connection, IPV6, encapsulate WAN data, High- Level Data Link Control (HDLC), Point-to-Point Protocol (PPP), ACLs access lists, and the network administrator\'s role and function. Students will learn to configure the Frame Relay operations and troubleshoot DHCP, DNS, ACL\'s. This course is one of four Cisco-related curricula designed to prepare students for Cisco Certified Network Associate (CCNA) certification examination. 72 hours lecture. (Letter Grade, or Pass/No Pass option.)'),
('c27', 27, 'CIS-27 - Information and Network Security', 3, '', '', '', '[class id=\"c1a\" text=\"CIS-1A\" /] and [class id=\"c23\" text=\"CIS-23\" /]', 'An introduction to the fundamental principles and topics of Information Technology Security and Risk Management at the organizational level. It addresses hardware, software, processes, communications, applications, and policies and procedures with respect to organizational Cybersecurity and Risk Management. Preparation for the CompTIA Security+ certification exams. 54 hours lecture.'),
('c27a', 27, 'CIS-27A - Computer Forensics Fundamentals', 3, '', '', '', '[class id=\"c27\" text=\"CIS-27\" /]', 'This course is an introduction to the methods used to properly conduct a computer forensics investigation beginning with a discussion of ethics, while mapping to the objectives of the International Association of Computer Investigative Specialists (IACIS) certification. Topics covered include an overview of computer forensics as a profession; the computer investigation process; understanding operating systems boot processes and disk structures; data acquisition and analysis; technical writing; and a review of familiar computer forensics tools. 54 hours lecture and 18 hours laboratory.'),
('c28a', 28, 'CSC-28A - MS Access Programming', 3, 'CSU', '', '', '[class id=\"c5\" text=\"CIS-5\" /]', 'Use of the data management program, MS Access, in writing command file programs to automate database management applications with the use of Visual Basic Applications variables, expressions and functions. This course shows students how event driven programs operate. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c5', 5, 'CSC-5 - Programming Concepts and Methodology I: C++', 4, 'UC, CSU (C-ID COMP 122)', 'None', '', '[class id=\"c1a\" text=\"CIS-1A\" /]', 'Introduction to the discipline of computer science incorporating problem definitions, algorithm development, and structured programming logic for business, scientific and mathematical applications. The C++ language will be used for programming problems. 54 hours lecture and 54 hours laboratory'),
('c61', 61, 'CSC-61 - Intro to Database Theory', 3, 'CSU', 'None', '', '', 'This course provides students with an introduction to the core concepts in data and information management. It is centered around the core skills of identifying organizational information requirements, modeling them using conceptual data modeling techniques, converting the conceptual data models into relational data models and verifying its structural characteristics with normalization techniques, and implementing and utilizing a relational database using an industrial-strength database management system. The course will also include coverage of basic database administration tasks and key concepts of data quality and data security. In addition to developing database applications, the course helps the students understand how large-scale packaged systems are highly dependent on the use of Database Management Systems (DBMSs). Building on the transactional database understanding, the course provides an introduction to data and information management technologies that provide decision support capabilities under the broad business intelligence umbrella. 54 hours of lecture and 18 hours laboratory. (TBA option)'),
('c62', 62, 'CIS-62 - Microsoft Access DBMS: Comprehensive', 3, '', '', '', 'Previous computer experience', 'This course provides a comprehensive introduction to the implementation of database management systems using Microsoft Access. The student will be provided hands-on experience in modeling work problems and transforming them to a relational data model. The student will design data tables to efficiently store data. The student will be shown techniques for entering, changing and deleting data using datasheets and forms. The student will learn to filter and modify data using queries and to output data using both forms and reports. Access macros will be applied to forms and reports. The student will be presented with database projects to reinforce their lectures. 54 hours lecture and 18 hours laboratory. (TBA option)'),
('c63', 63, 'CSC-63 - Introduction to Structured Query Language (SQL)', 3, '', '', '', '', 'This course provides an introduction to the relational database management system industry standard - Structured Query Language (SQL). Students will analyze, design, and implement database schema using the SQL programming language. SQL will be utilized to develop a database structure (DDL). The student will use SQL to create both Select and action queries(DML). Joins, Unions, Differences and sub-query statements will be covered. Both the Access and Oracle SQL statements will be covered. 54 hours lecture, and 18 hours laboratory. (TBA option)'),
('c7', 7, 'CSC-7 - Discrete Structures', 3, 'UC, CSU (C-ID COMP 152)', '[class id=\"c5\" text=\"CIS-5\" /]', '', '', 'A comprehensive course exploring the digital techniques, methods and software tools used by the industry to develop digital animation suitable for motion graphics, web design, film, video and multimedia platforms. 36 hours lecture and 72 hours laboratory.'),
('c72a', 72, 'CIS-72A - Introduction to Web Page Creation', 1.5, 'CSU', 'None', '', 'Competency in the use of a computer, familiarity with the Internet; [class id=\"c95a\" text=\"CIS-95A\" /]', 'An introduction to webpage creation using Extensible Hypertext Markup Language (XHTML). Use XHTML to design and create webpages with formatted text, hyperlinks, lists, images, tables, frames and forms. 27 hours lecture and 18 hours laboratory. (Letter Grade, or Pass/No Pass option.) (TBA option)'),
('c72b', 72, 'CIS-72B - Intermediate Web Page Creation using Cascading Style Sheets (CSS)', 1.5, '', '', '', 'Knowledge of HTML and the Internet; [class id=\"c72a\" text=\"CIS-72A\" /] and [class id=\"c95a\" text=\"CIS-95A\" /]', 'Intermediate webpage creation using cascading style sheets (CSS) to format and lay out webpage content. CSS works with HTML, so HTML knowledge is recommended. Inline styles, embedded styles, and external style sheets are covered. CSS is used to format text, links, set fonts, colors, margins and position text and graphics on a page. CSS is also a component of Dynamic HTML. 27 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c76b', 76, 'CIS-76B - Introduction to Dreamweaver', 3, '', '', '', '[class id=\"c95a\" text=\"CIS-95A\" /]', 'Provides students with the knowledge and skills required to quickly design and implement webpages and to administer and update existing websites using Dreamweaver. The course uses Dreamweaver to streamline and automate website management on a website. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c78a', 78, 'CIS-78A - Introduction to Adobe Photoshop', 3, 'CSU', '', '', '', 'Introduction to Adobe Photoshop including mastery of digital image editing, techniques for selecting, photo correction, manipulating images and vector drawing. This course also provides instruction in retouching images, special effects, working with image color and web page illustrations. 54 hours lecture and 18 hours laboratory. (TBA option)'),
('c79', 79, 'CAT-79 - Introduction to Adobe Illustrator', 3, 'CSU', '', '', '', 'Fundamentals of Adobe Illustrator, including creating objects, drawing paths and designing with type, creating freehand drawing and illustration, importing and working with graphics. Develop a working knowledge of the processes that generate graphic images: layering, shadowing, and color use. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c80', 80, 'CAT-80 - Word Processing: Microsoft Word for Windows', 3, 'CSU', '', '', 'Typing knowledge/skills with a least 40 wpm', 'This course is designed to provide introductory, intermediate and advanced skill levels necessary to produce a variety of professional documents using Microsoft Word, a word processing program. Students will develop skills in word processing techniques and tasks. 54 hours lecture and 18 hours laboratory. (TBA option)'),
('c81', 81, 'CIS-81 - Introduction to Desktop Publishing using Adobe InDesign', 3, '', '', '', '', 'Page design and layout techniques using Adobe InDesign. Mastery of beginning and intermediate techniques of document creation, including design skills. Successful incorporation of drawing and bit mapped files to create professional printed media. 54 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c91', 91, 'CIS-91 - Microsoft Project', 3, '', '', '', '', 'This course utilizes Microsoft Project to build, track and account for variances and changes in the baseline plan. Emphasis is placed on project management, tracking and information analysis. 54 hours lecture and 18 hours laboratory. (TBA option)'),
('c95a', 95, 'CIS-95A - Introduction to the Internet', 1.5, 'CSU', '', '', '', 'Skill development in the concepts of the Internet on microcomputer-based systems. This course is designed as a practical step-by-step introduction to working with the Internet using personal computers. 27 hours lecture.'),
('c98a', 98, 'CIS-98A - Introduction to Excel', 1.5, 'CSU', '', '', '', 'Skill development in electronic spreadsheets using Excel for business and scientific related applications. 27 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('c98b', 98, 'CIS-98B - Advanced Excel', 1.5, 'CSU', '[class id=\"c98a\" text=\"CIS-98A\" /]', '', '', 'Advanced concepts of MS-Excel including managing large spread- sheets, creating and working with databases, creating and using templates and macro creation. Spreadsheet manipulation with advanced macro techniques, customizing Excel screen and toolbars and solving problems with goal seeker and solver. 27 hours lecture and 18 hours laboratory. (TBA option) (Letter Grade, or Pass/No Pass option.)'),
('e1', 0, 'EXA-1 - Example class a simple test', 1, 'CSU, UC', '[class id=\"c1a\" text=\"CIS-1A\" /]', '[class id=\"c1b\" text=\"CIS-1B\" /]', 'None', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam dapibus aliquet ante at fringilla. Nunc a sapien et est facilisis hendrerit porta at dui. Nullam finibus, dolor quis porttitor finibus, purus turpis hendrerit ipsum, eget dictum justo lectus ac tortor. Nulla tempus nunc et facilisis facilisis.'),
('m1a', 1, 'MAT-1A - Calculus I', 4, 'UC*, CSU (C-ID MATH 210) (C-ID MATH 900S=MAT-1A + MAT-1B)', 'MAT-10 or qualifying placement level', '', '', 'Functions, limits, continuity, differentiation, applications of the derivative and integration, the fundamental theorem of calculus and basic integration. 72 hours lecture and 18 hours laboratory. (Letter Grade, or Pass/No Pass option.)'),
('m1b', 1, 'MAT-1B - Calculus II', 4, 'UC, CSU (C-ID MATH 220) (C-ID MATH 900S=MAT-1A + MAT-1B)', '[class id=\"m1a\" text=\"MAT-1A\" /]', '', '', 'Techniques of integration, applications of integration, improper integrals, infinite sequences and series, parametric equations, and polar coordinates. 72 hours lecture and 18 hours laboratory. (Letter Grade, or Pass/No Pass option.)'),
('p4a', 4, 'PHY-4A - Mechanics', 4, 'UC*, CSU (C-ID PHYS 200S=PHY-4A+PHY-4B+PHY- 4C+PHY-4D) (C-ID PHYS 205)', 'None', 'Concurrent enrollment in or prior completion of [class id=\"m1a\" text=\"MAT-1A\" /]', '', 'Examines vectors, particle kinematics and dynamics, work and power, conservation of energy and momentum, rotation, oscillations and gravitation. 54 hours lecture and 54 hours laboratory.'),
('p4b', 4, 'PHY-4B - Electricity and Magnetism', 4, 'UC*, CSU (C-ID PHY 200S=PHY-4A+PHY-4B+PHY- 4C+PHY-4D) (C-ID PHYS 210)', '[class id=\"p4a\" text=\"PHY-4A\" /]', 'Concurrent enrollment in or prior completion of [class id=\"m1b\" text=\"MAT-1B\" /]', '', 'Study of electric fields, voltage, current, magnetic fields, electromagnetic induction, alternating currents and electromagnetic waves. 54 hours lecture and 54 hours laboratory.');

-- --------------------------------------------------------

--
-- Table structure for table `enumCategories`
--

DROP TABLE IF EXISTS `enumCategories`;
CREATE TABLE `enumCategories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `enumCategories`
--

INSERT INTO `enumCategories` (`id`, `category`) VALUES
(1, 'Programming'),
(2, 'Networking & Information Security'),
(3, 'Web Master & Applications');

-- --------------------------------------------------------

--
-- Table structure for table `enumLanguages`
--

DROP TABLE IF EXISTS `enumLanguages`;
CREATE TABLE `enumLanguages` (
  `id` int(11) NOT NULL,
  `code` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `fullName` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `enumLanguages`
--

INSERT INTO `enumLanguages` (`id`, `code`, `fullName`) VALUES
(1, 'en', 'English'),
(2, 'es', 'Español');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `forUser` int(11) NOT NULL COMMENT 'token for the user',
  `byUser` int(11) NOT NULL COMMENT 'who set the token',
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `forUser`, `byUser`, `used`, `creationDate`) VALUES
(1, 'eQevERDvb1RGCiu', 9, 2, 1, '2017-06-23 07:38:12'),
(2, 'cL1m8HkydB2girG', 9, 2, 1, '2017-06-28 23:44:50'),
(4, 'S77L4bwvn6nhR6w', 10, 2, 1, '2017-07-03 20:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `lastIP` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `latestDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `lastIP`, `creationDate`, `latestDate`, `isAdmin`, `active`) VALUES
(1, 'rishermichael', '12139eb5c6a5ad8dd40bda292a39e9638c18936400dbb810d8aca98f1f98f0a4f0a69d9b55c1eaaf21b70b5845c1a2541d3c97bd08acd8cffc640a9bc820b006', '::1', '2017-05-31 19:30:17', '2017-06-01 18:35:28', 0, 1),
(2, 'tester', '511f86cd39b28e4e07d565f300a6f80873378ef7bc821fbadf93e5e8b108313fdbed534aa5d183411a5f3d707eb859ed546617d77cec1636d42e97b731778fb2', '::1', '2017-06-01 00:14:03', '2017-07-12 19:40:36', 1, 1),
(10, 'joe', ' ', '', '2017-07-03 20:50:42', '0000-00-00 00:00:00', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificateData`
--
ALTER TABLE `certificateData`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cert` (`cert`);

--
-- Indexes for table `certificateList`
--
ALTER TABLE `certificateList`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classData`
--
ALTER TABLE `classData`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class` (`class`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `enumCategories`
--
ALTER TABLE `enumCategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enumLanguages`
--
ALTER TABLE `enumLanguages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit`
--
ALTER TABLE `audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;
--
-- AUTO_INCREMENT for table `certificateData`
--
ALTER TABLE `certificateData`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `certificateList`
--
ALTER TABLE `certificateList`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `classData`
--
ALTER TABLE `classData`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `enumCategories`
--
ALTER TABLE `enumCategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `enumLanguages`
--
ALTER TABLE `enumLanguages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
