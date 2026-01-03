-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2026 at 05:01 PM
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
-- Database: `airline_management`
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
(5, 6, 450.00, 'Confirmed', 'TXN-001-TEST', 1, '2026-01-02 17:16:39');

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
-- Indexes for table `flight_track`
--
ALTER TABLE `flight_track`
  ADD KEY `flight_id` (`flight_id`);

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
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pricing_rule`
--
ALTER TABLE `pricing_rule`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
