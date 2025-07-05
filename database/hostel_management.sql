-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2025 at 06:58 AM
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
-- Database: `hostel_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `college_id` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `college_id`, `password`, `photo`) VALUES
(1, 'Jit Bauri', '1142305920', '$2y$10$qjpwKv/iJpiMLEoCST9AxOq61FzjyqNrFOrajEl8rd/7JVEvgJ4/y', 'jit_profile.gif'),
(2, 'Jaydeb Das', '1142305921', '$2y$10$YyLdhD5DKmwD0o8Zjxy2zu6hq3g/ZUM08Wy8ULmAfHAeEImvDJ7HG', '1142305921_12609cd7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `college_id` varchar(50) DEFAULT NULL,
  `university_id` varchar(50) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `guardian_mobile_number` varchar(15) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `room_number` int(11) DEFAULT NULL,
  `left_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`id`, `name`, `college_id`, `university_id`, `guardian_name`, `mobile_number`, `guardian_mobile_number`, `course`, `photo`, `room_number`, `left_on`) VALUES
(1, 'Jit Bauri', '1142305920', '114231220215', 'Sunil Bauri', '9883221819', '9475171367', 'Bsc Computer Science', 'profile picture.gif', 37, '2025-06-21 20:39:24');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `college_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `complain_date` date NOT NULL,
  `status` enum('pending','resolved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `college_id`, `title`, `description`, `category`, `complain_date`, `status`, `created_at`) VALUES
(1, '1142305920', 'electric problem', 'room no 37 fan not working ', 'Electrical', '2025-06-26', 'pending', '2025-06-26 01:57:33');

-- --------------------------------------------------------

--
-- Table structure for table `dues`
--

CREATE TABLE `dues` (
  `due_id` int(11) NOT NULL,
  `college_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dues`
--

INSERT INTO `dues` (`due_id`, `college_id`, `amount`, `due_date`, `is_paid`, `created_at`) VALUES
(1, 1142305920, 500.00, '2025-06-26', 0, '2025-06-26 00:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `college_id` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `name`, `college_id`, `password`, `active`) VALUES
(1, 'Jit Bauri', '1142305920', '$2y$10$onDJ708HwWJc6Zg3cXP91e2UNjqRuoVPVK/39uRdtAhLM0Q.nEJ0.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `meal_status`
--

CREATE TABLE `meal_status` (
  `college_id` varchar(50) NOT NULL,
  `status` enum('ON','OFF') DEFAULT 'ON',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_status`
--

INSERT INTO `meal_status` (`college_id`, `status`, `updated_at`) VALUES
('1142305920', 'ON', '2025-06-26 00:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `posted_by` varchar(100) DEFAULT NULL,
  `posted_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_students`
--

CREATE TABLE `pending_students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `college_id` varchar(50) DEFAULT NULL,
  `university_id` varchar(50) NOT NULL,
  `guardian_name` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `guardian_mobile_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `room_number` int(11) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_allocation`
--

CREATE TABLE `room_allocation` (
  `room_number` int(11) NOT NULL,
  `capacity` int(11) DEFAULT 1,
  `occupied` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_allocation`
--

INSERT INTO `room_allocation` (`room_number`, `capacity`, `occupied`) VALUES
(1, 1, 0),
(2, 1, 0),
(3, 1, 0),
(4, 1, 0),
(5, 1, 0),
(6, 1, 0),
(7, 1, 0),
(8, 1, 0),
(9, 1, 0),
(10, 1, 0),
(11, 1, 0),
(12, 1, 0),
(13, 1, 0),
(14, 1, 0),
(15, 1, 0),
(16, 1, 0),
(17, 1, 0),
(18, 1, 0),
(19, 1, 0),
(20, 1, 0),
(21, 1, 0),
(22, 1, 0),
(23, 1, 0),
(24, 1, 0),
(25, 1, 0),
(26, 1, 0),
(27, 1, 0),
(28, 1, 0),
(29, 1, 0),
(30, 1, 0),
(31, 1, 0),
(32, 1, 0),
(33, 1, 0),
(34, 1, 0),
(35, 1, 0),
(36, 1, 0),
(37, 1, 0),
(38, 1, 0),
(39, 1, 0),
(40, 1, 0),
(41, 1, 0),
(42, 1, 0),
(43, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `slider_images`
--

CREATE TABLE `slider_images` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slider_images`
--

INSERT INTO `slider_images` (`id`, `image_path`, `uploaded_at`) VALUES
(1, 'slid_4.jpg', '2025-06-21 22:18:34'),
(2, 'slid_5.jpg', '2025-06-21 22:18:41'),
(3, 'slild_6.jpg', '2025-06-21 22:18:53'),
(4, 'slid_7.jpg', '2025-06-21 22:19:08'),
(5, 'slid_8 new.jpg', '2025-06-21 22:19:20'),
(6, 'slid_9new.jpg', '2025-06-21 22:19:28'),
(7, 'slid_10new.jpg', '2025-06-21 22:19:34');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `college_id` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `university_id` varchar(50) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `guardian_mobile_number` varchar(15) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `room_number` int(11) DEFAULT NULL,
  `is_alumni` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `college_id`, `password`, `university_id`, `guardian_name`, `mobile_number`, `guardian_mobile_number`, `course`, `photo`, `room_number`, `is_alumni`) VALUES
(5, 'Jit Bauri', '1142305920', '$2y$10$pNPEpkRdY3XA5dqJuoHqku1dFR8Qk/ugUDlRT.evy.1034zORLnKW', '114231220215', 'Sunil Bauri', '9883221819', '9475171367', 'Bsc Computer Science', 'profile picture.gif', 37, 0),
(6, 'Jaydeb Das', '1142305921', 'bolpuR@2', '114231220216', 'Jaydeb Father', '8016402695', '6290862445', 'Bsc Computer Science', 'jaydeb.jpg', 18, 0),
(7, 'Promotho pal', '112459832659', 'bolpuR@2', '114231220217', 'promoth girdient', '9732731235', '973273123', 'Bsc Computer Science', 'IMG-20240901-WA0007.jpg', 23, 0);

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE `suggestions` (
  `id` int(11) NOT NULL,
  `college_id` varchar(20) NOT NULL,
  `suggestion` text NOT NULL,
  `status` enum('pending','reviewed','implemented','rejected') DEFAULT 'pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suggestions`
--

INSERT INTO `suggestions` (`id`, `college_id`, `suggestion`, `status`, `submitted_at`) VALUES
(1, '1142305920', 'every week hostel clean regularly', 'pending', '2025-06-26 02:00:33');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `college_id` varchar(20) NOT NULL,
  `visitor_name` varchar(100) NOT NULL,
  `relation` varchar(50) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `visit_date` date NOT NULL,
  `visit_time` time NOT NULL,
  `purpose` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `college_id`, `visitor_name`, `relation`, `mobile`, `visit_date`, `visit_time`, `purpose`, `created_at`, `updated_at`) VALUES
(1, '1142305920', 'Chanchal ', 'Friennd', '9883221819', '2025-06-26', '07:14:00', 'studr related ', '2025-06-26 01:44:17', '2025-06-26 01:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_menu`
--

CREATE TABLE `weekly_menu` (
  `id` int(11) NOT NULL,
  `day` varchar(15) DEFAULT NULL,
  `lunch_menu` text DEFAULT NULL,
  `dinner_menu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weekly_menu`
--

INSERT INTO `weekly_menu` (`id`, `day`, `lunch_menu`, `dinner_menu`) VALUES
(1, 'Monday', 'Rice,Dal, Mix Veg, Salad', 'Chapati, Paneer Butter Masala, Rice'),
(2, 'Tuesday', 'Rajma, Rice, Aloo Fry, Salad', 'Chapati, Chicken Curry / Paneer, Jeera Rice'),
(3, 'Wednesday', 'Chole, Rice, Bhindi, Salad', 'Chapati, Egg Curry / Mushroom Masala, Rice'),
(4, 'Thursday', 'Dal, Rice, Pumpkin, Salad', 'Chapati, Kadhi Pakora, Rice'),
(5, 'Friday', 'Mix Dal, Rice, Beans, Salad', 'Chapati, Veg Biryani / Chicken Biryani, Raita'),
(6, 'Saturday', 'Sambar, Rice, Aloo Fry, Salad', 'Chapati, Matar Paneer, Jeera Rice'),
(7, 'Sunday', 'Chole Bhature and panir', 'Special Thali (Paneer/Chicken, Rice, Sweet)');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `college_id` (`college_id`);

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `college_id` (`college_id`);

--
-- Indexes for table `dues`
--
ALTER TABLE `dues`
  ADD PRIMARY KEY (`due_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `college_id` (`college_id`);

--
-- Indexes for table `meal_status`
--
ALTER TABLE `meal_status`
  ADD PRIMARY KEY (`college_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_students`
--
ALTER TABLE `pending_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `college_id` (`college_id`);

--
-- Indexes for table `room_allocation`
--
ALTER TABLE `room_allocation`
  ADD PRIMARY KEY (`room_number`);

--
-- Indexes for table `slider_images`
--
ALTER TABLE `slider_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `college_id` (`college_id`);

--
-- Indexes for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `college_id` (`college_id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weekly_menu`
--
ALTER TABLE `weekly_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dues`
--
ALTER TABLE `dues`
  MODIFY `due_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pending_students`
--
ALTER TABLE `pending_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `slider_images`
--
ALTER TABLE `slider_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `weekly_menu`
--
ALTER TABLE `weekly_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `students` (`college_id`) ON DELETE CASCADE;

--
-- Constraints for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD CONSTRAINT `suggestions_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `students` (`college_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
