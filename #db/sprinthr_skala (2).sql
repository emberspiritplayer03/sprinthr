-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2023 at 04:41 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sprinthr_skala`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_geotagging`
--

CREATE TABLE IF NOT EXISTS `attendance_geotagging` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` text,
  `employee_code` text,
  `employee_name` text,
  `dateIn` text,
  `dateOut` text,
  `timeIn` text,
  `timeOut` text,
  `locationIn` text,
  `locationOut` text,
  `imageIn` text,
  `imageOut` text,
  `actualIn` text,
  `actualOut` text,
  `scheduleName` text,
  `actualLocationIn` text,
  `actualLocationOut` text,
  `actualBreakIn` varchar(100) DEFAULT NULL,
  `actualBreakOut` varchar(100) DEFAULT NULL,
  `breakIn` varchar(100) DEFAULT NULL,
  `breakOut` varchar(100) DEFAULT NULL,
  `OtIn` varchar(100) DEFAULT NULL,
  `OtOut` varchar(100) DEFAULT NULL,
  `breakInImage` text,
  `breakOutImage` text,
  `OtInImage` text,
  `OtOutImage` text,
  `breakInLocation` text,
  `breakOutLocation` text,
  `otInLocation` text,
  `otOutLocation` text,
  PRIMARY KEY (`attendance_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Table structure for table `captures`
--

CREATE TABLE IF NOT EXISTS `captures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_capture` datetime NOT NULL,
  `profile` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint(20) unsigned NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `dtr_telcos`
--

CREATE TABLE IF NOT EXISTS `dtr_telcos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) NOT NULL,
  `date_time_in` datetime DEFAULT NULL,
  `date_time_out` datetime DEFAULT NULL,
  `date_ot_in` datetime DEFAULT NULL,
  `date_ot_out` datetime DEFAULT NULL,
  `supervisor_id` bigint(20) DEFAULT NULL,
  `capture_date_time_in_path` longtext COLLATE utf8mb4_unicode_ci,
  `capture_date_time_out_path` longtext COLLATE utf8mb4_unicode_ci,
  `capture_date_overtime_in_path` longtext COLLATE utf8mb4_unicode_ci,
  `capture_date_overtime_out_path` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_access_rights`
--

CREATE TABLE IF NOT EXISTS `g_access_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `rights` varchar(240) COLLATE utf8_unicode_ci NOT NULL COMMENT 'serialize',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_activity_category`
--

CREATE TABLE IF NOT EXISTS `g_activity_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_category_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `activity_category_description` text COLLATE utf8_unicode_ci NOT NULL,
  `date_created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_activity_skills`
--

CREATE TABLE IF NOT EXISTS `g_activity_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_skills_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `activity_skills_description` text COLLATE utf8_unicode_ci NOT NULL,
  `date_created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_allowed_ip`
--

CREATE TABLE IF NOT EXISTS `g_allowed_ip` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `date_modified` varchar(50) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_anviz_connection`
--

CREATE TABLE IF NOT EXISTS `g_anviz_connection` (
  `id` int(11) NOT NULL,
  `terminal_no` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `device_name` varchar(32) NOT NULL,
  `firmware` varchar(32) NOT NULL,
  `ip_address` varchar(32) NOT NULL,
  `mask` varchar(32) NOT NULL,
  `gateway` varchar(32) NOT NULL,
  `server_ip` varchar(32) NOT NULL,
  `mac_address` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `g_anviz_raw_logs`
--

CREATE TABLE IF NOT EXISTS `g_anviz_raw_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `time` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `backup_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_anviz_users`
--

CREATE TABLE IF NOT EXISTS `g_anviz_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_code` varchar(32) NOT NULL,
  `device_employee_id` int(11) NOT NULL,
  `fullname` varchar(32) NOT NULL,
  `nickname` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `verification_type` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `card1` int(11) NOT NULL,
  `card2` int(11) NOT NULL,
  `card3` int(11) NOT NULL,
  `kgroup` int(11) NOT NULL,
  `sync` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant`
--

CREATE TABLE IF NOT EXISTS `g_applicant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `employee_id` int(11) DEFAULT NULL COMMENT 'if hired',
  `company_structure_id` int(11) NOT NULL,
  `job_vacancy_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `application_status_id` int(11) NOT NULL COMMENT '0=application submitted,  1=interview,  2=offered a job, 3=declined offer, 4=reject, 5=hired',
  `lastname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `middlename` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `extension_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `marital_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `birth_place` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `home_telephone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email_address` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `qualification` text COLLATE utf8_unicode_ci NOT NULL,
  `sss_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `tin_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `pagibig_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `philhealth_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `applied_date_time` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `hired_date` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `rejected_date` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'if applicant did not passed, or failed or declined or delinquent',
  `resume_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `resume_path` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `application_status_id` (`application_status_id`),
  KEY `job_id` (`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_attachment`
--

CREATE TABLE IF NOT EXISTS `g_applicant_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '346kb',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'doc, docx, pdf',
  `date_attached` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `added_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'hr admin name',
  `screen` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'personal details,\r\n employment details, qualification',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_education`
--

CREATE TABLE IF NOT EXISTS `g_applicant_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `institute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `course` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `year` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `gpa_score` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `attainment` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_examination`
--

CREATE TABLE IF NOT EXISTS `g_applicant_examination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `passing_percentage` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `exam_code` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `schedule_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `date_taken` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pending, failed, passed, rescheduled',
  `result` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `questions` text COLLATE utf8_unicode_ci NOT NULL,
  `time_duration` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'day:hour:minute',
  `scheduled_by` int(11) NOT NULL COMMENT 'employee_id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_language`
--

CREATE TABLE IF NOT EXISTS `g_applicant_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `language` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fluency` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'writing, speaking,reading',
  `competency` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'poor,basic,good, mother tongue',
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_license`
--

CREATE TABLE IF NOT EXISTS `g_applicant_license` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `license_type` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Engineer License etc',
  `license_number` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `issued_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `expiry_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_logs`
--

CREATE TABLE IF NOT EXISTS `g_applicant_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(180) CHARACTER SET latin1 NOT NULL,
  `country` varchar(200) CHARACTER SET latin1 NOT NULL,
  `firstname` varchar(180) CHARACTER SET latin1 NOT NULL,
  `lastname` varchar(180) CHARACTER SET latin1 NOT NULL,
  `email` varchar(180) CHARACTER SET latin1 NOT NULL,
  `password` varchar(150) CHARACTER SET latin1 NOT NULL,
  `status` varchar(50) CHARACTER SET latin1 NOT NULL,
  `date_time_created` varchar(110) CHARACTER SET latin1 NOT NULL,
  `date_time_validated` varchar(110) CHARACTER SET latin1 NOT NULL,
  `link` text CHARACTER SET latin1 NOT NULL,
  `is_password_change` varchar(3) CHARACTER SET latin1 NOT NULL DEFAULT 'No' COMMENT '''Yes'' or ''No''',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_medical_history`
--

CREATE TABLE IF NOT EXISTS `g_applicant_medical_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(20) NOT NULL,
  `date` varchar(50) NOT NULL,
  `chief_complaint` text NOT NULL,
  `medical_diagnosis` text NOT NULL,
  `treatment` text NOT NULL,
  `confined` varchar(5) NOT NULL,
  `confined_from` varchar(50) NOT NULL,
  `confined_to` varchar(50) NOT NULL,
  `physicians_name` varchar(150) NOT NULL,
  `clinic_or_hospital_name` text NOT NULL,
  `attachment` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_profile`
--

CREATE TABLE IF NOT EXISTS `g_applicant_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `applicant_log_id` int(11) NOT NULL,
  `lastname` varchar(20) CHARACTER SET latin1 NOT NULL,
  `firstname` varchar(20) CHARACTER SET latin1 NOT NULL,
  `middlename` varchar(20) CHARACTER SET latin1 NOT NULL,
  `extension_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `birthdate` date NOT NULL,
  `gender` varchar(20) CHARACTER SET latin1 NOT NULL,
  `marital_status` varchar(128) CHARACTER SET latin1 NOT NULL,
  `home_telephone` varchar(30) CHARACTER SET latin1 NOT NULL,
  `mobile` varchar(30) CHARACTER SET latin1 NOT NULL,
  `birth_place` varchar(128) CHARACTER SET latin1 NOT NULL,
  `address` varchar(250) CHARACTER SET latin1 NOT NULL,
  `city` varchar(30) CHARACTER SET latin1 NOT NULL,
  `province` varchar(250) CHARACTER SET latin1 NOT NULL,
  `zip_code` varchar(30) CHARACTER SET latin1 NOT NULL,
  `sss_number` varchar(64) CHARACTER SET latin1 NOT NULL,
  `tin_number` varchar(64) CHARACTER SET latin1 NOT NULL,
  `philhealth_number` varchar(64) CHARACTER SET latin1 NOT NULL,
  `pagibig_number` varchar(30) CHARACTER SET latin1 NOT NULL,
  `resume_name` varchar(250) CHARACTER SET latin1 NOT NULL,
  `resume_path` varchar(250) CHARACTER SET latin1 NOT NULL,
  `photo` varchar(150) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_requirements`
--

CREATE TABLE IF NOT EXISTS `g_applicant_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `requirements` text COLLATE utf8_unicode_ci NOT NULL,
  `is_complete` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '1=complete, 0=incomplete',
  `date_updated` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `applicant_id` (`applicant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_skills`
--

CREATE TABLE IF NOT EXISTS `g_applicant_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `skill` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `years_experience` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_training`
--

CREATE TABLE IF NOT EXISTS `g_applicant_training` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `provider` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cost` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `renewal_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_applicant_work_experience`
--

CREATE TABLE IF NOT EXISTS `g_applicant_work_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `company` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `job_title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `comment` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_attendance_rate`
--

CREATE TABLE IF NOT EXISTS `g_attendance_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_default` int(11) DEFAULT NULL,
  `salary_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nightshift_rate` float NOT NULL COMMENT '%',
  `regular_overtime` float NOT NULL COMMENT '% (example: 125%)',
  `nightshift_overtime` float NOT NULL COMMENT '%',
  `restday` float NOT NULL COMMENT '%',
  `restday_overtime` float NOT NULL COMMENT '%',
  `holiday_special` float NOT NULL COMMENT '%',
  `holiday_special_overtime` float NOT NULL COMMENT '%',
  `holiday_legal` float NOT NULL COMMENT '%',
  `holiday_legal_overtime` float NOT NULL COMMENT '%',
  `holiday_special_restday` float NOT NULL COMMENT '%',
  `holiday_special_restday_overtime` float NOT NULL COMMENT '%',
  `holiday_legal_restday` float NOT NULL COMMENT '%',
  `holiday_legal_restday_overtime` float NOT NULL COMMENT '%',
  `holiday_legal_restday_night_shift_overtime` float DEFAULT NULL,
  `regular_overtime_nightshift_differential` float NOT NULL,
  `restday_night_differential` float NOT NULL DEFAULT '13',
  `holiday_special_night_differential` float NOT NULL DEFAULT '13',
  `holiday_special_restday_night_differential` float NOT NULL DEFAULT '15',
  `holiday_legal_night_differential` float NOT NULL DEFAULT '20',
  `holiday_legal_restday_night_differential` float NOT NULL DEFAULT '26',
  `holiday_double_night_differential` float NOT NULL DEFAULT '33',
  `holiday_double_restday_night_differential` float NOT NULL DEFAULT '39',
  `regular_night_differential_overtime` float NOT NULL DEFAULT '12.5',
  `restday_night_differential_overtime` float NOT NULL DEFAULT '16.9',
  `holiday_special_night_differential_overtime` float NOT NULL DEFAULT '16.9',
  `holiday_special_restday_night_differential_overtime` float NOT NULL DEFAULT '19.5',
  `holiday_legal_night_differential_overtime` float NOT NULL DEFAULT '26',
  `holiday_legal_restday_night_differential_overtime` float NOT NULL DEFAULT '33.8',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `g_attendance_rate`
--

INSERT INTO `g_attendance_rate` (`id`, `is_default`, `salary_type`, `nightshift_rate`, `regular_overtime`, `nightshift_overtime`, `restday`, `restday_overtime`, `holiday_special`, `holiday_special_overtime`, `holiday_legal`, `holiday_legal_overtime`, `holiday_special_restday`, `holiday_special_restday_overtime`, `holiday_legal_restday`, `holiday_legal_restday_overtime`, `holiday_legal_restday_night_shift_overtime`, `regular_overtime_nightshift_differential`, `restday_night_differential`, `holiday_special_night_differential`, `holiday_special_restday_night_differential`, `holiday_legal_night_differential`, `holiday_legal_restday_night_differential`, `holiday_double_night_differential`, `holiday_double_restday_night_differential`, `regular_night_differential_overtime`, `restday_night_differential_overtime`, `holiday_special_night_differential_overtime`, `holiday_special_restday_night_differential_overtime`, `holiday_legal_night_differential_overtime`, `holiday_legal_restday_night_differential_overtime`) VALUES
(1, 0, 'DAILY', 10, 125, 125, 130, 125, 130, 169, 200, 260, 150, 195, 260, 338, 33.8, 125, 13, 13, 15, 20, 26, 33, 39, 12.5, 16.9, 16.9, 19.5, 26, 33.8),
(2, 1, 'MONTHLY', 10, 125, 125, 130, 125, 30, 169, 100, 260, 150, 195, 260, 338, 33.8, 125, 13, 13, 15, 20, 26, 33, 39, 12.5, 16.9, 16.9, 19.5, 26, 33.8);

-- --------------------------------------------------------

--
-- Table structure for table `g_audit_trail`
--

CREATE TABLE IF NOT EXISTS `g_audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(150) CHARACTER SET latin1 NOT NULL,
  `action` varchar(150) CHARACTER SET latin1 NOT NULL,
  `event_status` varchar(20) CHARACTER SET latin1 NOT NULL,
  `details` varchar(255) CHARACTER SET latin1 NOT NULL,
  `audit_date` varchar(50) CHARACTER SET latin1 NOT NULL,
  `ip_address` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_break_time_schedule`
--

CREATE TABLE IF NOT EXISTS `g_break_time_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_in` time NOT NULL,
  `schedule_out` time NOT NULL,
  `break_in` time NOT NULL,
  `break_out` time NOT NULL,
  `total_hrs_break` float NOT NULL DEFAULT '0',
  `total_hrs_to_deduct` int(2) NOT NULL,
  `to_deduct` int(2) NOT NULL DEFAULT '0' COMMENT '0 - not to deduct in total hrs worked / 1 - deduct to total hrs worked',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_break_time_schedule_details`
--

CREATE TABLE IF NOT EXISTS `g_break_time_schedule_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `header_id` int(11) NOT NULL,
  `obj_id` int(11) NOT NULL,
  `obj_type` varchar(2) NOT NULL COMMENT 'a = all, e = employee, d = department',
  `break_in` time NOT NULL,
  `break_out` time NOT NULL,
  `to_deduct` int(2) NOT NULL DEFAULT '0' COMMENT '0 = no / 1 = yes',
  `to_required_logs` int(2) NOT NULL DEFAULT '0',
  `applied_to_legal_holiday` int(2) NOT NULL,
  `applied_to_special_holiday` int(2) NOT NULL,
  `applied_to_restday` int(2) NOT NULL,
  `applied_to_regular_day` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_break_time_schedule_header`
--

CREATE TABLE IF NOT EXISTS `g_break_time_schedule_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_in` time NOT NULL,
  `schedule_out` time NOT NULL,
  `break_time_schedules` text NOT NULL,
  `applied_to` text NOT NULL,
  `date_start` varchar(80) NOT NULL,
  `date_created` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_company_branch`
--

CREATE TABLE IF NOT EXISTS `g_company_branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `location_id` int(40) NOT NULL,
  `is_archive` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `g_company_branch`
--

INSERT INTO `g_company_branch` (`id`, `company_structure_id`, `name`, `province`, `city`, `address`, `zip_code`, `phone`, `fax`, `location_id`, `is_archive`) VALUES
(1, 1, 'Main', 'Undefined', 'Undefined', 'Undefined', '0000', '000000', '0000', 0, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `g_company_info`
--

CREATE TABLE IF NOT EXISTS `g_company_info` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(6) NOT NULL,
  `address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `address1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `tin_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sss_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `philhealth_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pagibig_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `company_logo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `g_company_info`
--

INSERT INTO `g_company_info` (`id`, `company_structure_id`, `address`, `phone`, `fax`, `address1`, `city`, `address2`, `state`, `zip_code`, `tin_number`, `sss_number`, `philhealth_number`, `pagibig_number`, `remarks`, `company_logo`) VALUES
(1, 1, 'Undefined', '0', '0', '', 'Undefined', '', 'Undefined', '0000', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `g_company_structure`
--

CREATE TABLE IF NOT EXISTS `g_company_structure` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `company_branch_id` int(6) NOT NULL DEFAULT '0',
  `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Branch / Department / Group / Team',
  `parent_id` int(6) NOT NULL DEFAULT '0',
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `g_company_structure`
--

INSERT INTO `g_company_structure` (`id`, `company_branch_id`, `title`, `description`, `type`, `parent_id`, `is_archive`) VALUES
(4, 1, 'General Affairs', '', 'Department', 1, 'No'),
(1, 1, 'Undefined', 'Company Description', '', 0, 'No'),
(5, 1, 'Human Resources', '', 'Department', 1, 'No'),
(7, 1, 'Logistics', '', 'Department', 1, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `g_converted_leaves`
--

CREATE TABLE IF NOT EXISTS `g_converted_leaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(30) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `year` int(5) NOT NULL,
  `total_leave_converted` varchar(20) NOT NULL,
  `amount` float(11,2) NOT NULL COMMENT 'Non taxable amount',
  `date_converted` date NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_custom_overtime`
--

CREATE TABLE IF NOT EXISTS `g_custom_overtime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `day_type` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_cutoff_period`
--

CREATE TABLE IF NOT EXISTS `g_cutoff_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year_tag` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `payout_date` date NOT NULL,
  `cutoff_number` int(11) NOT NULL,
  `salary_cycle_id` int(11) NOT NULL,
  `is_lock` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
  `is_payroll_generated` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=513 ;

--
-- Dumping data for table `g_cutoff_period`
--

INSERT INTO `g_cutoff_period` (`id`, `year_tag`, `period_start`, `period_end`, `payout_date`, `cutoff_number`, `salary_cycle_id`, `is_lock`, `is_payroll_generated`) VALUES
(416, '2024', '2023-12-28', '2024-01-12', '2024-01-15', 1, 2, 'No', 'No'),
(512, '2023', '2023-01-13', '2023-01-27', '2023-01-30', 2, 2, 'No', 'No'),
(511, '2023', '2022-12-28', '2023-01-12', '2023-01-15', 1, 2, 'No', 'No'),
(510, '2023', '2023-02-13', '2023-02-27', '2023-02-28', 2, 2, 'No', 'No'),
(509, '2023', '2023-01-28', '2023-02-12', '2023-02-15', 1, 2, 'No', 'No'),
(508, '2023', '2023-03-13', '2023-03-27', '2023-03-30', 2, 2, 'No', 'No'),
(507, '2023', '2023-02-28', '2023-03-12', '2023-03-15', 1, 2, 'No', 'No'),
(506, '2023', '2023-04-13', '2023-04-27', '2023-04-30', 2, 2, 'No', 'No'),
(505, '2023', '2023-03-28', '2023-04-12', '2023-04-15', 1, 2, 'No', 'No'),
(504, '2023', '2023-05-13', '2023-05-27', '2023-05-30', 2, 2, 'No', 'No'),
(503, '2023', '2023-04-28', '2023-05-12', '2023-05-15', 1, 2, 'No', 'No'),
(502, '2023', '2023-06-13', '2023-06-27', '2023-06-30', 2, 2, 'No', 'No'),
(501, '2023', '2023-05-28', '2023-06-12', '2023-06-15', 1, 2, 'No', 'No'),
(500, '2023', '2023-07-13', '2023-07-27', '2023-07-30', 2, 2, 'No', 'No'),
(499, '2023', '2023-06-28', '2023-07-12', '2023-07-15', 1, 2, 'No', 'No'),
(498, '2023', '2023-08-13', '2023-08-27', '2023-08-30', 2, 2, 'No', 'No'),
(497, '2023', '2023-07-28', '2023-08-12', '2023-08-15', 1, 2, 'No', 'No'),
(496, '2023', '2023-09-13', '2023-09-27', '2023-09-30', 2, 2, 'No', 'No'),
(495, '2023', '2023-08-28', '2023-09-12', '2023-09-15', 1, 2, 'No', 'No'),
(494, '2023', '2023-10-13', '2023-10-27', '2023-10-30', 2, 2, 'No', 'No'),
(493, '2023', '2023-09-28', '2023-10-12', '2023-10-15', 1, 2, 'No', 'No'),
(492, '2023', '2023-11-13', '2023-11-27', '2023-11-30', 2, 2, 'No', 'No'),
(491, '2023', '2023-10-28', '2023-11-12', '2023-11-15', 1, 2, 'No', 'No'),
(490, '2023', '2023-12-13', '2023-12-27', '2023-12-30', 2, 2, 'No', 'No'),
(489, '2023', '2023-11-28', '2023-12-12', '2023-12-15', 1, 2, 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `g_daily_time_record`
--

CREATE TABLE IF NOT EXISTS `g_daily_time_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `employee_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_entry` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `time_entry` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_eeo_job_category`
--

CREATE TABLE IF NOT EXISTS `g_eeo_job_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `category_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_email_buffer`
--

CREATE TABLE IF NOT EXISTS `g_email_buffer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sent_from` varchar(80) CHARACTER SET latin1 NOT NULL COMMENT 'sender name, web email (Administrator, admin@gleent.com)',
  `email_address` varchar(50) CHARACTER SET latin1 NOT NULL,
  `sent_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `subject` varchar(100) CHARACTER SET latin1 NOT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  `attachment` varchar(100) CHARACTER SET latin1 NOT NULL,
  `is_sent` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT 'Yes / No',
  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT 'Yes / No',
  `error_message` text CHARACTER SET latin1 NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee`
--

CREATE TABLE IF NOT EXISTS `g_employee` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `hash` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `employee_device_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `company_structure_id` int(11) NOT NULL,
  `department_company_structure_id` int(11) NOT NULL DEFAULT '2',
  `employment_status_id` int(11) NOT NULL,
  `employee_status_id` int(11) NOT NULL,
  `eeo_job_category_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `photo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `salutation` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `middlename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `extension_name` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `birth_place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fathers_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mothers_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `marital_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `nationality` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `number_dependent` int(11) NOT NULL,
  `sss_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `tin_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `pagibig_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `philhealth_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `hired_date` date NOT NULL,
  `leave_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'resignation, terminated, endo dates',
  `resignation_date` date NOT NULL,
  `endo_date` date NOT NULL,
  `terminated_date` date NOT NULL,
  `inactive_date` date NOT NULL,
  `awol_date` date NOT NULL,
  `e_is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `is_tax_exempted` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `is_confidential` int(1) NOT NULL COMMENT '0 = No, 1 = Yes',
  `frequency_id` int(20) NOT NULL,
  `year_working_days` int(11) NOT NULL,
  `week_working_days` varchar(110) COLLATE utf8_unicode_ci NOT NULL,
  `cost_center` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `project_site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id-fname-lname-code` (`id`,`firstname`,`lastname`,`employee_code`),
  KEY `firstname-lastname` (`firstname`,`lastname`,`id`),
  KEY `id-first-last-code` (`id`,`firstname`,`lastname`,`employee_code`),
  KEY `firstname` (`firstname`),
  KEY `lastname` (`lastname`),
  KEY `employee_code` (`employee_code`),
  KEY `id` (`id`,`hash`,`firstname`,`lastname`,`employee_code`),
  KEY `company_structure_id` (`company_structure_id`),
  KEY `nationality` (`nationality`),
  KEY `id-employee_code` (`id`,`employee_code`),
  KEY `employee_status_id` (`employee_status_id`),
  KEY `hired_date-leave_date` (`hired_date`,`leave_date`),
  KEY `hired_date` (`hired_date`),
  KEY `leave_date` (`leave_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_activities`
--

CREATE TABLE IF NOT EXISTS `g_employee_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `project_site_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_site` varchar(191) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `activity_category_id` int(11) NOT NULL,
  `activity_skills_id` int(11) NOT NULL,
  `date` date NOT NULL COMMENT 'date in',
  `time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `date_out` date NOT NULL,
  `time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `date_created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_activity_attendance`
--

CREATE TABLE IF NOT EXISTS `g_employee_activity_attendance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_activity_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `project_site_id` int(11) DEFAULT NULL,
  `frequency_id` int(11) DEFAULT NULL,
  `payslip_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `activity_in` datetime DEFAULT NULL,
  `activity_out` datetime DEFAULT NULL,
  `activity_raw_worked_hrs` double(15,2) DEFAULT '0.00',
  `activity_deductible_break_hrs` double(15,2) DEFAULT '0.00',
  `activity_total_worked_hrs` double(15,2) DEFAULT '0.00',
  `total_amount_worked` double(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `laravel_project_site_attendances_employee_activity_id_unique` (`employee_activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_annualize_tax`
--

CREATE TABLE IF NOT EXISTS `g_employee_annualize_tax` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) NOT NULL,
  `year` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `gross_income_tax` float(11,2) NOT NULL,
  `less_personal_exemption` float(11,2) NOT NULL,
  `taxable_income` float(11,2) NOT NULL,
  `tax_due` float(11,2) NOT NULL,
  `tax_withheld_payroll` float(11,2) NOT NULL,
  `tax_refund_payable` float(11,2) NOT NULL,
  `sss_maternity_differential_taxable` float(11,2) NOT NULL DEFAULT '0.00',
  `sss_maternity_differential_nontaxable` float(11,2) NOT NULL DEFAULT '0.00',
  `hmo_premium` float(11,2) NOT NULL DEFAULT '0.00',
  `hmo_premium_taxable` float(11,2) NOT NULL DEFAULT '0.00',
  `hmo_premium_nontaxable` float(11,2) NOT NULL DEFAULT '0.00',
  `other_deductions_earnings_taxable` float(11,2) NOT NULL DEFAULT '0.00',
  `other_deductions_earnings_nontaxable` float(11,2) NOT NULL DEFAULT '0.00',
  `tax_withheld_previous_employer` float(11,2) NOT NULL DEFAULT '0.00',
  `taxable_compensation_previous_employer` float(11,2) NOT NULL DEFAULT '0.00',
  `cutoff_start_date` date NOT NULL,
  `cutoff_end_date` date NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_attachment`
--

CREATE TABLE IF NOT EXISTS `g_employee_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `filename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '346kb',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'doc, docx, pdf',
  `date_attached` date NOT NULL,
  `added_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'hr admin name',
  `screen` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'personal details, employment details, qualification',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_attendance`
--

CREATE TABLE IF NOT EXISTS `g_employee_attendance` (
  `id` bigint(50) NOT NULL AUTO_INCREMENT,
  `device_in` int(11) DEFAULT NULL,
  `device_out` int(11) DEFAULT NULL,
  `employee_id` bigint(11) NOT NULL,
  `date_attendance` date NOT NULL,
  `is_present` tinyint(1) NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `is_restday` tinyint(1) NOT NULL,
  `is_holiday` tinyint(1) NOT NULL,
  `is_ob` tinyint(1) NOT NULL,
  `ob_in` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ob_out` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ob_total_hrs` float DEFAULT NULL,
  `is_leave` smallint(1) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `is_suspended` tinyint(1) NOT NULL,
  `holiday_id` int(11) NOT NULL,
  `holiday_reference` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `holiday_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `holiday_type` tinyint(4) NOT NULL COMMENT '1 = legal, 2 = special',
  `scheduled_time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `scheduled_time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `scheduled_date_in` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `scheduled_date_out` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `total_schedule_hours` float NOT NULL,
  `actual_time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `actual_time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `actual_date_in` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `actual_date_out` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `overtime_time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `overtime_time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `overtime_date_in` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `overtime_date_out` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `total_overtime_hours` float NOT NULL,
  `early_overtime_in` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `early_overtime_out` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `total_hours_worked` float NOT NULL COMMENT 'based from actual time in and out',
  `night_shift_hours` float NOT NULL,
  `night_shift_overtime_hours` float NOT NULL,
  `night_shift_overtime_excess_hours` float NOT NULL,
  `night_shift_hours_special` float NOT NULL,
  `night_shift_hours_legal` float NOT NULL,
  `holiday_hours_special` float NOT NULL,
  `holiday_hours_legal` float NOT NULL,
  `overtime_hours` float NOT NULL,
  `overtime_excess_hours` float NOT NULL,
  `regular_overtime_hours` float NOT NULL,
  `regular_overtime_excess_hours` float NOT NULL,
  `regular_overtime_nightshift_hours` float NOT NULL,
  `regular_overtime_nightshift_excess_hours` float NOT NULL,
  `restday_overtime_hours` float NOT NULL,
  `restday_overtime_excess_hours` float NOT NULL,
  `restday_overtime_nightshift_hours` float NOT NULL,
  `restday_overtime_nightshift_excess_hours` float NOT NULL,
  `restday_legal_overtime_hours` float NOT NULL,
  `restday_legal_overtime_excess_hours` float NOT NULL,
  `restday_legal_overtime_ns_hours` float NOT NULL,
  `restday_legal_overtime_ns_excess_hours` float NOT NULL,
  `restday_special_overtime_hours` float NOT NULL,
  `restday_special_overtime_excess_hours` float NOT NULL,
  `restday_special_overtime_ns_hours` float NOT NULL,
  `restday_special_overtime_ns_excess_hours` float NOT NULL,
  `legal_overtime_hours` float NOT NULL,
  `legal_overtime_excess_hours` float NOT NULL,
  `legal_overtime_ns_hours` float NOT NULL,
  `legal_overtime_ns_excess_hours` float NOT NULL,
  `special_overtime_hours` float NOT NULL,
  `special_overtime_excess_hours` float NOT NULL,
  `special_overtime_ns_hours` float NOT NULL,
  `special_overtime_ns_excess_hours` float NOT NULL,
  `late_hours` float NOT NULL,
  `undertime_hours` float NOT NULL,
  `total_breaktime_deductible_hours` float NOT NULL,
  `project_site_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id-date_attendance` (`employee_id`,`date_attendance`),
  KEY `date_attendance` (`date_attendance`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Triggers `g_employee_attendance`
--
DROP TRIGGER IF EXISTS `triggerUpdateFpLogsOnInsert`;
DELIMITER //
CREATE TRIGGER `triggerUpdateFpLogsOnInsert` AFTER INSERT ON `g_employee_attendance`
 FOR EACH ROW BEGIN
				UPDATE g_fp_attendance_log
				SET is_transferred = 1
				WHERE `date` = NEW.date_attendance AND user_id = NEW.employee_id AND is_transferred = 0;
			END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `triggerUpdateFpLogsOnUpdate`;
DELIMITER //
CREATE TRIGGER `triggerUpdateFpLogsOnUpdate` AFTER UPDATE ON `g_employee_attendance`
 FOR EACH ROW BEGIN
				UPDATE g_fp_attendance_log
				SET is_transferred = 1
				WHERE date = NEW.date_attendance AND user_id = NEW.employee_id AND is_transferred = 0;
			END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_attendance_correction_request`
--

CREATE TABLE IF NOT EXISTS `g_employee_attendance_correction_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_applied` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `correct_date_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `correct_time_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `correct_time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pending / Approved / Disapproved',
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Yes / No',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_basic_salary_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_basic_salary_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `job_salary_rate_id` int(11) NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'monthly_rate, daily_rate, hourly_rate',
  `frequency_id` int(20) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `pay_period_id` int(11) NOT NULL COMMENT 'frequency rate: example Bi-Monthly or Monthly',
  `start_date` date NOT NULL,
  `end_date` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarks` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_benefits`
--

CREATE TABLE IF NOT EXISTS `g_employee_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) NOT NULL,
  `obj_type` varchar(20) CHARACTER SET latin1 NOT NULL,
  `benefit_id` int(11) NOT NULL,
  `apply_to_all` varchar(25) CHARACTER SET latin1 NOT NULL,
  `date_created` varchar(150) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_benefits_main`
--

CREATE TABLE IF NOT EXISTS `g_employee_benefits_main` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `company_structure_id` smallint(6) NOT NULL,
  `employee_department_id` bigint(20) NOT NULL,
  `benefit_id` int(11) NOT NULL,
  `applied_to` varchar(50) CHARACTER SET latin1 NOT NULL,
  `description` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `criteria` text COLLATE utf8_unicode_ci,
  `custom_criteria` text COLLATE utf8_unicode_ci,
  `excluded_emplooyee_id` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_structure_id` (`company_structure_id`),
  KEY `id` (`id`),
  KEY `employee_department_id` (`employee_department_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_branch_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_branch_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `company_branch_id` int(11) NOT NULL,
  `branch_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Null it means Current',
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `company_branch_id` (`company_branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_break_logs`
--

CREATE TABLE IF NOT EXISTS `g_employee_break_logs` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `employee_code` varchar(128) NOT NULL,
  `employee_name` varchar(128) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(64) NOT NULL,
  `type` varchar(64) NOT NULL,
  `remarks` varchar(128) NOT NULL,
  `sync` int(11) NOT NULL DEFAULT '1',
  `is_transferred` int(1) NOT NULL COMMENT '1 = transferred / 0 = new data',
  `employee_device_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_break_logs_summary`
--

CREATE TABLE IF NOT EXISTS `g_employee_break_logs_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attendance_date` date DEFAULT NULL,
  `employee_attendance_id` int(11) NOT NULL DEFAULT '0',
  `employee_id` int(11) NOT NULL DEFAULT '0',
  `schedule_id` int(11) NOT NULL DEFAULT '0',
  `required_log_break1` tinyint(1) NOT NULL DEFAULT '1',
  `log_break1_out_id` int(11) NOT NULL DEFAULT '0',
  `log_break1_out` varchar(191) DEFAULT NULL,
  `log_break1_in_id` int(11) NOT NULL DEFAULT '0',
  `log_break1_in` varchar(191) DEFAULT NULL,
  `required_log_break2` tinyint(1) NOT NULL DEFAULT '1',
  `log_break2_out_id` int(11) NOT NULL DEFAULT '0',
  `log_break2_out` varchar(191) DEFAULT NULL,
  `log_break2_in_id` int(11) NOT NULL DEFAULT '0',
  `log_break2_in` varchar(191) DEFAULT NULL,
  `required_log_break3` tinyint(1) NOT NULL DEFAULT '1',
  `log_break3_out_id` int(11) NOT NULL DEFAULT '0',
  `log_break3_out` varchar(191) DEFAULT NULL,
  `log_break3_in_id` int(11) NOT NULL DEFAULT '0',
  `log_break3_in` varchar(191) DEFAULT NULL,
  `log_ot_break1_out_id` int(11) NOT NULL DEFAULT '0',
  `log_ot_break1_out` varchar(191) DEFAULT NULL,
  `log_ot_break1_in_id` int(11) NOT NULL DEFAULT '0',
  `log_ot_break1_in` varchar(191) DEFAULT NULL,
  `log_ot_break2_out_id` int(11) NOT NULL DEFAULT '0',
  `log_ot_break2_out` varchar(191) DEFAULT NULL,
  `log_ot_break2_in_id` int(11) NOT NULL DEFAULT '0',
  `log_ot_break2_in` varchar(191) DEFAULT NULL,
  `total_break_hrs` float NOT NULL DEFAULT '0',
  `has_early_break_out` tinyint(1) NOT NULL DEFAULT '0',
  `total_early_break_out_hrs` float NOT NULL DEFAULT '0',
  `has_late_break_in` tinyint(1) NOT NULL DEFAULT '0',
  `total_late_break_in_hrs` float NOT NULL DEFAULT '0',
  `has_incomplete_break_logs` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_change_schedule_request`
--

CREATE TABLE IF NOT EXISTS `g_employee_change_schedule_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_applied` datetime NOT NULL,
  `date_start` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date_end` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `time_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `change_schedule_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` int(11) NOT NULL,
  `is_archive` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_contact_details`
--

CREATE TABLE IF NOT EXISTS `g_employee_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `home_telephone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `work_telephone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `work_email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `other_email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_contribution`
--

CREATE TABLE IF NOT EXISTS `g_employee_contribution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `sss_ee` float NOT NULL,
  `pagibig_ee` float NOT NULL,
  `philhealth_ee` float NOT NULL,
  `sss_er` float NOT NULL,
  `pagibig_er` float NOT NULL,
  `philhealth_er` float NOT NULL,
  `to_deduct` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_deductions`
--

CREATE TABLE IF NOT EXISTS `g_employee_deductions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` varchar(240) CHARACTER SET latin1 NOT NULL,
  `department_section_id` varchar(240) COLLATE utf8_unicode_ci NOT NULL,
  `employment_status_id` varchar(240) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET latin1 NOT NULL,
  `remarks` text CHARACTER SET latin1 NOT NULL,
  `amount` double NOT NULL,
  `payroll_period_id` int(11) NOT NULL,
  `apply_to_all_employee` varchar(10) CHARACTER SET latin1 NOT NULL,
  `status` varchar(20) CHARACTER SET latin1 NOT NULL,
  `is_taxable` varchar(10) CHARACTER SET latin1 NOT NULL,
  `frequency_id` int(11) NOT NULL,
  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
  `date_created` varchar(30) CHARACTER SET latin1 NOT NULL,
  `is_moved_deduction` int(11) NOT NULL COMMENT '0 = No, 1 = Yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_dependent`
--

CREATE TABLE IF NOT EXISTS `g_employee_dependent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `relationship` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_details_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_details_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(30) NOT NULL,
  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `modified_by` bigint(30) NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `history_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_modified` datetime NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
  PRIMARY KEY (`id`),
  KEY `firstname-lastname` (`id`),
  KEY `id-first-last-code` (`id`,`employee_code`),
  KEY `employee_code` (`employee_code`),
  KEY `id` (`id`,`employee_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_direct_deposit`
--

CREATE TABLE IF NOT EXISTS `g_employee_direct_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `bank_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `account` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `account_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'checking / savings',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_dynamic_field`
--

CREATE TABLE IF NOT EXISTS `g_employee_dynamic_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_employee_field_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `screen` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_earnings`
--

CREATE TABLE IF NOT EXISTS `g_employee_earnings` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` float(11,2) DEFAULT NULL,
  `payroll_period_id` int(11) NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `is_taxable` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL,
  `object_id` int(11) NOT NULL,
  `applied_to` int(10) NOT NULL,
  `earning_type` int(11) NOT NULL COMMENT '1 = percentage / 2 = amount',
  `percentage` float(11,2) NOT NULL,
  `percentage_multiplier` int(11) NOT NULL COMMENT '1 = monthly / 2 = daily',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `object_description` text COLLATE utf8_unicode_ci NOT NULL,
  `frequency_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_structure_id` (`company_structure_id`),
  KEY `payroll_period_id` (`payroll_period_id`),
  KEY `id` (`id`,`company_structure_id`,`payroll_period_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_education`
--

CREATE TABLE IF NOT EXISTS `g_employee_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `institute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `course` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `year` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `gpa_score` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `attainment` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `g_employee_education`
--

INSERT INTO `g_employee_education` (`id`, `employee_id`, `institute`, `course`, `year`, `start_date`, `end_date`, `gpa_score`, `attainment`) VALUES
(1, 862, 'School Name', 'Course Name', '', '2010-05-01', '2012-03-03', '5.0', ''),
(2, 863, 'School Name', 'Course Name', '', '2010-05-01', '2012-03-03', '5.0', ''),
(3, 863, 'School Name', 'Course Name', '', '2010-05-01', '2012-03-03', '5.0', ''),
(4, 5, 'School Name', 'Course Name', '', '2010-05-01', '2012-03-03', '5.0', ''),
(5, 6, 'School Name', 'Course Name', '', '2010-05-01', '2012-03-03', '5.0', ''),
(6, 6, 'School Name', 'Course Name', '', '2010-05-01', '2012-03-03', '5.0', ''),
(7, 512, 'School Name', 'Course Name', '', '2010-05-01', '2012-03-03', '5.0', ''),
(8, 589, 'School Name', 'Course Name', '', '2010-05-01', '2012-03-03', '5.0', ''),
(9, 589, 'School Name', 'Course Name', '', '2010-05-01', '2012-03-03', '5.0', '');

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_emergency_contact`
--

CREATE TABLE IF NOT EXISTS `g_employee_emergency_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `person` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `relationship` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `home_telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `work_telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(280) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_evaluation`
--

CREATE TABLE IF NOT EXISTS `g_employee_evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `score` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `attachments` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `evaluation_date` date NOT NULL,
  `next_evaluation_date` date NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL,
  `is_updated` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3498 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_extend_contract`
--

CREATE TABLE IF NOT EXISTS `g_employee_extend_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `is_done` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `end_date` (`end_date`),
  KEY `start_date` (`start_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_fixed_contributions`
--

CREATE TABLE IF NOT EXISTS `g_employee_fixed_contributions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `type` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `ee_amount` float(11,2) NOT NULL,
  `er_amount` float(11,2) NOT NULL,
  `is_activated` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_group_schedule`
--

CREATE TABLE IF NOT EXISTS `g_employee_group_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_group_id` int(11) NOT NULL COMMENT 'g_employee.id OR g_company_structure.id ',
  `schedule_group_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `date_start` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `date_end` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `employee_group` int(1) NOT NULL COMMENT '1 = Employee; 2 = Group',
  PRIMARY KEY (`id`),
  KEY `schedule_id` (`schedule_id`),
  KEY `employee_group_id` (`employee_group_id`),
  KEY `schedule_group_id` (`schedule_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This is template schedule' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_job_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_job_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `employment_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Part Time, Full Time etc',
  `start_date` date NOT NULL,
  `end_date` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_language`
--

CREATE TABLE IF NOT EXISTS `g_employee_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `language` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fluency` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'writing, speaking,reading',
  `competency` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'poor,basic,good, mother tongue',
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_leave_available`
--

CREATE TABLE IF NOT EXISTS `g_employee_leave_available` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `no_of_days_alloted` float NOT NULL,
  `no_of_days_available` float NOT NULL,
  `no_of_days_used` float NOT NULL,
  `covered_year` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_leave_credit_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_leave_credit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `credits_added` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_leave_credit_tracking`
--

CREATE TABLE IF NOT EXISTS `g_employee_leave_credit_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(10) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `credit` int(10) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_leave_request`
--

CREATE TABLE IF NOT EXISTS `g_employee_leave_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL DEFAULT '1',
  `employee_id` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `date_applied` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `time_applied` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date_start` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `date_end` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `apply_half_day_date_start` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `apply_half_day_date_end` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `leave_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Pending / Approved / Disapproved',
  `is_paid` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `is_archive` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Yes / No',
  PRIMARY KEY (`id`),
  KEY `emp_id-date_start-date_end` (`employee_id`,`date_start`,`date_end`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_leave_request_details`
--

CREATE TABLE IF NOT EXISTS `g_employee_leave_request_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_request_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_covered` varchar(24) NOT NULL,
  `leave_description` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `g_employee_leave_request_details`
--

INSERT INTO `g_employee_leave_request_details` (`id`, `leave_request_id`, `employee_id`, `date_covered`, `leave_description`) VALUES
(49, 242, 1, '2022-03-21', 'halfday start'),
(50, 243, 1, '2022-03-22', 'whole day'),
(51, 244, 1, '2022-03-23', 'halfday start'),
(53, 240, 1, '2022-03-17', 'halfday end'),
(54, 245, 2, '2022-03-21', 'whole day'),
(55, 245, 2, '2022-03-22', 'whole day'),
(56, 246, 1, '2022-03-14', 'whole day'),
(57, 246, 1, '2022-03-15', 'halfday end'),
(58, 247, 1, '2022-04-01', 'whole day'),
(59, 248, 1, '2022-03-18', 'halfday start'),
(60, 249, 124, '2022-03-25', 'whole day'),
(61, 250, 2, '2022-03-30', 'halfday start'),
(62, 250, 2, '2022-03-31', 'halfday end'),
(63, 251, 2, '2022-03-31', 'halfday start');

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_license`
--

CREATE TABLE IF NOT EXISTS `g_employee_license` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `license_type` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Engineer License etc',
  `license_number` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `issued_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `expiry_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `notes` varchar(164) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_loan`
--

CREATE TABLE IF NOT EXISTS `g_employee_loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `employee_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `loan_title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `release_date` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `interest_rate` varchar(50) CHARACTER SET latin1 NOT NULL,
  `loan_amount` double(11,2) NOT NULL,
  `amount_paid` double(11,2) NOT NULL,
  `months_to_pay` int(11) NOT NULL,
  `deduction_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` varchar(50) CHARACTER SET latin1 NOT NULL,
  `end_date` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `total_amount_to_pay` double(11,2) NOT NULL,
  `deduction_per_period` double(11,2) NOT NULL,
  `status` varchar(20) CHARACTER SET latin1 NOT NULL,
  `is_lock` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_loan_details`
--

CREATE TABLE IF NOT EXISTS `g_employee_loan_details` (
  `id` int(11) NOT NULL,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `date_of_payment` varchar(10) CHARACTER SET latin1 NOT NULL,
  `amount` double NOT NULL,
  `amount_paid` double NOT NULL,
  `is_paid` varchar(10) CHARACTER SET latin1 NOT NULL,
  `remarks` text CHARACTER SET latin1 NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_loan_payment_breakdown`
--

CREATE TABLE IF NOT EXISTS `g_employee_loan_payment_breakdown` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `loan_payment_id` int(11) NOT NULL,
  `reference_number` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `amount_paid` double NOT NULL,
  `date_paid` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_loan_payment_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_loan_payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_loan_id` int(11) NOT NULL,
  `deduction_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `reference_number` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `balance` double NOT NULL,
  `amount_paid` double NOT NULL,
  `date_paid` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_loan_payment_schedule`
--

CREATE TABLE IF NOT EXISTS `g_employee_loan_payment_schedule` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `loan_payment_scheduled_date` varchar(50) NOT NULL,
  `amount_to_pay` double(11,2) NOT NULL,
  `amount_paid` double(11,2) NOT NULL,
  `date_paid` varchar(50) NOT NULL,
  `remarks` varchar(180) NOT NULL,
  `is_lock` varchar(5) NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_make_up_schedule_request`
--

CREATE TABLE IF NOT EXISTS `g_employee_make_up_schedule_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `date_applied` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `date_from` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `date_to` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `end_time` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `is_approved` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_membership`
--

CREATE TABLE IF NOT EXISTS `g_employee_membership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `membership_type_id` int(11) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `subscription_ownership` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'company, individual',
  `subscription_amount` double NOT NULL,
  `commence_date` date NOT NULL,
  `renewal_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_memo`
--

CREATE TABLE IF NOT EXISTS `g_employee_memo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `memo_id` int(11) NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `memo` text COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(164) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_offense` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `offense_description` text COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'New',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_monthly_payslip`
--

CREATE TABLE IF NOT EXISTS `g_employee_monthly_payslip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `period_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `period_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `payout_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `basic_pay` double(15,2) NOT NULL,
  `gross_pay` double(15,2) NOT NULL,
  `total_earnings` decimal(10,2) DEFAULT NULL,
  `total_deductions` decimal(10,2) DEFAULT NULL,
  `net_pay` double(15,2) NOT NULL,
  `taxable` double(15,2) NOT NULL,
  `non_taxable` double(15,2) NOT NULL,
  `withheld_tax` float(15,2) NOT NULL,
  `month_13th` double(15,2) NOT NULL,
  `sss` double(15,2) NOT NULL,
  `sss_er` double(15,2) NOT NULL,
  `pagibig` double(15,2) NOT NULL,
  `pagibig_er` double(15,2) NOT NULL,
  `philhealth` double(15,2) NOT NULL,
  `philhealth_er` double(15,2) NOT NULL,
  `earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Initial earnings like overtime, late, basic pay',
  `other_earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'other earnings to be added manually',
  `deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `other_deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `labels` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `overtime` double NOT NULL,
  `number_of_declared_dependents` int(11) NOT NULL,
  `taxable_benefits` double(15,2) NOT NULL,
  `non_taxable_benefits` double(15,2) NOT NULL,
  `tardiness_amount` double(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `period_start_end` (`period_start`,`period_end`,`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_official_business_request`
--

CREATE TABLE IF NOT EXISTS `g_employee_official_business_request` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_applied` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `date_start` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `date_end` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `is_whole_day` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `time_start` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_end` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id-date` (`employee_id`,`date_start`,`date_end`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_overtime`
--

CREATE TABLE IF NOT EXISTS `g_employee_overtime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `date_in` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date_out` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pending, Approved, Disapproved',
  `is_archived` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `device_no` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id-date` (`employee_id`,`date`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_overtime_rates`
--

CREATE TABLE IF NOT EXISTS `g_employee_overtime_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `ot_rate` float(11,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_overtime_request`
--

CREATE TABLE IF NOT EXISTS `g_employee_overtime_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_attendance` date NOT NULL,
  `date_applied` datetime NOT NULL,
  `date_start` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date_end` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `time_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `overtime_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pending / Approved / Disapproved',
  `is_archive` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Yes / No',
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Not used. Deprecated' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_payable`
--

CREATE TABLE IF NOT EXISTS `g_employee_payable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `balance_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_amount` double(15,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_payable_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_payable_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_payable_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `amount_paid` double(15,2) NOT NULL,
  `date_paid` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_payslip`
--

CREATE TABLE IF NOT EXISTS `g_employee_payslip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `period_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `period_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `payout_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `basic_pay` double(15,2) NOT NULL,
  `gross_pay` double(15,2) NOT NULL,
  `total_earnings` decimal(10,2) DEFAULT NULL,
  `total_deductions` decimal(10,2) DEFAULT NULL,
  `net_pay` double(15,2) NOT NULL,
  `taxable` double(15,2) NOT NULL,
  `non_taxable` double(15,2) NOT NULL,
  `withheld_tax` float(15,2) NOT NULL,
  `month_13th` double(15,2) NOT NULL,
  `sss` double(15,2) NOT NULL,
  `pagibig` double(15,2) NOT NULL,
  `philhealth` double(15,2) NOT NULL,
  `earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Initial earnings like overtime, late, basic pay',
  `other_earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'other earnings to be added manually',
  `deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `other_deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `labels` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `overtime` double NOT NULL,
  `number_of_declared_dependents` int(11) NOT NULL,
  `taxable_benefits` double(15,2) NOT NULL,
  `non_taxable_benefits` double(15,2) NOT NULL,
  `tardiness_amount` double(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `period_start-end` (`period_start`,`period_end`,`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_payslip_loan_balance`
--

CREATE TABLE IF NOT EXISTS `g_employee_payslip_loan_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `period_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `period_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `loan_amount` double(11,2) NOT NULL,
  `period_deducted` double(11,2) NOT NULL,
  `total_amount_paid` double(11,2) NOT NULL,
  `loan_balance` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3577 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_performance`
--

CREATE TABLE IF NOT EXISTS `g_employee_performance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `performance_id` int(11) NOT NULL,
  `performance_title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `employee_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `position` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` date NOT NULL,
  `period_from` date NOT NULL,
  `period_to` date NOT NULL,
  `due_date` date NOT NULL,
  `summary` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'being reviewed, pending',
  `kpi` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_project_site_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_project_site_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(64) NOT NULL,
  `employee_status` varchar(75) NOT NULL,
  `status_date` varchar(75) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_request`
--

CREATE TABLE IF NOT EXISTS `g_employee_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `settings_request_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `request_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `end_time` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '0 = Pending / 1 = Approve / -1 = Disapprove',
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_request_approvers`
--

CREATE TABLE IF NOT EXISTS `g_employee_request_approvers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Generic / Overtime / Leave / Rest Day',
  `request_type_id` int(11) NOT NULL,
  `position_employee_id` int(11) NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Employee Id / Position Id / Department Id',
  `level` int(11) NOT NULL COMMENT '0 = Override',
  `override_level` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pending / Approved / Disapproved',
  `remarks` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Approver''s Remarks',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_request_loan`
--

CREATE TABLE IF NOT EXISTS `g_employee_request_loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_applied` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `loan_title` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `loan_amount` double(11,2) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` varchar(20) CHARACTER SET latin1 NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_requirements`
--

CREATE TABLE IF NOT EXISTS `g_employee_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `requirements` text COLLATE utf8_unicode_ci NOT NULL,
  `is_complete` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `date_updated` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_restday`
--

CREATE TABLE IF NOT EXISTS `g_employee_restday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id-date` (`employee_id`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This is a restday schedule NOT the actual restday work' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_rest_day_request`
--

CREATE TABLE IF NOT EXISTS `g_employee_rest_day_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `date_applied` datetime NOT NULL,
  `date_start` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `date_end` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `rest_day_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_schedule_specific`
--

CREATE TABLE IF NOT EXISTS `g_employee_schedule_specific` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `date_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `location` text COLLATE utf8_unicode_ci,
  `schedule_name` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `employee_id-date_start` (`employee_id`,`date_start`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_skills`
--

CREATE TABLE IF NOT EXISTS `g_employee_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `skill` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `years_experience` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_status_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `employee_status_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `start_date` varchar(10) NOT NULL,
  `end_date` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_subdivision_history`
--

CREATE TABLE IF NOT EXISTS `g_employee_subdivision_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `company_structure_id` int(11) NOT NULL COMMENT 'branch,division, department, team',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Finance Department, Production Team',
  `type` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Department, Group, Team etc',
  `start_date` date NOT NULL,
  `end_date` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'if null means current',
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `company_structure_id` (`company_structure_id`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_supervisor`
--

CREATE TABLE IF NOT EXISTS `g_employee_supervisor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL COMMENT 'employee_id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_tags`
--

CREATE TABLE IF NOT EXISTS `g_employee_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `tags` text COLLATE utf8_unicode_ci NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_training`
--

CREATE TABLE IF NOT EXISTS `g_employee_training` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `provider` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cost` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `renewal_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_undertime_request`
--

CREATE TABLE IF NOT EXISTS `g_employee_undertime_request` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_applied` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_undertime` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `is_approved` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_user`
--

CREATE TABLE IF NOT EXISTS `g_employee_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL,
  `date_created` varchar(80) NOT NULL,
  `last_modified` varchar(80) NOT NULL,
  `is_archive` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_weekly_device_payslip`
--

CREATE TABLE IF NOT EXISTS `g_employee_weekly_device_payslip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `period_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `period_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `payout_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `basic_pay` double(15,2) NOT NULL,
  `gross_pay` double(15,2) NOT NULL,
  `total_earnings` decimal(10,2) DEFAULT NULL,
  `total_deductions` decimal(10,2) DEFAULT NULL,
  `net_pay` double(15,2) NOT NULL,
  `taxable` double(15,2) NOT NULL,
  `non_taxable` double(15,2) NOT NULL,
  `withheld_tax` float(15,2) NOT NULL,
  `month_13th` double(15,2) NOT NULL,
  `sss` double(15,2) NOT NULL,
  `sss_er` double(15,2) NOT NULL,
  `pagibig` double(15,2) NOT NULL,
  `pagibig_er` double(15,2) NOT NULL,
  `philhealth` double(15,2) NOT NULL,
  `philhealth_er` double(15,2) NOT NULL,
  `earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Initial earnings like overtime, late, basic pay',
  `other_earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'other earnings to be added manually',
  `deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `other_deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `labels` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `overtime` double NOT NULL,
  `number_of_declared_dependents` int(11) NOT NULL,
  `taxable_benefits` double(15,2) NOT NULL,
  `non_taxable_benefits` double(15,2) NOT NULL,
  `tardiness_amount` double(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `period_start-end` (`period_start`,`period_end`,`employee_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6194 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_weekly_payslip`
--

CREATE TABLE IF NOT EXISTS `g_employee_weekly_payslip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `period_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `period_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `payout_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `basic_pay` double(15,2) NOT NULL,
  `gross_pay` double(15,2) NOT NULL,
  `total_earnings` decimal(10,2) DEFAULT NULL,
  `total_deductions` decimal(10,2) DEFAULT NULL,
  `net_pay` double(15,2) NOT NULL,
  `taxable` double(15,2) NOT NULL,
  `non_taxable` double(15,2) NOT NULL,
  `withheld_tax` float(15,2) NOT NULL,
  `month_13th` double(15,2) NOT NULL,
  `sss` double(15,2) NOT NULL,
  `sss_er` double(15,2) NOT NULL,
  `pagibig` double(15,2) NOT NULL,
  `pagibig_er` double(15,2) NOT NULL,
  `philhealth` double(15,2) NOT NULL,
  `philhealth_er` double(15,2) NOT NULL,
  `earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Initial earnings like overtime, late, basic pay',
  `other_earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'other earnings to be added manually',
  `deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `other_deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `labels` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `overtime` double NOT NULL,
  `number_of_declared_dependents` int(11) NOT NULL,
  `taxable_benefits` double(15,2) NOT NULL,
  `non_taxable_benefits` double(15,2) NOT NULL,
  `tardiness_amount` double(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `period_start-end` (`period_start`,`period_end`,`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_employee_work_experience`
--

CREATE TABLE IF NOT EXISTS `g_employee_work_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `company` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `job_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_error_attendance`
--

CREATE TABLE IF NOT EXISTS `g_error_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_attendance` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `is_fixed` tinyint(1) NOT NULL,
  `error_type_id` tinyint(10) NOT NULL COMMENT 'ERROR_INVALID_EMPLOYEE = 1; ERROR_INVALID_TIME = 2; ERROR_INVALID_OT = 3; ERROR_INVALID_DATE = 4; ERROR_NO_OUT = 5; ERROR_NO_IN = 6;',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_error_leave`
--

CREATE TABLE IF NOT EXISTS `g_error_leave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `employee_code` int(11) NOT NULL,
  `employee_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `date_applied` varchar(20) CHARACTER SET latin1 NOT NULL,
  `date_start` varchar(15) CHARACTER SET latin1 NOT NULL,
  `date_end` varchar(15) CHARACTER SET latin1 NOT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  `is_fixed` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
  `error_type_id` int(11) NOT NULL COMMENT '1 = EMPLOYEE_DOES_NOT_EXISTS, 2 = INVALID_START_END_DATE',
  PRIMARY KEY (`id`),
  KEY `default_log` (`employee_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_error_overtime`
--

CREATE TABLE IF NOT EXISTS `g_error_overtime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `employee_code` int(11) NOT NULL,
  `employee_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `date_attendance` varchar(20) CHARACTER SET latin1 NOT NULL,
  `time_in` varchar(15) CHARACTER SET latin1 NOT NULL,
  `time_out` varchar(15) CHARACTER SET latin1 NOT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  `is_fixed` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
  `error_type_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `default_log` (`employee_code`),
  KEY `employee_id,date_attendance` (`employee_id`,`date_attendance`),
  KEY `date_attendance` (`date_attendance`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_error_payslip`
--

CREATE TABLE IF NOT EXISTS `g_error_payslip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `period_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `period_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `is_fixed` tinyint(1) NOT NULL,
  `error_type_id` int(11) NOT NULL COMMENT '1 = ERROR_NO_SALARY, 2 = ERROR_NO_ATTENDANCE',
  `date_logged` date NOT NULL,
  `time_logged` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=289 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_exam`
--

CREATE TABLE IF NOT EXISTS `g_exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `applicable_to_job` text COLLATE utf8_unicode_ci NOT NULL,
  `apply_to_all_jobs` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `passing_percentage` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `time_duration` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'days:hours:minutes',
  `created_by` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_exam_choices`
--

CREATE TABLE IF NOT EXISTS `g_exam_choices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_question_id` int(11) NOT NULL,
  `choices` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_exam_question`
--

CREATE TABLE IF NOT EXISTS `g_exam_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `answer` text COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `type` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_excluded_employee_deduction`
--

CREATE TABLE IF NOT EXISTS `g_excluded_employee_deduction` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) NOT NULL,
  `payroll_period_id` int(11) NOT NULL,
  `new_payroll_period_id` int(11) NOT NULL,
  `variable_name` varchar(90) NOT NULL,
  `amount` float NOT NULL,
  `action` varchar(50) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_fp_attendance_log`
--

CREATE TABLE IF NOT EXISTS `g_fp_attendance_log` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `employee_code` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `employee_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(128) CHARACTER SET latin1 NOT NULL,
  `sync` int(11) NOT NULL DEFAULT '1',
  `is_transferred` int(1) unsigned zerofill NOT NULL COMMENT '1 = transferred / 0 = new data',
  `employee_device_id` int(11) NOT NULL COMMENT 'for anviz device',
  `dtr_telco` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `code,date,type` (`employee_code`,`date`,`type`),
  KEY `date` (`date`),
  KEY `employee_code` (`employee_code`),
  KEY `code-date-time` (`employee_code`,`date`,`time`),
  KEY `date-time` (`date`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_fp_attendance_summary`
--

CREATE TABLE IF NOT EXISTS `g_fp_attendance_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'for biometrics',
  `actual_date_in` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'for biometrics',
  `actual_time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `actual_date_out` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `actual_time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `actual_total_hours_worked` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT 'compute by biometrics',
  `done` int(11) NOT NULL COMMENT 'for biometrics',
  `sync` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_fp_fingerprint`
--

CREATE TABLE IF NOT EXISTS `g_fp_fingerprint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `employee_code` varchar(64) CHARACTER SET latin1 NOT NULL COMMENT 'employee_code example 100203-023',
  `name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `template` longblob NOT NULL,
  `finger` varchar(64) CHARACTER SET latin1 NOT NULL,
  `integer_representation` int(11) NOT NULL,
  `sync` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_frequency`
--

CREATE TABLE IF NOT EXISTS `g_frequency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frequency_type` varchar(20) COLLATE utf16_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `g_frequency`
--

INSERT INTO `g_frequency` (`id`, `frequency_type`, `date_created`) VALUES
(1, 'bi-monthly', '2020-02-18 00:00:00'),
(2, 'weekly', '2020-02-18 00:00:00'),
(3, 'monthly', '2021-02-09 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `g_group_restday`
--

CREATE TABLE IF NOT EXISTS `g_group_restday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL COMMENT 'department / section / group id',
  `date` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_holiday`
--

CREATE TABLE IF NOT EXISTS `g_holiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `public_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `holiday_type` tinyint(1) NOT NULL COMMENT '1 = legal, 2 = special',
  `holiday_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `holiday_month` tinyint(12) NOT NULL,
  `holiday_day` tinyint(31) NOT NULL,
  `holiday_year` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `month-day-year` (`holiday_month`,`holiday_day`,`holiday_year`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_holiday_branch`
--

CREATE TABLE IF NOT EXISTS `g_holiday_branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `holiday_id` int(11) NOT NULL,
  `company_branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_incentive_leave_history`
--

CREATE TABLE IF NOT EXISTS `g_incentive_leave_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month_number` int(2) NOT NULL,
  `year` int(11) NOT NULL,
  `total_given` int(5) NOT NULL,
  `date_process` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_job`
--

CREATE TABLE IF NOT EXISTS `g_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `job_specification_id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_job_application_event`
--

CREATE TABLE IF NOT EXISTS `g_job_application_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `date_time_created` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'employee_id',
  `hiring_manager_id` int(11) DEFAULT NULL,
  `date_time_event` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'on march 6, 3:00pm',
  `event_type` int(11) NOT NULL COMMENT 'Application Submitted: 0, Interview: 1, Job Offered: 2, Offer Declined: 3, Rejected: 4, Hired: 5',
  `application_status_id` int(11) NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `applicant_id` (`applicant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_job_employment_status`
--

CREATE TABLE IF NOT EXISTS `g_job_employment_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `employment_status_id` int(11) NOT NULL,
  `employment_status` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_job_salary_rate`
--

CREATE TABLE IF NOT EXISTS `g_job_salary_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `job_level` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'senior programmer, beginner programmer etc',
  `minimum_salary` double DEFAULT NULL,
  `maximum_salary` double DEFAULT NULL,
  `step_salary` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_job_specification`
--

CREATE TABLE IF NOT EXISTS `g_job_specification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `duties` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_job_vacancy`
--

CREATE TABLE IF NOT EXISTS `g_job_vacancy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `job_description` text COLLATE utf8_unicode_ci NOT NULL,
  `hiring_manager_id` int(11) NOT NULL COMMENT 'employee_id',
  `job_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `hiring_manager_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `publication_date` date NOT NULL,
  `advertisement_end` date NOT NULL,
  `is_active` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_leave`
--

CREATE TABLE IF NOT EXISTS `g_leave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `default_credit` int(11) NOT NULL,
  `is_paid` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `gl_is_archive` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Dumping data for table `g_leave`
--

INSERT INTO `g_leave` (`id`, `company_structure_id`, `name`, `type`, `default_credit`, `is_paid`, `gl_is_archive`, `is_default`) VALUES
(1, 1, 'Sick Leave', '', 0, 'Yes', 'Yes', 'No'),
(2, 1, 'Vacation Leave', '', 0, 'Yes', 'Yes', 'No'),
(3, 1, 'Bereavement Leave', '', 0, 'Yes', 'Yes', 'No'),
(4, 1, 'Service Incentive Leave', '', 5, 'Yes', 'No', 'No'),
(5, 1, 'Emergency Leave', '', 0, 'Yes', 'Yes', 'No'),
(6, 1, 'Paternity Leave', '', 0, 'Yes', 'Yes', 'No'),
(23, 1, 'Vacation Leave', '', 0, 'Yes', 'No', 'No'),
(24, 1, 'Sick Leave', '', 0, 'No', 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `g_loan_deduction_type`
--

CREATE TABLE IF NOT EXISTS `g_loan_deduction_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `deduction_type` varchar(180) CHARACTER SET latin1 NOT NULL,
  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `g_loan_deduction_type`
--

INSERT INTO `g_loan_deduction_type` (`id`, `company_structure_id`, `deduction_type`, `is_archive`, `date_created`) VALUES
(1, 1, 'Bi-monthly', 'No', '2015-05-14 08:08:26'),
(2, 1, 'Monthly', 'No', '2015-05-14 08:08:26'),
(3, 1, 'Weekly', 'No', '2015-05-14 08:08:26'),
(4, 1, 'Daily', 'No', '2015-05-14 08:08:26'),
(5, 1, 'Quarterly', 'No', '2015-05-14 08:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `g_loan_type`
--

CREATE TABLE IF NOT EXISTS `g_loan_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `loan_type` varchar(180) CHARACTER SET latin1 NOT NULL,
  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `g_loan_type`
--

INSERT INTO `g_loan_type` (`id`, `company_structure_id`, `loan_type`, `is_archive`, `date_created`) VALUES
(1, 1, 'Salary Loan', 'No', '2015-05-14 08:08:26'),
(2, 1, 'Cash Advance', 'No', '2015-05-14 08:08:26'),
(3, 1, 'Pagibig Loan', 'No', '2015-05-14 08:08:26'),
(4, 1, 'SSS Loan', 'No', '2015-05-14 08:08:26'),
(5, 1, 'Others', 'No', '2015-05-14 08:08:26'),
(16, 1, 'SSS Salary Loan', 'No', '2020-07-23 10:23:34'),
(17, 1, 'Calamity Loan', 'No', '2021-02-19 03:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `g_monthly_cutoff_period`
--

CREATE TABLE IF NOT EXISTS `g_monthly_cutoff_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year_tag` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `payout_date` date NOT NULL,
  `cutoff_number` int(11) NOT NULL,
  `salary_cycle_id` int(11) NOT NULL,
  `is_lock` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `is_payroll_generated` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `g_monthly_cutoff_period`
--

INSERT INTO `g_monthly_cutoff_period` (`id`, `year_tag`, `period_start`, `period_end`, `payout_date`, `cutoff_number`, `salary_cycle_id`, `is_lock`, `is_payroll_generated`) VALUES
(2, '2023', '2022-12-28', '2023-01-27', '2023-01-27', 1, 2, 'No', 'No'),
(3, '2024', '2023-12-28', '2024-01-27', '2024-01-27', 1, 2, 'No', 'No'),
(4, '2023', '2023-11-28', '2023-12-27', '2023-12-27', 1, 2, 'No', 'No'),
(5, '2023', '2023-10-28', '2023-11-27', '2023-11-27', 1, 2, 'No', 'No'),
(6, '2023', '2023-09-28', '2023-10-27', '2023-10-27', 1, 2, 'No', 'No'),
(7, '2023', '2023-08-28', '2023-09-27', '2023-09-27', 1, 2, 'No', 'No'),
(8, '2023', '2023-07-28', '2023-08-27', '2023-08-27', 1, 2, 'No', 'No'),
(9, '2023', '2023-06-28', '2023-07-27', '2023-07-27', 1, 2, 'No', 'No'),
(10, '2023', '2023-05-28', '2023-06-27', '2023-06-27', 1, 2, 'No', 'No'),
(11, '2023', '2023-04-28', '2023-05-27', '2023-05-27', 1, 2, 'No', 'No'),
(12, '2023', '2023-03-28', '2023-04-27', '2023-04-27', 1, 2, 'No', 'No'),
(13, '2023', '2023-02-28', '2023-03-27', '2023-03-27', 1, 2, 'No', 'No'),
(14, '2023', '2023-01-28', '2023-02-27', '2023-02-27', 1, 2, 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `g_net_taxable_table`
--

CREATE TABLE IF NOT EXISTS `g_net_taxable_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `over` float NOT NULL,
  `not_over` float NOT NULL,
  `amount` float NOT NULL,
  `rate_percentage` float NOT NULL,
  `excess_over` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_notifications`
--

CREATE TABLE IF NOT EXISTS `g_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_type` varchar(150) NOT NULL,
  `description` varchar(180) NOT NULL,
  `status` varchar(20) NOT NULL,
  `item` int(11) NOT NULL,
  `date_modified` varchar(50) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `g_notifications`
--

INSERT INTO `g_notifications` (`id`, `event_type`, `description`, `status`, `item`, `date_modified`, `date_created`) VALUES
(2, 'No Salary Rate', 'Employee has no salary rate yet.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(3, 'End of Contract', 'Employee reached end of contract this month.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(4, 'No Department', 'Employee has no assigned department yet.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(5, 'No Job Title', 'Employee has no Job Title yet.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(6, 'No Employment Status', 'Employee has no employment status.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(7, 'No Employee Status', 'Employee has no employee status.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(8, 'Tardiness', 'Employee is late this cutoff.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(9, 'Incomplete DTR', 'Employee with incomplete DTR this cutoff.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(10, 'Employee with no schedule', 'Employee with no schedule.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(11, 'Employee with incorrect shift', 'Employee with incorrect shift.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(12, 'Employee with undertime', 'Employee with undertime this cutoff.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(13, 'No Bank Account', 'Employee has no bank account.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(14, 'Employee with absent', 'Employee with absent(s).', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(15, 'Update Attendance', 'New DTR records detected, update attendance needed', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(16, 'Multiple in/out records', 'Multiple DTR IN/OUT detected', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(17, 'Upcoming Birthday', 'Employee with upcoming birthday.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(18, 'Unprocessed Payroll', 'Employee with unprocessed payroll, please do check the employee status, schedule, dtr and timesheet', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37'),
(19, 'Birthday Today', 'Employee with birthday today.', 'New', 0, '2021-08-24 10:05:37', '2021-08-24 10:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `g_overtime_allowance`
--

CREATE TABLE IF NOT EXISTS `g_overtime_allowance` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) NOT NULL,
  `object_type` varchar(5) NOT NULL,
  `applied_day_type` text NOT NULL,
  `ot_allowance` float NOT NULL,
  `multiplier` int(11) NOT NULL,
  `max_ot_allowance` float NOT NULL,
  `date_start` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `description_day_type` text NOT NULL,
  `date_created` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_overtime_general_settings`
--

CREATE TABLE IF NOT EXISTS `g_overtime_general_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `is_enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `g_overtime_general_settings`
--

INSERT INTO `g_overtime_general_settings` (`id`, `description`, `is_enabled`) VALUES
(1, 'require overtime logs', 1),
(2, 'require overtime breaktime logs', 1);

-- --------------------------------------------------------

--
-- Table structure for table `g_pagibig_table`
--

CREATE TABLE IF NOT EXISTS `g_pagibig_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `salary_from` float NOT NULL,
  `salary_to` float NOT NULL,
  `multiplier_employee` float NOT NULL,
  `multiplier_employer` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `g_pagibig_table`
--

INSERT INTO `g_pagibig_table` (`id`, `company_structure_id`, `salary_from`, `salary_to`, `multiplier_employee`, `multiplier_employer`) VALUES
(1, 1, 0, 1499.99, 0.01, 0.02),
(2, 1, 1500, 999999, 0.02, 0.02);

-- --------------------------------------------------------

--
-- Table structure for table `g_payroll_variables`
--

CREATE TABLE IF NOT EXISTS `g_payroll_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number_of_days` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `g_payroll_variables`
--

INSERT INTO `g_payroll_variables` (`id`, `number_of_days`) VALUES
(1, 26.17);

-- --------------------------------------------------------

--
-- Table structure for table `g_payslip_deductions`
--

CREATE TABLE IF NOT EXISTS `g_payslip_deductions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deduction_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='DEPRECATED!' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_payslip_earnings`
--

CREATE TABLE IF NOT EXISTS `g_payslip_earnings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `earning_type` int(10) NOT NULL COMMENT '0=normal, 1=adjustment, 2=allowance, 3=bonus, 4=advance',
  `earning_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='DEPRECATED!' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_payslip_template`
--

CREATE TABLE IF NOT EXISTS `g_payslip_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(320) NOT NULL,
  `is_default` varchar(5) NOT NULL COMMENT 'Yes or No',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `g_payslip_template`
--

INSERT INTO `g_payslip_template` (`id`, `template_name`, `is_default`) VALUES
(1, 'Template 01', 'Yes'),
(2, 'Template 02', 'No'),
(3, 'Template 03', 'No'),
(4, 'Template 04', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `g_performance`
--

CREATE TABLE IF NOT EXISTS `g_performance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `title` varchar(124) COLLATE utf8_unicode_ci NOT NULL,
  `job_id` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `date_created` date NOT NULL,
  `created_by` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `is_archive` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_performance_indicator`
--

CREATE TABLE IF NOT EXISTS `g_performance_indicator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `performance_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(230) COLLATE utf8_unicode_ci NOT NULL,
  `rate_min` int(11) NOT NULL,
  `rate_max` int(11) NOT NULL,
  `rate_default` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_philhealth`
--

CREATE TABLE IF NOT EXISTS `g_philhealth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `salary_from` float(11,2) NOT NULL,
  `salary_to` float(11,2) NOT NULL,
  `multiplier_employee` float NOT NULL,
  `multiplier_employer` float NOT NULL,
  `is_fixed` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Yes or No',
  `effective_date` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `g_philhealth`
--

INSERT INTO `g_philhealth` (`id`, `company_structure_id`, `salary_from`, `salary_to`, `multiplier_employee`, `multiplier_employer`, `is_fixed`, `effective_date`) VALUES
(1, 1, 0.00, 10000.00, 4, 4, 'Yes', '2022-06-01'),
(2, 1, 10000.01, 79999.99, 4, 4, 'No', '2022-06-01'),
(3, 1, 80000.00, 999999.00, 4, 4, 'Yes', '2022-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `g_philhealth_history`
--

CREATE TABLE IF NOT EXISTS `g_philhealth_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `salary_from` float(11,2) NOT NULL,
  `salary_to` float(11,2) NOT NULL,
  `multiplier_employee` float NOT NULL,
  `multiplier_employer` float NOT NULL,
  `is_fixed` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Yes or No',
  `date_end` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `g_philhealth_history`
--

INSERT INTO `g_philhealth_history` (`id`, `company_structure_id`, `salary_from`, `salary_to`, `multiplier_employee`, `multiplier_employer`, `is_fixed`, `date_end`) VALUES
(7, 1, 0.00, 10000.00, 3, 3, 'Yes', '2022-06-01'),
(8, 1, 10000.01, 59999.99, 3, 3, 'No', '2022-06-01'),
(9, 1, 60000.00, 999999.00, 3, 3, 'Yes', '2022-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `g_philhealth_table_rate`
--

CREATE TABLE IF NOT EXISTS `g_philhealth_table_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `philhealth_id` int(11) NOT NULL,
  `monthly_salary_bracket` float NOT NULL,
  `from_salary` float NOT NULL,
  `to_salary` float NOT NULL,
  `salary_base` float NOT NULL,
  `total_monthly_contribution` float NOT NULL,
  `employee_share` float NOT NULL COMMENT 'Employee Share',
  `employer_share` float NOT NULL COMMENT 'Employer Share',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_project_sites`
--

CREATE TABLE IF NOT EXISTS `g_project_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `location` varchar(191) DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `zk_device_id` int(11) NOT NULL,
  `pay_period_id` int(11) NOT NULL,
  `start_date` varchar(191) NOT NULL,
  `end_date` varchar(191) DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_project_site_frequencies`
--

CREATE TABLE IF NOT EXISTS `g_project_site_frequencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_site_id` int(11) NOT NULL DEFAULT '0',
  `frequency_id` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_requests`
--

CREATE TABLE IF NOT EXISTS `g_requests` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `requestor_employee_id` bigint(20) DEFAULT NULL,
  `request_id` bigint(20) DEFAULT NULL,
  `request_type` varchar(10) DEFAULT NULL,
  `approver_employee_id` bigint(20) DEFAULT NULL,
  `approver_name` varchar(180) DEFAULT NULL,
  `status` varchar(40) DEFAULT NULL,
  `is_lock` varchar(10) DEFAULT NULL,
  `remarks` text,
  `action_date` varchar(180) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `requestor_employee_id` (`requestor_employee_id`,`request_id`,`request_type`,`approver_employee_id`,`status`),
  KEY `approver_name` (`approver_name`),
  KEY `request_type` (`request_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_approvers`
--

CREATE TABLE IF NOT EXISTS `g_request_approvers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `approvers_name` text,
  `requestors_name` text,
  `date_created` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_approvers_level`
--

CREATE TABLE IF NOT EXISTS `g_request_approvers_level` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `request_approvers_id` bigint(20) DEFAULT NULL,
  `employee_id` bigint(20) DEFAULT NULL,
  `employee_name` varchar(160) DEFAULT NULL,
  `level` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_name` (`employee_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_approvers_requestors`
--

CREATE TABLE IF NOT EXISTS `g_request_approvers_requestors` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `request_approvers_id` bigint(20) DEFAULT NULL,
  `employee_department_group_id` bigint(20) DEFAULT NULL,
  `employee_department_group` varchar(5) DEFAULT NULL,
  `description` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_approvers_id` (`request_approvers_id`),
  KEY `employee_department_group` (`employee_department_group`),
  KEY `description` (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_banks`
--

CREATE TABLE IF NOT EXISTS `g_request_banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(20) NOT NULL,
  `bank_name` varchar(200) NOT NULL,
  `account` varchar(200) NOT NULL,
  `account_type` varchar(200) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `request_employee_update_id` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_contact_details`
--

CREATE TABLE IF NOT EXISTS `g_request_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_employee_update_id` varchar(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(20) NOT NULL,
  `province` varchar(20) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(20) NOT NULL,
  `home_telephone` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `work_telephone` varchar(30) NOT NULL,
  `work_email` varchar(30) NOT NULL,
  `other_email` varchar(30) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_dependents`
--

CREATE TABLE IF NOT EXISTS `g_request_dependents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_employee_update_id` varchar(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `relationship` varchar(64) NOT NULL,
  `birthdate` date NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_education`
--

CREATE TABLE IF NOT EXISTS `g_request_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_employee_update_id` varchar(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `institute` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year` varchar(10) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(60) NOT NULL,
  `gpa_score` varchar(20) NOT NULL,
  `attainment` varchar(64) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_emergency_contact`
--

CREATE TABLE IF NOT EXISTS `g_request_emergency_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_employee_update_id` varchar(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `person` varchar(160) NOT NULL,
  `relationship` varchar(20) NOT NULL,
  `home_telephone` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `work_telephone` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_employee_contact_details`
--

CREATE TABLE IF NOT EXISTS `g_request_employee_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(99) NOT NULL,
  `address` text NOT NULL,
  `city` text NOT NULL,
  `province` text NOT NULL,
  `zip_code` text NOT NULL,
  `country` text NOT NULL,
  `home_telephone` text NOT NULL,
  `mobile` text NOT NULL,
  `work_telephone` text NOT NULL,
  `work_email` text NOT NULL,
  `other_email` text NOT NULL,
  `is_transfered` enum('no','yes','removed') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_employee_update`
--

CREATE TABLE IF NOT EXISTS `g_request_employee_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` text NOT NULL,
  `request_id` text NOT NULL,
  `request_type` text NOT NULL,
  `request_status` enum('approved','pending','disapproved') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_license`
--

CREATE TABLE IF NOT EXISTS `g_request_license` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_employee_update_id` varchar(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `license_type` varchar(255) NOT NULL,
  `license_number` varchar(128) NOT NULL,
  `issued_date` varchar(128) NOT NULL,
  `expiry_date` varchar(128) NOT NULL,
  `notes` varchar(164) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_personal_details`
--

CREATE TABLE IF NOT EXISTS `g_request_personal_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(128) NOT NULL,
  `photo` varchar(50) NOT NULL,
  `request_employee_update_id` varchar(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `extension_name` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `number_dependent` int(11) NOT NULL,
  `salutation` varchar(20) NOT NULL,
  `sss_number` varchar(30) NOT NULL,
  `tin_number` varchar(30) NOT NULL,
  `pagibig_number` varchar(30) NOT NULL,
  `philhealth_number` varchar(30) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_profile_image`
--

CREATE TABLE IF NOT EXISTS `g_request_profile_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(99) NOT NULL,
  `photo` text NOT NULL,
  `is_transfered` enum('yes','no','removed') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_request_work_experience`
--

CREATE TABLE IF NOT EXISTS `g_request_work_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_employee_update_id` varchar(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `company` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `comment` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_roles`
--

CREATE TABLE IF NOT EXISTS `g_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(180) NOT NULL,
  `is_archive` varchar(10) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  `last_modified` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `g_roles`
--

INSERT INTO `g_roles` (`id`, `name`, `description`, `is_archive`, `date_created`, `last_modified`) VALUES
(1, 'Super Admin', 'Default role', 'No', '2015-05-14 00:00:00', '2020-07-17 05:40:24'),
(2, 'IT', '', 'No', '2020-07-24 05:04:22', '2020-07-24 05:08:03'),
(3, 'HR OFFICER', '', 'No', '2020-07-24 09:29:16', '');

-- --------------------------------------------------------

--
-- Table structure for table `g_role_actions`
--

CREATE TABLE IF NOT EXISTS `g_role_actions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `parent_module` varchar(30) NOT NULL,
  `module` varchar(80) NOT NULL,
  `action` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_salary_cycle`
--

CREATE TABLE IF NOT EXISTS `g_salary_cycle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cycle_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cut_offs` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'serialized from array',
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='deprecated!!' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_schedule`
--

CREATE TABLE IF NOT EXISTS `g_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `public_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `schedule_group_id` int(11) NOT NULL,
  `schedule_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `grace_period` int(11) NOT NULL,
  `working_days` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'mon, tue, wed, thu, fri, sat, sun',
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `is_default` int(1) NOT NULL COMMENT '1 = yes, 0 = no',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=615 ;

--
-- Dumping data for table `g_schedule`
--

INSERT INTO `g_schedule` (`id`, `public_id`, `schedule_group_id`, `schedule_name`, `grace_period`, `working_days`, `time_in`, `time_out`, `is_default`) VALUES
(614, '5f111f455da9f', 1, 'default', 0, 'mon,tue,wed,thu,fri', '08:00:00', '17:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `g_schedule_group`
--

CREATE TABLE IF NOT EXISTS `g_schedule_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `public_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `schedule_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `grace_period` int(11) NOT NULL,
  `effectivity_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `end_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` int(1) NOT NULL COMMENT '0 = no, 1 = yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `g_schedule_group`
--

INSERT INTO `g_schedule_group` (`id`, `public_id`, `schedule_name`, `grace_period`, `effectivity_date`, `end_date`, `is_default`) VALUES
(1, '55543bdb478c7', 'default', 0, '1970-01-01', '1970-01-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `g_schedule_request`
--

CREATE TABLE IF NOT EXISTS `g_schedule_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `effectivity_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `end_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `employee_ids` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `request_by` int(11) NOT NULL,
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_schedule_request_details`
--

CREATE TABLE IF NOT EXISTS `g_schedule_request_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_request_id` int(11) NOT NULL,
  `schedule_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `grace_period` int(11) NOT NULL,
  `working_days` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'mon, tue, wed, thu, fri, sat, sun',
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_application_status`
--

CREATE TABLE IF NOT EXISTS `g_settings_application_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '	Application Submitted 	1st Interview 	2nd Interview 	Offer Job 	no response  	Declined offer 	Failed_exam 	Backout 	Reject 	Hired',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_client_config`
--

CREATE TABLE IF NOT EXISTS `g_settings_client_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_expired` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_company_benefits`
--

CREATE TABLE IF NOT EXISTS `g_settings_company_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `benefit_code` varchar(100) CHARACTER SET latin1 NOT NULL,
  `benefit_name` varchar(180) CHARACTER SET latin1 NOT NULL,
  `benefit_description` text CHARACTER SET latin1 NOT NULL,
  `benefit_type` varchar(30) CHARACTER SET latin1 NOT NULL,
  `benefit_amount` double NOT NULL,
  `is_archived` varchar(10) CHARACTER SET latin1 NOT NULL,
  `is_taxable` varchar(10) CHARACTER SET latin1 NOT NULL,
  `date_created` varchar(150) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_deduction_breakdown`
--

CREATE TABLE IF NOT EXISTS `g_settings_deduction_breakdown` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET latin1 NOT NULL,
  `breakdown` varchar(50) CHARACTER SET latin1 NOT NULL,
  `is_active` varchar(10) CHARACTER SET latin1 NOT NULL,
  `is_taxable` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
  `salary_credit` int(2) NOT NULL DEFAULT '0' COMMENT '0 = basic pay / 1 = gross pay',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `g_settings_deduction_breakdown`
--

INSERT INTO `g_settings_deduction_breakdown` (`id`, `name`, `breakdown`, `is_active`, `is_taxable`, `salary_credit`) VALUES
(1, 'SSS', '100:0', 'Yes', 'No', 0),
(2, 'HDMF', '0:100', 'Yes', 'No', 0),
(3, 'Phil Health', '100:0', 'Yes', 'No', 0),
(4, 'TAX-BIR', '100:100', 'Yes', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_default_leave`
--

CREATE TABLE IF NOT EXISTS `g_settings_default_leave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `number_of_days_default` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_dependent_relationship`
--

CREATE TABLE IF NOT EXISTS `g_settings_dependent_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `relationship` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'child, wife etc',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `g_settings_dependent_relationship`
--

INSERT INTO `g_settings_dependent_relationship` (`id`, `company_structure_id`, `relationship`) VALUES
(1, 1, 'Husband'),
(2, 1, 'Wife'),
(3, 1, 'Cousin'),
(4, 1, 'Friend');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_employee_benefits`
--

CREATE TABLE IF NOT EXISTS `g_settings_employee_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET latin1 NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `description` varchar(200) CHARACTER SET latin1 NOT NULL,
  `amount` double NOT NULL,
  `is_taxable` varchar(5) CHARACTER SET latin1 NOT NULL,
  `is_archive` varchar(5) CHARACTER SET latin1 NOT NULL,
  `date_created` varchar(80) CHARACTER SET latin1 NOT NULL,
  `date_last_modified` varchar(80) CHARACTER SET latin1 NOT NULL,
  `cutoff` int(1) DEFAULT NULL,
  `multiplied_by` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_employee_benefits_non_monetary`
--

CREATE TABLE IF NOT EXISTS `g_settings_employee_benefits_non_monetary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `amount` int(11) NOT NULL,
  `is_archive` varchar(5) NOT NULL,
  `date_created` varchar(25) NOT NULL,
  `date_last_modified` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_employee_field`
--

CREATE TABLE IF NOT EXISTS `g_settings_employee_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `screen` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `default` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_employee_status`
--

CREATE TABLE IF NOT EXISTS `g_settings_employee_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `name` varchar(200) CHARACTER SET latin1 NOT NULL,
  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
  `date_created` varchar(180) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `g_settings_employee_status`
--

INSERT INTO `g_settings_employee_status` (`id`, `company_structure_id`, `name`, `is_archive`, `date_created`) VALUES
(1, 1, 'ACTIVE', 'No', '2015-05-14'),
(2, 1, 'RESIGNED', 'No', '2015-05-14'),
(3, 1, 'TERMINATED', 'No', '2015-05-14'),
(4, 1, 'END OF CONTRACT', 'No', '2015-05-14'),
(5, 1, 'INACTIVE', 'No', '2016-03-21 04:15:19'),
(6, 1, 'AWOL', 'No', '2021-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_employment_status`
--

CREATE TABLE IF NOT EXISTS `g_settings_employment_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `code` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Full Time Permanent, Full Time Contract, Full Time Internship',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `g_settings_employment_status`
--

INSERT INTO `g_settings_employment_status` (`id`, `company_structure_id`, `code`, `status`) VALUES
(1, 1, 'FT', 'Full Time'),
(2, 1, 'PT', 'Part Time'),
(3, 1, 'REG', 'Regular'),
(4, 1, 'PROB', 'Probationary'),
(5, 1, '', 'Contractual'),
(7, 1, '', 'Probitionary');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_fixed_contributions`
--

CREATE TABLE IF NOT EXISTS `g_settings_fixed_contributions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contribution` varchar(225) NOT NULL,
  `is_enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `g_settings_fixed_contributions`
--

INSERT INTO `g_settings_fixed_contributions` (`id`, `contribution`, `is_enabled`) VALUES
(1, 'pagibig', 0);

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_gp_exempted_employees`
--

CREATE TABLE IF NOT EXISTS `g_settings_gp_exempted_employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_grace_period`
--

CREATE TABLE IF NOT EXISTS `g_settings_grace_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `number_minute_default` double NOT NULL,
  `is_archive` int(11) NOT NULL DEFAULT '0' COMMENT '0=No, 1=yes',
  `is_default` int(11) NOT NULL DEFAULT '0' COMMENT '0=No, 1=yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `g_settings_grace_period`
--

INSERT INTO `g_settings_grace_period` (`id`, `company_structure_id`, `title`, `description`, `number_minute_default`, `is_archive`, `is_default`) VALUES
(1, 1, 'Default', 'Default', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_holiday`
--

CREATE TABLE IF NOT EXISTS `g_settings_holiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `holiday_type` tinyint(1) NOT NULL COMMENT '1 = legal, 2 = special',
  `holiday_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `holiday_month` tinyint(12) NOT NULL,
  `holiday_day` tinyint(31) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=45 ;

--
-- Dumping data for table `g_settings_holiday`
--

INSERT INTO `g_settings_holiday` (`id`, `holiday_type`, `holiday_title`, `holiday_month`, `holiday_day`) VALUES
(29, 1, 'Labor Day', 5, 1),
(25, 1, 'New Year''s Day', 1, 1),
(28, 1, 'Good Friday', 4, 18),
(27, 1, 'Maundry Thursday', 4, 17),
(26, 1, 'Day of Valour', 4, 9),
(30, 1, 'Independence Day', 6, 12),
(31, 1, 'Eid''l Fitr', 7, 28),
(32, 1, 'National Heroes'' Day', 8, 25),
(33, 1, 'Eidul Adha', 10, 5),
(34, 1, 'Bonifacio Day', 11, 30),
(35, 1, 'Christmas Day', 12, 25),
(36, 1, 'Rizal Day', 12, 30),
(37, 2, 'Chinese New Year', 1, 31),
(38, 2, 'Black Saturday', 4, 19),
(39, 2, 'Ninoy Aquino Day', 8, 21),
(40, 2, 'All Saints'' Day', 11, 1),
(41, 2, 'All Souls'' Day', 11, 2),
(42, 2, 'Christmas Eve', 12, 24),
(43, 2, 'Last Day of the Year', 12, 31),
(44, 2, 'EDSA Revolution Anniversary', 2, 25);

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_language`
--

CREATE TABLE IF NOT EXISTS `g_settings_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_leave_credit`
--

CREATE TABLE IF NOT EXISTS `g_settings_leave_credit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employment_years` varchar(5) NOT NULL,
  `default_credit` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL COMMENT 'from ''Leave Type'' table',
  `employment_status_id` int(11) NOT NULL COMMENT 'from ''Employment Status'' table',
  `is_archived` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_leave_general`
--

CREATE TABLE IF NOT EXISTS `g_settings_leave_general` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `convert_leave_criteria` int(2) NOT NULL,
  `leave_id` int(2) NOT NULL COMMENT 'from ''g_leave'' table',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `g_settings_leave_general`
--

INSERT INTO `g_settings_leave_general` (`id`, `convert_leave_criteria`, `leave_id`) VALUES
(1, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_license`
--

CREATE TABLE IF NOT EXISTS `g_settings_license` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `license_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'engineer, doctor',
  `description` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `g_settings_license`
--

INSERT INTO `g_settings_license` (`id`, `company_structure_id`, `license_type`, `description`) VALUES
(1, 1, 'Driver''s License', 'Driver''s License'),
(2, 1, 'Pharmacist PRC License', 'Pharmacist PRC License'),
(3, 1, 'Doctor''s License', 'Doctor''s License');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_location`
--

CREATE TABLE IF NOT EXISTS `g_settings_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'PH',
  `location` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Philippine',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=115 ;

--
-- Dumping data for table `g_settings_location`
--

INSERT INTO `g_settings_location` (`id`, `company_structure_id`, `code`, `location`) VALUES
(1, 1, 'PH', 'Philippines'),
(2, 1, 'AU', 'Australia'),
(3, 1, 'AF', 'Afghanistan'),
(4, 1, 'AL', 'Albania'),
(5, 1, 'DZ', 'Algeria'),
(6, 1, 'AS', 'American Samoa'),
(7, 1, 'AD', 'Andorra'),
(8, 1, 'AO', 'Angola'),
(9, 1, 'AI', 'Anguilla'),
(10, 1, 'AQ', 'Antarctica'),
(11, 1, 'AG', 'Antigua and Barbuda'),
(12, 1, 'AR', 'Argentina'),
(13, 1, 'AM', 'Armenia'),
(14, 1, 'AW', 'Aruba'),
(15, 1, 'AT', 'Austria'),
(16, 1, 'AZ', 'Azerbaijan'),
(17, 1, 'BS', 'Bahamas'),
(18, 1, 'BH', 'Bahrain'),
(19, 1, 'BD', 'Bangladesh'),
(20, 1, 'BB', 'Barbados'),
(21, 1, 'BY', 'Belarus'),
(22, 1, 'BE', 'Belgium'),
(23, 1, 'BZ', 'Belize'),
(24, 1, 'BJ', 'Benin'),
(25, 1, 'BM', 'Bermuda'),
(26, 1, 'BT', 'Bhutan'),
(27, 1, 'BO', 'Bolivia'),
(28, 1, 'BA', 'Bosnia and Herzegovina'),
(29, 1, 'BW', 'Botswana'),
(30, 1, 'BR', 'Brazil'),
(31, 1, 'IO', 'British Indian Ocean Territory'),
(32, 1, 'VG', 'British Virgin Islands'),
(33, 1, 'BN', 'Brunei'),
(34, 1, 'BG', 'Bulgaria'),
(35, 1, 'BF', 'Burkina Faso'),
(36, 1, 'MM', 'Burma (Myanmar)'),
(37, 1, 'BI', 'Burundi'),
(38, 1, 'KH', 'Cambodia'),
(39, 1, 'CM', 'Cameroon'),
(40, 1, 'CA', 'Canada'),
(41, 1, 'CV', 'Cape Verde'),
(42, 1, 'KY', 'Cayman Islands'),
(43, 1, 'CF', 'Central African Republic'),
(44, 1, 'TD', 'Chad'),
(45, 1, 'CL', 'Chile'),
(46, 1, 'CN', 'China'),
(47, 1, 'CX', 'Christmas Island'),
(48, 1, 'CC', 'Cocos (Keeling) Islands'),
(49, 1, 'CO', 'Colombia'),
(50, 1, 'KM', 'Comoros'),
(51, 1, 'CK', 'Cook Islands'),
(52, 1, 'CR', 'Costa Rica'),
(53, 1, 'HR', 'Croatia'),
(54, 1, 'CU', 'Cuba'),
(55, 1, 'CY', 'Cyprus'),
(56, 1, 'CZ', 'Czech Republic'),
(57, 1, 'CD', 'Democratic Republic of the Con'),
(58, 1, 'DK', 'Denmark'),
(59, 1, 'DJ', 'Djibouti'),
(60, 1, 'DM', 'Dominica'),
(61, 1, 'DO', 'Dominican Republic'),
(62, 1, 'EC', 'Ecuador'),
(63, 1, 'EG', 'Egypt'),
(64, 1, 'SV', 'El Salvador'),
(65, 1, 'GQ', 'Equatorial Guinea'),
(66, 1, 'ER', 'Eritrea'),
(67, 1, 'EE', 'Estonia'),
(68, 1, 'ET', 'Ethiopia'),
(69, 1, 'FK', 'Falkland Islands'),
(70, 1, 'FO', 'Faroe Islands'),
(71, 1, 'FJ', 'Fiji'),
(72, 1, 'FI', 'Finland'),
(73, 1, 'FR', 'France'),
(74, 1, 'PF', 'French Polynesia'),
(75, 1, 'GA', 'Gabon'),
(76, 1, 'GM', 'Gambia'),
(77, 1, 'GE', 'Georgia'),
(78, 1, 'DE', 'Germany'),
(79, 1, 'GH', 'Ghana'),
(80, 1, 'GI', 'Gibraltar'),
(81, 1, 'GR', 'Greece'),
(82, 1, 'GL', 'Greenland'),
(83, 1, 'GD', 'Grenada'),
(84, 1, 'GU', 'Guam'),
(85, 1, 'GT', 'Guatemala'),
(86, 1, 'GN', 'Guinea'),
(87, 1, 'GW', 'Guinea-Bissau'),
(88, 1, 'GY', 'Guyana'),
(89, 1, 'HT', 'Haiti'),
(90, 1, 'VA', 'Holy See (Vatican City)'),
(91, 1, 'HN', 'Honduras'),
(92, 1, 'HK', 'Hong Kong'),
(93, 1, 'HU', 'Hungary'),
(94, 1, 'IS', 'Iceland'),
(95, 1, 'IN', 'India'),
(96, 1, 'ID', 'Indonesia'),
(97, 1, 'IR', 'Iran'),
(98, 1, 'IQ', 'Iraq'),
(99, 1, 'IE', 'Ireland'),
(100, 1, 'IM', 'Isle of Man'),
(101, 1, 'IL', 'Israel'),
(102, 1, 'IT', 'Italy'),
(103, 1, 'CI', 'Ivory Coast'),
(104, 1, 'JM', 'Jamaica'),
(105, 1, 'JP', 'Japan'),
(106, 1, 'JE', 'Jersey'),
(107, 1, 'JO', 'Jordan'),
(108, 1, 'KZ', 'Kazakhstan'),
(109, 1, 'KE', 'Kenya'),
(110, 1, 'KI', 'Kiribati'),
(111, 1, 'KW', 'Kuwait'),
(112, 1, 'KG', 'Kyrgyzstan'),
(113, 1, 'LA', 'Laos'),
(114, 1, 'LV', 'Latvia');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_membership`
--

CREATE TABLE IF NOT EXISTS `g_settings_membership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `membership_type_id` int(11) NOT NULL,
  `membership` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'house loan etc.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_membership_type`
--

CREATE TABLE IF NOT EXISTS `g_settings_membership_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pagibig, sss etc',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_memo`
--

CREATE TABLE IF NOT EXISTS `g_settings_memo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `content` text CHARACTER SET latin1 NOT NULL,
  `created_by` varchar(255) CHARACTER SET latin1 NOT NULL,
  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
  `date_created` varchar(110) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `g_settings_memo`
--

INSERT INTO `g_settings_memo` (`id`, `title`, `content`, `created_by`, `is_archive`, `date_created`) VALUES
(1, 'Termination', 'Sample content for Termination', 'HR', 'No', '2015-05-14'),
(2, 'Tardiness', 'Sample content for Tardiness', 'HR', 'No', '2015-05-14');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_monthly_deduction_breakdown`
--

CREATE TABLE IF NOT EXISTS `g_settings_monthly_deduction_breakdown` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET latin1 NOT NULL,
  `breakdown` varchar(50) CHARACTER SET latin1 NOT NULL,
  `is_active` varchar(10) CHARACTER SET latin1 NOT NULL,
  `is_taxable` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
  `salary_credit` int(2) NOT NULL DEFAULT '0' COMMENT '0 = basic pay / 1 = gross pay',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `g_settings_monthly_deduction_breakdown`
--

INSERT INTO `g_settings_monthly_deduction_breakdown` (`id`, `name`, `breakdown`, `is_active`, `is_taxable`, `salary_credit`) VALUES
(1, 'SSS', '100:0', 'Yes', 'No', 0),
(2, 'HDMF', '100:0', 'Yes', 'No', 0),
(3, 'Phil Health', '100:0', 'Yes', 'No', 0),
(4, 'TAX-BIR', '100:100', 'Yes', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_pay_period`
--

CREATE TABLE IF NOT EXISTS `g_settings_pay_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `pay_period_code` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `pay_period_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cut_off` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `payout_day` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ex: 15,end',
  `is_default` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `g_settings_pay_period`
--

INSERT INTO `g_settings_pay_period` (`id`, `company_structure_id`, `pay_period_code`, `pay_period_name`, `cut_off`, `payout_day`, `is_default`) VALUES
(1, 1, 'BMO', 'Bi-Monthly', '28-12,13-27', '15,30', 1),
(2, 1, 'WEEKLY', 'Weekly', 'Wednesday - Tuesday', 'Saturday', 0),
(3, 1, 'MONTHLY', 'Monthly', '28-27', '27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_policy`
--

CREATE TABLE IF NOT EXISTS `g_settings_policy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy` varchar(255) CHARACTER SET latin1 NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 NOT NULL,
  `is_active` varchar(3) CHARACTER SET latin1 NOT NULL COMMENT '''Yes'' or ''No''',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `g_settings_policy`
--

INSERT INTO `g_settings_policy` (`id`, `policy`, `description`, `is_active`) VALUES
(1, 'File OT when late', 'File OT when late', 'Yes'),
(2, 'File LEAVE', 'File LEAVE', 'Yes'),
(3, 'File OT when later cccc', 'Settings for filing of OT when late cccc ccc', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_request`
--

CREATE TABLE IF NOT EXISTS `g_settings_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `request_type` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `applied_to_departments` varchar(240) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Array of department id',
  `applied_to_positions` varchar(240) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Array of position id',
  `applied_to_employees` varchar(240) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Array of employee id',
  `applied_to_description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Text value of joined applied position and employee id',
  `is_active` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '1 = Active / 0 = InActive',
  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '1 = Archive / 0 = Is not Archive',
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_request_approvers`
--

CREATE TABLE IF NOT EXISTS `g_settings_request_approvers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_request_id` int(11) NOT NULL,
  `position_employee_id` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Employee Id / Position Id ',
  `level` int(11) NOT NULL,
  `override_level` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_requirements`
--

CREATE TABLE IF NOT EXISTS `g_settings_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `title` varchar(180) NOT NULL,
  `is_archive` varchar(10) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `g_settings_requirements`
--

INSERT INTO `g_settings_requirements` (`id`, `company_structure_id`, `title`, `is_archive`, `date_created`) VALUES
(1, 1, 'SSS', 'No', '2015-05-14 00:00:00'),
(2, 1, 'Pagibig', 'No', '2015-05-14 00:00:00'),
(3, 1, '2x2 Picture', 'No', '2015-05-14 00:00:00'),
(4, 1, 'NBI Clearance', 'No', '2015-05-14 00:00:00'),
(5, 1, 'Police Clearance', 'No', '2015-05-14 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_salutation`
--

CREATE TABLE IF NOT EXISTS `g_settings_salutation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `salutation` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(124) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `g_settings_salutation`
--

INSERT INTO `g_settings_salutation` (`id`, `company_structure_id`, `salutation`, `description`) VALUES
(1, 1, 'Mr', ''),
(2, 1, 'Mrs', ''),
(3, 1, 'Ms', ''),
(4, 1, 'Dr', '');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_skills`
--

CREATE TABLE IF NOT EXISTS `g_settings_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `skill` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'swimming, cooking, driving',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `g_settings_skills`
--

INSERT INTO `g_settings_skills` (`id`, `company_structure_id`, `skill`) VALUES
(1, 1, 'Analytical/Research'),
(2, 1, 'Computer/Technical Literacy'),
(3, 1, 'Flexibility/Adaptability/Managing Multiple Priorities'),
(4, 1, 'Leadership/Management'),
(5, 1, 'Planning/Organizing'),
(6, 1, 'Teamwork'),
(7, 1, 'Self-Motivated/Ability to Work With Little or No Supervision');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_subdivision_type`
--

CREATE TABLE IF NOT EXISTS `g_settings_subdivision_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'branch,division, department, team',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `g_settings_subdivision_type`
--

INSERT INTO `g_settings_subdivision_type` (`id`, `company_structure_id`, `type`) VALUES
(1, 1, 'Team'),
(2, 1, 'Group');

-- --------------------------------------------------------

--
-- Table structure for table `g_settings_weekly_deduction_breakdown`
--

CREATE TABLE IF NOT EXISTS `g_settings_weekly_deduction_breakdown` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET latin1 NOT NULL,
  `breakdown` varchar(50) CHARACTER SET latin1 NOT NULL,
  `is_active` varchar(10) CHARACTER SET latin1 NOT NULL,
  `is_taxable` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
  `salary_credit` int(2) NOT NULL DEFAULT '0' COMMENT '0 = basic pay / 1 = gross pay',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `g_settings_weekly_deduction_breakdown`
--

INSERT INTO `g_settings_weekly_deduction_breakdown` (`id`, `name`, `breakdown`, `is_active`, `is_taxable`, `salary_credit`) VALUES
(1, 'SSS', '25:25:25:25', 'Yes', 'No', 0),
(2, 'HDMF', '25:25:25:25', 'Yes', 'No', 0),
(3, 'Phil Health', '25:25:25:25', 'Yes', 'No', 0),
(4, 'TAX-BIR', '100:100:0:0', 'Yes', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `g_shr_audit_trail`
--

CREATE TABLE IF NOT EXISTS `g_shr_audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL,
  `module` varchar(50) NOT NULL,
  `activity_action` varchar(50) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `audited_action` varchar(150) NOT NULL,
  `action_from` varchar(50) NOT NULL,
  `action_to` varchar(50) NOT NULL,
  `event_status` varchar(20) NOT NULL,
  `position` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `audit_date` varchar(20) NOT NULL,
  `audit_time` varchar(10) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=169 ;

--
-- Dumping data for table `g_shr_audit_trail`
--

INSERT INTO `g_shr_audit_trail` (`id`, `username`, `role`, `module`, `activity_action`, `activity_type`, `audited_action`, `action_from`, `action_to`, `event_status`, `position`, `department`, `audit_date`, `audit_time`, `ip_address`) VALUES
(1, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '09:39:am', '::1'),
(2, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '09:39:am', '::1'),
(3, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-B', '2023-01-28', '2023-02-12', 'SUCCESS', '', '', '2023-02-21', '09:40:am', '::1'),
(4, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '09:40:am', '::1'),
(5, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '09:40:am', '::1'),
(6, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-B', '2023-02-28', '2023-03-12', 'SUCCESS', '', '', '2023-02-21', '09:40:am', '::1'),
(7, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'March-A', '2023-03-13', '2023-03-27', 'SUCCESS', '', '', '2023-02-21', '09:40:am', '::1'),
(8, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'March-B', '2023-03-28', '2023-04-12', 'SUCCESS', '', '', '2023-02-21', '09:40:am', '::1'),
(9, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'April-A', '2023-04-13', '2023-04-27', 'SUCCESS', '', '', '2023-02-21', '09:40:am', '::1'),
(10, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'April-B', '2023-04-28', '2023-05-12', 'SUCCESS', '', '', '2023-02-21', '09:40:am', '::1'),
(11, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-A', '2023-12-13', '2023-12-27', 'SUCCESS', '', '', '2023-02-21', '09:40:am', '::1'),
(12, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-A', '2023-12-13', '2023-12-27', 'SUCCESS', '', '', '2023-02-21', '09:47:am', '::1'),
(13, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-A', '', '', 'SUCCESS', '', '', '2023-02-21', '09:47:am', '::1'),
(14, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '09:47:am', '::1'),
(15, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-21', '09:47:am', '::1'),
(16, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '09:47:am', '::1'),
(17, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '09:47:am', '::1'),
(18, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-21', '09:47:am', '::1'),
(19, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '09:47:am', '::1'),
(20, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '09:49:am', '::1'),
(21, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-B', '2023-01-28', '2023-02-12', 'SUCCESS', '', '', '2023-02-21', '09:54:am', '::1'),
(22, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '09:54:am', '::1'),
(23, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '09:57:am', '::1'),
(24, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:09:am', '::1'),
(25, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:10:am', '::1'),
(26, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:10:am', '::1'),
(27, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:11:am', '::1'),
(28, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:13:am', '::1'),
(29, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:13:am', '::1'),
(30, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:14:am', '::1'),
(31, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'November-A', '2022-11-13', '2022-11-27', 'SUCCESS', '', '', '2023-02-21', '10:14:am', '::1'),
(32, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'November-A', '2022-11-13', '2022-11-27', 'SUCCESS', '', '', '2023-02-21', '10:14:am', '::1'),
(33, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'November-B', '2022-11-28', '2022-12-12', 'SUCCESS', '', '', '2023-02-21', '10:14:am', '::1'),
(34, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'November-B', '2022-11-28', '2022-12-12', 'SUCCESS', '', '', '2023-02-21', '10:14:am', '::1'),
(35, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:14:am', '::1'),
(36, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:14:am', '::1'),
(37, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '10:15:am', '::1'),
(38, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '10:15:am', '::1'),
(39, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:15:am', '::1'),
(40, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'November-B', '2023-11-28', '2023-12-12', 'SUCCESS', '', '', '2023-02-21', '10:15:am', '::1'),
(41, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:15:am', '::1'),
(42, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-B', '2023-01-28', '2023-02-12', 'SUCCESS', '', '', '2023-02-21', '10:17:am', '::1'),
(43, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '10:17:am', '::1'),
(44, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-B', '2023-02-28', '2023-03-12', 'SUCCESS', '', '', '2023-02-21', '10:17:am', '::1'),
(45, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:23:am', '::1'),
(46, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(47, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(48, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-B', '2023-01-28', '2023-02-12', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(49, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(50, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-B', '2023-02-28', '2023-03-12', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(51, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(52, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(53, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-B', '2023-01-28', '2023-02-12', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(54, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(55, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-21', '10:24:am', '::1'),
(56, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:25:am', '::1'),
(57, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-B', '2023-01-28', '2023-02-12', 'SUCCESS', '', '', '2023-02-21', '10:25:am', '::1'),
(58, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '10:25:am', '::1'),
(59, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-B', '2023-02-28', '2023-03-12', 'SUCCESS', '', '', '2023-02-21', '10:25:am', '::1'),
(60, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-A', '2023-12-13', '2023-12-27', 'SUCCESS', '', '', '2023-02-21', '10:25:am', '::1'),
(61, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-21', '10:25:am', '::1'),
(62, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:26:am', '::1'),
(63, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-B', '2023-01-28', '2023-02-12', 'SUCCESS', '', '', '2023-02-21', '10:26:am', '::1'),
(64, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '10:26:am', '::1'),
(65, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-B', '2023-02-28', '2023-03-12', 'SUCCESS', '', '', '2023-02-21', '10:26:am', '::1'),
(66, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'March-A', '2023-03-13', '2023-03-27', 'SUCCESS', '', '', '2023-02-21', '10:26:am', '::1'),
(67, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-B', '2023-02-28', '2023-03-12', 'SUCCESS', '', '', '2023-02-21', '10:26:am', '::1'),
(68, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'March-A', '2023-03-13', '2023-03-27', 'SUCCESS', '', '', '2023-02-21', '10:26:am', '::1'),
(69, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'March-A', '2023-03-13', '2023-03-27', 'SUCCESS', '', '', '2023-02-21', '10:26:am', '::1'),
(70, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-21', '10:26:am', '::1'),
(71, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-21', '10:28:am', '::1'),
(72, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '10:30:am', '::1'),
(73, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-21', '10:30:am', '::1'),
(74, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'November-B', '2023-11-28', '2023-12-12', 'SUCCESS', '', '', '2023-02-21', '10:30:am', '::1'),
(75, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-A', '2023-12-13', '2023-12-27', 'SUCCESS', '', '', '2023-02-21', '10:31:am', '::1'),
(76, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-A', '2023-12-13', '2023-12-27', 'SUCCESS', '', '', '2023-02-21', '11:25:am', '::1'),
(77, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-A', '2023-12-13', '2023-12-27', 'SUCCESS', '', '', '2023-02-21', '11:25:am', '::1'),
(78, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-A', '2023-12-13', '2023-12-27', 'SUCCESS', '', '', '2023-02-21', '11:25:am', '::1'),
(79, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-A', '2023-12-13', '2023-12-27', 'SUCCESS', '', '', '2023-02-21', '11:25:am', '::1'),
(80, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '11:27:am', '::1'),
(81, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-February-A and Bi-Monthly Frequency, No Data', '2023-02-13', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:27:am', '::1'),
(82, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-February-A and Bi-Monthly Frequency, No Data', '2023-02-13', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:29:am', '::1'),
(83, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-February-A and Bi-Monthly Frequency, No Data', '2023-02-13', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:33:am', '::1'),
(84, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-February-A and Bi-Monthly Frequency, No Data', '2023-02-13', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:36:am', '::1'),
(85, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Approved Requests of ', ' Cut Off Period of 2023-February-A and Bi-Monthly Frequency, No Data', '2023-02-13', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:37:am', '::1'),
(86, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-February-A and Bi-Monthly Frequency, No Data', '2023-02-13', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:37:am', '::1'),
(87, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-February-A and Bi-Monthly Frequency, No Data', '2023-02-13', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(88, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency, No Data', '2022-12-28', '2023-01-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(89, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency, No Data', '2023-01-28', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(90, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-February-B and Weekly Frequency, No Data', '2023-02-28', '2023-03-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(91, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-March-B and Weekly Frequency, No Data', '2023-03-28', '2023-04-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(92, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-April-B and Weekly Frequency, No Data', '2023-04-28', '2023-05-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(93, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-November-B and Weekly Frequency, No Data', '2023-11-28', '2023-12-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(94, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-October-B and Weekly Frequency, No Data', '2023-10-28', '2023-11-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(95, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-August-B and Weekly Frequency, No Data', '2023-08-28', '2023-09-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(96, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Approved Requests of ', ' Cut Off Period of 2023-August-B and Weekly Frequency, No Data', '2023-08-28', '2023-09-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(97, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Disapproved Requests of ', ' Cut Off Period of 2023-August-B and Weekly Frequency, No Data', '2023-08-28', '2023-09-27', 'FAILED', '', '', '2023-02-21', '11:38:am', '::1'),
(98, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Error Reports of ', ' Cut Off Period of 2023-August-B and Weekly Frequency, No Data', '2023-08-28', '2023-09-27', 'FAILED', '', '', '2023-02-21', '11:39:am', '::1'),
(99, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Error Reports of ', ' Cut Off Period of 2022-December-B and Weekly Frequency, No Data', '2022-12-28', '2023-01-27', 'FAILED', '', '', '2023-02-21', '11:39:am', '::1'),
(100, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Disapproved Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency, No Data', '2022-12-28', '2023-01-27', 'FAILED', '', '', '2023-02-21', '11:39:am', '::1'),
(101, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Approved Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency, No Data', '2022-12-28', '2023-01-27', 'FAILED', '', '', '2023-02-21', '11:39:am', '::1'),
(102, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency, No Data', '2022-12-28', '2023-01-27', 'FAILED', '', '', '2023-02-21', '11:39:am', '::1'),
(103, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency, No Data', '2023-01-28', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:39:am', '::1'),
(104, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency, No Data', '2023-01-28', '2023-02-27', 'FAILED', '', '', '2023-02-21', '11:39:am', '::1'),
(105, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Pending Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency, No Data', '2022-12-28', '2023-01-27', 'FAILED', '', '', '2023-02-21', '11:41:am', '::1'),
(106, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Approved Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency, No Data', '2022-12-28', '2023-01-27', 'FAILED', '', '', '2023-02-21', '11:41:am', '::1'),
(107, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Overtime Disapproved Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency, No Data', '2022-12-28', '2023-01-27', 'FAILED', '', '', '2023-02-21', '11:41:am', '::1'),
(108, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency', '2022-12-28', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '11:55:am', '::1'),
(109, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '11:56:am', '::1'),
(110, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '11:56:am', '::1'),
(111, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-February-B and Weekly Frequency', '2023-02-28', '2023-03-27', 'SUCCESS', '', '', '2023-02-21', '11:56:am', '::1'),
(112, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency', '2022-12-28', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '11:56:am', '::1'),
(113, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency', '2022-12-28', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '11:56:am', '::1'),
(114, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency', '2022-12-28', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '11:56:am', '::1'),
(115, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '11:56:am', '::1'),
(116, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '11:56:am', '::1'),
(117, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-February-B and Weekly Frequency', '2023-02-28', '2023-03-27', 'SUCCESS', '', '', '2023-02-21', '11:56:am', '::1'),
(118, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2022-December-B and Weekly Frequency', '2022-12-28', '2023-01-27', 'SUCCESS', '', '', '2023-02-21', '11:57:am', '::1'),
(119, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '11:57:am', '::1'),
(120, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '11:59:am', '::1'),
(121, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '11:59:am', '::1'),
(122, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-January-A and Weekly Frequency', '2023-01-04', '2023-01-10', 'SUCCESS', '', '', '2023-02-21', '12:00:pm', '::1'),
(123, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '12:00:pm', '::1'),
(124, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '12:06:pm', '::1'),
(125, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '12:07:pm', '::1'),
(126, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-June-B and Weekly Frequency', '2023-06-28', '2023-07-27', 'SUCCESS', '', '', '2023-02-21', '12:07:pm', '::1'),
(127, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-June-B and Weekly Frequency', '2023-06-28', '2023-07-27', 'SUCCESS', '', '', '2023-02-21', '12:07:pm', '::1'),
(128, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-June-B and Weekly Frequency', '2023-06-28', '2023-07-27', 'SUCCESS', '', '', '2023-02-21', '12:08:pm', '::1'),
(129, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-June-B and Weekly Frequency', '2023-06-28', '2023-07-27', 'SUCCESS', '', '', '2023-02-21', '12:08:pm', '::1'),
(130, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-June-B and Weekly Frequency', '2023-06-28', '2023-07-27', 'SUCCESS', '', '', '2023-02-21', '12:08:pm', '::1'),
(131, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:09:pm', '::1'),
(132, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:09:pm', '::1'),
(133, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:15:pm', '::1'),
(134, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:15:pm', '::1'),
(135, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:15:pm', '::1'),
(136, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:15:pm', '::1'),
(137, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:15:pm', '::1'),
(138, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:15:pm', '::1'),
(139, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:15:pm', '::1'),
(140, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:15:pm', '::1'),
(141, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:16:pm', '::1'),
(142, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:16:pm', '::1'),
(143, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Approved Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:32:pm', '::1'),
(144, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Pending Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:32:pm', '::1'),
(145, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Disapproved Leave Requests of ', ' Cut Off Period of 2023-May-B and Weekly Frequency', '2023-05-28', '2023-06-27', 'SUCCESS', '', '', '2023-02-21', '12:34:pm', '::1'),
(146, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', ' Disapproved Leave Requests of ', ' Cut Off Period of 2023-January-B and Weekly Frequency', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '12:34:pm', '::1'),
(147, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '01:31:pm', '::1'),
(148, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '01:31:pm', '::1'),
(149, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '01:31:pm', '::1'),
(150, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '01:31:pm', '::1'),
(151, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '01:31:pm', '::1'),
(152, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-21', '01:31:pm', '::1'),
(153, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-22', '11:20:am', '::1'),
(154, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-22', '11:20:am', '::1'),
(155, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-22', '11:20:am', '::1'),
(156, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-22', '11:21:am', '::1'),
(157, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-22', '11:21:am', '::1'),
(158, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-27', 'SUCCESS', '', '', '2023-02-22', '11:21:am', '::1'),
(159, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-27', 'SUCCESS', '', '', '2023-02-22', '11:21:am', '::1'),
(160, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-27', 'SUCCESS', '', '', '2023-02-22', '11:21:am', '::1'),
(161, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-22', '11:21:am', '::1'),
(162, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-22', '11:21:am', '::1'),
(163, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-27', 'SUCCESS', '', '', '2023-02-22', '11:21:am', '::1'),
(164, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-B', '2023-01-28', '2023-02-27', 'SUCCESS', '', '', '2023-02-22', '11:21:am', '::1'),
(165, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-B', '2023-02-28', '2023-03-27', 'SUCCESS', '', '', '2023-02-22', '11:22:am', '::1'),
(166, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'February-A', '2023-02-13', '2023-02-27', 'SUCCESS', '', '', '2023-02-22', '11:22:am', '::1'),
(167, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'December-B', '2022-12-28', '2023-01-12', 'SUCCESS', '', '', '2023-02-22', '11:22:am', '::1'),
(168, 'admin123', 'Super Admin', 'TIMEKEEPING', 'Load', 'Attendance Records of ', 'January-A', '2023-01-13', '2023-01-27', 'SUCCESS', '', '', '2023-02-22', '11:23:am', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `g_sprint_variables`
--

CREATE TABLE IF NOT EXISTS `g_sprint_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `variable_name` varchar(100) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `custom_value_a` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `g_sprint_variables`
--

INSERT INTO `g_sprint_variables` (`id`, `variable_name`, `value`, `custom_value_a`) VALUES
(1, 'default_total_working_days', '312', ''),
(2, 'ceta', '0', ''),
(3, 'sea', '0', '1'),
(4, 'minimum_rate', '310', ''),
(5, 'night_shift_hour', '22:00:00 to 06:00:00', ''),
(6, 'default_fiscal_year', 'January 01', ''),
(7, 'loans_gross_limit', '0', ''),
(8, 'confi_ot_rate', '120', ''),
(9, 'default_default_weekly_payroll_rates', 'Enable', ''),
(10, 'default_default_bimonthly_payroll_rates', 'Enable', ''),
(11, 'year_leave_reset', '2023', ''),
(12, 'default_default_monthly_payroll_rates', 'Disable', '');

-- --------------------------------------------------------

--
-- Table structure for table `g_sss`
--

CREATE TABLE IF NOT EXISTS `g_sss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(258) CHARACTER SET latin1 NOT NULL,
  `effectivity_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `g_sss`
--

INSERT INTO `g_sss` (`id`, `description`, `effectivity_date`) VALUES
(1, 'test', '2010-07-07'),
(2, 'sdfsf', '2012-07-10');

-- --------------------------------------------------------

--
-- Table structure for table `g_sss_table_rate`
--

CREATE TABLE IF NOT EXISTS `g_sss_table_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sss_table_id` int(11) NOT NULL,
  `from_salary` float NOT NULL,
  `to_salary` float NOT NULL,
  `monthly_salary_credit` float NOT NULL,
  `ss_er` float NOT NULL COMMENT 'Social Security ER',
  `ss_ee` float NOT NULL COMMENT 'Social Security EE',
  `ss_total` float NOT NULL COMMENT 'Total',
  `company_ec` float NOT NULL,
  `tc_er` float NOT NULL COMMENT 'Total Contribution ER',
  `tc_ee` float NOT NULL COMMENT 'Total Contribution EE',
  `tc_total` float NOT NULL COMMENT 'Total',
  `total_contribution` float NOT NULL COMMENT 'Total Contribution',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_system_modules`
--

CREATE TABLE IF NOT EXISTS `g_system_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `owner_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(240) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_tax_table`
--

CREATE TABLE IF NOT EXISTS `g_tax_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `pay_frequency` varchar(200) CHARACTER SET latin1 NOT NULL COMMENT 'Monthly / Semi Monthly',
  `status` varchar(200) CHARACTER SET latin1 NOT NULL,
  `d0` float NOT NULL,
  `d1` float NOT NULL,
  `d2` float NOT NULL,
  `d3` float NOT NULL,
  `d4` float NOT NULL,
  `d5` float NOT NULL,
  `d6` float NOT NULL,
  `d7` float NOT NULL,
  `d8` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_user`
--

CREATE TABLE IF NOT EXISTS `g_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employment_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'terminated, regular',
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'email address',
  `hash` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `module` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `receive_notification` int(11) NOT NULL COMMENT '0/1',
  `date_entered` date NOT NULL,
  `date_modified` date NOT NULL,
  `modified_user_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_user_group`
--

CREATE TABLE IF NOT EXISTS `g_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_structure_id` int(11) NOT NULL,
  `group_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `g_weekly_cutoff_period`
--

CREATE TABLE IF NOT EXISTS `g_weekly_cutoff_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year_tag` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `payout_date` date NOT NULL,
  `cutoff_number` int(11) NOT NULL,
  `salary_cycle_id` int(11) NOT NULL,
  `is_lock` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `is_payroll_generated` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci AUTO_INCREMENT=261 ;

--
-- Dumping data for table `g_weekly_cutoff_period`
--

INSERT INTO `g_weekly_cutoff_period` (`id`, `year_tag`, `period_start`, `period_end`, `payout_date`, `cutoff_number`, `salary_cycle_id`, `is_lock`, `is_payroll_generated`) VALUES
(53, '2021', '2021-01-07', '2021-01-13', '2021-01-13', 1, 2, 'No', 'No'),
(54, '2021', '2021-01-14', '2021-01-20', '2021-01-20', 2, 2, 'No', 'No'),
(55, '2021', '2021-01-21', '2021-01-27', '2021-01-27', 3, 2, 'No', 'No'),
(56, '2021', '2021-01-28', '2021-02-03', '2021-02-03', 4, 2, 'No', 'No'),
(57, '2021', '2021-02-04', '2021-02-10', '2021-02-10', 1, 2, 'No', 'No'),
(58, '2021', '2021-02-11', '2021-02-17', '2021-02-17', 2, 2, 'No', 'No'),
(59, '2021', '2021-02-18', '2021-02-24', '2021-02-24', 3, 2, 'No', 'No'),
(60, '2021', '2021-02-25', '2021-03-03', '2021-03-03', 4, 2, 'No', 'No'),
(61, '2021', '2021-03-04', '2021-03-10', '2021-03-10', 1, 2, 'No', 'No'),
(62, '2021', '2021-03-11', '2021-03-17', '2021-03-17', 2, 2, 'No', 'No'),
(63, '2021', '2021-03-18', '2021-03-24', '2021-03-24', 3, 2, 'No', 'No'),
(64, '2021', '2021-03-25', '2021-03-31', '2021-03-31', 4, 2, 'No', 'No'),
(65, '2021', '2021-04-01', '2021-04-07', '2021-04-07', 1, 2, 'No', 'No'),
(66, '2021', '2021-04-08', '2021-04-14', '2021-04-14', 2, 2, 'No', 'No'),
(67, '2021', '2021-04-15', '2021-04-21', '2021-04-21', 3, 2, 'No', 'No'),
(68, '2021', '2021-04-22', '2021-04-28', '2021-04-28', 4, 2, 'No', 'No'),
(69, '2021', '2021-04-29', '2021-05-05', '2021-05-05', 5, 2, 'No', 'No'),
(70, '2021', '2021-05-06', '2021-05-12', '2021-05-12', 1, 2, 'No', 'No'),
(71, '2021', '2021-05-13', '2021-05-19', '2021-05-19', 2, 2, 'No', 'No'),
(72, '2021', '2021-05-20', '2021-05-26', '2021-05-26', 3, 2, 'No', 'No'),
(73, '2021', '2021-05-27', '2021-06-02', '2021-06-02', 4, 2, 'No', 'No'),
(74, '2021', '2021-06-03', '2021-06-09', '2021-06-09', 1, 2, 'No', 'No'),
(75, '2021', '2021-06-10', '2021-06-16', '2021-06-16', 2, 2, 'No', 'No'),
(76, '2021', '2021-06-17', '2021-06-23', '2021-06-23', 3, 2, 'No', 'No'),
(77, '2021', '2021-06-24', '2021-06-30', '2021-06-30', 4, 2, 'No', 'No'),
(78, '2021', '2021-07-01', '2021-07-07', '2021-07-07', 1, 2, 'No', 'No'),
(79, '2021', '2021-07-08', '2021-07-14', '2021-07-14', 2, 2, 'No', 'No'),
(80, '2021', '2021-07-15', '2021-07-21', '2021-07-21', 3, 2, 'No', 'No'),
(81, '2021', '2021-07-22', '2021-07-28', '2021-07-28', 4, 2, 'No', 'No'),
(82, '2021', '2021-07-29', '2021-08-04', '2021-08-04', 5, 2, 'No', 'No'),
(83, '2021', '2021-08-05', '2021-08-11', '2021-08-11', 1, 2, 'No', 'No'),
(84, '2021', '2021-08-12', '2021-08-18', '2021-08-18', 2, 2, 'No', 'No'),
(85, '2021', '2021-08-19', '2021-08-25', '2021-08-25', 3, 2, 'No', 'No'),
(86, '2021', '2021-08-26', '2021-09-01', '2021-09-01', 4, 2, 'No', 'No'),
(87, '2021', '2021-09-02', '2021-09-08', '2021-09-08', 1, 2, 'No', 'No'),
(88, '2021', '2021-09-09', '2021-09-15', '2021-09-15', 2, 2, 'No', 'No'),
(89, '2021', '2021-09-16', '2021-09-22', '2021-09-22', 3, 2, 'No', 'No'),
(90, '2021', '2021-09-23', '2021-09-29', '2021-09-29', 4, 2, 'No', 'No'),
(91, '2021', '2021-09-30', '2021-10-06', '2021-10-06', 5, 2, 'No', 'No'),
(92, '2021', '2021-10-07', '2021-10-13', '2021-10-13', 1, 2, 'No', 'No'),
(93, '2021', '2021-10-14', '2021-10-20', '2021-10-20', 2, 2, 'No', 'No'),
(94, '2021', '2021-10-21', '2021-10-27', '2021-10-27', 3, 2, 'No', 'No'),
(95, '2021', '2021-10-28', '2021-11-03', '2021-11-03', 4, 2, 'No', 'No'),
(96, '2021', '2021-11-04', '2021-11-10', '2021-11-10', 1, 2, 'No', 'No'),
(97, '2021', '2021-11-11', '2021-11-17', '2021-11-17', 2, 2, 'No', 'No'),
(98, '2021', '2021-11-18', '2021-11-24', '2021-11-24', 3, 2, 'No', 'No'),
(99, '2021', '2021-11-25', '2021-12-01', '2021-12-01', 4, 2, 'No', 'No'),
(100, '2021', '2021-12-02', '2021-12-08', '2021-12-08', 1, 2, 'No', 'No'),
(101, '2021', '2021-12-09', '2021-12-15', '2021-12-15', 2, 2, 'No', 'No'),
(102, '2021', '2021-12-16', '2021-12-22', '2021-12-22', 3, 2, 'No', 'No'),
(103, '2021', '2021-12-23', '2021-12-29', '2021-12-29', 4, 2, 'No', 'No'),
(104, '2021', '2021-12-30', '2022-01-05', '2022-01-05', 5, 2, 'No', 'No'),
(105, '2022', '2022-01-06', '2022-01-12', '2022-01-12', 1, 2, 'No', 'No'),
(106, '2022', '2022-01-13', '2022-01-19', '2022-01-19', 2, 2, 'No', 'No'),
(107, '2022', '2022-01-20', '2022-01-26', '2022-01-26', 3, 2, 'No', 'No'),
(108, '2022', '2022-01-27', '2022-02-02', '2022-02-02', 4, 2, 'No', 'No'),
(109, '2022', '2022-02-03', '2022-02-09', '2022-02-09', 1, 2, 'No', 'No'),
(110, '2022', '2022-02-10', '2022-02-16', '2022-02-16', 2, 2, 'No', 'No'),
(111, '2022', '2022-02-17', '2022-02-23', '2022-02-23', 3, 2, 'No', 'No'),
(112, '2022', '2022-02-24', '2022-03-02', '2022-03-02', 4, 2, 'No', 'No'),
(113, '2022', '2022-03-03', '2022-03-09', '2022-03-09', 1, 2, 'No', 'No'),
(114, '2022', '2022-03-10', '2022-03-16', '2022-03-16', 2, 2, 'No', 'No'),
(115, '2022', '2022-03-17', '2022-03-23', '2022-03-23', 3, 2, 'No', 'No'),
(116, '2022', '2022-03-24', '2022-03-30', '2022-03-30', 4, 2, 'No', 'No'),
(117, '2022', '2022-03-31', '2022-04-06', '2022-04-06', 5, 2, 'No', 'No'),
(118, '2022', '2022-04-07', '2022-04-13', '2022-04-13', 1, 2, 'No', 'No'),
(119, '2022', '2022-04-14', '2022-04-20', '2022-04-20', 2, 2, 'No', 'No'),
(120, '2022', '2022-04-21', '2022-04-27', '2022-04-27', 3, 2, 'No', 'No'),
(121, '2022', '2022-04-28', '2022-05-04', '2022-05-04', 4, 2, 'No', 'No'),
(122, '2022', '2022-05-05', '2022-05-11', '2022-05-11', 1, 2, 'No', 'No'),
(123, '2022', '2022-05-12', '2022-05-18', '2022-05-18', 2, 2, 'No', 'No'),
(124, '2022', '2022-05-19', '2022-05-25', '2022-05-25', 3, 2, 'No', 'No'),
(125, '2022', '2022-05-26', '2022-06-01', '2022-06-01', 4, 2, 'No', 'No'),
(126, '2022', '2022-06-02', '2022-06-08', '2022-06-08', 1, 2, 'No', 'No'),
(127, '2022', '2022-06-09', '2022-06-15', '2022-06-15', 2, 2, 'No', 'No'),
(128, '2022', '2022-06-16', '2022-06-22', '2022-06-22', 3, 2, 'No', 'No'),
(129, '2022', '2022-06-23', '2022-06-29', '2022-06-29', 4, 2, 'No', 'No'),
(130, '2022', '2022-06-30', '2022-07-06', '2022-07-06', 5, 2, 'No', 'No'),
(131, '2022', '2022-07-07', '2022-07-13', '2022-07-13', 1, 2, 'No', 'No'),
(132, '2022', '2022-07-14', '2022-07-20', '2022-07-20', 2, 2, 'No', 'No'),
(133, '2022', '2022-07-21', '2022-07-27', '2022-07-27', 3, 2, 'No', 'No'),
(134, '2022', '2022-07-28', '2022-08-03', '2022-08-03', 4, 2, 'No', 'No'),
(135, '2022', '2022-08-04', '2022-08-10', '2022-08-10', 1, 2, 'No', 'No'),
(136, '2022', '2022-08-11', '2022-08-17', '2022-08-17', 2, 2, 'No', 'No'),
(137, '2022', '2022-08-18', '2022-08-24', '2022-08-24', 3, 2, 'No', 'No'),
(138, '2022', '2022-08-25', '2022-08-31', '2022-08-31', 4, 2, 'No', 'No'),
(139, '2022', '2022-09-01', '2022-09-07', '2022-09-07', 1, 2, 'No', 'No'),
(140, '2022', '2022-09-08', '2022-09-14', '2022-09-14', 2, 2, 'No', 'No'),
(141, '2022', '2022-09-15', '2022-09-21', '2022-09-21', 3, 2, 'No', 'No'),
(142, '2022', '2022-09-22', '2022-09-28', '2022-09-28', 4, 2, 'No', 'No'),
(143, '2022', '2022-09-29', '2022-10-05', '2022-10-05', 5, 2, 'No', 'No'),
(144, '2022', '2022-10-06', '2022-10-12', '2022-10-12', 1, 2, 'No', 'No'),
(145, '2022', '2022-10-13', '2022-10-19', '2022-10-19', 2, 2, 'No', 'No'),
(146, '2022', '2022-10-20', '2022-10-26', '2022-10-26', 3, 2, 'No', 'No'),
(147, '2022', '2022-10-27', '2022-11-02', '2022-11-02', 4, 2, 'No', 'No'),
(148, '2022', '2022-11-03', '2022-11-09', '2022-11-09', 1, 2, 'No', 'No'),
(149, '2022', '2022-11-10', '2022-11-16', '2022-11-16', 2, 2, 'No', 'No'),
(150, '2022', '2022-11-17', '2022-11-23', '2022-11-23', 3, 2, 'No', 'No'),
(151, '2022', '2022-11-24', '2022-11-30', '2022-11-30', 4, 2, 'No', 'No'),
(152, '2022', '2022-12-01', '2022-12-07', '2022-12-07', 1, 2, 'No', 'No'),
(153, '2022', '2022-12-08', '2022-12-14', '2022-12-14', 2, 2, 'No', 'No'),
(154, '2022', '2022-12-15', '2022-12-21', '2022-12-21', 3, 2, 'No', 'No'),
(155, '2022', '2022-12-22', '2022-12-28', '2022-12-28', 4, 2, 'No', 'No'),
(156, '2022', '2022-12-29', '2023-01-04', '2023-01-04', 5, 2, 'No', 'No'),
(209, '2023', '2023-01-04', '2023-01-10', '2023-01-10', 1, 2, 'No', 'No'),
(210, '2023', '2023-01-11', '2023-01-17', '2023-01-17', 2, 2, 'No', 'No'),
(211, '2023', '2023-01-18', '2023-01-24', '2023-01-24', 3, 2, 'No', 'No'),
(212, '2023', '2023-01-25', '2023-01-31', '2023-01-31', 4, 2, 'No', 'No'),
(213, '2023', '2023-02-01', '2023-02-07', '2023-02-07', 1, 2, 'No', 'No'),
(214, '2023', '2023-02-08', '2023-02-14', '2023-02-14', 2, 2, 'No', 'No'),
(215, '2023', '2023-02-15', '2023-02-21', '2023-02-21', 3, 2, 'No', 'No'),
(216, '2023', '2023-02-22', '2023-02-28', '2023-02-28', 4, 2, 'No', 'No'),
(217, '2023', '2023-03-01', '2023-03-07', '2023-03-07', 1, 2, 'No', 'No'),
(218, '2023', '2023-03-08', '2023-03-14', '2023-03-14', 2, 2, 'No', 'No'),
(219, '2023', '2023-03-15', '2023-03-21', '2023-03-21', 3, 2, 'No', 'No'),
(220, '2023', '2023-03-22', '2023-03-28', '2023-03-28', 4, 2, 'No', 'No'),
(221, '2023', '2023-03-29', '2023-04-04', '2023-04-04', 5, 2, 'No', 'No'),
(222, '2023', '2023-04-05', '2023-04-11', '2023-04-11', 1, 2, 'No', 'No'),
(223, '2023', '2023-04-12', '2023-04-18', '2023-04-18', 2, 2, 'No', 'No'),
(224, '2023', '2023-04-19', '2023-04-25', '2023-04-25', 3, 2, 'No', 'No'),
(225, '2023', '2023-04-26', '2023-05-02', '2023-05-02', 4, 2, 'No', 'No'),
(226, '2023', '2023-05-03', '2023-05-09', '2023-05-09', 1, 2, 'No', 'No'),
(227, '2023', '2023-05-10', '2023-05-16', '2023-05-16', 2, 2, 'No', 'No'),
(228, '2023', '2023-05-17', '2023-05-23', '2023-05-23', 3, 2, 'No', 'No'),
(229, '2023', '2023-05-24', '2023-05-30', '2023-05-30', 4, 2, 'No', 'No'),
(230, '2023', '2023-05-31', '2023-06-06', '2023-06-06', 5, 2, 'No', 'No'),
(231, '2023', '2023-06-07', '2023-06-13', '2023-06-13', 1, 2, 'No', 'No'),
(232, '2023', '2023-06-14', '2023-06-20', '2023-06-20', 2, 2, 'No', 'No'),
(233, '2023', '2023-06-21', '2023-06-27', '2023-06-27', 3, 2, 'No', 'No'),
(234, '2023', '2023-06-28', '2023-07-04', '2023-07-04', 4, 2, 'No', 'No'),
(235, '2023', '2023-07-05', '2023-07-11', '2023-07-11', 1, 2, 'No', 'No'),
(236, '2023', '2023-07-12', '2023-07-18', '2023-07-18', 2, 2, 'No', 'No'),
(237, '2023', '2023-07-19', '2023-07-25', '2023-07-25', 3, 2, 'No', 'No'),
(238, '2023', '2023-07-26', '2023-08-01', '2023-08-01', 4, 2, 'No', 'No'),
(239, '2023', '2023-08-02', '2023-08-08', '2023-08-08', 1, 2, 'No', 'No'),
(240, '2023', '2023-08-09', '2023-08-15', '2023-08-15', 2, 2, 'No', 'No'),
(241, '2023', '2023-08-16', '2023-08-22', '2023-08-22', 3, 2, 'No', 'No'),
(242, '2023', '2023-08-23', '2023-08-29', '2023-08-29', 4, 2, 'No', 'No'),
(243, '2023', '2023-08-30', '2023-09-05', '2023-09-05', 5, 2, 'No', 'No'),
(244, '2023', '2023-09-06', '2023-09-12', '2023-09-12', 1, 2, 'No', 'No'),
(245, '2023', '2023-09-13', '2023-09-19', '2023-09-19', 2, 2, 'No', 'No'),
(246, '2023', '2023-09-20', '2023-09-26', '2023-09-26', 3, 2, 'No', 'No'),
(247, '2023', '2023-09-27', '2023-10-03', '2023-10-03', 4, 2, 'No', 'No'),
(248, '2023', '2023-10-04', '2023-10-10', '2023-10-10', 1, 2, 'No', 'No'),
(249, '2023', '2023-10-11', '2023-10-17', '2023-10-17', 2, 2, 'No', 'No'),
(250, '2023', '2023-10-18', '2023-10-24', '2023-10-24', 3, 2, 'No', 'No'),
(251, '2023', '2023-10-25', '2023-10-31', '2023-10-31', 4, 2, 'No', 'No'),
(252, '2023', '2023-11-01', '2023-11-07', '2023-11-07', 1, 2, 'No', 'No'),
(253, '2023', '2023-11-08', '2023-11-14', '2023-11-14', 2, 2, 'No', 'No'),
(254, '2023', '2023-11-15', '2023-11-21', '2023-11-21', 3, 2, 'No', 'No'),
(255, '2023', '2023-11-22', '2023-11-28', '2023-11-28', 4, 2, 'No', 'No'),
(256, '2023', '2023-11-29', '2023-12-05', '2023-12-05', 5, 2, 'No', 'No'),
(257, '2023', '2023-12-06', '2023-12-12', '2023-12-12', 1, 2, 'No', 'No'),
(258, '2023', '2023-12-13', '2023-12-19', '2023-12-19', 2, 2, 'No', 'No'),
(259, '2023', '2023-12-20', '2023-12-26', '2023-12-26', 3, 2, 'No', 'No'),
(260, '2023', '2023-12-27', '2024-01-02', '2024-01-02', 4, 2, 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `g_yearly_bonus_release_dates`
--

CREATE TABLE IF NOT EXISTS `g_yearly_bonus_release_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `total_basic_pay` float(11,2) NOT NULL,
  `amount` float(11,2) NOT NULL,
  `taxable_amount` float(11,2) NOT NULL,
  `tax` float(11,2) NOT NULL,
  `percentage` float(11,2) NOT NULL,
  `total_bonus_amount` float(11,2) NOT NULL,
  `deducted_amount` float(11,2) NOT NULL,
  `year_released` int(11) NOT NULL,
  `month_start` int(2) NOT NULL,
  `month_end` int(2) NOT NULL,
  `deduction_month_start` int(2) NOT NULL,
  `deduction_month_end` int(2) NOT NULL,
  `payroll_start_date` date NOT NULL,
  `cutoff_start_date` date NOT NULL,
  `cutoff_end_date` date NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `generate_based` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jgl_activities`
--

CREATE TABLE IF NOT EXISTS `jgl_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `jgl_created_activities`
--

CREATE TABLE IF NOT EXISTS `jgl_created_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` int(10) unsigned DEFAULT NULL,
  `employee_id` int(10) unsigned DEFAULT NULL,
  `activity_id` int(10) unsigned DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `date_time_in` datetime DEFAULT NULL,
  `date_time_out` datetime DEFAULT NULL,
  `status_id` int(10) unsigned DEFAULT NULL,
  `progress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `location_id` int(10) unsigned DEFAULT NULL,
  `project_site_id` int(10) unsigned DEFAULT NULL,
  `designation_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `jgl_designations`
--

CREATE TABLE IF NOT EXISTS `jgl_designations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `jgl_histories`
--

CREATE TABLE IF NOT EXISTS `jgl_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_activity_id` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jgl_locations`
--

CREATE TABLE IF NOT EXISTS `jgl_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1588 ;

-- --------------------------------------------------------

--
-- Table structure for table `jgl_project_sites`
--

CREATE TABLE IF NOT EXISTS `jgl_project_sites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `jgl_statuses`
--

CREATE TABLE IF NOT EXISTS `jgl_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_13th_month_pro_rated_per_employee`
--

CREATE TABLE IF NOT EXISTS `laravel_13th_month_pro_rated_per_employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(11) NOT NULL,
  `year_covered` varchar(225) NOT NULL,
  `month` varchar(225) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_worked` float NOT NULL,
  `city_name` varchar(225) NOT NULL,
  `city_id` int(11) NOT NULL,
  `minimum_rate` double(15,2) NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL,
  `total_worked_hrs` decimal(10,2) NOT NULL,
  `gross_total` decimal(10,2) NOT NULL,
  `late_hrs` decimal(10,2) NOT NULL,
  `late_amount` decimal(10,2) NOT NULL,
  `undertime_hrs` decimal(10,2) NOT NULL,
  `undertime_amount` decimal(10,2) NOT NULL,
  `total_net_pay` decimal(10,2) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=192 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_employee_attendance`
--

CREATE TABLE IF NOT EXISTS `laravel_employee_attendance` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `project_site_id` int(11) NOT NULL,
  `project_site` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_out` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_minimum_rates_settings`
--

CREATE TABLE IF NOT EXISTS `laravel_minimum_rates_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `is_enabled` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_project_sites`
--

CREATE TABLE IF NOT EXISTS `laravel_project_sites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `multiple_logs` tinyint(1) DEFAULT NULL COMMENT 'enable disable multiple logs',
  `weekly` tinyint(1) DEFAULT NULL COMMENT 'enable disable weekly pay period',
  `monthly` tinyint(1) DEFAULT NULL COMMENT 'enable disable monthly pay period',
  `bi_monthly` tinyint(1) DEFAULT NULL COMMENT 'enable disable bi-monthly pay period',
  `sss` tinyint(1) DEFAULT NULL COMMENT 'enable disable sss contri',
  `philhealth` tinyint(1) DEFAULT NULL COMMENT 'enable disable philhealth',
  `pagibig` tinyint(1) DEFAULT NULL COMMENT 'enable disable pagibig ',
  `device_id` int(11) DEFAULT NULL,
  `device_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tag_deleted` int(11) DEFAULT '0' COMMENT '0 = active, 1 = deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_project_site_attendances`
--

CREATE TABLE IF NOT EXISTS `laravel_project_site_attendances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_activity_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `fetch_from` smallint(11) NOT NULL DEFAULT '0' COMMENT '0=activity, 1=device logs',
  `project_site_id` int(11) DEFAULT NULL,
  `schedule_project_site_id` int(11) DEFAULT NULL,
  `frequency_id` int(11) DEFAULT NULL,
  `payslip_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `is_holiday` int(11) DEFAULT NULL,
  `holiday_type` int(11) DEFAULT NULL,
  `activity_in` datetime DEFAULT NULL,
  `activity_out` datetime DEFAULT NULL,
  `activity_raw_worked_mins` int(11) DEFAULT '0',
  `activity_deductible_break_mins` int(11) DEFAULT '0',
  `activity_total_worked_mins` int(11) DEFAULT '0',
  `activity_late_mins` int(11) DEFAULT '0',
  `activity_undertime_mins` int(11) DEFAULT '0',
  `activity_overtime_mins` int(11) DEFAULT '0',
  `percentage` double(20,14) DEFAULT '0.00000000000000',
  `basic_pay_percentage` double(20,14) DEFAULT '0.00000000000000',
  `late_percentage` double(20,14) DEFAULT '0.00000000000000',
  `undertime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `regular_percentage` double(20,14) DEFAULT '0.00000000000000',
  `regular_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `night_diff_percentage` double(20,14) DEFAULT '0.00000000000000',
  `night_diff_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `restday_percentage` double(20,14) DEFAULT '0.00000000000000',
  `restday_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `restday_night_diff_percentage` double(20,14) DEFAULT '0.00000000000000',
  `restday_night_diff_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `special_percentage` double(20,14) DEFAULT '0.00000000000000',
  `special_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `special_night_diff_percentage` double(20,14) DEFAULT '0.00000000000000',
  `special_night_diff_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `special_restday_percentage` double(20,14) DEFAULT '0.00000000000000',
  `special_restday_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `special_restday_night_diff_percentage` double(20,14) DEFAULT '0.00000000000000',
  `special_restday_night_diff_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `legal_percentage` double(20,14) DEFAULT '0.00000000000000',
  `legal_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `legal_night_diff_percentage` double(20,14) DEFAULT '0.00000000000000',
  `legal_night_diff_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `legal_restday_percentage` double(20,14) DEFAULT '0.00000000000000',
  `legal_restday_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `legal_restday_night_diff_percentage` double(20,14) DEFAULT '0.00000000000000',
  `legal_restday_night_diff_overtime_percentage` double(20,14) DEFAULT '0.00000000000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `laravel_project_site_attendances_employee_activity_id_unique` (`employee_activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_project_site_cutoff_periods`
--

CREATE TABLE IF NOT EXISTS `laravel_project_site_cutoff_periods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year_tag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `payout_date` date DEFAULT NULL,
  `cutoff_number` int(11) DEFAULT NULL,
  `salary_cycle_id` int(11) DEFAULT NULL,
  `is_lock` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_payroll_generated` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_project_site_holidays`
--

CREATE TABLE IF NOT EXISTS `laravel_project_site_holidays` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_site_id` int(11) NOT NULL,
  `holiday_type` int(11) NOT NULL,
  `holiday_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `holiday_month` int(11) NOT NULL,
  `holiday_day` int(11) NOT NULL,
  `holiday_year` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_project_site_payslips`
--

CREATE TABLE IF NOT EXISTS `laravel_project_site_payslips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pay_period_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `fetch_from` int(11) NOT NULL DEFAULT '0' COMMENT '0=activity, 1=device logs',
  `project_site_id` int(11) NOT NULL,
  `period_start` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `period_end` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payout_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `basic_pay` decimal(20,3) DEFAULT NULL,
  `gross_pay` decimal(20,3) DEFAULT NULL,
  `total_earnings` decimal(20,3) DEFAULT NULL,
  `total_deductions` decimal(20,3) DEFAULT NULL,
  `net_pay` decimal(20,3) DEFAULT NULL,
  `taxable` decimal(20,3) DEFAULT NULL,
  `non_taxable` decimal(20,3) DEFAULT NULL,
  `withheld_tax` double(20,3) DEFAULT NULL,
  `month_13th` decimal(20,3) DEFAULT NULL,
  `sss_ee` decimal(20,3) DEFAULT NULL,
  `sss_er` decimal(20,3) DEFAULT NULL,
  `sss_ec` decimal(20,3) DEFAULT NULL,
  `pagibig_ee` decimal(20,3) DEFAULT NULL,
  `pagibig_er` decimal(20,3) DEFAULT NULL,
  `philhealth_ee` decimal(20,3) DEFAULT NULL,
  `philhealth_er` decimal(20,3) DEFAULT NULL,
  `earnings` longtext COLLATE utf8mb4_unicode_ci,
  `other_earnings` longtext COLLATE utf8mb4_unicode_ci,
  `deductions` longtext COLLATE utf8mb4_unicode_ci,
  `other_deductions` longtext COLLATE utf8mb4_unicode_ci,
  `labels` mediumtext COLLATE utf8mb4_unicode_ci,
  `overtime` decimal(20,3) DEFAULT NULL,
  `number_of_declared_dependents` int(11) DEFAULT NULL,
  `taxable_benefits` decimal(20,3) DEFAULT NULL,
  `non_taxable_benefits` decimal(20,3) DEFAULT NULL,
  `tardiness_amount` decimal(20,3) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_project_site_unauthorized_logs`
--

CREATE TABLE IF NOT EXISTS `laravel_project_site_unauthorized_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `employee_activity_id` int(11) DEFAULT NULL,
  `project_site_id` int(11) DEFAULT NULL,
  `schedule_project_site_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `log_time_in` datetime DEFAULT NULL,
  `log_time_out` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_pro_rated`
--

CREATE TABLE IF NOT EXISTS `laravel_pro_rated` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_rate` decimal(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_settings`
--

CREATE TABLE IF NOT EXISTS `laravel_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `variable` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `laravel_settings_variable_unique` (`variable`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `laravel_settings`
--

INSERT INTO `laravel_settings` (`id`, `variable`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'attendance_based_on_device', '1', '0 = false, 1 = true', '2021-03-24 16:00:00', '2021-04-05 02:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `laravel_site_calendars`
--

CREATE TABLE IF NOT EXISTS `laravel_site_calendars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_site_id` int(11) DEFAULT NULL,
  `holiday_type` int(11) DEFAULT NULL,
  `holiday_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `holiday_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tag_deleted` int(11) DEFAULT '0' COMMENT '0 = active, 1 = deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2021_10_26_143013_create_dtr_telcos_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pbcattbl`
--

CREATE TABLE IF NOT EXISTS `pbcattbl` (
  `pbt_tnam` char(193) CHARACTER SET latin1 NOT NULL,
  `pbt_tid` int(11) DEFAULT NULL,
  `pbt_ownr` char(193) CHARACTER SET latin1 NOT NULL,
  `pbd_fhgt` smallint(6) DEFAULT NULL,
  `pbd_fwgt` smallint(6) DEFAULT NULL,
  `pbd_fitl` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `pbd_funl` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `pbd_fchr` smallint(6) DEFAULT NULL,
  `pbd_fptc` smallint(6) DEFAULT NULL,
  `pbd_ffce` char(18) CHARACTER SET latin1 DEFAULT NULL,
  `pbh_fhgt` smallint(6) DEFAULT NULL,
  `pbh_fwgt` smallint(6) DEFAULT NULL,
  `pbh_fitl` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `pbh_funl` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `pbh_fchr` smallint(6) DEFAULT NULL,
  `pbh_fptc` smallint(6) DEFAULT NULL,
  `pbh_ffce` char(18) CHARACTER SET latin1 DEFAULT NULL,
  `pbl_fhgt` smallint(6) DEFAULT NULL,
  `pbl_fwgt` smallint(6) DEFAULT NULL,
  `pbl_fitl` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `pbl_funl` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `pbl_fchr` smallint(6) DEFAULT NULL,
  `pbl_fptc` smallint(6) DEFAULT NULL,
  `pbl_ffce` char(18) CHARACTER SET latin1 DEFAULT NULL,
  `pbt_cmnt` varchar(254) CHARACTER SET latin1 DEFAULT NULL,
  UNIQUE KEY `pbcatt_x` (`pbt_tnam`,`pbt_ownr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_site_frequencies`
--

CREATE TABLE IF NOT EXISTS `project_site_frequencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_site_id` int(11) NOT NULL DEFAULT '0',
  `frequency_id` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `p_philhealth`
--

CREATE TABLE IF NOT EXISTS `p_philhealth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_base` float(10,2) NOT NULL,
  `salary_bracket` smallint(6) NOT NULL,
  `from_salary` decimal(15,2) NOT NULL,
  `to_salary` decimal(15,2) NOT NULL,
  `monthly_contribution` float(10,2) NOT NULL,
  `employee_share` float(10,2) NOT NULL,
  `company_share` float(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Dumping data for table `p_philhealth`
--

INSERT INTO `p_philhealth` (`id`, `salary_base`, `salary_bracket`, `from_salary`, `to_salary`, `monthly_contribution`, `employee_share`, `company_share`) VALUES
(1, 8000.00, 1, 0.00, 8999.99, 200.00, 100.00, 100.00),
(2, 9000.00, 2, 9000.00, 9999.99, 225.00, 112.50, 112.50),
(3, 10000.00, 3, 10000.00, 10999.99, 250.00, 125.00, 125.00),
(4, 11000.00, 4, 11000.00, 11999.99, 275.00, 137.50, 137.50),
(5, 12000.00, 5, 12000.00, 12999.99, 300.00, 150.00, 150.00),
(6, 13000.00, 6, 13000.00, 13999.99, 325.00, 162.50, 162.50),
(7, 14000.00, 7, 14000.00, 14999.99, 350.00, 175.00, 175.00),
(8, 15000.00, 8, 15000.00, 15999.99, 375.00, 187.50, 187.50),
(9, 16000.00, 9, 16000.00, 16999.99, 400.00, 200.00, 200.00),
(10, 17000.00, 10, 17000.00, 17999.99, 425.00, 212.50, 212.50),
(11, 18000.00, 11, 18000.00, 18999.99, 450.00, 225.00, 225.00),
(12, 19000.00, 12, 19000.00, 19999.99, 475.00, 237.50, 237.50),
(13, 20000.00, 13, 20000.00, 20999.99, 500.00, 250.00, 250.00),
(14, 21000.00, 14, 21000.00, 21999.99, 525.00, 262.50, 262.50),
(15, 22000.00, 15, 22000.00, 22999.99, 550.00, 275.00, 275.00),
(16, 23000.00, 16, 23000.00, 23999.99, 575.00, 287.50, 287.50),
(17, 24000.00, 17, 24000.00, 24999.99, 600.00, 300.00, 300.00),
(18, 25000.00, 18, 25000.00, 25999.99, 625.00, 312.50, 312.50),
(19, 26000.00, 19, 26000.00, 26999.99, 650.00, 325.00, 325.00),
(20, 27000.00, 20, 27000.00, 27999.99, 675.00, 337.50, 337.50),
(21, 28000.00, 21, 28000.00, 28999.99, 700.00, 350.00, 350.00),
(22, 29000.00, 22, 29000.00, 29999.99, 725.00, 362.50, 362.50),
(23, 30000.00, 23, 30000.00, 30999.99, 750.00, 375.00, 375.00),
(24, 31000.00, 24, 31000.00, 31999.99, 775.00, 387.50, 387.50),
(25, 32000.00, 25, 32000.00, 32999.99, 800.00, 400.00, 400.00),
(26, 33000.00, 26, 33000.00, 33999.99, 825.00, 412.50, 412.50),
(27, 34000.00, 27, 34000.00, 34999.99, 850.00, 425.00, 425.00),
(28, 35000.00, 28, 35000.00, 9999999.99, 875.00, 437.50, 437.50);

-- --------------------------------------------------------

--
-- Table structure for table `p_sss`
--

CREATE TABLE IF NOT EXISTS `p_sss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `monthly_salary_credit` float(10,2) NOT NULL,
  `from_salary` float(10,2) NOT NULL,
  `to_salary` float(10,2) NOT NULL,
  `employee_share` float(10,2) NOT NULL,
  `company_share` float(10,2) NOT NULL,
  `company_ec` float(10,2) NOT NULL,
  `provident_ee` float(10,2) NOT NULL,
  `provident_er` float(10,2) NOT NULL,
  `is_active` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=54 ;

--
-- Dumping data for table `p_sss`
--

INSERT INTO `p_sss` (`id`, `monthly_salary_credit`, `from_salary`, `to_salary`, `employee_share`, `company_share`, `company_ec`, `provident_ee`, `provident_er`, `is_active`) VALUES
(1, 4000.00, 0.00, 4249.99, 180.00, 380.00, 10.00, 0.00, 0.00, 'Yes'),
(2, 4500.00, 4250.00, 4749.99, 202.50, 427.50, 10.00, 0.00, 0.00, 'Yes'),
(3, 5000.00, 4750.00, 5249.99, 225.00, 475.00, 10.00, 0.00, 0.00, 'Yes'),
(4, 5500.00, 5250.00, 5749.99, 247.50, 522.50, 10.00, 0.00, 0.00, 'Yes'),
(5, 6000.00, 5750.00, 6249.99, 270.00, 570.00, 10.00, 0.00, 0.00, 'Yes'),
(6, 6500.00, 6250.00, 6749.99, 292.50, 617.50, 10.00, 0.00, 0.00, 'Yes'),
(7, 7000.00, 6750.00, 7249.99, 315.00, 665.00, 10.00, 0.00, 0.00, 'Yes'),
(8, 7500.00, 7250.00, 7749.99, 337.50, 712.50, 10.00, 0.00, 0.00, 'Yes'),
(9, 8000.00, 7750.00, 8249.99, 360.00, 760.00, 10.00, 0.00, 0.00, 'Yes'),
(10, 8500.00, 8250.00, 8749.99, 382.50, 807.50, 10.00, 0.00, 0.00, 'Yes'),
(11, 9000.00, 8750.00, 9249.99, 405.00, 855.00, 10.00, 0.00, 0.00, 'Yes'),
(12, 9500.00, 9250.00, 9749.99, 427.50, 902.50, 10.00, 0.00, 0.00, 'Yes'),
(13, 10000.00, 9750.00, 10249.99, 450.00, 950.00, 10.00, 0.00, 0.00, 'Yes'),
(14, 10500.00, 10250.00, 10749.99, 472.50, 997.50, 10.00, 0.00, 0.00, 'Yes'),
(15, 11000.00, 10750.00, 11249.99, 495.00, 1045.00, 10.00, 0.00, 0.00, 'Yes'),
(16, 11500.00, 11250.00, 11749.99, 517.50, 1092.50, 10.00, 0.00, 0.00, 'Yes'),
(17, 12000.00, 11750.00, 12249.99, 540.00, 1140.00, 10.00, 0.00, 0.00, 'Yes'),
(18, 12500.00, 12250.00, 12749.99, 562.50, 1187.50, 10.00, 0.00, 0.00, 'Yes'),
(19, 13000.00, 12750.00, 13249.99, 585.00, 1235.00, 10.00, 0.00, 0.00, 'Yes'),
(20, 13500.00, 13250.00, 13749.99, 607.50, 1282.50, 10.00, 0.00, 0.00, 'Yes'),
(21, 14000.00, 13750.00, 14249.99, 630.00, 1330.00, 10.00, 0.00, 0.00, 'Yes'),
(22, 14500.00, 14250.00, 14749.99, 652.50, 1377.50, 10.00, 0.00, 0.00, 'Yes'),
(23, 15000.00, 14750.00, 15249.99, 675.00, 1425.00, 30.00, 0.00, 0.00, 'Yes'),
(24, 15500.00, 15250.00, 15749.99, 697.50, 1472.50, 30.00, 0.00, 0.00, 'Yes'),
(25, 16000.00, 15750.00, 16249.99, 720.00, 1520.00, 30.00, 0.00, 0.00, 'Yes'),
(26, 16500.00, 16250.00, 16749.99, 742.50, 1567.50, 30.00, 0.00, 0.00, 'Yes'),
(27, 17000.00, 16750.00, 17249.99, 765.00, 1615.00, 30.00, 0.00, 0.00, 'Yes'),
(28, 17500.00, 17250.00, 17749.99, 787.50, 1662.50, 30.00, 0.00, 0.00, 'Yes'),
(29, 18000.00, 17750.00, 18249.99, 810.00, 1710.00, 30.00, 0.00, 0.00, 'Yes'),
(30, 18500.00, 18250.00, 18749.99, 832.50, 1757.50, 30.00, 0.00, 0.00, 'Yes'),
(31, 19000.00, 18750.00, 19249.99, 855.00, 1805.00, 30.00, 0.00, 0.00, 'Yes'),
(32, 19500.00, 19250.00, 19749.99, 877.50, 1852.50, 30.00, 0.00, 0.00, 'Yes'),
(33, 20000.00, 19750.00, 20249.99, 900.00, 1900.00, 30.00, 0.00, 0.00, 'Yes'),
(34, 20000.00, 20250.00, 20749.99, 900.00, 1900.00, 30.00, 22.50, 47.50, 'Yes'),
(35, 20000.00, 20750.00, 21249.99, 900.00, 1900.00, 30.00, 45.00, 95.00, 'Yes'),
(36, 20000.00, 21250.00, 21749.99, 900.00, 1900.00, 30.00, 67.50, 142.50, 'Yes'),
(37, 20000.00, 21750.00, 22249.99, 900.00, 1900.00, 30.00, 90.00, 190.00, 'Yes'),
(38, 20000.00, 22250.00, 22749.99, 900.00, 1900.00, 30.00, 112.50, 237.50, 'Yes'),
(39, 20000.00, 22750.00, 23249.99, 900.00, 1900.00, 30.00, 135.00, 285.00, 'Yes'),
(40, 20000.00, 23250.00, 23749.99, 900.00, 1900.00, 30.00, 157.50, 332.50, 'Yes'),
(41, 20000.00, 23750.00, 24249.99, 900.00, 1900.00, 30.00, 180.00, 380.00, 'Yes'),
(42, 20000.00, 24250.00, 24749.99, 900.00, 1900.00, 30.00, 202.50, 427.50, 'Yes'),
(43, 20000.00, 24750.00, 25249.99, 900.00, 1900.00, 30.00, 225.00, 475.00, 'Yes'),
(44, 20000.00, 25250.00, 25749.99, 900.00, 1900.00, 30.00, 247.50, 522.50, 'Yes'),
(45, 20000.00, 25750.00, 26249.99, 900.00, 1900.00, 30.00, 270.00, 570.00, 'Yes'),
(46, 20000.00, 26250.00, 26749.99, 900.00, 1900.00, 30.00, 292.50, 617.50, 'Yes'),
(47, 20000.00, 26750.00, 27249.99, 900.00, 1900.00, 30.00, 315.00, 665.00, 'Yes'),
(48, 20000.00, 27250.00, 27749.99, 900.00, 1900.00, 30.00, 337.50, 712.50, 'Yes'),
(49, 20000.00, 27750.00, 28249.99, 900.00, 1900.00, 30.00, 360.00, 760.00, 'Yes'),
(50, 20000.00, 28250.00, 28749.99, 900.00, 1900.00, 30.00, 382.50, 807.50, 'Yes'),
(51, 20000.00, 28750.00, 29249.99, 900.00, 1900.00, 30.00, 405.00, 855.00, 'Yes'),
(52, 20000.00, 29250.00, 29749.99, 900.00, 1900.00, 30.00, 427.50, 902.50, 'Yes'),
(53, 20000.00, 29750.00, 999999.00, 900.00, 1900.00, 30.00, 450.00, 950.00, 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7NEN90MB1X7387VSFuFyr4gl8vpkQCDVeQ0C9N5T', NULL, '192.168.1.66', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoielZNcWJNTzg1d1UzS3JxT0o4dmJNQ1Rub2lhaUFKbUN3Q2dDTk5QUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xOTIuMTY4LjEuNjY6ODAwMC9tYW5hZ2VyL2FjdGl2aXRpZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1622700257),
('tdjoVkBTnv2riIaxK9CrJvQ90WELo0jLzXva2tIH', NULL, '192.168.1.220', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWVTQVNmcUJPV1ZiSTR1YlNrb2xNRUtMcGV2cFk5WUt1YmdqU0JrNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xOTIuMTY4LjEuNjY6ODAwMC9tYW5hZ2VyL2FjdGl2aXRpZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1622698550);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_employee_payslip`
--

CREATE TABLE IF NOT EXISTS `tmp_employee_payslip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `field` varchar(180) NOT NULL,
  `amount` float NOT NULL,
  `created` datetime NOT NULL,
  `year` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zk_device`
--

CREATE TABLE IF NOT EXISTS `zk_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connection_status` varchar(32) NOT NULL,
  `machine_no` int(11) NOT NULL,
  `device_name` varchar(128) NOT NULL,
  `ip_address` varchar(64) NOT NULL,
  `port` int(11) NOT NULL,
  `serial_number` varchar(128) NOT NULL,
  `product_name` varchar(64) NOT NULL,
  `total_user` int(11) NOT NULL,
  `total_logs` int(11) NOT NULL,
  `total_fp` int(11) NOT NULL,
  `total_password` int(11) NOT NULL,
  `total_admin` int(11) NOT NULL,
  `total_fc` int(11) NOT NULL,
  `project_site_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zk_device_logs`
--

CREATE TABLE IF NOT EXISTS `zk_device_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_log` varchar(64) NOT NULL,
  `time_log` time NOT NULL,
  `log_type` varchar(64) NOT NULL,
  `is_sync` int(11) NOT NULL,
  `verification_method` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zk_user_card_pw`
--

CREATE TABLE IF NOT EXISTS `zk_user_card_pw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_no` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `employee_name` varchar(64) NOT NULL,
  `rfid` varchar(64) NOT NULL,
  `spassword` varchar(64) NOT NULL,
  `sEnabled` int(11) NOT NULL,
  `sprivilege` int(11) NOT NULL,
  `verify_style` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `zk_user_fingerprint`
--

CREATE TABLE IF NOT EXISTS `zk_user_fingerprint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `employee_name` varchar(64) NOT NULL,
  `isize` int(11) NOT NULL,
  `finger_index` int(11) NOT NULL,
  `template` varchar(526) NOT NULL,
  `privilege` int(11) NOT NULL,
  `enabled` varchar(64) NOT NULL,
  `iflag` varchar(64) NOT NULL,
  `source` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
