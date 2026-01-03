-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2026 at 05:17 PM
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
-- Table structure for table `aircraft`
--

CREATE TABLE `aircraft` (
  `aircraft_id` int(11) NOT NULL,
  `model` varchar(50) NOT NULL,
  `tail_num` varchar(10) NOT NULL,
  `current_loc` point NOT NULL,
  `last_update` datetime NOT NULL,
  `template_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aircraft`
--

INSERT INTO `aircraft` (`aircraft_id`, `model`, `tail_num`, `current_loc`, `last_update`, `template_id`) VALUES
(1, 'Boeing 737-800', 'N101AA', 0x00000000010100000060764f1e165244402041f163cc7152c0, '2025-12-24 10:48:48', 10),
(2, 'Embraer E175', 'N202BB', 0x000000000101000000f1f44a5986f84040a01a2fdd249a5dc0, '2025-12-24 10:48:48', 20),
(3, 'Airbus A350-900', 'N303CC', 0x0000000001010000005c8fc2f528bc4940c1a8a44e4013ddbf, '2025-12-24 10:48:48', 30),
(4, 'Boeing 777-300ER', 'N404DD', 0x00000000010100000080b74082e2d737405396218e75995640, '2025-12-24 10:48:48', 10),
(5, 'Boeing 895-753', 'N759EE', 0x000000000101000000cd3b4ed191905640cd3b4ed191105640, '0000-00-00 00:00:00', 5);

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
(1, 'King Abdul Aziz Airp', 'ksa', 'jeddah', 'KSA', 0, 0),
(7, 'Heathrow Airport', 'UK', 'London', 'LHR', 0, 0),
(8, 'Shahajalal Internati', 'Bangladesh', 'Dhaka', 'DHK', 0, 0),
(9, 'King Fahd Abdul Aziz', 'Saudi Arab', 'Jeddah', 'KSA', 0, 0);

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
(1, 'ggggggg', '123456789', 'hacking', '2026-01-03 15:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(8) NOT NULL,
  `booking_status` varchar(60) NOT NULL,
  `booking_date` datetime NOT NULL,
  `template_id` int(10) NOT NULL,
  `seat_label` varchar(60) NOT NULL,
  `flight_id` int(8) NOT NULL,
  `passenger_id` int(6) NOT NULL,
  `price_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `booking_status`, `booking_date`, `template_id`, `seat_label`, `flight_id`, `passenger_id`, `price_id`) VALUES
(1, 'Confirmed', '2026-01-20 00:00:00', 5, '14D', 4, 4, 3),
(2, 'Pending', '2026-01-27 00:00:00', 90, '22K', 44, 47, 147);

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
(1, 'blblblblblblblblaaaaaaaaaaa', 'Technical');

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
  `seat` varchar(5) NOT NULL,
  `fl_status` text NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp(),
  `airport_id` varchar(8) NOT NULL,
  `aircraft_id` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flight_id`, `flight_number`, `standard_dep_time`, `standard_arr_time`, `source`, `destination`, `gate`, `seat`, `fl_status`, `last_updated`, `airport_id`, `aircraft_id`) VALUES
(1, '1', '13:00:00', '20:00:00', 'Madinah', 'Bahrain', 'H-7', 'B31', 'Scheduled', '2025-12-23 14:58:17', '', ''),
(2, '1', '10:00:00', '01:15:00', 'Madinah ', 'Bahrain', 'G-6', 'E21', 'Arrived', '2025-12-23 14:59:22', '', ''),
(3, '3', '03:00:00', '11:15:00', 'USA', 'Canada', 'H-7', 'B55', 'Takeoff', '2025-12-23 15:00:00', '', ''),
(4, '4', '08:05:00', '16:15:00', 'BD', 'Jeddah', 'F-5', 'E11', 'Arrived', '2025-12-23 15:00:45', '', ''),
(5, '5', '01:05:00', '22:15:00', 'Qatar', 'USA', 'D-8', 'B99', 'Scheduled', '2025-12-23 15:01:37', '', ''),
(6, '6', '11:03:00', '00:01:00', 'USA', 'BD', 'F-8', 'B45', 'Delayed', '2025-12-23 15:02:29', '', ''),
(7, '7', '04:03:00', '09:01:00', 'Doha', 'Qatar', 'A-8', 'E08', 'Scheduled', '2025-12-23 15:02:58', '', ''),
(8, '8', '10:03:00', '18:04:00', 'Oman', 'Switzerland', 'E-7', 'B5', 'Scheduled', '2025-12-23 15:03:34', '', ''),
(9, '9', '03:03:00', '07:04:00', 'NEW YORK', 'Jeddah', 'N-1', 'E-6', 'Scheduled', '2025-12-23 15:04:22', '', ''),
(10, '10', '12:03:00', '13:04:00', 'Madinah', 'Jeddah', 'A-1', 'E01', 'Scheduled', '2025-12-23 15:04:50', '', '');

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
(1, 6, '00:06:00', '1', '2025-12-23 21:02:29');

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
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `passport_num` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `transit`
--

INSERT INTO `transit` (`transit_id`, `flight_leg`, `flight_id`, `flight_crew_id`) VALUES
(2, 3, 9, 58),
(3, 4, 6, 485);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aircraft`
--
ALTER TABLE `aircraft`
  ADD PRIMARY KEY (`aircraft_id`);

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
  ADD PRIMARY KEY (`booking_id`);

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
  ADD PRIMARY KEY (`flight_id`);

--
-- Indexes for table `flight_delay`
--
ALTER TABLE `flight_delay`
  ADD PRIMARY KEY (`delay_id`),
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
  ADD PRIMARY KEY (`passenger_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `passport_num` (`passport_num`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aircraft`
--
ALTER TABLE `aircraft`
  MODIFY `aircraft_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `airport`
--
ALTER TABLE `airport`
  MODIFY `airport_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `baggage`
--
ALTER TABLE `baggage`
  MODIFY `baggage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `baggage_claims`
--
ALTER TABLE `baggage_claims`
  MODIFY `claim_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blacklist`
--
ALTER TABLE `blacklist`
  MODIFY `blacklist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `crew`
--
ALTER TABLE `crew`
  MODIFY `crew_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crew_assignment`
--
ALTER TABLE `crew_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delay_reason`
--
ALTER TABLE `delay_reason`
  MODIFY `reason_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `emergency_reports`
--
ALTER TABLE `emergency_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flight_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `flight_delay`
--
ALTER TABLE `flight_delay`
  MODIFY `delay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passenger`
--
ALTER TABLE `passenger`
  MODIFY `passenger_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for dumped tables
--

--
-- Constraints for table `baggage_claims`
--
ALTER TABLE `baggage_claims`
  ADD CONSTRAINT `baggage_claims_ibfk_1` FOREIGN KEY (`baggage_id`) REFERENCES `baggage` (`baggage_id`);

--
-- Constraints for table `crew_assignment`
--
ALTER TABLE `crew_assignment`
  ADD CONSTRAINT `crew_assignment_ibfk_1` FOREIGN KEY (`crew_id`) REFERENCES `crew` (`crew_id`);

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
-- Constraints for table `transit`
--
ALTER TABLE `transit`
  ADD CONSTRAINT `transit_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`flight_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
