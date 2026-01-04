-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 01:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cse370_pqlr_airway`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Ahmad Raza', 'ahmad.raza@airline.com', '$2y$10$eImiTXuWVxfM37uY4JANjO.o5.A.11.V/I11', '2026-01-02 22:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `aircraft`
--

CREATE TABLE `aircraft` (
  `aircraft_id` int(11) NOT NULL,
  `tail_num` varchar(20) NOT NULL,
  `model` varchar(100) NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aircraft`
--

INSERT INTO `aircraft` (`aircraft_id`, `tail_num`, `model`, `template_id`, `last_update`) VALUES
(4, '3', 'MOTOR', 10, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `airport`
--

CREATE TABLE `airport` (
  `airport_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `country` varchar(10) NOT NULL,
  `city` varchar(20) NOT NULL,
  `IATA_Code` varchar(4) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `airport`
--

INSERT INTO `airport` (`airport_id`, `name`, `country`, `city`, `IATA_Code`, `longitude`, `latitude`) VALUES
(10, 'King Abdul Aziz Airp', 'Saudi Arab', 'Jeddah', 'KSA', 90.25, 80.25);

-- --------------------------------------------------------

--
-- Table structure for table `baggage`
--

CREATE TABLE `baggage` (
  `baggage_id` int(11) NOT NULL,
  `flight_id` int(11) DEFAULT NULL,
  `passenger_name` varchar(255) NOT NULL,
  `tag_number` varchar(50) NOT NULL,
  `status` enum('Checked-in','In Transit','Arrived','Claimed','Lost','Damaged') DEFAULT 'Checked-in',
  `weight` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baggage`
--

INSERT INTO `baggage` (`baggage_id`, `flight_id`, `passenger_name`, `tag_number`, `status`, `weight`, `created_at`) VALUES
(1, 12, 'fai', '4586', 'Damaged', 30.00, '2026-01-03 22:52:26'),
(2, 12, 'fai', '5896', 'Checked-in', 30.00, '2026-01-03 22:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `baggage_claims`
--

CREATE TABLE `baggage_claims` (
  `claim_id` int(11) NOT NULL,
  `baggage_id` int(11) DEFAULT NULL,
  `claim_type` enum('Lost','Damaged') NOT NULL,
  `description` text DEFAULT NULL,
  `claim_status` enum('Pending','Investigating','Resolved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baggage_claims`
--

INSERT INTO `baggage_claims` (`claim_id`, `baggage_id`, `claim_type`, `description`, `claim_status`, `created_at`) VALUES
(1, 1, 'Lost', 'laaa', 'Pending', '2026-01-03 22:52:38');

-- --------------------------------------------------------

--
-- Table structure for table `blacklist`
--

CREATE TABLE `blacklist` (
  `blacklist_id` int(11) NOT NULL,
  `passenger_name` varchar(255) NOT NULL,
  `passport_number` varchar(50) NOT NULL,
  `reason` text DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blacklist`
--

INSERT INTO `blacklist` (`blacklist_id`, `passenger_name`, `passport_number`, `reason`, `added_date`) VALUES
(2, 'bvfjkdv', '147852369D', 'hacking', '2026-01-03 22:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `booking_status` enum('Confirmed','Pending','Cancelled') DEFAULT 'Pending',
  `booking_date` date NOT NULL,
  `template_id` int(11) NOT NULL,
  `seat_label` varchar(5) NOT NULL,
  `flight_id` int(11) DEFAULT NULL,
  `passenger_id` int(11) DEFAULT NULL,
  `price_id` int(11) DEFAULT NULL,
  `rule_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `booking_status`, `booking_date`, `template_id`, `seat_label`, `flight_id`, `passenger_id`, `price_id`, `rule_id`) VALUES
(1, 'Pending', '2026-01-03', 10, '10A', 11, 1, 1, 1),
(3, 'Pending', '2026-01-04', 20, '20A', 11, 5, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `crew`
--

CREATE TABLE `crew` (
  `crew_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `role` enum('Pilot','Co-Pilot','Cabin Crew','Engineer') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `grad_uni` varchar(255) DEFAULT NULL,
  `training_year` int(11) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `language_skills` varchar(255) DEFAULT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `certification_status` varchar(100) DEFAULT NULL,
  `availability_status` tinyint(1) DEFAULT 1,
  `marital_status` varchar(50) DEFAULT NULL,
  `family_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crew`
--

INSERT INTO `crew` (`crew_id`, `name`, `age`, `role`, `phone`, `salary`, `experience_years`, `grad_uni`, `training_year`, `nationality`, `language_skills`, `license_number`, `certification_status`, `availability_status`, `marital_status`, `family_address`) VALUES
(1, 'yoyo', 40, 'Co-Pilot', '14785236998', 30000.00, 5, 'Brac University', 5, 'Bangladeshi', 'English, Bangla', '485796', NULL, 0, 'Married', 'lalala/lalala/85 road');

-- --------------------------------------------------------

--
-- Table structure for table `crew_assignment`
--

CREATE TABLE `crew_assignment` (
  `assignment_id` int(11) NOT NULL,
  `crew_id` int(11) DEFAULT NULL,
  `flight_id` int(11) DEFAULT NULL,
  `shift_start` datetime DEFAULT NULL,
  `shift_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crew_assignment`
--

INSERT INTO `crew_assignment` (`assignment_id`, `crew_id`, `flight_id`, `shift_start`, `shift_end`) VALUES
(1, 1, 12, '2026-01-04 05:05:00', '2026-01-04 13:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `delay`
--

CREATE TABLE `delay` (
  `delay_id` int(8) NOT NULL,
  `flight_id` int(7) NOT NULL,
  `duration` time NOT NULL,
  `reason_code` int(10) NOT NULL,
  `start_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delay`
--

INSERT INTO `delay` (`delay_id`, `flight_id`, `duration`, `reason_code`, `start_time`) VALUES
(1, 11, '00:00:00', 0, '08:50:00'),
(2, 12, '00:00:00', 0, '09:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `delay_reason`
--

CREATE TABLE `delay_reason` (
  `reason_code` int(11) NOT NULL,
  `description` varchar(80) NOT NULL,
  `category` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delay_reason`
--

INSERT INTO `delay_reason` (`reason_code`, `description`, `category`) VALUES
(1, 'blblblblblblblblaa', 'Weather'),
(2, 'wowwww', 'Security');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_reports`
--

CREATE TABLE `emergency_reports` (
  `report_id` int(11) NOT NULL,
  `flight_id` int(11) DEFAULT NULL,
  `incident_type` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `reported_by` varchar(255) DEFAULT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `flight_id` int(8) NOT NULL,
  `flight_number` varchar(7) NOT NULL,
  `standard_dep_time` time NOT NULL,
  `standard_arr_time` time NOT NULL,
  `source` varchar(60) NOT NULL,
  `destination` varchar(60) NOT NULL,
  `gate` varchar(6) NOT NULL,
  `fl_status` text NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp(),
  `airport_id` int(11) NOT NULL,
  `aircraft_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flight_id`, `flight_number`, `standard_dep_time`, `standard_arr_time`, `source`, `destination`, `gate`, `fl_status`, `last_updated`, `airport_id`, `aircraft_id`, `scheduled_date`) VALUES
(11, '1', '08:50:00', '12:12:00', 'banglore', 'thailand', 'G-6', 'Delayed', '2026-01-03 18:44:29', 10, 4, '2026-01-06'),
(12, '2', '09:30:00', '14:00:00', 'london', 'usa', 'G-6', 'Delayed', '2026-01-03 19:26:04', 10, 4, '2026-01-05');

-- --------------------------------------------------------

--
-- Table structure for table `flight_delay`
--

CREATE TABLE `flight_delay` (
  `delay_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `reason_code` varchar(10) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight_delay`
--

INSERT INTO `flight_delay` (`delay_id`, `flight_id`, `duration`, `reason_code`, `start_time`) VALUES
(2, 11, 'TBD', 'PENDING', '0000-00-00 00:00:00'),
(3, 12, 'TBD', 'PENDING', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `flight_track`
--

CREATE TABLE `flight_track` (
  `pt_id` int(11) NOT NULL,
  `flight_id` int(8) NOT NULL,
  `speed` smallint(5) UNSIGNED NOT NULL,
  `altitude` mediumint(8) UNSIGNED NOT NULL,
  `longtitude` decimal(11,8) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `timestamp` datetime(3) NOT NULL,
  `pt_status` varchar(20) NOT NULL,
  `heading` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_logs`
--

CREATE TABLE `maintenance_logs` (
  `log_id` int(11) NOT NULL,
  `aircraft_id` int(11) DEFAULT NULL,
  `maintenance_type` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `maintenance_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `passenger`
--

CREATE TABLE `passenger` (
  `passenger_id` int(11) NOT NULL,
  `passenger_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passport_number` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `d_o_b` datetime NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passenger`
--

INSERT INTO `passenger` (`passenger_id`, `passenger_name`, `email`, `passport_number`, `phone`, `d_o_b`, `status`) VALUES
(1, 'ggggggg', 'fff@email.com', '147852369D', '14785236998', '2026-02-04 00:00:00', 'Active'),
(2, 'bvfjkdv', 'ekjsl@yahoo.com', 'vjnkzxfbv4582', '458745866', '1987-03-24 00:00:00', 'Blacklisted'),
(5, 'fai', 'fai@gmail.com', '123456789', '123456789', '2007-11-21 00:00:00', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `pay_status` varchar(20) NOT NULL DEFAULT 'In_Review',
  `transaction_ref` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `confirmed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `booking_id`, `amount`, `pay_status`, `transaction_ref`, `admin_id`, `confirmed_at`) VALUES
(5, 6, 450.00, 'Confirmed', 'TXN-001-TEST', 1, '2026-01-02 17:16:39'),
(6, 3, 500.00, 'In_Review', 'TXN-2026-08735DFC', 1, '2026-01-04 00:13:46');

-- --------------------------------------------------------

--
-- Table structure for table `pricing_rule`
--

CREATE TABLE `pricing_rule` (
  `rule_id` int(11) NOT NULL,
  `rule_name` varchar(50) NOT NULL,
  `st_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `st_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `multiplier` decimal(3,2) NOT NULL,
  `priority_check` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pricing_rule`
--

INSERT INTO `pricing_rule` (`rule_id`, `rule_name`, `st_date`, `end_date`, `st_time`, `end_time`, `multiplier`, `priority_check`) VALUES
(1, 'Morning Rush', NULL, NULL, '05:00:00', '09:00:00', 1.25, 10),
(2, 'Evening Rush', NULL, NULL, '18:00:00', '21:00:00', 1.20, 10),
(3, 'Winter Season', '2026-01-01', '2026-02-28', NULL, NULL, 0.85, 2),
(4, 'Summer Season', '2026-03-29', '2026-08-24', NULL, NULL, 1.35, 5),
(5, 'New Years Peak', '2026-01-01', '2026-01-04', NULL, NULL, 1.60, 30),
(6, 'Last Minute', NULL, NULL, NULL, NULL, 1.80, 50),
(7, 'Regular Rate', '2026-01-01', '2026-12-31', NULL, NULL, 1.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `seat_template`
--

CREATE TABLE `seat_template` (
  `template_id` int(11) NOT NULL,
  `seat_label` varchar(5) NOT NULL,
  `class` varchar(20) NOT NULL,
  `capacity` smallint(6) NOT NULL,
  `base_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seat_template`
--

INSERT INTO `seat_template` (`template_id`, `seat_label`, `class`, `capacity`, `base_price`) VALUES
(10, '10A', 'Economy', 180, 200.00),
(10, '10B', 'Economy', 180, 200.00),
(10, '10C', 'Economy', 180, 200.00),
(10, '10D', 'Economy', 180, 200.00),
(10, '10E', 'Economy', 180, 200.00),
(10, '10F', 'Economy', 180, 200.00),
(10, '11A', 'Economy', 180, 200.00),
(10, '11B', 'Economy', 180, 200.00),
(10, '11C', 'Economy', 180, 200.00),
(10, '11D', 'Economy', 180, 200.00),
(10, '11E', 'Economy', 180, 200.00),
(10, '11F', 'Economy', 180, 200.00),
(10, '12A', 'Economy', 180, 200.00),
(10, '12B', 'Economy', 180, 200.00),
(10, '12C', 'Economy', 180, 200.00),
(10, '12D', 'Economy', 180, 200.00),
(10, '12E', 'Economy', 180, 200.00),
(10, '12F', 'Economy', 180, 200.00),
(10, '14A', 'Economy', 180, 200.00),
(10, '14B', 'Economy', 180, 200.00),
(10, '14C', 'Economy', 180, 200.00),
(10, '14D', 'Economy', 180, 200.00),
(10, '14E', 'Economy', 180, 200.00),
(10, '14F', 'Economy', 180, 200.00),
(10, '15A', 'Economy', 180, 200.00),
(10, '15B', 'Economy', 180, 200.00),
(10, '15C', 'Economy', 180, 200.00),
(10, '15D', 'Economy', 180, 200.00),
(10, '15E', 'Economy', 180, 200.00),
(10, '15F', 'Economy', 180, 200.00),
(10, '16A', 'Economy', 180, 200.00),
(10, '16B', 'Economy', 180, 200.00),
(10, '16C', 'Economy', 180, 200.00),
(10, '16D', 'Economy', 180, 200.00),
(10, '16E', 'Economy', 180, 200.00),
(10, '16F', 'Economy', 180, 200.00),
(10, '17A', 'Economy', 180, 200.00),
(10, '17B', 'Economy', 180, 200.00),
(10, '17C', 'Economy', 180, 200.00),
(10, '17D', 'Economy', 180, 200.00),
(10, '17E', 'Economy', 180, 200.00),
(10, '17F', 'Economy', 180, 200.00),
(10, '18A', 'Economy', 180, 200.00),
(10, '18B', 'Economy', 180, 200.00),
(10, '18C', 'Economy', 180, 200.00),
(10, '18D', 'Economy', 180, 200.00),
(10, '18E', 'Economy', 180, 200.00),
(10, '18F', 'Economy', 180, 200.00),
(10, '19A', 'Economy', 180, 200.00),
(10, '19B', 'Economy', 180, 200.00),
(10, '19C', 'Economy', 180, 200.00),
(10, '19D', 'Economy', 180, 200.00),
(10, '19E', 'Economy', 180, 200.00),
(10, '19F', 'Economy', 180, 200.00),
(10, '1A', 'Business', 180, 500.00),
(10, '1B', 'Business', 180, 500.00),
(10, '1C', 'Business', 180, 500.00),
(10, '1D', 'Business', 180, 500.00),
(10, '1E', 'Business', 180, 500.00),
(10, '1F', 'Business', 180, 500.00),
(10, '20A', 'Economy', 180, 200.00),
(10, '20B', 'Economy', 180, 200.00),
(10, '20C', 'Economy', 180, 200.00),
(10, '20D', 'Economy', 180, 200.00),
(10, '20E', 'Economy', 180, 200.00),
(10, '20F', 'Economy', 180, 200.00),
(10, '21A', 'Economy', 180, 200.00),
(10, '21B', 'Economy', 180, 200.00),
(10, '21C', 'Economy', 180, 200.00),
(10, '21D', 'Economy', 180, 200.00),
(10, '21E', 'Economy', 180, 200.00),
(10, '21F', 'Economy', 180, 200.00),
(10, '22A', 'Economy', 180, 200.00),
(10, '22B', 'Economy', 180, 200.00),
(10, '22C', 'Economy', 180, 200.00),
(10, '22D', 'Economy', 180, 200.00),
(10, '22E', 'Economy', 180, 200.00),
(10, '22F', 'Economy', 180, 200.00),
(10, '23A', 'Economy', 180, 200.00),
(10, '23B', 'Economy', 180, 200.00),
(10, '23C', 'Economy', 180, 200.00),
(10, '23D', 'Economy', 180, 200.00),
(10, '23E', 'Economy', 180, 200.00),
(10, '23F', 'Economy', 180, 200.00),
(10, '24A', 'Economy', 180, 200.00),
(10, '24B', 'Economy', 180, 200.00),
(10, '24C', 'Economy', 180, 200.00),
(10, '24D', 'Economy', 180, 200.00),
(10, '24E', 'Economy', 180, 200.00),
(10, '24F', 'Economy', 180, 200.00),
(10, '25A', 'Economy', 180, 200.00),
(10, '25B', 'Economy', 180, 200.00),
(10, '25C', 'Economy', 180, 200.00),
(10, '25D', 'Economy', 180, 200.00),
(10, '25E', 'Economy', 180, 200.00),
(10, '25F', 'Economy', 180, 200.00),
(10, '26A', 'Economy', 180, 200.00),
(10, '26B', 'Economy', 180, 200.00),
(10, '26C', 'Economy', 180, 200.00),
(10, '26D', 'Economy', 180, 200.00),
(10, '26E', 'Economy', 180, 200.00),
(10, '26F', 'Economy', 180, 200.00),
(10, '27A', 'Economy', 180, 200.00),
(10, '27B', 'Economy', 180, 200.00),
(10, '27C', 'Economy', 180, 200.00),
(10, '27D', 'Economy', 180, 200.00),
(10, '27E', 'Economy', 180, 200.00),
(10, '27F', 'Economy', 180, 200.00),
(10, '28A', 'Economy', 180, 200.00),
(10, '28B', 'Economy', 180, 200.00),
(10, '28C', 'Economy', 180, 200.00),
(10, '28D', 'Economy', 180, 200.00),
(10, '28E', 'Economy', 180, 200.00),
(10, '28F', 'Economy', 180, 200.00),
(10, '29A', 'Economy', 180, 200.00),
(10, '29B', 'Economy', 180, 200.00),
(10, '29C', 'Economy', 180, 200.00),
(10, '29D', 'Economy', 180, 200.00),
(10, '29E', 'Economy', 180, 200.00),
(10, '29F', 'Economy', 180, 200.00),
(10, '2A', 'Business', 180, 500.00),
(10, '2B', 'Business', 180, 500.00),
(10, '2C', 'Business', 180, 500.00),
(10, '2D', 'Business', 180, 500.00),
(10, '2E', 'Business', 180, 500.00),
(10, '2F', 'Business', 180, 500.00),
(10, '30A', 'Economy', 180, 200.00),
(10, '30B', 'Economy', 180, 200.00),
(10, '30C', 'Economy', 180, 200.00),
(10, '30D', 'Economy', 180, 200.00),
(10, '30E', 'Economy', 180, 200.00),
(10, '30F', 'Economy', 180, 200.00),
(10, '3A', 'Business', 180, 500.00),
(10, '3B', 'Business', 180, 500.00),
(10, '3C', 'Business', 180, 500.00),
(10, '3D', 'Business', 180, 500.00),
(10, '3E', 'Business', 180, 500.00),
(10, '3F', 'Business', 180, 500.00),
(10, '4A', 'Economy', 180, 200.00),
(10, '4B', 'Economy', 180, 200.00),
(10, '4C', 'Economy', 180, 200.00),
(10, '4D', 'Economy', 180, 200.00),
(10, '4E', 'Economy', 180, 200.00),
(10, '4F', 'Economy', 180, 200.00),
(10, '5A', 'Economy', 180, 200.00),
(10, '5B', 'Economy', 180, 200.00),
(10, '5C', 'Economy', 180, 200.00),
(10, '5D', 'Economy', 180, 200.00),
(10, '5E', 'Economy', 180, 200.00),
(10, '5F', 'Economy', 180, 200.00),
(10, '6A', 'Economy', 180, 200.00),
(10, '6B', 'Economy', 180, 200.00),
(10, '6C', 'Economy', 180, 200.00),
(10, '6D', 'Economy', 180, 200.00),
(10, '6E', 'Economy', 180, 200.00),
(10, '6F', 'Economy', 180, 200.00),
(10, '7A', 'Economy', 180, 200.00),
(10, '7B', 'Economy', 180, 200.00),
(10, '7C', 'Economy', 180, 200.00),
(10, '7D', 'Economy', 180, 200.00),
(10, '7E', 'Economy', 180, 200.00),
(10, '7F', 'Economy', 180, 200.00),
(10, '8A', 'Economy', 180, 200.00),
(10, '8B', 'Economy', 180, 200.00),
(10, '8C', 'Economy', 180, 200.00),
(10, '8D', 'Economy', 180, 200.00),
(10, '8E', 'Economy', 180, 200.00),
(10, '8F', 'Economy', 180, 200.00),
(10, '9A', 'Economy', 180, 200.00),
(10, '9B', 'Economy', 180, 200.00),
(10, '9C', 'Economy', 180, 200.00),
(10, '9D', 'Economy', 180, 200.00),
(10, '9E', 'Economy', 180, 200.00),
(10, '9F', 'Economy', 180, 200.00),
(20, '10A', 'Economy', 60, 200.00),
(20, '10B', 'Economy', 60, 200.00),
(20, '10C', 'Economy', 60, 200.00),
(20, '10D', 'Economy', 60, 200.00),
(20, '11A', 'Economy', 60, 200.00),
(20, '11B', 'Economy', 60, 200.00),
(20, '11C', 'Economy', 60, 200.00),
(20, '11D', 'Economy', 60, 200.00),
(20, '12A', 'Economy', 60, 200.00),
(20, '12B', 'Economy', 60, 200.00),
(20, '12C', 'Economy', 60, 200.00),
(20, '12D', 'Economy', 60, 200.00),
(20, '14A', 'Economy', 60, 200.00),
(20, '14B', 'Economy', 60, 200.00),
(20, '14C', 'Economy', 60, 200.00),
(20, '14D', 'Economy', 60, 200.00),
(20, '15A', 'Economy', 60, 200.00),
(20, '15B', 'Economy', 60, 200.00),
(20, '15C', 'Economy', 60, 200.00),
(20, '15D', 'Economy', 60, 200.00),
(20, '1A', 'Business', 60, 500.00),
(20, '1B', 'Business', 60, 500.00),
(20, '1C', 'Business', 60, 500.00),
(20, '1D', 'Business', 60, 500.00),
(20, '2A', 'Business', 60, 500.00),
(20, '2B', 'Business', 60, 500.00),
(20, '2C', 'Business', 60, 500.00),
(20, '2D', 'Business', 60, 500.00),
(20, '3A', 'Economy', 60, 200.00),
(20, '3B', 'Economy', 60, 200.00),
(20, '3C', 'Economy', 60, 200.00),
(20, '3D', 'Economy', 60, 200.00),
(20, '4A', 'Economy', 60, 200.00),
(20, '4B', 'Economy', 60, 200.00),
(20, '4C', 'Economy', 60, 200.00),
(20, '4D', 'Economy', 60, 200.00),
(20, '5A', 'Economy', 60, 200.00),
(20, '5B', 'Economy', 60, 200.00),
(20, '5C', 'Economy', 60, 200.00),
(20, '5D', 'Economy', 60, 200.00),
(20, '6A', 'Economy', 60, 200.00),
(20, '6B', 'Economy', 60, 200.00),
(20, '6C', 'Economy', 60, 200.00),
(20, '6D', 'Economy', 60, 200.00),
(20, '7A', 'Economy', 60, 200.00),
(20, '7B', 'Economy', 60, 200.00),
(20, '7C', 'Economy', 60, 200.00),
(20, '7D', 'Economy', 60, 200.00),
(20, '8A', 'Economy', 60, 200.00),
(20, '8B', 'Economy', 60, 200.00),
(20, '8C', 'Economy', 60, 200.00),
(20, '8D', 'Economy', 60, 200.00),
(20, '9A', 'Economy', 60, 200.00),
(20, '9B', 'Economy', 60, 200.00),
(20, '9C', 'Economy', 60, 200.00),
(20, '9D', 'Economy', 60, 200.00),
(30, '10A', 'Economy', 100, 200.00),
(30, '10B', 'Economy', 100, 200.00),
(30, '10C', 'Economy', 100, 200.00),
(30, '10D', 'Economy', 100, 200.00),
(30, '10E', 'Economy', 100, 200.00),
(30, '11A', 'Economy', 100, 200.00),
(30, '11B', 'Economy', 100, 200.00),
(30, '11C', 'Economy', 100, 200.00),
(30, '11D', 'Economy', 100, 200.00),
(30, '11E', 'Economy', 100, 200.00),
(30, '12A', 'Economy', 100, 200.00),
(30, '12B', 'Economy', 100, 200.00),
(30, '12C', 'Economy', 100, 200.00),
(30, '12D', 'Economy', 100, 200.00),
(30, '12E', 'Economy', 100, 200.00),
(30, '14A', 'Economy', 100, 200.00),
(30, '14B', 'Economy', 100, 200.00),
(30, '14C', 'Economy', 100, 200.00),
(30, '14D', 'Economy', 100, 200.00),
(30, '14E', 'Economy', 100, 200.00),
(30, '15A', 'Economy', 100, 200.00),
(30, '15B', 'Economy', 100, 200.00),
(30, '15C', 'Economy', 100, 200.00),
(30, '15D', 'Economy', 100, 200.00),
(30, '15E', 'Economy', 100, 200.00),
(30, '16A', 'Economy', 100, 200.00),
(30, '16B', 'Economy', 100, 200.00),
(30, '16C', 'Economy', 100, 200.00),
(30, '16D', 'Economy', 100, 200.00),
(30, '16E', 'Economy', 100, 200.00),
(30, '17A', 'Economy', 100, 200.00),
(30, '17B', 'Economy', 100, 200.00),
(30, '17C', 'Economy', 100, 200.00),
(30, '17D', 'Economy', 100, 200.00),
(30, '17E', 'Economy', 100, 200.00),
(30, '18A', 'Economy', 100, 200.00),
(30, '18B', 'Economy', 100, 200.00),
(30, '18C', 'Economy', 100, 200.00),
(30, '18D', 'Economy', 100, 200.00),
(30, '18E', 'Economy', 100, 200.00),
(30, '19A', 'Economy', 100, 200.00),
(30, '19B', 'Economy', 100, 200.00),
(30, '19C', 'Economy', 100, 200.00),
(30, '19D', 'Economy', 100, 200.00),
(30, '19E', 'Economy', 100, 200.00),
(30, '1A', 'Business', 100, 500.00),
(30, '1B', 'Business', 100, 500.00),
(30, '1C', 'Business', 100, 500.00),
(30, '1D', 'Business', 100, 500.00),
(30, '1E', 'Business', 100, 500.00),
(30, '20A', 'Economy', 100, 200.00),
(30, '20B', 'Economy', 100, 200.00),
(30, '20C', 'Economy', 100, 200.00),
(30, '20D', 'Economy', 100, 200.00),
(30, '20E', 'Economy', 100, 200.00),
(30, '2A', 'Business', 100, 500.00),
(30, '2B', 'Business', 100, 500.00),
(30, '2C', 'Business', 100, 500.00),
(30, '2D', 'Business', 100, 500.00),
(30, '2E', 'Business', 100, 500.00),
(30, '3A', 'Business', 100, 500.00),
(30, '3B', 'Business', 100, 500.00),
(30, '3C', 'Business', 100, 500.00),
(30, '3D', 'Business', 100, 500.00),
(30, '3E', 'Business', 100, 500.00),
(30, '4A', 'Economy', 100, 200.00),
(30, '4B', 'Economy', 100, 200.00),
(30, '4C', 'Economy', 100, 200.00),
(30, '4D', 'Economy', 100, 200.00),
(30, '4E', 'Economy', 100, 200.00),
(30, '5A', 'Economy', 100, 200.00),
(30, '5B', 'Economy', 100, 200.00),
(30, '5C', 'Economy', 100, 200.00),
(30, '5D', 'Economy', 100, 200.00),
(30, '5E', 'Economy', 100, 200.00),
(30, '6A', 'Economy', 100, 200.00),
(30, '6B', 'Economy', 100, 200.00),
(30, '6C', 'Economy', 100, 200.00),
(30, '6D', 'Economy', 100, 200.00),
(30, '6E', 'Economy', 100, 200.00),
(30, '7A', 'Economy', 100, 200.00),
(30, '7B', 'Economy', 100, 200.00),
(30, '7C', 'Economy', 100, 200.00),
(30, '7D', 'Economy', 100, 200.00),
(30, '7E', 'Economy', 100, 200.00),
(30, '8A', 'Economy', 100, 200.00),
(30, '8B', 'Economy', 100, 200.00),
(30, '8C', 'Economy', 100, 200.00),
(30, '8D', 'Economy', 100, 200.00),
(30, '8E', 'Economy', 100, 200.00),
(30, '9A', 'Economy', 100, 200.00),
(30, '9B', 'Economy', 100, 200.00),
(30, '9C', 'Economy', 100, 200.00),
(30, '9D', 'Economy', 100, 200.00),
(30, '9E', 'Economy', 100, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `security_passengers`
--

CREATE TABLE `security_passengers` (
  `passenger_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `passport_number` varchar(50) NOT NULL,
  `residing_country` varchar(100) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `loyalty_program` tinyint(1) DEFAULT 0,
  `boarding_status` enum('Pending','Checked-in','Boarded') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transit`
--

CREATE TABLE `transit` (
  `transit_id` int(11) NOT NULL,
  `flight_leg` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `flight_crew_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(130) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `role` enum('admin','passenger','stuff') NOT NULL DEFAULT 'passenger'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `pwd`, `email`, `role`) VALUES
(1, 'ann', '$2y$12$NqTq3ckJxOzaOA02w/DpHOIzB2hcCf51pRqpZr7sr5G7eC.u6vcnm', 'ann@gmail.com', 'stuff'),
(2, 'manon', '$2y$12$E./ti3adVbZLtOzY8vVpJuc8NxIoKLMAXeUYXzwCsOvRYXnXJ8IwO', 'manon@gmail.com', 'passenger'),
(3, 'affy', '$2y$12$ZynDj8PUo1OA/pzYrwBoR.FNWyFjoLFBkqHkietcb.xUmQW/dUDzG', 'affy@gmail.com', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `aircraft`
--
ALTER TABLE `aircraft`
  ADD PRIMARY KEY (`aircraft_id`),
  ADD UNIQUE KEY `tail_num` (`tail_num`),
  ADD KEY `template_id` (`template_id`);

--
-- Indexes for table `airport`
--
ALTER TABLE `airport`
  ADD PRIMARY KEY (`airport_id`);

--
-- Indexes for table `baggage`
--
ALTER TABLE `baggage`
  ADD PRIMARY KEY (`baggage_id`),
  ADD UNIQUE KEY `tag_number` (`tag_number`);

--
-- Indexes for table `baggage_claims`
--
ALTER TABLE `baggage_claims`
  ADD PRIMARY KEY (`claim_id`),
  ADD KEY `baggage_id` (`baggage_id`);

--
-- Indexes for table `blacklist`
--
ALTER TABLE `blacklist`
  ADD PRIMARY KEY (`blacklist_id`),
  ADD UNIQUE KEY `passport_number` (`passport_number`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD UNIQUE KEY `template_id` (`template_id`,`seat_label`) USING BTREE,
  ADD KEY `flight_id` (`flight_id`),
  ADD KEY `passenger_id` (`passenger_id`),
  ADD KEY `price_id` (`price_id`),
  ADD KEY `rule_id` (`rule_id`);

--
-- Indexes for table `crew`
--
ALTER TABLE `crew`
  ADD PRIMARY KEY (`crew_id`);

--
-- Indexes for table `crew_assignment`
--
ALTER TABLE `crew_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `crew_id` (`crew_id`);

--
-- Indexes for table `delay`
--
ALTER TABLE `delay`
  ADD PRIMARY KEY (`delay_id`),
  ADD KEY `flight_id` (`flight_id`);

--
-- Indexes for table `delay_reason`
--
ALTER TABLE `delay_reason`
  ADD PRIMARY KEY (`reason_code`);

--
-- Indexes for table `emergency_reports`
--
ALTER TABLE `emergency_reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`flight_id`),
  ADD KEY `aircraft_id` (`aircraft_id`),
  ADD KEY `airport_id` (`airport_id`);

--
-- Indexes for table `flight_delay`
--
ALTER TABLE `flight_delay`
  ADD PRIMARY KEY (`delay_id`),
  ADD KEY `flight_id` (`flight_id`);

--
-- Indexes for table `flight_track`
--
ALTER TABLE `flight_track`
  ADD KEY `flight_id` (`flight_id`);

--
-- Indexes for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `aircraft_id` (`aircraft_id`);

--
-- Indexes for table `passenger`
--
ALTER TABLE `passenger`
  ADD PRIMARY KEY (`passenger_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `pricing_rule`
--
ALTER TABLE `pricing_rule`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `seat_template`
--
ALTER TABLE `seat_template`
  ADD PRIMARY KEY (`template_id`,`seat_label`);

--
-- Indexes for table `security_passengers`
--
ALTER TABLE `security_passengers`
  ADD PRIMARY KEY (`passenger_id`),
  ADD UNIQUE KEY `passport_number` (`passport_number`);

--
-- Indexes for table `transit`
--
ALTER TABLE `transit`
  ADD PRIMARY KEY (`transit_id`),
  ADD KEY `flight_id` (`flight_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `aircraft`
--
ALTER TABLE `aircraft`
  MODIFY `aircraft_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `airport`
--
ALTER TABLE `airport`
  MODIFY `airport_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `baggage`
--
ALTER TABLE `baggage`
  MODIFY `baggage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `baggage_claims`
--
ALTER TABLE `baggage_claims`
  MODIFY `claim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blacklist`
--
ALTER TABLE `blacklist`
  MODIFY `blacklist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `crew`
--
ALTER TABLE `crew`
  MODIFY `crew_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `crew_assignment`
--
ALTER TABLE `crew_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delay`
--
ALTER TABLE `delay`
  MODIFY `delay_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delay_reason`
--
ALTER TABLE `delay_reason`
  MODIFY `reason_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `emergency_reports`
--
ALTER TABLE `emergency_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flight_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `flight_delay`
--
ALTER TABLE `flight_delay`
  MODIFY `delay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passenger`
--
ALTER TABLE `passenger`
  MODIFY `passenger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pricing_rule`
--
ALTER TABLE `pricing_rule`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `security_passengers`
--
ALTER TABLE `security_passengers`
  MODIFY `passenger_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transit`
--
ALTER TABLE `transit`
  MODIFY `transit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aircraft`
--
ALTER TABLE `aircraft`
  ADD CONSTRAINT `aircraft_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `seat_template` (`template_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `baggage_claims`
--
ALTER TABLE `baggage_claims`
  ADD CONSTRAINT `baggage_claims_ibfk_1` FOREIGN KEY (`baggage_id`) REFERENCES `baggage` (`baggage_id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`flight_id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`passenger_id`) REFERENCES `passenger` (`passenger_id`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`price_id`) REFERENCES `pricing_rule` (`rule_id`),
  ADD CONSTRAINT `booking_ibfk_4` FOREIGN KEY (`rule_id`) REFERENCES `pricing_rule` (`rule_id`);

--
-- Constraints for table `crew_assignment`
--
ALTER TABLE `crew_assignment`
  ADD CONSTRAINT `crew_assignment_ibfk_1` FOREIGN KEY (`crew_id`) REFERENCES `crew` (`crew_id`);

--
-- Constraints for table `delay`
--
ALTER TABLE `delay`
  ADD CONSTRAINT `delay_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`flight_id`);

--
-- Constraints for table `flight`
--
ALTER TABLE `flight`
  ADD CONSTRAINT `flight_ibfk_1` FOREIGN KEY (`aircraft_id`) REFERENCES `aircraft` (`aircraft_id`),
  ADD CONSTRAINT `flight_ibfk_2` FOREIGN KEY (`airport_id`) REFERENCES `airport` (`airport_id`);

--
-- Constraints for table `flight_delay`
--
ALTER TABLE `flight_delay`
  ADD CONSTRAINT `flight_delay_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`flight_id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD CONSTRAINT `maintenance_logs_ibfk_1` FOREIGN KEY (`aircraft_id`) REFERENCES `aircraft` (`aircraft_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON UPDATE CASCADE;

--
-- Constraints for table `transit`
--
ALTER TABLE `transit`
  ADD CONSTRAINT `transit_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`flight_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
