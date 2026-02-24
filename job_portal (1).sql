-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 04:03 PM
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
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_logs`
--

CREATE TABLE `chat_logs` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `session_id` varchar(64) NOT NULL,
  `sender` enum('USER','BOT') NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_logs`
--

INSERT INTO `chat_logs` (`id`, `created_at`, `session_id`, `sender`, `message`) VALUES
(1, '2026-02-19 00:25:20', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'BOT', 'Hi! Start: Need help?\nAre you logged in?'),
(2, '2026-02-19 00:25:23', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'USER', '[button] START'),
(3, '2026-02-19 00:25:23', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'BOT', 'Please choose Yes/No using the buttons.'),
(4, '2026-02-19 00:25:33', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'USER', '[button] LOGGED_IN_YES'),
(5, '2026-02-19 00:25:33', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'BOT', 'What do you need help with?'),
(6, '2026-02-19 00:25:43', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'USER', '[button] TOPIC_APPLY'),
(7, '2026-02-19 00:25:43', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'BOT', 'Applying for a job \n\nIs the \'Apply\' button missing / giving an error?'),
(8, '2026-02-19 00:25:50', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'USER', '[button] APPLY_ERR_NO'),
(9, '2026-02-19 00:25:50', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'BOT', 'Help: Track application status / confirm submission.\n\nGo to:\nProfile → My Applications → select job → view status.\n\nSolved?'),
(10, '2026-02-19 00:25:55', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'USER', '[button] SOLVED_YES'),
(11, '2026-02-19 00:25:55', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'BOT', 'End: Glad I could help! \n\nNeed anything else?'),
(12, '2026-02-19 00:25:58', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'USER', '[button] DONE'),
(13, '2026-02-19 00:25:58', 'ac1bd1c997bde9fc7c4d3de883e8fb77', 'BOT', 'Chat closed. Have a good day'),
(14, '2026-02-24 15:01:44', 'c32a69f73ce4b2e3a2b778e7f7a45e63', 'BOT', 'Hi! Start: Need help?\nAre you logged in?'),
(15, '2026-02-24 15:01:53', 'c32a69f73ce4b2e3a2b778e7f7a45e63', 'USER', '[button] START'),
(16, '2026-02-24 15:01:53', 'c32a69f73ce4b2e3a2b778e7f7a45e63', 'BOT', 'Please choose Yes/No using the buttons.'),
(17, '2026-02-24 15:01:54', 'c32a69f73ce4b2e3a2b778e7f7a45e63', 'USER', '[button] LOGGED_IN_YES'),
(18, '2026-02-24 15:01:54', 'c32a69f73ce4b2e3a2b778e7f7a45e63', 'BOT', 'What do you need help with?');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `salary` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_promoted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `company`, `location`, `salary`, `type`, `category`, `description`, `created_at`, `is_promoted`) VALUES
(1, 'Senior Software Engineer', 'TechCorp', 'London', '£60k–£80k', 'Full-time', 'Technology', 'Build scalable apps', '2026-02-13 14:23:38', 0),
(2, 'Marketing Manager', 'Creative Media Ltd', 'Manchester', '£45k–£55k', 'Full-time', 'Marketing', 'Lead campaigns', '2026-02-13 14:23:38', 0),
(3, 'Data Analyst', 'Finance Group', 'Birmingham', '£40k–£50k', 'Full-time', 'Finance', 'Analyze business data', '2026-02-13 14:23:38', 0),
(4, 'Junior Cybersecurity Analyst', 'SecureTech Ltd', 'Leeds', '£28,000 - £35,000', NULL, 'Cybersecurity', 'Assist in monitoring security systems, responding to incidents, and performing vulnerability assessments.', '2026-02-14 20:03:50', 1),
(5, 'Data Scientist', 'Insight Analytics', 'Manchester', '£45,000 - £60,000', NULL, 'Data Science', 'Develop predictive models and analyse large datasets to support business decisions.', '2026-02-14 20:03:50', 0),
(6, 'Frontend Developer', 'PixelForge', 'Sheffield', '£35,000 - £45,000', NULL, 'Programming', 'Build responsive web interfaces using HTML, CSS, JavaScript and modern frameworks.', '2026-02-14 20:03:50', 1),
(7, 'IT Support Technician', 'NorthStar IT', 'Bradford', '£24,000 - £30,000', NULL, 'Networking', 'Provide technical support and maintain internal IT systems.', '2026-02-14 20:03:50', 0),
(8, 'UX/UI Designer', 'Creative Minds Studio', 'Manchester', '£32,000 - £40,000', NULL, 'Designing', 'Design user-centred digital experiences and interactive prototypes.', '2026-02-14 20:03:50', 0),
(9, 'Network Engineer', 'Connect Solutions', 'Leeds', '£40,000 - £50,000', NULL, 'Networking', 'Manage and maintain enterprise network infrastructure.', '2026-02-14 20:03:50', 1),
(10, 'Project Manager', 'Elevate Group', 'London', '£50,000 - £65,000', NULL, 'Management', 'Lead cross-functional teams and deliver projects on time and within budget.', '2026-02-14 20:03:50', 0),
(11, 'Backend Developer', 'CodeSphere', 'Manchester', '£38,000 - £48,000', NULL, 'Programming', 'Develop secure APIs and scalable backend systems using PHP and MySQL.', '2026-02-14 20:03:50', 0),
(12, 'Cloud Engineer', 'SkyNet Systems', 'Remote', '£55,000 - £70,000', NULL, 'Networking', 'Design and deploy cloud-based infrastructure solutions.', '2026-02-14 20:03:50', 1),
(13, 'Marketing Data Analyst', 'BrightWave Marketing', 'Leeds', '£30,000 - £42,000', NULL, 'Data Science', 'Analyse campaign performance data and provide insights.', '2026-02-14 20:03:50', 0),
(14, 'Cybersecurity Consultant', 'ShieldGuard', 'London', '£60,000 - £80,000', NULL, 'Cybersecurity', 'Advise clients on security best practices and risk mitigation strategies.', '2026-02-14 20:03:50', 1),
(15, 'Software Engineer', 'NextGen Tech', 'Manchester', '£40,000 - £55,000', NULL, 'Programming', 'Develop and maintain scalable software applications.', '2026-02-14 20:03:50', 0),
(16, 'IT Project Coordinator', 'TechBridge Ltd', 'Sheffield', '£30,000 - £38,000', NULL, 'Management', 'Support project managers with documentation and scheduling.', '2026-02-14 20:03:50', 0),
(17, 'Graphic Designer', 'Urban Creatives', 'Leeds', '£26,000 - £34,000', NULL, 'Designing', 'Create branding materials, social media graphics, and marketing visuals.', '2026-02-14 20:03:50', 0),
(18, 'DevOps Engineer', 'CloudCore', 'Remote', '£50,000 - £65,000', NULL, 'Programming', 'Automate deployment pipelines and manage CI/CD systems.', '2026-02-14 20:03:50', 1),
(19, 'Database Administrator', 'DataSecure Ltd', 'Manchester', '£45,000 - £58,000', NULL, 'Data Science', 'Maintain and optimise MySQL databases.', '2026-02-14 20:03:50', 0),
(20, 'Business Analyst', 'Strategic Solutions', 'London', '£42,000 - £55,000', NULL, 'Management', 'Gather requirements and improve operational processes.', '2026-02-14 20:03:50', 0),
(21, 'Penetration Tester', 'CyberElite', 'Leeds', '£48,000 - £62,000', NULL, 'Cybersecurity', 'Conduct ethical hacking tests and security audits.', '2026-02-14 20:03:50', 1),
(22, 'Full Stack Developer', 'Innovate Digital', 'Manchester', '£45,000 - £60,000', NULL, 'Programming', 'Develop both frontend and backend components of web applications.', '2026-02-14 20:03:50', 0);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `session_id` varchar(64) NOT NULL,
  `name` varchar(120) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `best_time` varchar(120) NOT NULL,
  `topic` varchar(60) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `status` enum('OPEN','CALLED','CLOSED') DEFAULT 'OPEN'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Martins Tifentals', 'mtifental@gmail.com', '$2y$10$NduA4D4eIE8kHgcCRH.h9u.BA3Ds1Miw0klN5Ng7PqXdEifVFTwXW', '2026-02-18 21:44:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_logs`
--
ALTER TABLE `chat_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
