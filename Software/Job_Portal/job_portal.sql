-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2026 at 10:34 PM
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
(14, '2026-02-26 02:31:48', '3772870b4ec099bf6637fc62c7dcf93a', 'BOT', 'Hi! Start: Need help?\nAre you logged in?'),
(15, '2026-02-26 02:31:50', '3772870b4ec099bf6637fc62c7dcf93a', 'USER', '[button] START'),
(16, '2026-02-26 02:31:50', '3772870b4ec099bf6637fc62c7dcf93a', 'BOT', 'Please choose Yes/No using the buttons.'),
(17, '2026-02-26 02:31:51', '3772870b4ec099bf6637fc62c7dcf93a', 'USER', '[button] LOGGED_IN_NO'),
(18, '2026-02-26 02:31:51', '3772870b4ec099bf6637fc62c7dcf93a', 'BOT', 'Suggest: Login / Reset Password.\n\nTry:\n1) Click “Forgot Password”\n2) Check Spam/Junk\n3) Make sure Caps Lock is off\n\nDid this solve it?'),
(19, '2026-02-26 02:31:52', '3772870b4ec099bf6637fc62c7dcf93a', 'USER', '[button] SOLVED_NO'),
(20, '2026-02-26 02:31:52', '3772870b4ec099bf6637fc62c7dcf93a', 'BOT', 'Sorry it didn’t solve it.\nOffer: Call back / Phone support.\n\nWant a phone call?'),
(21, '2026-02-26 02:31:52', '3772870b4ec099bf6637fc62c7dcf93a', 'USER', '[button] CALL_NO'),
(22, '2026-02-26 02:31:52', '3772870b4ec099bf6637fc62c7dcf93a', 'BOT', 'No problem. Offer: email / reopen chat.\n\nWant to start over?'),
(23, '2026-02-26 02:31:53', '3772870b4ec099bf6637fc62c7dcf93a', 'USER', '[button] RESTART'),
(24, '2026-02-26 02:31:53', '3772870b4ec099bf6637fc62c7dcf93a', 'BOT', 'Restarting…'),
(25, '2026-03-01 01:57:29', '3772870b4ec099bf6637fc62c7dcf93a', 'BOT', 'Hi! Start: Need help?\nAre you logged in?'),
(26, '2026-03-02 15:02:29', 'cbb19d18e3c16ac4eace4e2d018b05bf', 'BOT', 'Hi! Start: Need help?\nAre you logged in?');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
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
(1, 'Biomedical Scientist', 'MediLab UK', 'Leeds', 42000, 'Full-time', 'Healthcare', 'Conduct laboratory biomedical testing', '2026-03-11 10:00:00', 0),
(2, 'Senior Biomedical Scientist', 'HealthCore Labs', 'Manchester', 55000, 'Full-time', 'Healthcare', 'Lead biomedical laboratory diagnostics', '2026-03-11 10:00:00', 0),
(3, 'Clinical Biomedical Researcher', 'BioResearch Ltd', 'London', 48000, 'Full-time', 'Healthcare', 'Assist medical research projects', '2026-03-11 10:00:00', 0),
(4, 'Business Manager', 'BrightPath Consulting', 'London', 60000, 'Full-time', 'Business', 'Manage company operations and growth', '2026-03-11 10:00:00', 1),
(5, 'Car Mechanic', 'AutoFix Garage', 'Bradford', 32000, 'Full-time', 'Automotive', 'Repair and maintain vehicles', '2026-03-11 10:00:00', 0),
(6, 'Retail Cashier', 'QuickMart Stores', 'Leeds', 24000, 'Full-time', 'Retail', 'Handle customer payments and sales', '2026-03-11 10:00:00', 0),
(7, 'University CEO', 'Bradford Academic Group', 'Bradford', 95000, 'Full-time', 'Education', 'Lead university operations and strategy', '2026-03-11 10:00:00', 1),
(8, 'Civil Engineer', 'UrbanBuild Ltd', 'Leeds', 50000, 'Full-time', 'Engineering', 'Design infrastructure projects', '2026-03-11 10:00:00', 0),
(9, 'Structural Civil Engineer', 'MetroStructures', 'Manchester', 52000, 'Full-time', 'Engineering', 'Analyse building structures', '2026-03-11 10:00:00', 0),
(10, 'Site Civil Engineer', 'ConstructPro', 'Birmingham', 47000, 'Full-time', 'Engineering', 'Supervise construction sites', '2026-03-11 10:00:00', 0),
(11, 'Computer Scientist', 'FutureCompute Labs', 'London', 65000, 'Full-time', 'Technology', 'Research computing systems', '2026-03-11 10:00:00', 1),
(12, 'Cyber Security Analyst', 'SecureNet Solutions', 'Leeds', 45000, 'Full-time', 'Cybersecurity', 'Monitor security threats', '2026-03-11 10:00:00', 0),
(13, 'Junior Cyber Security Analyst', 'DataShield UK', 'Sheffield', 38000, 'Full-time', 'Cybersecurity', 'Assist with system security monitoring', '2026-03-11 10:00:00', 0),
(14, 'Dentist', 'SmileCare Clinic', 'Manchester', 70000, 'Full-time', 'Healthcare', 'Provide dental care services', '2026-03-11 10:00:00', 1),
(15, 'Mechanical Engineer', 'PrimeEngineering', 'Leeds', 48000, 'Full-time', 'Engineering', 'Design mechanical systems', '2026-03-11 10:00:00', 0),
(16, 'Electrical Engineer', 'VoltTech Systems', 'London', 52000, 'Full-time', 'Engineering', 'Develop electrical infrastructure', '2026-03-11 10:00:00', 0),
(17, 'Industrial Engineer', 'FactoryLogic Ltd', 'Birmingham', 49000, 'Full-time', 'Engineering', 'Improve manufacturing efficiency', '2026-03-11 10:00:00', 0),
(18, 'Faith & Community Advisor', 'Unity Trust', 'Bradford', 36000, 'Full-time', 'Community', 'Support faith community initiatives', '2026-03-11 10:00:00', 0),
(19, 'Freelance Web Developer', 'CodeFlex Agency', 'Remote', 60000, 'Contract', 'Technology', 'Develop client web applications', '2026-03-11 10:00:00', 0),
(20, 'Lawyer', 'Justice Legal Group', 'London', 75000, 'Full-time', 'Legal', 'Provide legal representation', '2026-03-11 10:00:00', 1),
(21, 'Corporate Lawyer', 'CityLaw Partners', 'London', 82000, 'Full-time', 'Legal', 'Advise corporate clients', '2026-03-11 10:00:00', 1),
(22, 'Criminal Lawyer', 'Defence Associates', 'Manchester', 68000, 'Full-time', 'Legal', 'Represent criminal cases', '2026-03-11 10:00:00', 0),
(23, 'Legal Consultant', 'LexBridge Consulting', 'Leeds', 66000, 'Full-time', 'Legal', 'Provide legal advisory', '2026-03-11 10:00:00', 0),
(24, 'Registered Nurse', 'CarePlus Hospital', 'Leeds', 34000, 'Full-time', 'Healthcare', 'Provide patient care', '2026-03-11 10:00:00', 0),
(25, 'Penetration Tester', 'HackSecure Labs', 'London', 63000, 'Full-time', 'Cybersecurity', 'Perform ethical hacking tests', '2026-03-11 10:00:00', 1),
(26, 'Pharmacist', 'HealthFirst Pharmacy', 'Leeds', 58000, 'Full-time', 'Healthcare', 'Dispense medication and advise patients', '2026-03-11 10:00:00', 0),
(27, 'Senior Pharmacist', 'MediTrust Pharmacy', 'Manchester', 64000, 'Full-time', 'Healthcare', 'Supervise pharmacy operations', '2026-03-11 10:00:00', 0),
(28, 'Clinical Pharmacist', 'CareLine Hospital', 'London', 62000, 'Full-time', 'Healthcare', 'Support hospital medication management', '2026-03-11 10:00:00', 0),
(29, 'Community Pharmacist', 'WellLife Pharmacy', 'Bradford', 56000, 'Full-time', 'Healthcare', 'Provide prescriptions to community', '2026-03-11 10:00:00', 0),
(30, 'Pharmacy Manager', 'PharmaGroup UK', 'Leeds', 67000, 'Full-time', 'Healthcare', 'Manage pharmacy team', '2026-03-11 10:00:00', 1),
(31, 'Hospital Pharmacist', 'CityCare Medical', 'Manchester', 61000, 'Full-time', 'Healthcare', 'Support hospital pharmacy', '2026-03-11 10:00:00', 0),
(32, 'IT Project Manager', 'TechBridge Ltd', 'London', 70000, 'Full-time', 'Management', 'Lead technology projects', '2026-03-11 10:00:00', 1),
(33, 'Clinical Psychologist', 'MindCare Clinic', 'Leeds', 52000, 'Full-time', 'Healthcare', 'Provide psychological therapy', '2026-03-11 10:00:00', 0),
(34, 'Research Scientist', 'Innovate Research Ltd', 'London', 59000, 'Full-time', 'Research', 'Conduct scientific research', '2026-03-11 10:00:00', 0),
(35, 'Motivational Speaker', 'Impact Talks UK', 'London', 45000, 'Contract', 'Public Speaking', 'Deliver inspirational talks', '2026-03-11 10:00:00', 0),
(36, 'Software Engineer', 'CodeCraft Ltd', 'Manchester', 60000, 'Full-time', 'Technology', 'Develop software systems', '2026-03-11 10:00:00', 1),
(37, 'Senior Software Engineer', 'NextGen Software', 'London', 72000, 'Full-time', 'Technology', 'Lead development teams', '2026-03-11 10:00:00', 1),
(38, 'Backend Developer', 'ServerLogic', 'Leeds', 55000, 'Full-time', 'Technology', 'Build backend services', '2026-03-11 10:00:00', 0),
(39, 'Frontend Developer', 'UIWorks', 'Manchester', 50000, 'Full-time', 'Technology', 'Create responsive web interfaces', '2026-03-11 10:00:00', 0),
(40, 'Full Stack Developer', 'DigitalForge', 'London', 65000, 'Full-time', 'Technology', 'Develop full web solutions', '2026-03-11 10:00:00', 1),
(41, 'Data Analyst', 'Insight Metrics', 'Leeds', 46000, 'Full-time', 'Data', 'Analyse company data', '2026-03-11 10:00:00', 0),
(42, 'Data Scientist', 'AI Solutions Ltd', 'London', 70000, 'Full-time', 'Data', 'Develop predictive models', '2026-03-11 10:00:00', 1),
(43, 'Network Engineer', 'NetSecure Systems', 'Manchester', 54000, 'Full-time', 'Networking', 'Maintain network infrastructure', '2026-03-11 10:00:00', 0),
(44, 'Cloud Engineer', 'SkyCloud Ltd', 'Remote', 68000, 'Full-time', 'Cloud', 'Deploy cloud systems', '2026-03-11 10:00:00', 1),
(45, 'UX Designer', 'CreativeFlow', 'Leeds', 48000, 'Full-time', 'Design', 'Design user experiences', '2026-03-11 10:00:00', 0),
(46, 'UI Designer', 'PixelWorks', 'Manchester', 47000, 'Full-time', 'Design', 'Design digital interfaces', '2026-03-11 10:00:00', 0),
(47, 'Construction Project Manager', 'BuildRight Ltd', 'Birmingham', 66000, 'Full-time', 'Construction', 'Manage construction projects', '2026-03-11 10:00:00', 0),
(48, 'Structural Engineer', 'SteelCore Ltd', 'Leeds', 58000, 'Full-time', 'Engineering', 'Design structural frameworks', '2026-03-11 10:00:00', 0),
(49, 'Automotive Technician', 'MotorWorks', 'Bradford', 35000, 'Full-time', 'Automotive', 'Service vehicles', '2026-03-11 10:00:00', 0),
(50, 'Mechanical Technician', 'MechaTech Ltd', 'Leeds', 39000, 'Full-time', 'Engineering', 'Maintain machinery', '2026-03-11 10:00:00', 0),
(51, 'Security Consultant', 'CyberTrust', 'London', 71000, 'Full-time', 'Cybersecurity', 'Advise organisations on security', '2026-03-11 10:00:00', 1),
(52, 'AI Engineer', 'DeepLogic Labs', 'London', 75000, 'Full-time', 'AI', 'Develop AI systems', '2026-03-11 10:00:00', 1),
(53, 'Machine Learning Engineer', 'NeuralTech', 'London', 74000, 'Full-time', 'AI', 'Build ML algorithms', '2026-03-11 10:00:00', 1),
(54, 'Business Analyst', 'StrategyCore', 'Manchester', 52000, 'Full-time', 'Business', 'Analyse business processes', '2026-03-11 10:00:00', 0),
(55, 'Operations Manager', 'PrimeOperations', 'Leeds', 63000, 'Full-time', 'Management', 'Oversee daily operations', '2026-03-11 10:00:00', 0),
(56, 'Marketing Manager', 'BrandSpark', 'London', 61000, 'Full-time', 'Marketing', 'Lead marketing strategies', '2026-03-11 10:00:00', 0),
(57, 'Digital Marketing Specialist', 'AdBoost', 'Manchester', 48000, 'Full-time', 'Marketing', 'Run digital campaigns', '2026-03-11 10:00:00', 0),
(58, 'HR Manager', 'PeopleFirst Ltd', 'London', 59000, 'Full-time', 'Human Resources', 'Manage HR functions', '2026-03-11 10:00:00', 0),
(59, 'Recruitment Consultant', 'TalentLink', 'Leeds', 45000, 'Full-time', 'Human Resources', 'Hire top talent', '2026-03-11 10:00:00', 0),
(60, 'Financial Analyst', 'MoneyWise Ltd', 'London', 62000, 'Full-time', 'Finance', 'Analyse financial reports', '2026-03-11 10:00:00', 0),
(61, 'Accountant', 'ClearBooks Ltd', 'Manchester', 51000, 'Full-time', 'Finance', 'Manage financial records', '2026-03-11 10:00:00', 0),
(62, 'Investment Analyst', 'CapitalGrowth', 'London', 68000, 'Full-time', 'Finance', 'Analyse investments', '2026-03-11 10:00:00', 1),
(63, 'Economist', 'PolicyInsight', 'London', 64000, 'Full-time', 'Finance', 'Research economic trends', '2026-03-11 10:00:00', 0),
(64, 'University Lecturer', 'Northern University', 'Leeds', 57000, 'Full-time', 'Education', 'Teach university students', '2026-03-11 10:00:00', 0),
(65, 'Academic Researcher', 'Scholars Institute', 'London', 53000, 'Full-time', 'Research', 'Conduct academic studies', '2026-03-11 10:00:00', 0),
(66, 'Laboratory Technician', 'LabWorks', 'Manchester', 36000, 'Full-time', 'Science', 'Assist laboratory research', '2026-03-11 10:00:00', 0),
(67, 'Environmental Scientist', 'GreenEarth Labs', 'Leeds', 49000, 'Full-time', 'Science', 'Study environmental impacts', '2026-03-11 10:00:00', 0),
(68, 'Geotechnical Engineer', 'GeoBuild Ltd', 'Birmingham', 56000, 'Full-time', 'Engineering', 'Analyse soil structures', '2026-03-11 10:00:00', 0),
(69, 'Transport Planner', 'UrbanTransit', 'London', 54000, 'Full-time', 'Planning', 'Plan city transport systems', '2026-03-11 10:00:00', 0),
(70, 'Logistics Manager', 'RapidLogistics', 'Manchester', 60000, 'Full-time', 'Logistics', 'Manage supply chains', '2026-03-11 10:00:00', 0),
(71, 'Warehouse Supervisor', 'StorageHub', 'Leeds', 38000, 'Full-time', 'Logistics', 'Supervise warehouse operations', '2026-03-11 10:00:00', 0),
(72, 'Supply Chain Analyst', 'FlowSupply', 'London', 52000, 'Full-time', 'Logistics', 'Analyse supply chain efficiency', '2026-03-11 10:00:00', 0),
(73, 'Retail Store Manager', 'MegaMart', 'Bradford', 41000, 'Full-time', 'Retail', 'Manage store staff', '2026-03-11 10:00:00', 0),
(74, 'Customer Service Manager', 'ServicePro', 'Leeds', 44000, 'Full-time', 'Customer Service', 'Manage support teams', '2026-03-11 10:00:00', 0),
(75, 'Technical Support Specialist', 'HelpDesk UK', 'Manchester', 40000, 'Full-time', 'IT Support', 'Provide IT assistance', '2026-03-11 10:00:00', 0),
(76, 'Systems Administrator', 'CoreSystems', 'London', 58000, 'Full-time', 'IT', 'Maintain company servers', '2026-03-11 10:00:00', 0),
(77, 'DevOps Engineer', 'DeployTech', 'Remote', 69000, 'Full-time', 'Technology', 'Manage CI/CD pipelines', '2026-03-11 10:00:00', 1),
(78, 'Mobile App Developer', 'AppForge', 'Manchester', 61000, 'Full-time', 'Technology', 'Develop mobile apps', '2026-03-11 10:00:00', 0),
(79, 'Game Developer', 'PixelPlay Studios', 'Leeds', 58000, 'Full-time', 'Technology', 'Develop video games', '2026-03-11 10:00:00', 0),
(80, 'Product Manager', 'Innovate Products', 'London', 73000, 'Full-time', 'Management', 'Lead product strategy', '2026-03-11 10:00:00', 1),
(81, 'Quality Assurance Engineer', 'TestLab', 'Manchester', 49000, 'Full-time', 'Technology', 'Test software systems', '2026-03-11 10:00:00', 0),
(82, 'Penetration Testing Lead', 'RedTeam Labs', 'London', 78000, 'Full-time', 'Cybersecurity', 'Lead ethical hacking team', '2026-03-11 10:00:00', 1),
(83, 'Security Operations Analyst', 'CyberWatch', 'Leeds', 50000, 'Full-time', 'Cybersecurity', 'Monitor security alerts', '2026-03-11 10:00:00', 0),
(84, 'Digital Forensics Specialist', 'TraceCyber', 'London', 67000, 'Full-time', 'Cybersecurity', 'Investigate cybercrime', '2026-03-11 10:00:00', 0),
(85, 'Pharmacy Technician', 'CarePharm', 'Leeds', 33000, 'Full-time', 'Healthcare', 'Assist pharmacists', '2026-03-11 10:00:00', 0),
(86, 'Medical Laboratory Assistant', 'MediTest', 'Manchester', 31000, 'Full-time', 'Healthcare', 'Prepare lab samples', '2026-03-11 10:00:00', 0),
(87, 'Clinical Data Manager', 'HealthData UK', 'London', 58000, 'Full-time', 'Healthcare', 'Manage clinical datasets', '2026-03-11 10:00:00', 0),
(88, 'Public Health Advisor', 'HealthAuthority', 'London', 61000, 'Full-time', 'Healthcare', 'Promote public health programs', '2026-03-11 10:00:00', 0),
(89, 'Community Outreach Officer', 'Unity Outreach', 'Bradford', 37000, 'Full-time', 'Community', 'Support local programmes', '2026-03-11 10:00:00', 0),
(90, 'Nonprofit Program Manager', 'HopeFoundation', 'Leeds', 46000, 'Full-time', 'Nonprofit', 'Manage charity projects', '2026-03-11 10:00:00', 0),
(91, 'Ethics Consultant', 'MoralAdvisors', 'London', 62000, 'Full-time', 'Consulting', 'Advise ethical decisions', '2026-03-11 10:00:00', 0),
(92, 'Faith Community Coordinator', 'FaithBridge', 'Bradford', 39000, 'Full-time', 'Community', 'Coordinate faith events', '2026-03-11 10:00:00', 0),
(93, 'Research Assistant', 'Discovery Labs', 'Leeds', 34000, 'Full-time', 'Research', 'Support scientific research', '2026-03-11 10:00:00', 0),
(94, 'Innovation Consultant', 'FutureEdge', 'London', 70000, 'Full-time', 'Consulting', 'Advise on innovation strategy', '2026-03-11 10:00:00', 1),
(95, 'Technology Consultant', 'TechAdvisory', 'Manchester', 68000, 'Full-time', 'Technology', 'Advise tech solutions', '2026-03-11 10:00:00', 1),
(96, 'Startup Founder', 'LaunchLab', 'London', 60000, 'Full-time', 'Entrepreneurship', 'Lead startup operations', '2026-03-11 10:00:00', 1),
(97, 'Business Development Manager', 'GrowthCorp', 'London', 66000, 'Full-time', 'Business', 'Expand company partnerships', '2026-03-11 10:00:00', 0),
(98, 'Strategic Planner', 'VisionStrategy', 'Leeds', 58000, 'Full-time', 'Business', 'Plan long-term company strategy', '2026-03-11 10:00:00', 0),
(99, 'Policy Analyst', 'GovInsight', 'London', 57000, 'Full-time', 'Government', 'Analyse public policy', '2026-03-11 10:00:00', 0),
(100, 'Innovation Researcher', 'FutureThink Labs', 'London', 63000, 'Full-time', 'Research', 'Research emerging technologies', '2026-03-11 10:00:00', 0),
(101, 'Junior Software Developer', 'BlueStack Systems', 'Leeds', 42000, 'Full-time', 'Technology', 'Assist in developing web applications', '2026-03-11 10:00:00', 1),
(102, 'Civil Engineering Technician', 'BuildAxis Ltd', 'Sheffield', 39000, 'Full-time', 'Engineering', 'Support civil engineering design projects', '2026-03-11 10:00:00', 1),
(103, 'IT Security Specialist', 'CyberFort UK', 'London', 67000, 'Full-time', 'Cybersecurity', 'Protect company networks and systems', '2026-03-11 10:00:00', 1),
(104, 'Pharmacy Assistant', 'WellCare Pharmacy', 'Leeds', 30000, 'Full-time', 'Healthcare', 'Support pharmacists with dispensing medication', '2026-03-11 10:00:00', 1),
(105, 'Construction Site Manager', 'UrbanRise Construction', 'Manchester', 64000, 'Full-time', 'Construction', 'Manage day-to-day construction site work', '2026-03-11 10:00:00', 1),
(106, 'Automotive Service Advisor', 'DriveFix Garage', 'Bradford', 36000, 'Full-time', 'Automotive', 'Assist customers with vehicle service needs', '2026-03-11 10:00:00', 1),
(107, 'Junior Data Analyst', 'DataVision Ltd', 'Leeds', 41000, 'Full-time', 'Data', 'Analyse company datasets', '2026-03-11 10:00:00', 1),
(108, 'Cloud Systems Administrator', 'NimbusTech', 'Remote', 59000, 'Full-time', 'Cloud', 'Maintain cloud infrastructure', '2026-03-11 10:00:00', 1),
(109, 'Family Lawyer', 'LegalPoint LLP', 'London', 71000, 'Full-time', 'Legal', 'Handle family law cases', '2026-03-11 10:00:00', 1),
(110, 'Electrical Maintenance Engineer', 'VoltWorks', 'Birmingham', 53000, 'Full-time', 'Engineering', 'Maintain electrical systems', '2026-03-11 10:00:00', 1),
(111, 'UX Researcher', 'DesignPulse', 'Manchester', 50000, 'Full-time', 'Design', 'Study user behaviour and product usability', '2026-03-11 10:00:00', 1),
(112, 'Healthcare Administrator', 'CityHealth NHS', 'Leeds', 45000, 'Full-time', 'Healthcare', 'Coordinate healthcare services', '2026-03-11 10:00:00', 1),
(113, 'Digital Content Manager', 'MediaSpark', 'London', 55000, 'Full-time', 'Marketing', 'Manage digital media content', '2026-03-11 10:00:00', 1),
(114, 'Software QA Tester', 'TestRight Labs', 'Manchester', 46000, 'Full-time', 'Technology', 'Test applications for bugs', '2026-03-11 10:00:00', 1),
(115, 'Machine Learning Analyst', 'AIWorks', 'London', 68000, 'Full-time', 'AI', 'Support machine learning models', '2026-03-11 10:00:00', 1),
(116, 'Corporate Compliance Officer', 'RegulaCorp', 'London', 62000, 'Full-time', 'Legal', 'Ensure regulatory compliance', '2026-03-11 10:00:00', 1),
(117, 'Hospital Pharmacist', 'HealthBridge Medical', 'Leeds', 60000, 'Full-time', 'Healthcare', 'Provide hospital pharmaceutical services', '2026-03-11 10:00:00', 1),
(118, 'Mechanical Design Engineer', 'MechaDesign', 'Birmingham', 57000, 'Full-time', 'Engineering', 'Design mechanical components', '2026-03-11 10:00:00', 1),
(119, 'Cyber Incident Responder', 'SecureLayer', 'London', 69000, 'Full-time', 'Cybersecurity', 'Respond to cyber incidents', '2026-03-11 10:00:00', 1),
(120, 'Business Strategy Consultant', 'StrategyHub', 'London', 72000, 'Full-time', 'Consulting', 'Advise business growth strategies', '2026-03-11 10:00:00', 1),
(121, 'Retail Operations Supervisor', 'ShopSmart Ltd', 'Leeds', 38000, 'Full-time', 'Retail', 'Oversee daily retail operations', '2026-03-11 10:00:00', 1),
(122, 'Biomedical Lab Technician', 'LifeTest Labs', 'Manchester', 35000, 'Full-time', 'Healthcare', 'Assist biomedical scientists', '2026-03-11 10:00:00', 1),
(123, 'Technical Project Lead', 'InnovateTech', 'London', 73000, 'Full-time', 'Technology', 'Lead development teams', '2026-03-11 10:00:00', 1),
(124, 'Environmental Consultant', 'EcoAdvisory', 'Leeds', 54000, 'Full-time', 'Environment', 'Advise sustainable projects', '2026-03-11 10:00:00', 1),
(125, 'AI Research Scientist', 'FutureAI Labs', 'London', 75000, 'Full-time', 'AI', 'Conduct artificial intelligence research', '2026-03-11 10:00:00', 1),
(126, 'Warehouse Operative', 'LogistiStore', 'Leeds', 29000, 'Full-time', 'Logistics', 'Manage warehouse stock', '2026-03-11 10:00:00', 0),
(127, 'Junior Civil Engineer', 'BridgePoint Engineering', 'Leeds', 44000, 'Full-time', 'Engineering', 'Assist infrastructure design', '2026-03-11 10:00:00', 0),
(128, 'IT Helpdesk Technician', 'SupportDesk UK', 'Manchester', 34000, 'Full-time', 'IT Support', 'Provide technical support', '2026-03-11 10:00:00', 0),
(129, 'Digital Marketing Assistant', 'AdLaunch', 'London', 36000, 'Full-time', 'Marketing', 'Assist marketing campaigns', '2026-03-11 10:00:00', 0),
(130, 'Finance Assistant', 'MoneyTrack Ltd', 'Leeds', 37000, 'Full-time', 'Finance', 'Support accounting department', '2026-03-11 10:00:00', 0),
(131, 'Junior Cyber Security Analyst', 'ThreatGuard', 'Sheffield', 43000, 'Full-time', 'Cybersecurity', 'Monitor cyber threats', '2026-03-11 10:00:00', 0),
(132, 'Community Support Worker', 'HelpingHands', 'Bradford', 31000, 'Full-time', 'Community', 'Assist local community programs', '2026-03-11 10:00:00', 0),
(133, 'IT Systems Technician', 'CoreNet Solutions', 'Leeds', 41000, 'Full-time', 'IT', 'Maintain IT systems', '2026-03-11 10:00:00', 0),
(134, 'Junior Accountant', 'ClearLedger', 'Manchester', 42000, 'Full-time', 'Finance', 'Prepare financial reports', '2026-03-11 10:00:00', 0),
(135, 'Pharmacy Dispenser', 'CareMeds Pharmacy', 'Leeds', 32000, 'Full-time', 'Healthcare', 'Prepare prescriptions', '2026-03-11 10:00:00', 0),
(136, 'Mechanical Workshop Technician', 'AutoTech Industries', 'Bradford', 36000, 'Full-time', 'Engineering', 'Maintain workshop machinery', '2026-03-11 10:00:00', 0),
(137, 'Product Support Specialist', 'DeviceCore', 'London', 47000, 'Full-time', 'Technology', 'Assist product customers', '2026-03-11 10:00:00', 0),
(138, 'Legal Secretary', 'CourtAssist', 'London', 33000, 'Full-time', 'Legal', 'Support legal teams', '2026-03-11 10:00:00', 0),
(139, 'Research Intern', 'ScholarLab', 'Leeds', 28000, 'Full-time', 'Research', 'Assist research projects', '2026-03-11 10:00:00', 0),
(140, 'Network Support Engineer', 'NetFlow Systems', 'Manchester', 48000, 'Full-time', 'Networking', 'Support network operations', '2026-03-11 10:00:00', 0),
(141, 'Healthcare Receptionist', 'CityCare Clinic', 'Leeds', 30000, 'Full-time', 'Healthcare', 'Manage patient appointments', '2026-03-11 10:00:00', 0),
(142, 'Software Support Analyst', 'AppAssist Ltd', 'London', 44000, 'Full-time', 'Technology', 'Provide application support', '2026-03-11 10:00:00', 0),
(143, 'Transport Logistics Planner', 'MoveSmart Logistics', 'Birmingham', 46000, 'Full-time', 'Logistics', 'Plan transport operations', '2026-03-11 10:00:00', 0),
(144, 'Graphic Design Assistant', 'CreativeInk', 'Manchester', 35000, 'Full-time', 'Design', 'Assist with visual designs', '2026-03-11 10:00:00', 0),
(145, 'Junior HR Officer', 'PeopleBridge', 'Leeds', 39000, 'Full-time', 'Human Resources', 'Support HR tasks', '2026-03-11 10:00:00', 0),
(146, 'Sales Executive', 'MarketPro Ltd', 'London', 52000, 'Full-time', 'Sales', 'Sell company products', '2026-03-11 10:00:00', 0),
(147, 'Quality Control Inspector', 'PrecisionWorks', 'Sheffield', 41000, 'Full-time', 'Manufacturing', 'Inspect product quality', '2026-03-11 10:00:00', 0),
(148, 'Construction Labourer', 'BuildStrong Ltd', 'Birmingham', 31000, 'Full-time', 'Construction', 'Assist construction teams', '2026-03-11 10:00:00', 0),
(149, 'IT Trainer', 'SkillTech Academy', 'Leeds', 49000, 'Full-time', 'Education', 'Train employees in IT skills', '2026-03-11 10:00:00', 0),
(150, 'Customer Service Advisor', 'HelpLine UK', 'Manchester', 33000, 'Full-time', 'Customer Service', 'Assist customer enquiries', '2026-03-11 10:00:00', 0);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `links` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `profile_picture`, `cv_file`, `bio`, `location`, `phone`, `skills`, `education`, `experience`, `cv`, `links`, `role`) VALUES
(1, 'Martins Tifentals', 'mtifental@gmail.com', '$2y$10$NduA4D4eIE8kHgcCRH.h9u.BA3Ds1Miw0klN5Ng7PqXdEifVFTwXW', '2026-02-18 21:44:00', NULL, NULL, '', '', '', '', '', '', NULL, '', 'admin');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

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
