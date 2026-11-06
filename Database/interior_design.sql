-- CK Interior Design Platform - database for all 48 user stories
-- Import into an empty database called `interior_design` (phpMyAdmin > Import).
--
-- Changes from the original schema:
--   useraccount  + userId (primary key)          -> #12 search by ID, links projects/companies/shortlists to a user
--   userprofile  + status                         -> #6 suspend profile
--   company      + designerID                     -> #15-#19 one company per designer
--   idp          + stage, createdAt, updatedAt    -> #27-#36 current vs past projects, #46-#48 reports
--   shortlist    + createdAt                      -> #24-#26, #38, reports
--   report       + reportType                     -> #46-#48 daily / weekly / monthly
--   project_views (new table)                     -> #23 counts each view, #37 view statistics
--
-- Demo logins (passwords stored hashed):
--   User Admin:        admin / admin123        manager / Manager123!
--   Platform Manager:  platform / Platform123!
--   Interior Designer: designer / Designer123!  designer2 / Designer234!
--   Customer:          customer / Customer123!  customer2 / Customer234!

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Users: login accounts (#1, #8-#12) and role profiles (#3-#7)
-- --------------------------------------------------------

CREATE TABLE `useraccount` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `useraccount` (`userId`, `username`, `password`, `email`, `status`) VALUES
(1, 'admin', '$2y$10$O.3./A9UIBIWigycLsVwC.Y6zInPzxN5BMVW/HY4BelPXUwLvFPiS', 'admin@ck.com', 'Active'),
(2, 'manager', '$2y$10$DvVc.SwilWJrvT.Swt505.vWwDFOw7LOXLtatfuXS/9aRePR1S9LW', 'manager@example.com', 'Active'),
(3, 'platform', '$2y$10$V/vXr7cKKNgdeIIusAAb2esu9wLAa/gXuzygYDrmaT.KeNvN1O1Q2', 'platform@example.com', 'Active'),
(4, 'designer', '$2y$10$7NgXCVYYFZ2qfUku0siNie9YXTTB4et2fUcb/iBm7VcLwYusQ3p8y', 'designer@example.com', 'Active'),
(5, 'designer2', '$2y$10$.7K49dbvA7IhHe6cZFgA/.MdJZv5T1bo/GpjRVoj4hUWp4eVn2yMy', 'alex@example.com', 'Active'),
(6, 'customer', '$2y$10$no/zTGOg1N6hPVb4CQq4sOpHH88xEKsLe2e/bHhx2cDIEtb.fi4Bq', 'customer@example.com', 'Active'),
(7, 'customer2', '$2y$10$PbAz0agOIkKTeqQSyJq1NuBoziTUt2twhbJ5UGpsltsXEiTZurP9O', 'jamie@example.com', 'Active');

CREATE TABLE `userprofile` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `userprofile` (`username`, `password`, `name`, `type`, `status`) VALUES
('admin', '$2y$10$O.3./A9UIBIWigycLsVwC.Y6zInPzxN5BMVW/HY4BelPXUwLvFPiS', 'System Admin', 'User Admin', 'Active'),
('manager', '$2y$10$DvVc.SwilWJrvT.Swt505.vWwDFOw7LOXLtatfuXS/9aRePR1S9LW', 'Demo Manager', 'User Admin', 'Active'),
('platform', '$2y$10$V/vXr7cKKNgdeIIusAAb2esu9wLAa/gXuzygYDrmaT.KeNvN1O1Q2', 'Platform Manager', 'Platform Manager', 'Active'),
('designer', '$2y$10$7NgXCVYYFZ2qfUku0siNie9YXTTB4et2fUcb/iBm7VcLwYusQ3p8y', 'Demo Designer', 'Interior Designer', 'Active'),
('designer2', '$2y$10$.7K49dbvA7IhHe6cZFgA/.MdJZv5T1bo/GpjRVoj4hUWp4eVn2yMy', 'Alex Tan', 'Interior Designer', 'Active'),
('customer', '$2y$10$no/zTGOg1N6hPVb4CQq4sOpHH88xEKsLe2e/bHhx2cDIEtb.fi4Bq', 'Demo Customer', 'Customer', 'Active'),
('customer2', '$2y$10$PbAz0agOIkKTeqQSyJq1NuBoziTUt2twhbJ5UGpsltsXEiTZurP9O', 'Jamie Lee', 'Customer', 'Active');

-- --------------------------------------------------------
-- IDP categories (#41-#45)
-- --------------------------------------------------------

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `category` (`categoryID`, `categoryName`, `description`) VALUES
(1, 'Residential', 'Homes and apartments'),
(2, 'Commercial', 'Offices and retail spaces'),
(3, 'Hospitality', 'Hotels, cafes and restaurants'),
(4, 'Minimalist', 'Simple and uncluttered design'),
(5, 'Scandinavian', 'Natural materials and light colours'),
(6, 'Industrial', 'Exposed materials and raw finishes (not used yet - try deleting it for #44)');

-- --------------------------------------------------------
-- Company information, one per designer (#15-#19)
-- --------------------------------------------------------

CREATE TABLE `company` (
  `companyID` int(11) NOT NULL AUTO_INCREMENT,
  `companyName` varchar(150) NOT NULL,
  `regNum` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `designerID` int(11) NOT NULL,
  PRIMARY KEY (`companyID`),
  UNIQUE KEY `designerID` (`designerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `company` (`companyID`, `companyName`, `regNum`, `description`, `address`, `designerID`) VALUES
(1, 'CK Interiors', '202612345K', 'Full-home design and renovation management for HDB flats and condominiums.', '10 Anson Road, Singapore 079903', 4),
(2, 'Alex Tan Studio', '202698765W', 'Commercial and hospitality interiors with a warm, natural style.', '3 Temasek Boulevard, Singapore 038983', 5);

-- --------------------------------------------------------
-- Interior design projects (#22, #23, #27-#36)
-- photos holds the image path inside Assets/ (e.g. projects/family-home.jpg)
-- --------------------------------------------------------

CREATE TABLE `idp` (
  `projectID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `photos` longblob NOT NULL,
  `designerID` int(11) NOT NULL,
  `status` varchar(30) NOT NULL,
  `progress` int(3) NOT NULL,
  `stage` varchar(20) NOT NULL DEFAULT 'Current',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`projectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `idp` (`projectID`, `title`, `category`, `description`, `photos`, `designerID`, `status`, `progress`, `stage`, `createdAt`, `updatedAt`) VALUES
(1, 'Scandinavian Family Home', 'Scandinavian', 'A bright family home with oak furniture, soft neutral colours and natural light.', 'projects/family-home.jpg', 4, 'In Progress', 65, 'Current', NOW() - INTERVAL 20 DAY, NOW()),
(2, 'Modern City Apartment', 'Minimalist', 'A compact apartment with smart storage and an open-plan living area.', 'projects/city-apartment.jpg', 4, 'Planning', 20, 'Current', NOW() - INTERVAL 3 DAY, NOW()),
(3, 'Creative Studio Office', 'Commercial', 'A flexible workspace with collaborative tables and warm task lighting.', 'projects/studio-office.jpg', 5, 'In Progress', 45, 'Current', NOW() - INTERVAL 10 HOUR, NOW()),
(4, 'Boutique Cafe Renovation', 'Hospitality', 'A welcoming cafe-boutique with timber finishes, greenery and comfortable seating.', 'projects/boutique-cafe.jpg', 5, 'Completed', 100, 'Past', NOW() - INTERVAL 60 DAY, NOW() - INTERVAL 40 DAY),
(5, 'Hotel Suite Refresh', 'Hospitality', 'A relaxing hotel suite with layered lighting, textured fabrics and city views.', 'projects/hotel-suite.webp', 4, 'Completed', 100, 'Past', NOW() - INTERVAL 45 DAY, NOW() - INTERVAL 25 DAY);

-- --------------------------------------------------------
-- Customer shortlists (#24-#26, #38)
-- customerID = useraccount.userId of the customer
-- --------------------------------------------------------

CREATE TABLE `shortlist` (
  `shortlistID` int(11) NOT NULL AUTO_INCREMENT,
  `customerID` int(11) NOT NULL,
  `projectID` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`shortlistID`),
  UNIQUE KEY `customer_project` (`customerID`, `projectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `shortlist` (`customerID`, `projectID`, `createdAt`) VALUES
(6, 1, NOW() - INTERVAL 2 DAY),
(6, 3, NOW() - INTERVAL 5 HOUR),
(7, 1, NOW() - INTERVAL 12 DAY),
(7, 5, NOW() - INTERVAL 1 HOUR);

-- --------------------------------------------------------
-- Project views (#23 records, #37 counts, #46-#48 reports)
-- --------------------------------------------------------

CREATE TABLE `project_views` (
  `viewID` int(11) NOT NULL AUTO_INCREMENT,
  `projectID` int(11) NOT NULL,
  `viewedAt` datetime NOT NULL,
  PRIMARY KEY (`viewID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_views` (`projectID`, `viewedAt`) VALUES
(1, NOW() - INTERVAL 2 HOUR), (1, NOW() - INTERVAL 1 DAY - INTERVAL 3 HOUR), (1, NOW() - INTERVAL 4 DAY),
(1, NOW() - INTERVAL 15 DAY), (2, NOW() - INTERVAL 6 HOUR), (3, NOW() - INTERVAL 1 HOUR),
(3, NOW() - INTERVAL 3 DAY), (4, NOW() - INTERVAL 20 DAY), (5, NOW() - INTERVAL 30 MINUTE),
(5, NOW() - INTERVAL 2 DAY);

-- --------------------------------------------------------
-- Generated reports (#46-#48)
-- --------------------------------------------------------

CREATE TABLE `report` (
  `reportID` int(11) NOT NULL AUTO_INCREMENT,
  `reportType` varchar(10) NOT NULL,
  `generationDate` datetime NOT NULL,
  `summaryData` text NOT NULL,
  PRIMARY KEY (`reportID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Original tables kept unchanged (not used by the current code)
-- --------------------------------------------------------

CREATE TABLE `customeraccount` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
