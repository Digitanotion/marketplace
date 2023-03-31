-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2022 at 11:43 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mallusers`
--

-- --------------------------------------------------------

--
-- Table structure for table `mallusrs`
--

CREATE TABLE `mallusrs` (
  `defaultUsrID` int(100) NOT NULL,
  `mallUsrID` varchar(40) DEFAULT NULL,
  `mallUsrFirstName` varchar(80) DEFAULT NULL,
  `mallUsrLastName` varchar(80) DEFAULT NULL,
  `mallUsrLocation` varchar(30) DEFAULT NULL,
  `mallUsrBirthday` varchar(30) DEFAULT NULL,
  `mallUsrSex` varchar(10) DEFAULT NULL,
  `mallUsrPhoto` varchar(40) DEFAULT NULL,
  `mallUsrPhoneNo` varchar(15) DEFAULT NULL,
  `mallUsrPhoneNoStatus` int(1) DEFAULT NULL,
  `mallUsrEmail` varchar(40) DEFAULT NULL,
  `mallUsrEmailStatus` int(1) DEFAULT NULL,
  `mallUsrOnline` int(1) DEFAULT NULL,
  `mallUsrPassword` varchar(40) DEFAULT NULL,
  `mallUsrBalance` int(10) DEFAULT NULL,
  `mallUsrAccountStatus` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mallusrs`
--

INSERT INTO `mallusrs` (`defaultUsrID`, `mallUsrID`, `mallUsrFirstName`, `mallUsrLastName`, `mallUsrLocation`, `mallUsrBirthday`, `mallUsrSex`, `mallUsrPhoto`, `mallUsrPhoneNo`, `mallUsrPhoneNoStatus`, `mallUsrEmail`, `mallUsrEmailStatus`, `mallUsrOnline`, `mallUsrPassword`, `mallUsrBalance`, `mallUsrAccountStatus`) VALUES
(1, '60776fe9c6', 'Nnebedum', 'Ikechukwu', '', '', '', '', '08165190800992', 1, 'kenechukwuokafor7@gmail.com', 1, 1, '123', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mallusrs`
--
ALTER TABLE `mallusrs`
  ADD PRIMARY KEY (`defaultUsrID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mallusrs`
--
ALTER TABLE `mallusrs`
  MODIFY `defaultUsrID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
