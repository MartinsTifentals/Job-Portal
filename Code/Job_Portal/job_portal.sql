-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2026 at 01:17 PM
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
(22, 'Full Stack Developer', 'Innovate Digital', 'Manchester', '£45,000 - £60,000', NULL, 'Programming', 'Develop both frontend and backend components of web applications.', '2026-02-14 20:03:50', 0),
(23, 'Systems Administrator', 'CoreIT Services', 'Bradford', '£30,000 - £40,000', NULL, 'Networking', 'Maintain servers, networks, and internal systems.', '2026-02-14 20:03:50', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
