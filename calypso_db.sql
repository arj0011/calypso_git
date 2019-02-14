-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 29, 2018 at 03:58 PM
-- Server version: 5.7.20
-- PHP Version: 7.0.26-2+ubuntu16.04.1+deb.sury.org+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calypso_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint(20) NOT NULL,
  `title` varchar(250) NOT NULL,
  `message` text NOT NULL,
  `user_ids` text,
  `type_id` int(11) DEFAULT NULL,
  `notification_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-status change,2-offer,3-best offer',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-alacart,2-foodparcel,3-partypackage',
  `status` enum('PENDING','COMPLETED') NOT NULL DEFAULT 'PENDING',
  `sent_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_notifications`
--

INSERT INTO `admin_notifications` (`id`, `title`, `message`, `user_ids`, `type_id`, `notification_type`, `type`, `status`, `sent_time`) VALUES
(1, 'New Product', 'New product has been launched', 'N;', 34, 2, 0, 'COMPLETED', '2017-09-28 15:36:44'),
(2, 'New Product', 'New product has been launched', 'N;', 35, 2, 0, 'COMPLETED', '2017-09-28 15:38:58'),
(3, 'New Product', 'New product has been launched', 'N;', 36, 2, 0, 'COMPLETED', '2017-09-28 18:51:58'),
(4, 'New Product', 'New product has been launched', 'N;', 37, 2, 0, 'COMPLETED', '2017-09-28 18:58:44'),
(5, 'New Product', 'New product has been launched', NULL, 38, 2, 0, 'COMPLETED', '2017-10-04 16:45:49'),
(6, 'New Product', 'New product has been launched', NULL, 39, 2, 0, 'COMPLETED', '2017-10-04 17:13:41'),
(7, 'New Product', 'New product has been launched', 'a:8:{i:0;s:1:"2";i:1;s:1:"3";i:2;s:2:"26";i:3;s:2:"27";i:4;s:2:"28";i:5;s:2:"29";i:6;s:2:"30";i:7;s:2:"31";}', 40, 2, 0, 'COMPLETED', '2017-10-04 17:57:02'),
(8, 'New Product', 'New product has been launched', 'a:11:{i:0;s:1:"2";i:1;s:1:"3";i:2;s:2:"26";i:3;s:2:"27";i:4;s:2:"28";i:5;s:2:"29";i:6;s:2:"30";i:7;s:2:"31";i:8;s:2:"32";i:9;s:2:"33";i:10;s:2:"34";}', 41, 2, 0, 'COMPLETED', '2017-10-07 11:51:29'),
(9, 'New Product', 'New product has been launched', 'a:11:{i:0;s:1:"2";i:1;s:1:"3";i:2;s:2:"26";i:3;s:2:"27";i:4;s:2:"28";i:5;s:2:"29";i:6;s:2:"30";i:7;s:2:"31";i:8;s:2:"32";i:9;s:2:"33";i:10;s:2:"34";}', 42, 2, 0, 'COMPLETED', '2017-10-07 11:54:24'),
(10, 'New Product', 'New product has been launched', 'a:11:{i:0;s:1:"2";i:1;s:1:"3";i:2;s:2:"26";i:3;s:2:"27";i:4;s:2:"28";i:5;s:2:"29";i:6;s:2:"30";i:7;s:2:"31";i:8;s:2:"32";i:9;s:2:"33";i:10;s:2:"34";}', 43, 2, 0, 'COMPLETED', '2017-10-07 12:11:18'),
(11, 'New Product', 'New product has been launched', 'a:11:{i:0;s:1:"2";i:1;s:1:"3";i:2;s:2:"26";i:3;s:2:"27";i:4;s:2:"28";i:5;s:2:"29";i:6;s:2:"30";i:7;s:2:"31";i:8;s:2:"32";i:9;s:2:"33";i:10;s:2:"34";}', 44, 2, 0, 'COMPLETED', '2017-10-07 12:43:40'),
(15, 'Offer', '10% OFF Your Al-a Cart Bill', 'a:2:{i:0;s:2:"35";i:2;s:2:"49";}', 1, 3, 0, 'PENDING', '2017-12-29 15:46:11'),
(16, 'Offer', 'New Offer arrived on specific quantity', 'a:3:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";}', 34, 3, 0, 'PENDING', '2018-01-02 18:59:52'),
(17, 'Offer', 'New Offer arrived on specific quantity', 'a:3:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";}', 35, 3, 0, 'PENDING', '2018-01-02 19:15:38'),
(18, 'Best Offer', 'New Offer on order of quantity 10', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 36, 3, 1, 'PENDING', '2018-01-06 19:45:33'),
(19, 'Best Offer', 'New Offer on order of quantity 10', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 37, 3, 1, 'PENDING', '2018-01-10 11:27:51'),
(20, 'Best Offer', 'New Offer on order of quantity 10', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 38, 3, 2, 'PENDING', '2018-01-10 11:33:09'),
(21, 'Best Offer', 'New Offer on order of quantity 10', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 39, 3, 3, 'PENDING', '2018-01-10 11:39:39'),
(22, 'Offer', '3% OFF on Al-a Cart Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 2, 2, 1, 'PENDING', '2018-01-10 15:40:14'),
(23, 'Offer', '12% OFF on Al-a Cart Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 3, 2, 1, 'PENDING', '2018-01-10 16:20:04'),
(24, 'Offer', '5% OFF on Al-a Cart Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 4, 2, 1, 'PENDING', '2018-01-11 13:02:10'),
(25, 'Offer', '7% OFF on Al-a Cart Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 5, 2, 1, 'PENDING', '2018-01-11 13:02:29'),
(26, 'Offer', '900% OFF on Al-a Cart Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 6, 2, 1, 'PENDING', '2018-01-11 13:02:48'),
(27, 'Offer', '11% OFF on Al-a Cart Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 7, 2, 1, 'PENDING', '2018-01-11 13:03:08'),
(28, 'Offer', '11% OFF on Foodparcel Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 8, 2, 2, 'PENDING', '2018-01-11 13:13:05'),
(29, 'Offer', '15% OFF on Foodparcel Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 9, 2, 2, 'PENDING', '2018-01-11 13:13:36'),
(30, 'Offer', '19% OFF on Foodparcel Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 10, 2, 2, 'PENDING', '2018-01-11 13:13:56'),
(31, 'Offer', '10% OFF on Partypackage Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 11, 2, 3, 'PENDING', '2018-01-11 13:18:45'),
(32, 'Offer', '5% OFF on Partypackage Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 12, 2, 3, 'PENDING', '2018-01-11 13:19:14'),
(33, 'Offer', '13% OFF on Partypackage Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 13, 2, 3, 'PENDING', '2018-01-11 13:20:10'),
(34, 'Best Offer', 'New Offer on order of quantity 3', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 40, 3, 1, 'PENDING', '2018-01-11 14:48:09'),
(35, 'Offer', '45435% OFF on Foodparcel Bill', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 14, 2, 2, 'PENDING', '2018-01-12 14:17:09'),
(36, 'Best Offer', 'New Offer on order of quantity 34', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 41, 3, 1, 'PENDING', '2018-01-12 16:30:56'),
(37, 'Best Offer', 'New Offer on order of quantity 434', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 42, 3, 1, 'PENDING', '2018-01-12 16:57:05'),
(38, 'Best Offer', 'New Offer on order of quantity 45', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 43, 3, 2, 'PENDING', '2018-01-12 18:01:53'),
(39, 'Best Offer', 'New Offer on order of quantity 1', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 44, 3, 1, 'PENDING', '2018-01-17 15:52:50'),
(40, 'Best Offer', 'New Offer on order of quantity 5476', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 45, 3, 1, 'PENDING', '2018-01-19 15:17:03'),
(41, 'Best Offer', 'New Offer on order of quantity 10', 'a:6:{i:0;s:2:"35";i:1;s:2:"42";i:2;s:2:"49";i:3;s:2:"50";i:4;s:2:"51";i:5;s:2:"52";}', 46, 3, 1, 'PENDING', '2018-01-20 11:23:23');

-- --------------------------------------------------------

--
-- Table structure for table `alla_cart`
--

CREATE TABLE `alla_cart` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `strike_price` float(8,2) DEFAULT NULL,
  `price` float(8,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alla_cart`
--

INSERT INTO `alla_cart` (`id`, `category_id`, `item_name`, `strike_price`, `price`, `image`, `description`, `status`, `created_date`) VALUES
(2, 8, 'Chikan Biryani', NULL, 300.00, '1507974740_biryani.jpg', 'Chikan Biryani', 1, '2017-10-14 15:22:20'),
(3, 2, 'Dal Pulao', NULL, 100.00, '1507974927_dal-pulao.jpg', 'Dal Pulao', 1, '2017-10-14 15:25:27'),
(4, 10, 'Burger', NULL, 100.00, '1507974965_Fast_food_meal.jpg', 'Burger', 1, '2017-10-14 15:26:05'),
(5, 11, 'Chikan Burger', NULL, 150.00, '1507975014_junk-food.jpg', 'Chikan Burger', 1, '2017-10-14 15:26:54'),
(6, 8, 'Chikan Matan', NULL, 300.00, '1508144822_chickenT.jpg', 'Chikan pulao', 1, '2017-10-16 14:37:02'),
(7, 8, 'Chikan Pulao', NULL, 450.00, '1508144877_non-veg-foods-500x500.jpg', 'Chikan Matan', 1, '2017-10-16 14:37:57'),
(8, 8, 'Chikan Tangdi', NULL, 500.00, '1508145051_chickenT.jpg', 'Chikan Tangdi', 1, '2017-10-16 14:40:51'),
(9, 11, 'Junk Transparent', NULL, 100.00, '1508145159_1-2-junk-food-transparent.png', 'Junk Transparent', 0, '2017-10-16 14:42:39'),
(10, 11, 'Food Pretgel', NULL, 200.00, '1508145193_07-8-ways-to-hate-junk-food-pretzels.jpg', 'Food Pretgel', 1, '2017-10-16 14:43:13'),
(11, 10, 'Paneer pitzza', NULL, 500.00, '1508145361_vegetables-italian-pizza-restaurant.jpg', 'Paneer pitzza', 1, '2017-10-16 14:46:01'),
(12, 10, 'Pitzza', NULL, 450.00, '1508145403_feature-image-kids-fast-food.jpg', 'Pitzza', 1, '2017-10-16 14:46:43'),
(13, 9, 'Zinhga', NULL, 600.00, '1508145506_zhinge.jpg', 'Zinhga', 1, '2017-10-16 14:48:26'),
(14, 2, 'Al-a cart 1', 111.00, 101.00, '1515231109_07-8-ways-to-hate-junk-food-pretzels.jpg', 'Description', 0, '2018-01-10 00:00:00'),
(15, 2, 'dfdsf', 123243.00, 3243.00, '1515742968_1-2-junk-food-transparent.png', 'dcxczxc', 1, '2018-01-12 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `billing_offer`
--

CREATE TABLE `billing_offer` (
  `id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `amount` float(8,2) NOT NULL,
  `discount` float(8,2) NOT NULL,
  `created` date DEFAULT NULL,
  `image` text NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0=inactive,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `billing_offer`
--

INSERT INTO `billing_offer` (`id`, `type`, `amount`, `discount`, `created`, `image`, `status`) VALUES
(1, 1, 500.00, 5.00, '2018-01-10', '1515579272_07-8-ways-to-hate-junk-food-pretzels.jpg', 1),
(2, 1, 1000.00, 10.00, '2018-01-10', '1515579014_1-2-junk-food-transparent.png', 1),
(3, 1, 1500.00, 12.00, '2018-01-10', '1515581404_biryani.jpg', 1),
(4, 1, 500.00, 5.00, '2018-01-11', '1515655930_1-2-junk-food-transparent.png', 1),
(5, 1, 700.00, 7.00, '2018-01-11', '1515655949_07-8-ways-to-hate-junk-food-pretzels.jpg', 1),
(6, 1, 900.00, 900.00, '2018-01-11', '1515655968_biryani.jpg', 1),
(7, 1, 1100.00, 11.00, '2018-01-11', '1515655988_1-2-junk-food-transparent.png', 1),
(8, 2, 1100.00, 11.00, '2018-01-11', '1515656585_1-2-junk-food-transparent.png', 1),
(9, 2, 1500.00, 15.00, '2018-01-11', '1515656616_07-8-ways-to-hate-junk-food-pretzels.jpg', 1),
(10, 2, 1900.00, 19.00, '2018-01-11', '1515656636_1-2-junk-food-transparent.png', 1),
(11, 3, 10000.00, 5.00, '2018-01-11', '1515656925_1-2-junk-food-transparent.png', 1),
(12, 3, 15000.00, 15.00, '2018-01-11', '1515656954_07-8-ways-to-hate-junk-food-pretzels.jpg', 1),
(13, 3, 20000.00, 17.00, '2018-01-11', '1515657010_1-2-junk-food-transparent.png', 1),
(14, 2, 44554.00, 45435.00, '2018-01-12', '1515746829_1-2-junk-food-transparent.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_image` text NOT NULL,
  `male` int(11) DEFAULT NULL,
  `female` int(11) DEFAULT NULL,
  `children` int(11) DEFAULT NULL,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `quanity` int(11) NOT NULL,
  `price` float(8,2) NOT NULL,
  `order_id` int(11) NOT NULL,
  `party_data` longtext,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `item_id`, `item_name`, `item_image`, `male`, `female`, `children`, `min_age`, `max_age`, `quanity`, `price`, `order_id`, `party_data`, `created`) VALUES
(1, 4, 'Punjabi Thali', 'uploads/foodparcel/1513087253_biryani.jpg', NULL, NULL, NULL, NULL, NULL, 1, 300.00, 1, NULL, '2017-12-13 20:36:39'),
(2, 5, 'Hydrabadi Thali', 'uploads/foodparcel/1513144012_hydrabadi.jpg', NULL, NULL, NULL, NULL, NULL, 1, 500.00, 1, NULL, '2017-12-13 20:36:39'),
(3, 1, 'Dal Makhni', 'uploads/allacart/1507974598_dalmakhni.jpg', NULL, NULL, NULL, NULL, NULL, 1, 200.00, 2, NULL, '2017-12-18 16:26:15'),
(4, 2, 'Chikan Biryani', 'uploads/allacart/1507974740_biryani.jpg', NULL, NULL, NULL, NULL, NULL, 1, 300.00, 4, NULL, '2017-12-18 17:23:32'),
(7, 1, 'Office Party', 'uploads/calypso/1509098502_officeparty.jpg', 1, 2, 3, NULL, NULL, 10, 100.00, 7, NULL, '2017-12-19 17:01:14'),
(8, 1, 'Office Party', 'uploads/calypso/1509098502_officeparty.jpg', 1, 2, 3, NULL, NULL, 10, 100.00, 8, NULL, '2017-12-19 20:27:30'),
(9, 1, 'Office Party', 'uploads/calypso/1509098502_officeparty.jpg', 1, 2, 3, NULL, NULL, 10, 100.00, 9, NULL, '2017-12-19 20:29:45'),
(10, 1, 'Office Party', 'uploads/calypso/1509098502_officeparty.jpg', 1, 2, 3, NULL, NULL, 10, 100.00, 10, NULL, '2017-12-19 20:33:35'),
(14, 4, 'burger', 'uploads/allacart/1507974965_Fast_food_meal.jpg', 1, 0, 0, NULL, NULL, 1, 1.00, 14, NULL, '2017-12-20 11:49:08'),
(16, 3, 'sdds', 'uploads/partypackage/1513693209_1-2-junk-food-transparent.png', 1, 1, 0, NULL, NULL, 1, 111.00, 16, NULL, '2017-12-20 18:06:40'),
(17, 1, 'Dal Makhni', 'uploads/allacart/1507974598_dalmakhni.jpg', 1, 1, 0, NULL, NULL, 1, 200.00, 17, NULL, '2017-12-21 19:12:23'),
(18, 1, 'Dal Makhni', 'uploads/allacart/1507974598_dalmakhni.jpg', 1, 1, 0, NULL, NULL, 1, 200.00, 18, NULL, '2017-12-21 19:26:52'),
(20, 4, 'Punjabi Thali', 'uploads/foodparcel/1513087253_biryani.jpg', NULL, NULL, NULL, NULL, NULL, 1, 300.00, 20, NULL, '2017-12-21 20:06:14'),
(21, 3, 'Xmas party', 'uploads/partypackage/1513693209_1-2-junk-food-transparent.png', 5, 3, 2, NULL, NULL, 1, 100.00, 22, NULL, '2017-12-21 20:34:20'),
(22, 12, 'Pitzza', 'uploads/allacart/1508145403_feature-image-kids-fast-food.jpg', 1, 0, NULL, NULL, NULL, 1, 450.00, 23, NULL, '2017-12-22 16:24:28'),
(23, 5, 'Hydrabadi Thali', 'uploads/foodparcel/1513144012_hydrabadi.jpg', NULL, NULL, NULL, NULL, NULL, 1, 500.00, 24, NULL, '2017-12-22 16:31:31'),
(24, 3, 'Xmas party', 'uploads/partypackage/1513693209_1-2-junk-food-transparent.png', 5, 3, 2, 50, NULL, 1, 100.00, 25, '[\r\n	{\r\n		"category_id":"2",\r\n		"category_name":"Veg Food",\r\n		"category_image":"http://localhost/calypso/uploads/category/1507805115_veg-food.jpg",\r\n		"limit":"1",\r\n		"items":"[\r\n			{\r\n				"item_id":"1",\r\n				"item_name":"Dal Makhni",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974598_dalmakhni.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"3",\r\n				"item_name":"Dal Pulao",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974927_dal-pulao.jpg",\r\n				"is_selected":"1"\r\n			}\r\n		]\r\n\r\n	},\r\n	{\r\n		"category_id":"8",\r\n		"category_name":"Non-Veg Food",\r\n		"category_image":"http://localhost//calypso/uploads/category/1507805167_non-veg_food.jpg",\r\n		"limit":"2",\r\n		"items":"[\r\n			{\r\n				"item_id":"2",\r\n				"item_name":"Chikan Biryani",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974740_biryani.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"7",\r\n				"item_name":"Chikan Pulao",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1508144877_non-veg-foods-500x500.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"8",\r\n				"item_name":"Chikan Tangdi",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1508145051_chickenT.jpg",\r\n				"is_selected":"1"\r\n			},\r\n		]\r\n	}\r\n]', '2017-12-22 16:56:55'),
(25, 3, 'Xmas party', 'uploads/partypackage/1513693209_1-2-junk-food-transparent.png', 5, 3, 2, 50, NULL, 1, 100.00, 26, '[\r\n	{\r\n		"category_id":"2",\r\n		"category_name":"Veg Food",\r\n		"category_image":"http://localhost/calypso/uploads/category/1507805115_veg-food.jpg",\r\n		"limit":"1",\r\n		"items":"[\r\n			{\r\n				"item_id":"1",\r\n				"item_name":"Dal Makhni",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974598_dalmakhni.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"3",\r\n				"item_name":"Dal Pulao",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974927_dal-pulao.jpg",\r\n				"is_selected":"1"\r\n			}\r\n		]\r\n\r\n	},\r\n	{\r\n		"category_id":"8",\r\n		"category_name":"Non-Veg Food",\r\n		"category_image":"http://localhost//calypso/uploads/category/1507805167_non-veg_food.jpg",\r\n		"limit":"2",\r\n		"items":"[\r\n			{\r\n				"item_id":"2",\r\n				"item_name":"Chikan Biryani",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974740_biryani.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"7",\r\n				"item_name":"Chikan Pulao",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1508144877_non-veg-foods-500x500.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"8",\r\n				"item_name":"Chikan Tangdi",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1508145051_chickenT.jpg",\r\n				"is_selected":"1"\r\n			},\r\n		]\r\n	}\r\n]', '2017-12-22 17:03:10'),
(26, 3, 'Xmas party', 'uploads/partypackage/1513693209_1-2-junk-food-transparent.png', 5, 3, 2, 50, 10, 1, 100.00, 27, '[\r\n	{\r\n		"category_id":"2",\r\n		"category_name":"Veg Food",\r\n		"category_image":"http://localhost/calypso/uploads/category/1507805115_veg-food.jpg",\r\n		"limit":"1",\r\n		"items":"[\r\n			{\r\n				"item_id":"1",\r\n				"item_name":"Dal Makhni",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974598_dalmakhni.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"3",\r\n				"item_name":"Dal Pulao",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974927_dal-pulao.jpg",\r\n				"is_selected":"1"\r\n			}\r\n		]\r\n\r\n	},\r\n	{\r\n		"category_id":"8",\r\n		"category_name":"Non-Veg Food",\r\n		"category_image":"http://localhost//calypso/uploads/category/1507805167_non-veg_food.jpg",\r\n		"limit":"2",\r\n		"items":"[\r\n			{\r\n				"item_id":"2",\r\n				"item_name":"Chikan Biryani",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974740_biryani.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"7",\r\n				"item_name":"Chikan Pulao",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1508144877_non-veg-foods-500x500.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"8",\r\n				"item_name":"Chikan Tangdi",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1508145051_chickenT.jpg",\r\n				"is_selected":"1"\r\n			},\r\n		]\r\n	}\r\n]', '2017-12-22 17:04:20'),
(28, 3, 'Xmas party', 'uploads/partypackage/1513693209_1-2-junk-food-transparent.png', 5, 3, 2, 50, 10, 1, 100.00, 29, '[\r\n	{\r\n		"category_id":"2",\r\n		"category_name":"Veg Food",\r\n		"category_image":"http://localhost/calypso/uploads/category/1507805115_veg-food.jpg",\r\n		"limit":"1",\r\n		"items":"[\r\n			{\r\n				"item_id":"1",\r\n				"item_name":"Dal Makhni",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974598_dalmakhni.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"3",\r\n				"item_name":"Dal Pulao",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974927_dal-pulao.jpg",\r\n				"is_selected":"1"\r\n			}\r\n		]\r\n\r\n	},\r\n	{\r\n		"category_id":"8",\r\n		"category_name":"Non-Veg Food",\r\n		"category_image":"http://localhost//calypso/uploads/category/1507805167_non-veg_food.jpg",\r\n		"limit":"2",\r\n		"items":"[\r\n			{\r\n				"item_id":"2",\r\n				"item_name":"Chikan Biryani",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1507974740_biryani.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"7",\r\n				"item_name":"Chikan Pulao",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1508144877_non-veg-foods-500x500.jpg",\r\n				"is_selected":"0"\r\n			},\r\n			{\r\n				"item_id":"8",\r\n				"item_name":"Chikan Tangdi",\r\n				"item_image":"http://localhost/calypso/uploads/allacart/1508145051_chickenT.jpg",\r\n				"is_selected":"1"\r\n			},\r\n		]\r\n	}\r\n]', '2017-12-22 17:20:36'),
(29, 2, 'Chikan Biryani', 'uploads/allacart/1507974740_biryani.jpg', 1, 0, NULL, NULL, NULL, 1, 300.00, 30, NULL, '2018-01-10 16:55:36'),
(30, 2, 'Chikan Biryani', 'uploads/allacart/1507974740_biryani.jpg', 1, 0, NULL, NULL, NULL, 1, 300.00, 31, NULL, '2018-01-10 17:02:10'),
(31, 27, 'Rayta', 'uploads/allacart/1514460484_Raita_with_cucumber_and_mint.jpg', 1, 0, NULL, NULL, NULL, 1, 30.00, 32, NULL, '2018-01-11 15:12:37'),
(32, 27, 'Rayta', 'uploads/allacart/1514460484_Raita_with_cucumber_and_mint.jpg', 1, 0, NULL, NULL, NULL, 1, 30.00, 33, NULL, '2018-01-11 15:15:56'),
(33, 1, 'Punjabi Thali', 'uploads/foodparcel/1515238068_pujabi-thali.jpg.jpg', NULL, NULL, NULL, NULL, NULL, 1, 100.00, 34, NULL, '2018-01-15 12:01:11'),
(34, 1, 'Punjabi Thali', 'uploads/foodparcel/1515238068_pujabi-thali.jpg.jpg', NULL, NULL, NULL, NULL, NULL, 1, 100.00, 35, NULL, '2018-01-15 12:02:06');

-- --------------------------------------------------------

--
-- Table structure for table `cart_product`
--

CREATE TABLE `cart_product` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart_product`
--

INSERT INTO `cart_product` (`id`, `product_id`, `user_id`, `name`, `price`, `qty`) VALUES
(56, 1, 26, 'GLUE DEVIL', 17.5, 3),
(57, 4, 26, '3D Chess 7 Wonders', 120, 5),
(58, 8, 10, 'Sony Playsation 4 Pro 1TB', 1250, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_management`
--

CREATE TABLE `category_management` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active',
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category_management`
--

INSERT INTO `category_management` (`id`, `category_name`, `parent_id`, `image`, `status`, `created_date`) VALUES
(2, 'Veg Food', 0, '1507805115_veg-food.jpg', 1, '2018-01-04 00:00:00'),
(8, 'Non-Veg Food', 0, '1507805167_non-veg_food.jpg', 1, '2017-10-09 16:16:07'),
(9, 'Sea Food', 0, '1507805185_see-food.jpg', 1, '2017-10-10 16:16:25'),
(10, 'Fast Food', 0, '1507805215_Fast_food_meal.jpg', 1, '2017-10-11 16:16:55'),
(11, 'Junk Food', 0, '1507886725_junk-food.jpg', 1, '2017-10-13 00:00:00'),
(12, 'Some category.', 0, '1515998516_1-2-junk-food-transparent.png', 1, '2018-01-15 00:00:00'),
(13, 'Some category2', 0, '1516683209_zinghafish.jpg', 1, '2018-01-23 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cms`
--

CREATE TABLE `cms` (
  `cms_id` int(11) NOT NULL,
  `page_id` varchar(50) NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `page_icon` varchar(100) DEFAULT NULL,
  `active` smallint(6) NOT NULL COMMENT '1= active, 0=inactive',
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cms`
--

INSERT INTO `cms` (`cms_id`, `page_id`, `description`, `image`, `created_by`, `page_icon`, `active`, `create_date`) VALUES
(1, 'about', '<p>adsdcsddsdsdsdddddsdsdsdserrr<br></p>', '', NULL, NULL, 1, '2017-09-19 16:22:43'),
(2, 'privacy_policy', '<p>cvgfg c gfgvvdfgdtgrfgfgfgff<br></p>', '', NULL, NULL, 1, '2017-09-19 16:33:43'),
(3, 'terms_condition', '<p>xcxcxfdfdddgg<br></p>', '', NULL, NULL, 1, '2017-10-07 12:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `configure_products`
--

CREATE TABLE `configure_products` (
  `id` int(11) NOT NULL,
  `key_id` int(11) NOT NULL,
  `key_value` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contact_support`
--

CREATE TABLE `contact_support` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `query` text NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_support`
--

INSERT INTO `contact_support` (`id`, `user_id`, `full_name`, `email`, `query`, `created_date`) VALUES
(1, 3, 'Preeti Birle', 'preetibirle.mobiwebtech@gmail.com', 'tesing', '2017-09-20 15:13:33');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `countries_id` int(11) NOT NULL,
  `countries_name` varchar(64) NOT NULL DEFAULT '',
  `countries_iso_code` varchar(2) NOT NULL,
  `countries_isd_code` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`countries_id`, `countries_name`, `countries_iso_code`, `countries_isd_code`) VALUES
(1, 'Afghanistan', 'AF', '0093'),
(2, 'Albania', 'AL', '00355'),
(3, 'Algeria', 'DZ', '00213'),
(4, 'American Samoa', 'AS', '001-684'),
(5, 'Andorra', 'AD', '00376'),
(6, 'Angola', 'AO', '00244'),
(7, 'Anguilla', 'AI', '001-264'),
(8, 'Antarctica', 'AQ', '00672'),
(9, 'Antigua and Barbuda', 'AG', '001-268'),
(10, 'Argentina', 'AR', '0054'),
(11, 'Armenia', 'AM', '00374'),
(12, 'Aruba', 'AW', '00297'),
(13, 'Australia', 'AU', '0061'),
(14, 'Austria', 'AT', '0043'),
(15, 'Azerbaijan', 'AZ', '00994'),
(16, 'Bahamas', 'BS', '001-242'),
(17, 'Bahrain', 'BH', '00973'),
(18, 'Bangladesh', 'BD', '00880'),
(19, 'Barbados', 'BB', '001-246'),
(20, 'Belarus', 'BY', '00375'),
(21, 'Belgium', 'BE', '0032'),
(22, 'Belize', 'BZ', '00501'),
(23, 'Benin', 'BJ', '00229'),
(24, 'Bermuda', 'BM', '001-441'),
(25, 'Bhutan', 'BT', '00975'),
(26, 'Bolivia', 'BO', '00591'),
(27, 'Bosnia and Herzegowina', 'BA', '00387'),
(28, 'Botswana', 'BW', '00267'),
(29, 'Bouvet Island', 'BV', '0047'),
(30, 'Brazil', 'BR', '0055'),
(31, 'British Indian Ocean Territory', 'IO', '00246'),
(32, 'Brunei Darussalam', 'BN', '00673'),
(33, 'Bulgaria', 'BG', '00359'),
(34, 'Burkina Faso', 'BF', '00226'),
(35, 'Burundi', 'BI', '00257'),
(36, 'Cambodia', 'KH', '00855'),
(37, 'Cameroon', 'CM', '00237'),
(38, 'Canada', 'CA', '001'),
(39, 'Cape Verde', 'CV', '00238'),
(40, 'Cayman Islands', 'KY', '001-345'),
(41, 'Central African Republic', 'CF', '00236'),
(42, 'Chad', 'TD', '00235'),
(43, 'Chile', 'CL', '0056'),
(44, 'China', 'CN', '0086'),
(45, 'Christmas Island', 'CX', '0061'),
(46, 'Cocos (Keeling) Islands', 'CC', '0061'),
(47, 'Colombia', 'CO', '0057'),
(48, 'Comoros', 'KM', '00269'),
(49, 'Congo Democratic Republic of', 'CG', '00242'),
(50, 'Cook Islands', 'CK', '00682'),
(51, 'Costa Rica', 'CR', '00506'),
(52, 'Cote D\'Ivoire', 'CI', '00225'),
(53, 'Croatia', 'HR', '00385'),
(54, 'Cuba', 'CU', '0053'),
(55, 'Cyprus', 'CY', '00357'),
(56, 'Czech Republic', 'CZ', '00420'),
(57, 'Denmark', 'DK', '0045'),
(58, 'Djibouti', 'DJ', '00253'),
(59, 'Dominica', 'DM', '001-767'),
(60, 'Dominican Republic', 'DO', '001-809'),
(61, 'Timor-Leste', 'TL', '00670'),
(62, 'Ecuador', 'EC', '00593'),
(63, 'Egypt', 'EG', '0020'),
(64, 'El Salvador', 'SV', '00503'),
(65, 'Equatorial Guinea', 'GQ', '00240'),
(66, 'Eritrea', 'ER', '00291'),
(67, 'Estonia', 'EE', '00372'),
(68, 'Ethiopia', 'ET', '00251'),
(69, 'Falkland Islands (Malvinas)', 'FK', '00500'),
(70, 'Faroe Islands', 'FO', '00298'),
(71, 'Fiji', 'FJ', '00679'),
(72, 'Finland', 'FI', '00358'),
(73, 'France', 'FR', '0033'),
(75, 'French Guiana', 'GF', '00594'),
(76, 'French Polynesia', 'PF', '00689'),
(77, 'French Southern Territories', 'TF', NULL),
(78, 'Gabon', 'GA', '00241'),
(79, 'Gambia', 'GM', '00220'),
(80, 'Georgia', 'GE', '00995'),
(81, 'Germany', 'DE', '0049'),
(82, 'Ghana', 'GH', '00233'),
(83, 'Gibraltar', 'GI', '00350'),
(84, 'Greece', 'GR', '0030'),
(85, 'Greenland', 'GL', '00299'),
(86, 'Grenada', 'GD', '001-473'),
(87, 'Guadeloupe', 'GP', '00590'),
(88, 'Guam', 'GU', '001-671'),
(89, 'Guatemala', 'GT', '00502'),
(90, 'Guinea', 'GN', '00224'),
(91, 'Guinea-bissau', 'GW', '00245'),
(92, 'Guyana', 'GY', '00592'),
(93, 'Haiti', 'HT', '00509'),
(94, 'Heard Island and McDonald Islands', 'HM', '00011'),
(95, 'Honduras', 'HN', '00504'),
(96, 'Hong Kong', 'HK', '00852'),
(97, 'Hungary', 'HU', '0036'),
(98, 'Iceland', 'IS', '00354'),
(99, 'India', 'IN', '0091'),
(100, 'Indonesia', 'ID', '0062'),
(101, 'Iran (Islamic Republic of)', 'IR', '0098'),
(102, 'Iraq', 'IQ', '00964'),
(103, 'Ireland', 'IE', '00353'),
(104, 'Israel', 'IL', '00972'),
(105, 'Italy', 'IT', '0039'),
(106, 'Jamaica', 'JM', '001-876'),
(107, 'Japan', 'JP', '0081'),
(108, 'Jordan', 'JO', '00962'),
(109, 'Kazakhstan', 'KZ', '007'),
(110, 'Kenya', 'KE', '00254'),
(111, 'Kiribati', 'KI', '00686'),
(112, 'Korea, Democratic People\'s Republic of', 'KP', '00850'),
(113, 'South Korea', 'KR', '0082'),
(114, 'Kuwait', 'KW', '00965'),
(115, 'Kyrgyzstan', 'KG', '00996'),
(116, 'Lao People\'s Democratic Republic', 'LA', '00856'),
(117, 'Latvia', 'LV', '00371'),
(118, 'Lebanon', 'LB', '00961'),
(119, 'Lesotho', 'LS', '00266'),
(120, 'Liberia', 'LR', '00231'),
(121, 'Libya', 'LY', '00218'),
(122, 'Liechtenstein', 'LI', '00423'),
(123, 'Lithuania', 'LT', '00370'),
(124, 'Luxembourg', 'LU', '00352'),
(125, 'Macao', 'MO', '00853'),
(126, 'Macedonia, The Former Yugoslav Republic of', 'MK', '00389'),
(127, 'Madagascar', 'MG', '00261'),
(128, 'Malawi', 'MW', '00265'),
(129, 'Malaysia', 'MY', '0060'),
(130, 'Maldives', 'MV', '00960'),
(131, 'Mali', 'ML', '00223'),
(132, 'Malta', 'MT', '00356'),
(133, 'Marshall Islands', 'MH', '00692'),
(134, 'Martinique', 'MQ', '00596'),
(135, 'Mauritania', 'MR', '00222'),
(136, 'Mauritius', 'MU', '00230'),
(137, 'Mayotte', 'YT', '00262'),
(138, 'Mexico', 'MX', '0052'),
(139, 'Micronesia, Federated States of', 'FM', '00691'),
(140, 'Moldova', 'MD', '00373'),
(141, 'Monaco', 'MC', '00377'),
(142, 'Mongolia', 'MN', '00976'),
(143, 'Montserrat', 'MS', '001-664'),
(144, 'Morocco', 'MA', '00212'),
(145, 'Mozambique', 'MZ', '00258'),
(146, 'Myanmar', 'MM', '0095'),
(147, 'Namibia', 'NA', '00264'),
(148, 'Nauru', 'NR', '00674'),
(149, 'Nepal', 'NP', '00977'),
(150, 'Netherlands', 'NL', '0031'),
(151, 'Netherlands Antilles', 'AN', '00599'),
(152, 'New Caledonia', 'NC', '00687	'),
(153, 'New Zealand', 'NZ', '0064'),
(154, 'Nicaragua', 'NI', '00505'),
(155, 'Niger', 'NE', '00227'),
(156, 'Nigeria', 'NG', '00234'),
(157, 'Niue', 'NU', '00683'),
(158, 'Norfolk Island', 'NF', '00672'),
(159, 'Northern Mariana Islands', 'MP', '001-670'),
(160, 'Norway', 'NO', '0047'),
(161, 'Oman', 'OM', '00968'),
(162, 'Pakistan', 'PK', '0092'),
(163, 'Palau', 'PW', '00680'),
(164, 'Panama', 'PA', '00507'),
(165, 'Papua New Guinea', 'PG', '00675'),
(166, 'Paraguay', 'PY', '00595'),
(167, 'Peru', 'PE', '0051'),
(168, 'Philippines', 'PH', '0063'),
(169, 'Pitcairn', 'PN', '0064'),
(170, 'Poland', 'PL', '0048'),
(171, 'Portugal', 'PT', '00351'),
(172, 'Puerto Rico', 'PR', '001-787'),
(173, 'Qatar', 'QA', '00974'),
(174, 'Reunion', 'RE', '00262'),
(175, 'Romania', 'RO', '0040'),
(176, 'Russian Federation', 'RU', '007'),
(177, 'Rwanda', 'RW', '00250'),
(178, 'Saint Kitts and Nevis', 'KN', '001-869'),
(179, 'Saint Lucia', 'LC', '001-758'),
(180, 'Saint Vincent and the Grenadines', 'VC', '001-784'),
(181, 'Samoa', 'WS', '00685'),
(182, 'San Marino', 'SM', '00378'),
(183, 'Sao Tome and Principe', 'ST', '00239'),
(184, 'Saudi Arabia', 'SA', '00966'),
(185, 'Senegal', 'SN', '00221'),
(186, 'Seychelles', 'SC', '00248'),
(187, 'Sierra Leone', 'SL', '00232'),
(188, 'Singapore', 'SG', '0065'),
(189, 'Slovakia (Slovak Republic)', 'SK', '00421'),
(190, 'Slovenia', 'SI', '00386'),
(191, 'Solomon Islands', 'SB', '00677'),
(192, 'Somalia', 'SO', '00252'),
(193, 'South Africa', 'ZA', '0027'),
(194, 'South Georgia and the South Sandwich Islands', 'GS', '00500'),
(195, 'Spain', 'ES', '0034'),
(196, 'Sri Lanka', 'LK', '0094'),
(197, 'Saint Helena, Ascension and Tristan da Cunha', 'SH', '00290'),
(198, 'St. Pierre and Miquelon', 'PM', '00508'),
(199, 'Sudan', 'SD', '00249'),
(200, 'Suriname', 'SR', '00597'),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', '0047'),
(202, 'Swaziland', 'SZ', '00268'),
(203, 'Sweden', 'SE', '0046'),
(204, 'Switzerland', 'CH', '0041'),
(205, 'Syrian Arab Republic', 'SY', '00963'),
(206, 'Taiwan', 'TW', '00886'),
(207, 'Tajikistan', 'TJ', '00992'),
(208, 'Tanzania, United Republic of', 'TZ', '00255'),
(209, 'Thailand', 'TH', '0066'),
(210, 'Togo', 'TG', '00228'),
(211, 'Tokelau', 'TK', '00690'),
(212, 'Tonga', 'TO', '00676'),
(213, 'Trinidad and Tobago', 'TT', '001-868'),
(214, 'Tunisia', 'TN', '00216'),
(215, 'Turkey', 'TR', '0090'),
(216, 'Turkmenistan', 'TM', '00993'),
(217, 'Turks and Caicos Islands', 'TC', '001-649'),
(218, 'Tuvalu', 'TV', '00688'),
(219, 'Uganda', 'UG', '00256'),
(220, 'Ukraine', 'UA', '00380'),
(221, 'United Arab Emirates', 'AE', '00971'),
(222, 'United Kingdom', 'GB', '0044'),
(223, 'United States', 'US', '001'),
(224, 'United States Minor Outlying Islands', 'UM', '00246'),
(225, 'Uruguay', 'UY', '00598'),
(226, 'Uzbekistan', 'UZ', '00998'),
(227, 'Vanuatu', 'VU', '00678'),
(228, 'Vatican City State (Holy See)', 'VA', '00379'),
(229, 'Venezuela', 'VE', '0058'),
(230, 'Vietnam', 'VN', '0084'),
(231, 'Virgin Islands (British)', 'VG', '001-284'),
(232, 'Virgin Islands (U.S.)', 'VI', '001-340'),
(233, 'Wallis and Futuna Islands', 'WF', '00681'),
(234, 'Western Sahara', 'EH', '00212'),
(235, 'Yemen', 'YE', '00967'),
(236, 'Serbia', 'RS', '00381'),
(238, 'Zambia', 'ZM', '00260'),
(239, 'Zimbabwe', 'ZW', '00263'),
(240, 'Aaland Islands', 'AX', '00358'),
(241, 'Palestine', 'PS', '00970'),
(242, 'Montenegro', 'ME', '00382'),
(243, 'Guernsey', 'GG', '0044-14'),
(244, 'Isle of Man', 'IM', '0044-16'),
(245, 'Jersey', 'JE', '0044-15'),
(247, 'Curaçao', 'CW', '00599'),
(248, 'Ivory Coast', 'CI', '00225'),
(249, 'Kosovo', 'XK', '00383');

-- --------------------------------------------------------

--
-- Table structure for table `device_history`
--

CREATE TABLE `device_history` (
  `id` int(11) NOT NULL,
  `device_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `device_history`
--

INSERT INTO `device_history` (`id`, `device_id`) VALUES
(1, '123456789'),
(5, '123456780'),
(6, '78dfd8fdf98fd7fd8f8dfd8fdf'),
(7, '78dfd8fdf98fd7fd8f8dfd8fdfsafdfgdfgdfgdfgdfgdfg');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `redirect_url` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '0=inactive,1=active',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `image`, `redirect_url`, `status`, `created`) VALUES
(1, '1514287921_1-2-junk-food-transparent.png', 'http://google.com', 1, '2017-12-26 17:02:01'),
(3, '1514293647_biryani.jpg', 'http://google.com', 1, '2017-12-26 18:37:27'),
(4, '1515746894_1-2-junk-food-transparent.png', 'http://google.com', 1, '2018-01-12 14:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `food_parcel`
--

CREATE TABLE `food_parcel` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` float(8,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `food_parcel`
--

INSERT INTO `food_parcel` (`id`, `item_name`, `price`, `image`, `description`, `status`, `created_date`) VALUES
(1, 'Punjabi Thali', 100.00, '1515238068_pujabi-thali.jpg', 'Pack Description', 1, '2018-01-06 00:00:00'),
(2, 'chicker thali', 434.00, '1515743606_1-2-junk-food-transparent.png', 'dsffds', 1, '2018-01-12 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `items_dates_day`
--

CREATE TABLE `items_dates_day` (
  `id` int(11) NOT NULL,
  `item_dates_id` int(11) NOT NULL,
  `day` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items_dates_day`
--

INSERT INTO `items_dates_day` (`id`, `item_dates_id`, `day`) VALUES
(41, 22, 'Wednesday'),
(42, 22, 'Thursday'),
(43, 23, 'Friday'),
(44, 23, 'Saturday'),
(45, 24, 'Wednesday'),
(46, 24, 'Thursday'),
(47, 25, 'Friday'),
(48, 25, 'Saturday'),
(49, 26, 'Tuesday'),
(50, 26, 'Wednesday'),
(51, 27, 'Thursday'),
(52, 27, 'Friday'),
(53, 28, 'Thursday'),
(54, 28, 'Friday'),
(55, 29, 'Wednesday'),
(56, 29, 'Thursday'),
(57, 30, 'Thursday'),
(58, 30, 'Friday'),
(59, 31, 'Thursday'),
(60, 31, 'Friday'),
(61, 32, 'Thursday'),
(62, 32, 'Friday'),
(63, 33, 'Thursday'),
(64, 33, 'Friday'),
(65, 34, 'Tuesday'),
(66, 34, 'Wednesday'),
(67, 35, 'Tuesday'),
(68, 35, 'Wednesday'),
(69, 36, 'Saturday'),
(70, 36, 'Sunday'),
(71, 37, 'Wednesday'),
(72, 37, 'Thursday'),
(73, 38, 'Wednesday'),
(74, 38, 'Thursday'),
(75, 39, 'Wednesday'),
(76, 39, 'Thursday'),
(77, 40, 'Friday'),
(78, 40, 'Saturday'),
(79, 41, 'Friday'),
(80, 41, 'Saturday'),
(81, 42, 'Monday'),
(82, 42, 'Tuesday'),
(83, 43, 'Friday'),
(84, 43, 'Saturday'),
(85, 44, 'Monday'),
(86, 44, 'Wednesday'),
(87, 44, 'Thursday'),
(88, 44, 'Friday'),
(89, 44, 'Saturday'),
(90, 44, 'Sunday'),
(91, 45, 'Friday'),
(92, 45, 'Saturday'),
(93, 46, 'Monday'),
(94, 46, 'Sunday');

-- --------------------------------------------------------

--
-- Table structure for table `items_dates_days_price`
--

CREATE TABLE `items_dates_days_price` (
  `id` int(11) NOT NULL,
  `item_dates_id` int(11) NOT NULL,
  `item_dates_day_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `price` float(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items_dates_days_price`
--

INSERT INTO `items_dates_days_price` (`id`, `item_dates_id`, `item_dates_day_id`, `start_time`, `end_time`, `price`) VALUES
(173, 22, 41, '10:00:00', '18:00:00', 180.00),
(174, 22, 41, '18:00:00', '23:00:00', 250.00),
(175, 22, 42, '10:00:00', '18:00:00', 185.00),
(176, 22, 42, '18:00:00', '23:00:00', 260.00),
(177, 23, 43, '10:00:00', '18:00:00', 150.00),
(178, 23, 43, '18:00:00', '23:00:00', 160.00),
(179, 23, 44, '12:00:00', '15:00:00', 140.00),
(180, 23, 44, '17:00:00', '20:00:00', 120.00),
(181, 24, 45, '15:00:00', '17:00:00', 490.00),
(182, 24, 45, '20:00:00', '23:00:00', 495.00),
(183, 24, 46, '11:00:00', '13:00:00', 480.00),
(184, 24, 46, '15:00:00', '17:00:00', 485.00),
(185, 25, 47, '10:00:00', '11:00:00', 400.00),
(186, 25, 47, '14:00:00', '16:00:00', 420.00),
(187, 25, 48, '17:00:00', '20:00:00', 430.00),
(188, 25, 48, '21:00:00', '23:00:00', 455.00),
(189, 26, 49, '11:00:00', '12:00:00', 480.00),
(190, 26, 50, '11:00:00', '12:00:00', 490.00),
(191, 26, 49, '12:00:00', '23:00:00', 485.00),
(192, 26, 50, '12:00:00', '23:00:00', 495.00),
(193, 27, 51, '10:00:00', '11:00:00', 550.00),
(194, 27, 51, '11:00:00', '23:00:00', 590.00),
(195, 27, 52, '10:00:00', '11:00:00', 560.00),
(196, 27, 52, '11:00:00', '23:00:00', 590.00),
(197, 28, 53, '10:00:00', '11:00:00', 420.00),
(198, 28, 53, '11:00:00', '23:00:00', 440.00),
(199, 28, 54, '10:00:00', '11:00:00', 425.00),
(200, 28, 54, '11:00:00', '23:00:00', 445.00),
(201, 29, 55, '00:30:00', '00:45:00', 100.00),
(202, 29, 56, '01:00:00', '01:15:00', 200.00),
(203, 30, 57, '10:00:00', '11:00:00', 100.00),
(204, 30, 58, '10:00:00', '11:00:00', 700.00),
(205, 31, 59, '10:00:00', '11:00:00', 70.00),
(206, 31, 60, '10:00:00', '11:00:00', 700.00),
(207, 32, 61, '10:00:00', '11:00:00', 70.00),
(208, 32, 62, '10:00:00', '11:00:00', 700.00),
(209, 33, 63, '10:00:00', '11:00:00', 70.00),
(210, 33, 64, '00:15:00', '00:30:00', 700.00),
(211, 34, 65, '10:00:00', '11:00:00', 300.00),
(212, 34, 66, '10:00:00', '11:00:00', 333.00),
(213, 35, 67, '10:00:00', '11:00:00', 300.00),
(214, 35, 68, '10:00:00', '11:00:00', 333.00),
(215, 36, 69, '10:00:00', '11:00:00', 100.00),
(216, 36, 70, '11:00:00', '12:00:00', 101.00),
(217, 37, 71, '00:00:00', '00:15:00', 490.00),
(218, 37, 71, '00:30:00', '00:45:00', 495.00),
(219, 37, 72, '01:00:00', '03:45:00', 200.00),
(220, 37, 72, '04:00:00', '08:00:00', 205.00),
(221, 38, 73, '12:00:00', '22:00:00', 490.00),
(222, 38, 74, '10:00:00', '20:00:00', 200.00),
(223, 39, 75, '12:00:00', '15:00:00', 200.00),
(224, 39, 76, '12:00:00', '15:00:00', 200.00),
(225, 40, 77, '00:00:00', '01:00:00', 1.00),
(227, 40, 78, '00:00:00', '01:00:00', 22.00),
(228, 41, 79, '00:00:00', '05:00:00', 100.00),
(229, 41, 80, '05:00:00', '10:00:00', 101.00),
(230, 42, 81, '00:00:00', '03:00:00', 444.00),
(231, 42, 82, '08:00:00', '10:15:00', 343.00),
(232, 42, 82, '12:00:00', '18:00:00', 234.00),
(233, 43, 83, '00:00:00', '03:00:00', 355.00),
(234, 43, 84, '12:00:00', '17:00:00', 545.00),
(235, 44, 85, '10:00:00', '14:00:00', 100.00),
(236, 44, 86, '15:00:00', '19:00:00', 105.00),
(237, 44, 87, '17:00:00', '20:00:00', 110.00),
(238, 44, 88, '10:00:00', '17:00:00', 115.00),
(239, 44, 89, '14:00:00', '17:00:00', 111.00),
(240, 44, 90, '17:00:00', '21:00:00', 120.00),
(241, 45, 91, '00:00:00', '01:30:00', 56.00),
(242, 45, 91, '04:00:00', '07:45:00', 45.00),
(243, 45, 92, '08:00:00', '11:45:00', 33.00),
(244, 45, 92, '17:15:00', '20:45:00', 33.00),
(245, 46, 93, '10:00:00', '22:00:00', 10.00),
(246, 46, 94, '11:00:00', '19:00:00', 20.00),
(247, 46, 93, '00:00:00', '02:00:00', 20.00),
(248, 46, 94, '02:00:00', '04:30:00', 30.00);

-- --------------------------------------------------------

--
-- Table structure for table `item_dates`
--

CREATE TABLE `item_dates` (
  `id` int(11) NOT NULL,
  `offer_title` varchar(255) NOT NULL,
  `min_qty` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_dates`
--

INSERT INTO `item_dates` (`id`, `offer_title`, `min_qty`, `type`, `start_date`, `end_date`, `item_id`) VALUES
(22, 'Punjabi Thali New Offer', 10, 'foodparcel', '2017-12-13', '2017-12-14', 4),
(23, 'Punjabi Thali best offer', 5, 'foodparcel', '2017-12-15', '2017-12-16', 4),
(24, 'Hydrabadi Thali New Offer', 10, 'foodparcel', '2017-12-13', '2017-12-14', 5),
(25, 'Hydrabadi Thali Best Offer', 5, 'foodparcel', '2017-12-15', '2017-12-16', 5),
(26, 'Offer on Pizza', 5, 'allacart', '2017-12-19', '2017-12-20', 11),
(27, 'new offer', 5, 'allacart', '2017-12-21', '2017-12-22', 13),
(28, 'new offer on pizza', 6, 'allacart', '2017-12-21', '2017-12-22', 12),
(29, 'New offer', 10, 'allacart', '2017-12-27', '2017-12-28', 13),
(30, 'New Offer', 5, 'allacart', '2017-12-28', '2017-12-29', 14),
(31, 'new arrival', 5, 'allacart', '2017-12-28', '2017-12-29', 12),
(32, 'Punjabi Thali New Offer', 5, 'foodparcel', '2017-12-28', '2017-12-29', 5),
(33, 'Punjabi Thali New Offer', 1, 'partypackage', '2017-12-28', '2017-12-29', 3),
(34, 'Punjabi Thali New Offer', 23, 'foodparcel', '2018-01-02', '2018-01-03', 5),
(35, 'veg food', 5, 'allacart', '2018-01-02', '2018-01-03', 14),
(36, 'offer', 10, 'allacart', '2018-01-06', '2018-01-07', 14),
(37, 'New offer', 10, 'allacart', '2018-01-10', '2018-01-11', 13),
(38, 'New offer', 10, 'foodparcel', '2018-01-10', '2018-01-11', 1),
(39, 'New offer', 10, 'partypackage', '2018-01-10', '2018-01-11', 5),
(40, 'xcxc', 3, 'allacart', '2018-01-12', '2018-01-13', 13),
(41, 'dfdf', 34, 'allacart', '2018-01-12', '2018-01-13', 15),
(42, 'sdfsdfs', 434, 'allacart', '2018-01-15', '2018-01-16', 15),
(43, 'dfgdfgdfg', 45, 'foodparcel', '2018-01-12', '2018-01-13', 2),
(44, 'New offer on pizza', 1, 'allacart', '2018-01-17', '2018-01-22', 12),
(45, 'fchchh', 5476, 'allacart', '2018-01-19', '2018-01-20', 15),
(46, 'New offer', 10, 'allacart', '2018-01-21', '2018-01-22', 15);

-- --------------------------------------------------------

--
-- Table structure for table `key_configuration`
--

CREATE TABLE `key_configuration` (
  `id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `key_value` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1= active, 0=inactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `key_configuration`
--

INSERT INTO `key_configuration` (`id`, `key_name`, `key_value`, `status`) VALUES
(1, 'loyalty_type', '', 1),
(2, 'loyalty_value', '', 1),
(3, 'premium_member_offer', '', 1),
(4, 'site_name', 'Calypso', 1),
(5, 'site_logo', 'uploads/setting/1507528995_1024X1024.jpg', 1),
(6, 'trending_type', '', 1),
(7, 'gst', '10', 1),
(8, 'alacart_cancel_percent', '2', 1),
(9, 'foodparcel_cancel_percent', '5', 1),
(10, 'partypackage_cancel_percent', '10', 1),
(11, 'alacart_cancel_time', '5', 1),
(12, 'foodparcel_cancel_time', '4', 1),
(13, 'partypackage_cancel_time', '24', 1),
(14, 'wallet_amount', '250', 1);

-- --------------------------------------------------------

--
-- Table structure for table `membership_token`
--

CREATE TABLE `membership_token` (
  `user_id` int(11) NOT NULL,
  `token` varchar(90) NOT NULL,
  `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membership_token`
--

INSERT INTO `membership_token` (`user_id`, `token`, `expiry_date`) VALUES
(3, '754918', '2017-10-08 08:03:41'),
(26, '466275', '2017-10-12 05:58:59'),
(56, '466275', '2017-09-12 05:58:59');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notifi_type` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `params` text,
  `type` tinyint(4) DEFAULT NULL COMMENT '1=al-a cart,2-foodparcel,3-party package',
  `is_read` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `offer_text` text NOT NULL,
  `offer_image` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active',
  `show_front` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no,1=yes',
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `offer_text`, `offer_image`, `type`, `status`, `show_front`, `created_date`) VALUES
(1, 'New Arrival', '1512738661_Fast-Food-2.jpg', 'allacart', 1, 0, '2017-12-08 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_time` time DEFAULT NULL,
  `gst` float(8,2) NOT NULL,
  `total_amount` float(8,2) NOT NULL,
  `net_amount` float(8,2) NOT NULL,
  `partial_payment` float(8,2) DEFAULT NULL,
  `pending_amount` float(8,2) DEFAULT NULL,
  `paid_amount` float(8,2) DEFAULT NULL,
  `wallet_amount` float(8,2) DEFAULT NULL,
  `cancel_amount` float(8,2) DEFAULT NULL,
  `cancel_return_amount` float(8,2) DEFAULT NULL,
  `discount` float(8,2) DEFAULT NULL,
  `is_fullpayment` tinyint(4) DEFAULT NULL,
  `payment_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=COD,2=payment gatway',
  `payment_id` bigint(20) DEFAULT NULL,
  `unique_order_id` varchar(255) NOT NULL,
  `redeemption_code` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1=pending,2=confirm,3=process,4=complete,5=cancelled,6=delivered',
  `type` tinyint(4) NOT NULL COMMENT '1=allacart,2=foodparcel,3=partypackage',
  `payment_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = pending,1 = done ',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `address_id`, `delivery_date`, `delivery_time`, `gst`, `total_amount`, `net_amount`, `partial_payment`, `pending_amount`, `paid_amount`, `wallet_amount`, `cancel_amount`, `cancel_return_amount`, `discount`, `is_fullpayment`, `payment_type`, `payment_id`, `unique_order_id`, `redeemption_code`, `status`, `type`, `payment_status`, `created`) VALUES
(1, 42, 4, '2017-12-17', '19:00:00', 80.00, 800.00, 880.00, 0.00, 0.00, 880.00, NULL, NULL, NULL, NULL, NULL, 1, NULL, '88152485', 'FRz6bCtpMF3UA', 4, 1, 1, '2017-12-13 20:36:39'),
(2, 42, NULL, '2017-12-17', NULL, 20.00, 200.00, 220.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 0, NULL, '60864953', 'XyJBHVHWadJNJ', 4, 1, 1, '2017-12-18 16:26:15'),
(3, 42, NULL, '2017-12-18', NULL, 30.00, 300.00, 330.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 0, NULL, '80481773', 'cTXdopBJlq9bi', 2, 1, 0, '2017-12-18 17:23:32'),
(4, 42, NULL, '2018-01-05', NULL, 30.00, 300.00, 330.00, 0.00, 0.00, 330.00, NULL, NULL, 330.00, NULL, NULL, 2, 554545454, '49169833', 'qVG6rc8InixdH', 5, 1, 1, '2017-12-19 12:30:00'),
(5, 42, NULL, '2017-12-19', NULL, 0.00, 30000.00, 30000.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 0, NULL, '97796515', NULL, 1, 3, 0, '2017-12-19 13:10:37'),
(7, 42, NULL, '2017-12-19', NULL, 3000.00, 33000.00, 33000.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 1, NULL, '21230770', NULL, 1, 3, 0, '2017-12-19 17:01:14'),
(8, 42, NULL, '2017-12-19', '05:30:00', 3000.00, 33000.00, 33000.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 1, NULL, '50354500', NULL, 1, 3, 0, '2017-12-19 20:27:30'),
(9, 42, NULL, '2017-12-19', '20:30:00', 3000.00, 33000.00, 33000.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 1, NULL, '82659039', NULL, 1, 3, 0, '2017-12-19 20:29:45'),
(10, 42, NULL, '2017-12-19', '20:30:00', 3000.00, 33000.00, 33000.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 1, NULL, '22190013', NULL, 1, 3, 0, '2017-12-19 20:33:35'),
(14, 42, NULL, '2017-12-20', '12:00:00', 10.00, 100.00, 110.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 1, NULL, '20586850', '1PM2iaiclE48o', 2, 1, 0, '2017-12-20 11:49:08'),
(16, 42, NULL, '2017-12-20', '20:00:00', 122.10, 1221.00, 1343.10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '63816457', NULL, 1, 3, 0, '2017-12-20 18:06:40'),
(17, 42, NULL, '2017-12-21', '20:00:00', 20.00, 200.00, 220.00, NULL, 220.00, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '80303304', '4olobKjFr92vm', 2, 1, 0, '2017-12-21 19:12:23'),
(18, 42, NULL, '2017-12-21', '20:00:00', 20.00, 200.00, 220.00, NULL, 0.00, 220.00, NULL, NULL, NULL, NULL, NULL, 1, NULL, '98234067', '4A2l5nNE49ved', 6, 1, 1, '2017-12-21 19:26:52'),
(20, 42, 4, '2017-12-21', '23:00:00', 30.00, 300.00, 330.00, NULL, 0.00, 330.00, NULL, NULL, NULL, NULL, NULL, 1, NULL, '24952205', 'tf1sw0cbXmFmB', 4, 2, 1, '2017-12-21 20:06:14'),
(21, 42, NULL, '2017-12-21', '22:00:00', 100.00, 1000.00, 1100.00, NULL, 1100.00, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '37010141', NULL, 1, 3, 0, '2017-12-21 20:32:41'),
(22, 42, NULL, '2017-12-21', '22:00:00', 100.00, 1000.00, 1100.00, NULL, 1100.00, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '93731309', NULL, 1, 3, 0, '2017-12-21 20:34:20'),
(23, 42, NULL, '2017-12-25', '22:00:00', 45.00, 450.00, 495.00, 0.00, 0.00, 495.00, NULL, NULL, NULL, NULL, NULL, 2, 859685, '37475326', 'z98mxeO3SqyEe', 5, 1, 1, '2017-12-22 16:24:28'),
(24, 42, 2, '2017-12-22', '23:00:00', 50.00, 500.00, 550.00, 0.00, 0.00, 550.00, NULL, NULL, NULL, NULL, NULL, 2, 254152, '94188923', 'p2ycVVvwme10B', 4, 2, 1, '2017-12-22 16:31:31'),
(25, 42, NULL, '2017-12-22', '22:00:00', 100.00, 1000.00, 1100.00, 0.00, 0.00, 1089.00, NULL, NULL, NULL, NULL, NULL, 2, 4526398, '84284761', NULL, 3, 3, 0, '2017-12-22 16:56:55'),
(27, 42, NULL, '2017-12-22', '22:00:00', 100.00, 1000.00, 1100.00, 0.00, 0.00, 1089.00, NULL, NULL, NULL, 1.00, 1, 2, 4526398, '48601135', NULL, 4, 3, 1, '2017-12-22 17:04:20'),
(29, 42, NULL, '2017-12-25', '22:00:00', 100.00, 1000.00, 1100.00, 50.00, 0.00, 1100.00, NULL, NULL, NULL, NULL, NULL, 2, 4526374, '83182788', NULL, 6, 3, 1, '2017-12-22 17:20:36'),
(30, 42, NULL, '2018-01-10', '16:54:00', 30.00, 300.00, 330.00, 0.00, 0.00, 330.00, NULL, 6.60, 323.40, NULL, NULL, 2, 4325443, '32411318', 'tNFomVq6XAJfh', 5, 1, 1, '2018-01-10 16:55:36'),
(31, 42, NULL, '2018-01-10', '09:00:00', 30.00, 300.00, 330.00, 0.00, 0.00, 330.00, NULL, NULL, NULL, NULL, NULL, 2, 4325444, '11085602', 'aDh148H8wnZ6w', 4, 1, 1, '2018-01-10 17:02:10'),
(32, 42, NULL, '2018-01-11', '20:00:00', 3.00, 30.00, 33.00, 0.00, 0.00, 33.00, 29.50, 0.66, 32.34, NULL, NULL, 2, 4325449, '55470383', '5ovPY0UbN6bOa', 5, 1, 1, '2018-01-11 15:12:34'),
(33, 42, NULL, '2018-01-11', '20:00:00', 3.00, 30.00, 33.00, 0.00, 0.00, 33.00, 10.30, NULL, 33.00, NULL, NULL, 2, 4325449, '66678310', 'MiBmLCelZmb3H', 5, 1, 1, '2018-01-11 15:15:56'),
(34, 42, 1, '2018-01-15', '13:15:00', 10.00, 100.00, 110.00, 0.00, 0.00, 110.00, NULL, NULL, NULL, NULL, NULL, 0, NULL, '85223819', NULL, 1, 2, 0, '2018-01-15 12:01:11'),
(35, 42, 1, '2018-01-15', '14:03:00', 10.00, 100.00, 110.00, 0.00, 0.00, 110.00, NULL, NULL, NULL, NULL, NULL, 0, NULL, '15098393', NULL, 1, 2, 0, '2018-01-15 12:02:06');

-- --------------------------------------------------------

--
-- Table structure for table `order_meta`
--

CREATE TABLE `order_meta` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_price` float NOT NULL,
  `product_qty` int(11) NOT NULL,
  `product_total` float DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_meta`
--

INSERT INTO `order_meta` (`id`, `order_id`, `product_id`, `product_price`, `product_qty`, `product_total`, `status`) VALUES
(34, 3, 4, 170, 2, 340, 1),
(33, 4, 3, 20, 2, 40, 1),
(3, 2, 1, 20, 2, 40, 1),
(4, 2, 4, 170, 2, 340, 1),
(5, 3, 1, 20, 2, 40, 1),
(6, 3, 4, 170, 2, 340, 1),
(7, 4, 1, 20, 2, 40, 1),
(8, 4, 4, 170, 2, 340, 1),
(9, 5, 1, 20, 2, 40, 1),
(10, 5, 4, 170, 2, 340, 1),
(11, 6, 1, 20, 2, 40, 1),
(12, 6, 4, 170, 2, 340, 1),
(13, 7, 1, 20, 2, 40, 1),
(14, 7, 4, 170, 2, 340, 1),
(15, 8, 1, 20, 2, 40, 1),
(16, 8, 4, 170, 2, 340, 1),
(17, 9, 1, 20, 2, 40, 1),
(18, 9, 4, 170, 2, 340, 1),
(19, 10, 1, 20, 2, 40, 1),
(20, 10, 4, 170, 2, 340, 1),
(21, 11, 1, 20, 2, 40, 1),
(22, 11, 4, 170, 2, 340, 1),
(23, 12, 1, 20, 2, 40, 1),
(24, 12, 4, 170, 2, 340, 1),
(25, 13, 1, 20, 2, 40, 1),
(26, 13, 4, 170, 2, 340, 1),
(27, 14, 1, 20, 2, 40, 1),
(28, 14, 4, 170, 2, 340, 1),
(29, 15, 1, 20, 2, 40, 1),
(30, 15, 4, 170, 2, 340, 1),
(31, 16, 1, 20, 2, 40, 1),
(32, 16, 4, 170, 2, 340, 1),
(35, 18, 1, 20, 2, 40, 1),
(36, 18, 4, 170, 2, 340, 1),
(37, 19, 1, 20, 2, 40, 1),
(38, 19, 4, 170, 2, 340, 1),
(39, 20, 1, 20, 2, 40, 1),
(40, 20, 4, 170, 2, 340, 1),
(41, 21, 1, 20, 2, 40, 1),
(42, 21, 4, 170, 2, 340, 1),
(43, 22, 1, 20, 2, 40, 1),
(44, 22, 4, 170, 2, 340, 1),
(45, 23, 1, 20, 2, 40, 1),
(46, 23, 4, 170, 2, 340, 1);

-- --------------------------------------------------------

--
-- Table structure for table `package_category`
--

CREATE TABLE `package_category` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `items_id` varchar(255) NOT NULL,
  `item_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `package_category`
--

INSERT INTO `package_category` (`id`, `package_id`, `category_id`, `items_id`, `item_limit`) VALUES
(8, 3, 2, '1,3', 1),
(9, 3, 8, '2,7,8', 2),
(10, 4, 2, '3', 1),
(11, 4, 8, '2,6', 2),
(12, 5, 2, '3', 1),
(13, 5, 8, '2,7', 2),
(14, 6, 2, '3', 23),
(15, 6, 9, '13', 3);

-- --------------------------------------------------------

--
-- Table structure for table `parcel_items`
--

CREATE TABLE `parcel_items` (
  `id` int(11) NOT NULL,
  `parcel_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parcel_items`
--

INSERT INTO `parcel_items` (`id`, `parcel_id`, `item_id`, `category_id`, `item_limit`) VALUES
(1, 1, 3, 2, 1),
(2, 1, 4, 10, 2),
(3, 1, 6, 8, 3),
(12, 2, 3, 2, 2),
(13, 2, 5, 11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `party_package`
--

CREATE TABLE `party_package` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `strike_price` float(8,2) DEFAULT NULL,
  `price` float(8,2) NOT NULL,
  `partial_payment` float(8,2) NOT NULL,
  `discount` float(8,2) DEFAULT NULL,
  `min_person` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `gender_pref` varchar(255) NOT NULL COMMENT '1 = male,2 = female ',
  `min_age` int(11) NOT NULL,
  `max_age` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `party_package`
--

INSERT INTO `party_package` (`id`, `item_name`, `strike_price`, `price`, `partial_payment`, `discount`, `min_person`, `image`, `description`, `gender_pref`, `min_age`, `max_age`, `status`, `created`) VALUES
(3, 'Xmas party', 110.00, 100.00, 50.00, 1.00, 10, '1513693209_1-2-junk-food-transparent.png', 'Package Description', '1', 10, 40, 0, '2017-12-19 19:50:09'),
(4, 'New year party', 200.00, 150.00, 1000.00, 1.00, 20, '1515165021_1-2-junk-food-transparent.png', 'Package Description', '1', 10, 20, 1, '2018-01-05 20:40:21'),
(5, 'Arrive Party', 100.00, 90.00, 100.00, 1.00, 10, '1515240107_officeparty.jpg', 'Package Description', '1', 10, 20, 1, '2018-01-06 17:31:47'),
(6, 'Bold Party', 445.00, 434.00, 343.00, 3.00, 333, '1515746754_1-2-junk-food-transparent.png', 'cvcvc', '1', 10, 20, 1, '2018-01-12 14:15:54');

-- --------------------------------------------------------

--
-- Table structure for table `priceOffers`
--

CREATE TABLE `priceOffers` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `day` int(11) NOT NULL COMMENT '0=Monday,1=Tuesday,2=Wednesday,3=Thursday,4=Friday,5=Saturday,6=Sunday',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `price` float(8,2) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `priceOffers`
--

INSERT INTO `priceOffers` (`id`, `item_id`, `start_date`, `end_date`, `day`, `start_time`, `end_time`, `price`, `created`) VALUES
(1, 14, '2017-10-23', '2017-10-26', 0, '10:00:00', '12:00:00', 800.00, '2017-10-23 10:42:24'),
(2, 14, '2017-10-25', '2017-10-30', 0, '12:00:00', '15:00:00', 780.00, '2017-10-23 10:49:17');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `qr_code` varchar(100) DEFAULT NULL,
  `qr_image` varchar(255) DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active',
  `launching_date` date DEFAULT NULL,
  `prelaunch_date` date DEFAULT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `product_name`, `price`, `image`, `description`, `qr_code`, `qr_image`, `product_type`, `status`, `launching_date`, `prelaunch_date`, `created_date`) VALUES
(6, 2, NULL, 'game2', 12, '', 'wrerere', NULL, NULL, '2', 1, '2017-09-06', NULL, '2017-09-07 13:19:30'),
(7, 1, NULL, 'wsdsd', 30, '', 'fgfggfg', NULL, NULL, '1', 1, '1970-01-01', NULL, '2017-09-18 13:19:13'),
(8, 1, NULL, 'dsdddd', 30, '', 'fgfgfgfgfgg', NULL, NULL, '1', 1, '1970-01-01', NULL, '2017-09-18 13:21:14'),
(9, 1, NULL, 'ewewew', 34, '', 'erere', NULL, NULL, NULL, 1, '1970-01-01', NULL, '2017-09-21 14:37:12'),
(10, 1, NULL, 'aaaaA', 34, '', 'sdsdsdsd', NULL, NULL, NULL, 1, '1970-01-01', NULL, '2017-09-21 14:38:46'),
(11, 1, NULL, 'rrrrrr', 50, '', 'ghghhghg', NULL, NULL, NULL, 1, '2017-09-21', NULL, '2017-09-21 14:39:17'),
(12, 4, NULL, 'dfgggfg', 34, '', 'dfdfdfd', NULL, NULL, NULL, 1, '1970-01-01', NULL, '2017-09-21 14:42:30'),
(13, 4, NULL, 'asassd', 34, '', 'dsddsds', NULL, NULL, NULL, 1, '1970-01-01', NULL, '2017-09-21 14:46:51'),
(14, 4, NULL, 'weewewew', 50, '', 'wswqww', NULL, NULL, NULL, 1, '1970-01-01', NULL, '2017-09-21 14:54:48'),
(15, 4, NULL, 'wqwqwqwq', 34, '', 'qwqwqw', NULL, NULL, NULL, 1, '1970-01-01', NULL, '2017-09-21 14:55:13'),
(31, 1, NULL, 'iPhone6', 50, '', 'assasasass', NULL, NULL, NULL, 1, '2017-09-26', NULL, '2017-09-26 14:30:52'),
(32, 1, NULL, 'hhhhkjk', 50, '', 'jkjkjjkjk', NULL, NULL, '1', 1, '2017-09-27', NULL, '2017-09-27 13:23:47'),
(33, 1, NULL, 'bnjhjj', 20, '', 'sdsdsd', 'game_123', 'game_123_1507299776.png', '1', 1, '2017-09-27', NULL, '2017-09-27 13:25:30'),
(35, 4, NULL, 'ujkjkjkjjk', 50, '', 'kjjkjkjkjkk', '614796', '614796_1506593338.png', NULL, 1, '2017-09-28', NULL, '2017-09-28 15:38:58'),
(36, 4, NULL, 'tyutghgjh', 34, '', 'hhjjhhh', '470999', '470999_1506604918.png', NULL, 1, '2017-09-28', NULL, '2017-09-28 18:51:58'),
(37, 1, NULL, 'ghghghggg', 34, '', 'hhjhhh', '474513', '474513_1506605324.png', '1', 1, '2017-09-28', NULL, '2017-09-28 18:58:44'),
(38, 1, NULL, 'dfdfdfdfdfd', 45, '', 'fdffdfdfdf', '265921', '265921_1507115749.png', NULL, 1, '2017-10-04', NULL, '2017-10-04 16:45:49'),
(39, 1, NULL, 'sassasasas', 50, '', 'sasasasas', '743293', '743293_1507299661.png', NULL, 1, '2017-10-04', NULL, '2017-10-04 17:13:41'),
(40, 1, NULL, 'ffgfggg', 50, '', 'dfgfgfg', '524238', '524238_1507299648.png', NULL, 1, '2017-10-04', NULL, '2017-10-04 17:57:02'),
(41, 1, NULL, 'fdfffffdf', 50, '', 'fdfdfdfdfd', '524238', '524238_1507357289.png', NULL, 1, '2017-10-07', NULL, '2017-10-07 11:51:29'),
(42, 1, NULL, 'fdfffffdfdsdss', 50, '', 'dgfggfgg', '5242381', '5242381_1507357738.png', NULL, 1, '2017-10-07', NULL, '2017-10-07 11:54:24'),
(43, 1, NULL, 'dsdsdsss', 45, '', 'sdsdsdssd', '333334', '333334_1507358478.png', NULL, 1, '2017-10-07', NULL, '2017-10-07 12:11:18'),
(44, 1, NULL, 'ghghghgghg', 50, '', 'hjhjhj', 'game_1', 'game_1_1507360420.png', NULL, 1, '2017-10-07', NULL, '2017-10-07 12:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `product_subcategory`
--

CREATE TABLE `product_subcategory` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=inactive, 1=active',
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `option_name` varchar(100) NOT NULL,
  `option_value` varchar(100) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1 for on 0 for off'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suggestion_feedback`
--

CREATE TABLE `suggestion_feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `suggestion` text,
  `feedback` text,
  `type` tinyint(4) NOT NULL COMMENT '1=suggestion,2=feedback',
  `status` tinyint(4) NOT NULL COMMENT '0-inactive,1-active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suggestion_feedback`
--

INSERT INTO `suggestion_feedback` (`id`, `user_id`, `title`, `suggestion`, `feedback`, `type`, `status`) VALUES
(1, 42, 'first suggestion', 'Hello suggestion', '', 1, 1),
(2, 42, 'second suggestion', 'demo suggestion', '', 1, 1),
(3, 42, '', '', 'Hello feedback', 2, 1),
(4, 42, '', '', 'demo suggestion', 2, 1),
(8, 42, 'suggestion for email', 'suggestion for email description', NULL, 1, 1),
(14, 42, NULL, NULL, 'testing feedback', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `user_type` enum('USER','ADMIN') NOT NULL DEFAULT 'USER',
  `activation_code` varchar(6) DEFAULT NULL,
  `code_expiry` bigint(20) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_on` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '1 =active 0= inactive',
  `full_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `anniversary` date DEFAULT NULL,
  `gender` tinyint(4) NOT NULL COMMENT '1 = male, 2 = female',
  `occupation` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `near_landmark` text,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `signup_type` enum('FACEBOOK','TWITTER') DEFAULT NULL,
  `email_verify` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 for unverified 1 for verified',
  `current_wallet_balance` int(11) DEFAULT NULL,
  `is_logged_out` tinyint(2) NOT NULL DEFAULT '0' COMMENT '''0 = No, 1= Yes''',
  `is_blocked` tinyint(2) NOT NULL DEFAULT '0' COMMENT '''0 = no, 1 = yes''',
  `device_id` varchar(255) DEFAULT NULL COMMENT 'Device Unique ID',
  `device_type` enum('ANDROID','IOS','WEB') NOT NULL DEFAULT 'WEB' COMMENT 'Used to send push notifications',
  `device_token` text,
  `badges` int(10) NOT NULL DEFAULT '0',
  `social_type` enum('FACEBOOK','TWITTER') DEFAULT NULL,
  `social_id` varchar(250) DEFAULT '0',
  `is_social_signup` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 = NO, 1 = YES',
  `login_session_key` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `password`, `salt`, `email`, `username`, `user_type`, `activation_code`, `code_expiry`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `update_on`, `last_login`, `active`, `full_name`, `phone`, `date_of_birth`, `anniversary`, `gender`, `occupation`, `address`, `near_landmark`, `country`, `city`, `user_image`, `signup_type`, `email_verify`, `current_wallet_balance`, `is_logged_out`, `is_blocked`, `device_id`, `device_type`, `device_token`, `badges`, `social_type`, `social_id`, `is_social_signup`, `login_session_key`) VALUES
(1, '15b6aa96f02a44898f5d27aecd824a38', NULL, 'admin@admin.com', 'admin_123', 'ADMIN', '55454', NULL, 'eNortjKysFJKTMnNzHNcMJN6yfm5uoa6hqYGxpZGhqZGJkrWXDDM2wob', 1503921524, NULL, '2018-01-29 11:16:32', '2017-08-21 00:00:00', '2018-01-29 11:16:32', 1, 'Admin', '454345454', '2017-08-06', NULL, 0, NULL, 'dgfgfg', NULL, '', '', '', 'FACEBOOK', 1, 0, 0, 0, NULL, 'WEB', NULL, 0, NULL, NULL, 0, NULL),
(35, 'e10adc3949ba59abbe56e057f20f883e', NULL, 'arjun@gmail.com', NULL, 'USER', NULL, NULL, NULL, NULL, NULL, '2018-01-17 14:13:52', NULL, '2018-01-17 14:13:52', 0, 'arjun choudhary', '9876543210', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'uploads/users/1507886607_bob-dummy-300x300.jpg', NULL, 1, NULL, 0, 0, NULL, 'WEB', NULL, 0, NULL, '0', 0, NULL),
(42, '751cb3f4aa17c36186f4856c8982bf27', NULL, 'arjun.mobiwebtech@gmail.com', NULL, 'USER', '1765', 1515138938, 'eNortjIysVKytDQyMzAwMjMy0TUx0jU0BUJjCwNjCyVrXDBxZgaY', 1515138038, NULL, '2018-01-11 15:22:52', NULL, '2018-01-11 15:22:52', 1, 'anuj', '9926002625', '1989-06-11', '2017-01-25', 1, 'Software Developer', 'indore madhya pradesh', 'palasia', NULL, NULL, 'uploads/users/5a2e65ac444d8_User_5a2e65ac43785_1512990124_.jpg', NULL, 1, NULL, 0, 0, '1234567890', 'ANDROID', 'dsfhdsfjfhhsdfhsjhfjdshjfkhsxcx', 10, NULL, '', 0, '940b9e1c-e6dc-bdc7-0386-a72836334abc'),
(49, 'e64b78fc3bc91bcbc7dc232ba8ec59e0', NULL, 'arj0011@gmail.com', NULL, 'USER', '4885', NULL, NULL, NULL, NULL, '2017-12-28 15:45:55', NULL, '2017-12-28 15:45:55', 1, 'arjun Mobiweb', '9876543210', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, '123456789', 'ANDROID', 'fdfd4f5ds4f4d5sf4ds4f5sdf45d4f5ds5fds4f5dsf', 0, NULL, '', 0, '74764057-62e0-00ee-0224-10ad03f310a8'),
(50, '448ddd517d3abb70045aea6929f02367', NULL, 'dummyfacebook001111@gmail.com', NULL, 'USER', '6618', NULL, NULL, NULL, NULL, '2018-01-05 15:23:25', NULL, '2018-01-05 15:23:25', 1, 'arjun Mobiweb', '992600268', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, '123456789', 'ANDROID', 'fdfd4f5ds4f4d5sf4ds4f5sdf45d4f5ds5fds4f5dsf', 0, NULL, '', 0, '422e51bc-2e98-cdc1-de53-edf0c580f3dd'),
(51, '9210319d0a4376aa535705a53af34ded', NULL, 'dummyfacebook00111123@gmail.com', NULL, 'USER', '5382', NULL, NULL, NULL, NULL, '2018-01-05 15:24:54', NULL, '2018-01-05 15:24:54', 1, 'arjun Mobiweb', '9926002681', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, '123456789', 'ANDROID', 'fdfd4f5ds4f4d5sf4ds4f5sdf45d4f5ds5fds4f5dsf', 0, NULL, '', 0, 'b3540322-db85-5330-42e0-279bd0f95d41'),
(52, '9210319d0a4376aa535705a53af34ded', NULL, 'dummy121@gmail.com', NULL, 'USER', '1205', NULL, NULL, NULL, NULL, '2018-01-05 17:15:52', NULL, '2018-01-05 17:15:52', 1, 'arjun Mobiweb', '9926002624', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 0, '123456789', 'ANDROID', 'fdfd4f5ds4f4d5sf4ds4f5sdf45d4f5ds5fds4f5dsf', 0, NULL, '', 0, '9cd485b9-bc55-3185-feff-82f4e3192762'),
(61, '827ccb0eea8a706c4c34a16891f84e7b', NULL, '', NULL, 'USER', '6559', NULL, NULL, NULL, NULL, '2018-01-24 15:07:17', NULL, '2018-01-24 15:07:17', 1, 'samyak Mobiweb', '987654322', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, '123456789', 'ANDROID', 'fdfd4f5ds4f4d5sf4ds4f5sdf45d4f5ds5fds4f5dsf', 0, NULL, '', 0, '5bea60a1-8e7a-ebdd-2b58-a6ed2cec6b43'),
(62, '827ccb0eea8a706c4c34a16891f84e7b', NULL, '', NULL, 'USER', '6559', NULL, NULL, NULL, NULL, '2018-01-24 15:07:17', NULL, '2018-01-24 15:07:17', 1, 'samyak Mobiweb', '987654323', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 0, '1234567890', 'ANDROID', 'dsfhdsfjfhhsdfhsjhfjdshjfkhsxcx', 0, NULL, '', 0, '0c61c83c-6b96-8770-a32d-1453a4e433a8'),
(66, '827ccb0eea8a706c4c34a16891f84e7b', NULL, '', NULL, 'USER', '6559', NULL, NULL, NULL, NULL, '2018-01-24 15:07:17', NULL, '2018-01-24 15:07:17', 1, 'Sourabh Dhariwal', '987654324', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 0, '1234567890', 'ANDROID', 'dsfhdsfjfhhsdfhsjhfjdshjfkhsxcx', 1, NULL, '', 0, '2bd56d2e-cfb1-907a-b6a3-4a8a069c97b4'),
(67, '827ccb0eea8a706c4c34a16891f84e7b', NULL, '', NULL, 'USER', '6559', NULL, NULL, NULL, NULL, '2018-01-24 15:07:17', NULL, '2018-01-24 15:07:17', 1, 'Sourabh Dhariwal', '987654325', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, '123456780', 'ANDROID', 'fdfd4f5ds4f4d5sf4ds4f5sdf45d4f5ds5fds4f5dsf', 0, NULL, '', 0, 'b58b214d-d7fd-23ce-0578-52502f724802'),
(68, '827ccb0eea8a706c4c34a16891f84e7b', NULL, '', NULL, 'USER', '6559', NULL, NULL, NULL, NULL, '2018-01-24 15:07:17', NULL, '2018-01-24 15:07:17', 1, 'Sourabh Dhariwal', '987654326', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, '123456780', 'ANDROID', 'fdfd4f5ds4f4d5sf4ds4f5sdf45d4f5ds5fds4f5dsf', 0, NULL, '', 0, 'bc865ec9-4fb1-4567-1510-f12c353ba07d'),
(70, '827ccb0eea8a706c4c34a16891f84e7b', NULL, '', NULL, 'USER', '6135', NULL, NULL, NULL, NULL, '2018-01-24 17:04:14', NULL, '2018-01-24 17:04:14', 1, 'Dhariwal', '987654328', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 0, '78dfd8fdf98fd7fd8f8dfd8fdf', 'ANDROID', 'dsfhdsfjfhhsdfhsjhfjdshjfkhsxcx', 1, NULL, '', 0, '15db6b35-3e38-4d85-b921-ac3219f20e9b'),
(71, '827ccb0eea8a706c4c34a16891f84e7b', NULL, '', NULL, 'USER', '2334', NULL, NULL, NULL, NULL, '2018-01-25 12:59:33', NULL, '2018-01-25 12:59:33', 1, 'Pawan', '9876543219', '1989-11-11', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, '78dfd8fdf98fd7fd8f8dfd8fdfsafdfgdfgdfgdfgdfgdfg', 'ANDROID', 'fdfd4f5ds4f4d5sf4ds4f5sdf45d4f5ds5fds4f5dsf', 1, NULL, '', 0, 'd09d9c2d-9410-a861-9182-d709f2cd6092');

-- --------------------------------------------------------

--
-- Table structure for table `users_device_history`
--

CREATE TABLE `users_device_history` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `device_token` text NOT NULL COMMENT 'Used to send push notfications',
  `device_type` enum('ANDROID','IOS') NOT NULL,
  `device_id` varchar(150) NOT NULL COMMENT 'Device Unique ID',
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_device_history`
--

INSERT INTO `users_device_history` (`id`, `user_id`, `device_token`, `device_type`, `device_id`, `added_date`) VALUES
(1, 4, 'sdsdssds', 'IOS', '343435353', '2017-08-30 16:39:30'),
(153, 35, '1234567890', 'ANDROID', '343554545545454gfffgfhgh', '2017-10-09 16:48:22'),
(155, 34, 'dfdfer4545', 'IOS', '45454344', '2017-10-10 18:40:29'),
(172, 52, '344243234234', 'ANDROID', '452447552', '2018-01-05 17:15:52'),
(179, 66, 'dsfhdsfjfhhsdfhsjhfjdshjfkhsxcx', 'ANDROID', '1234567890', '2018-01-24 11:50:14'),
(190, 70, 'dsfhdsfjfhhsdfhsjhfjdshjfkhsxcx', 'ANDROID', '78dfd8fdf98fd7fd8f8dfd8fdf', '2018-01-24 17:04:14'),
(191, 71, 'fdfd4f5ds4f4d5sf4ds4f5sdf45d4f5ds5fds4f5dsf', 'ANDROID', '78dfd8fdf98fd7fd8f8dfd8fdfsafdfgdfgdfgdfgdfgdfg', '2018-01-25 12:59:33');

-- --------------------------------------------------------

--
-- Table structure for table `users_notifications`
--

CREATE TABLE `users_notifications` (
  `id` bigint(20) NOT NULL,
  `type_id` int(11) NOT NULL,
  `notification_parent_id` int(11) NOT NULL DEFAULT '0',
  `sender_id` int(11) NOT NULL,
  `reciever_id` int(11) NOT NULL,
  `device_reciever_id` int(11) NOT NULL DEFAULT '0',
  `notification_type` varchar(50) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-alacart,2-foodparcel,3-partypackage',
  `title` varchar(150) NOT NULL,
  `message` text,
  `is_read` smallint(6) NOT NULL COMMENT '0 = No, 1 = Yes',
  `sent_time` datetime NOT NULL,
  `params` text,
  `is_send` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Not sent 1= sent'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_notifications`
--

INSERT INTO `users_notifications` (`id`, `type_id`, `notification_parent_id`, `sender_id`, `reciever_id`, `device_reciever_id`, `notification_type`, `type`, `title`, `message`, `is_read`, `sent_time`, `params`, `is_send`) VALUES
(1, 3, 1, 1, 2, 0, 'Offer', 0, 'Offer', 'fhhghg', 0, '2017-09-26 16:28:30', NULL, 0),
(2, 3, 1, 1, 3, 0, 'Offer', 0, 'Offer', 'fhhghg', 0, '2017-09-26 16:28:30', NULL, 1),
(3, 3, 1, 1, 26, 0, 'Offer', 0, 'Offer', 'fhhghg', 0, '2017-09-26 16:28:30', NULL, 1),
(4, 3, 1, 1, 27, 0, 'Offer', 0, 'Offer', 'fhhghg', 0, '2017-09-26 16:28:30', NULL, 0),
(5, 3, 1, 1, 28, 0, 'Offer', 0, 'Offer', 'fhhghg', 0, '2017-09-26 16:28:30', NULL, 0),
(6, 3, 1, 1, 29, 0, 'Offer', 0, 'Offer', 'fhhghg', 0, '2017-09-26 16:28:30', NULL, 0),
(7, 3, 1, 1, 30, 0, 'Offer', 0, 'Offer', 'fhhghg', 0, '2017-09-26 16:28:30', NULL, 0),
(8, 4, 2, 1, 1, 0, 'Offer', 0, 'Offer', 'hghgghg', 0, '2017-09-26 16:30:20', NULL, 0),
(9, 4, 2, 1, 2, 0, 'Offer', 0, 'Offer', 'hghgghg', 0, '2017-09-26 16:30:20', NULL, 0),
(10, 4, 2, 1, 3, 0, 'Offer', 0, 'Offer', 'hghgghg', 0, '2017-09-26 16:30:20', NULL, 1),
(11, 4, 2, 1, 4, 0, 'Offer', 0, 'Offer', 'hghgghg', 0, '2017-09-26 16:30:20', NULL, 0),
(12, 4, 2, 1, 5, 0, 'Offer', 0, 'Offer', 'hghgghg', 0, '2017-09-26 16:30:20', NULL, 0),
(13, 4, 2, 1, 6, 0, 'Offer', 0, 'Offer', 'hghgghg', 0, '2017-09-26 16:30:20', NULL, 0),
(14, 4, 2, 1, 7, 0, 'Offer', 0, 'Offer', 'hghgghg', 0, '2017-09-26 16:30:20', NULL, 0),
(15, 5, 3, 1, 1, 0, 'Offer', 0, 'Offer', 'hjhb nmh', 0, '2017-09-26 16:30:43', NULL, 0),
(16, 5, 3, 1, 2, 0, 'Offer', 0, 'Offer', 'hjhb nmh', 0, '2017-09-26 16:30:43', NULL, 0),
(17, 5, 3, 1, 3, 0, 'Offer', 0, 'Offer', 'hjhb nmh', 0, '2017-09-26 16:30:43', NULL, 1),
(18, 5, 3, 1, 4, 0, 'Offer', 0, 'Offer', 'hjhb nmh', 0, '2017-09-26 16:30:43', NULL, 0),
(19, 5, 3, 1, 5, 0, 'Offer', 0, 'Offer', 'hjhb nmh', 0, '2017-09-26 16:30:43', NULL, 0),
(20, 5, 3, 1, 6, 0, 'Offer', 0, 'Offer', 'hjhb nmh', 0, '2017-09-26 16:30:43', NULL, 0),
(21, 5, 3, 1, 7, 0, 'Offer', 0, 'Offer', 'hjhb nmh', 0, '2017-09-26 16:30:43', NULL, 0),
(22, 6, 4, 1, 2, 0, 'Offer', 0, 'Offer', 'ghjhjhjh', 0, '2017-09-26 16:32:36', NULL, 0),
(23, 6, 4, 1, 3, 0, 'Offer', 0, 'Offer', 'ghjhjhjh', 0, '2017-09-26 16:32:36', NULL, 1),
(24, 6, 4, 1, 26, 0, 'Offer', 0, 'Offer', 'ghjhjhjh', 0, '2017-09-26 16:32:36', NULL, 0),
(25, 6, 4, 1, 27, 0, 'Offer', 0, 'Offer', 'ghjhjhjh', 0, '2017-09-26 16:32:36', NULL, 0),
(26, 6, 4, 1, 28, 0, 'Offer', 0, 'Offer', 'ghjhjhjh', 0, '2017-09-26 16:32:36', NULL, 0),
(27, 6, 4, 1, 29, 0, 'Offer', 0, 'Offer', 'ghjhjhjh', 0, '2017-09-26 16:32:36', NULL, 0),
(28, 6, 4, 1, 30, 0, 'Offer', 0, 'Offer', 'ghjhjhjh', 0, '2017-09-26 16:32:36', NULL, 0),
(29, 7, 5, 1, 1, 0, 'Offer', 0, 'Offer', 'bvbvbvbv', 0, '2017-09-26 17:44:22', NULL, 0),
(30, 7, 5, 1, 2, 0, 'Offer', 0, 'Offer', 'bvbvbvbv', 0, '2017-09-26 17:44:22', NULL, 0),
(31, 7, 5, 1, 3, 0, 'Offer', 0, 'Offer', 'bvbvbvbv', 0, '2017-09-26 17:44:22', NULL, 0),
(32, 7, 5, 1, 4, 0, 'Offer', 0, 'Offer', 'bvbvbvbv', 0, '2017-09-26 17:44:22', NULL, 0),
(33, 7, 5, 1, 5, 0, 'Offer', 0, 'Offer', 'bvbvbvbv', 0, '2017-09-26 17:44:22', NULL, 0),
(34, 7, 5, 1, 6, 0, 'Offer', 0, 'Offer', 'bvbvbvbv', 0, '2017-09-26 17:44:22', NULL, 0),
(35, 7, 5, 1, 7, 0, 'Offer', 0, 'Offer', 'bvbvbvbv', 0, '2017-09-26 17:44:22', NULL, 0),
(36, 34, 1, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:36:44', NULL, 0),
(37, 34, 1, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:36:44', NULL, 1),
(38, 34, 1, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:36:44', NULL, 0),
(39, 34, 1, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:36:44', NULL, 0),
(40, 34, 1, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:36:44', NULL, 0),
(41, 34, 1, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:36:44', NULL, 0),
(42, 34, 1, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:36:44', NULL, 0),
(43, 34, 1, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:36:44', NULL, 0),
(44, 34, 1, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:36:44', NULL, 0),
(45, 35, 2, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:38:58', NULL, 0),
(46, 35, 2, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:38:58', NULL, 1),
(47, 35, 2, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:38:58', NULL, 0),
(48, 35, 2, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:38:58', NULL, 0),
(49, 35, 2, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:38:58', NULL, 0),
(50, 35, 2, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:38:58', NULL, 0),
(51, 35, 2, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:38:58', NULL, 0),
(52, 35, 2, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:38:58', NULL, 0),
(53, 35, 2, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 15:38:58', NULL, 0),
(54, 36, 3, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:51:58', NULL, 0),
(55, 36, 3, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:51:58', NULL, 1),
(56, 36, 3, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:51:58', NULL, 0),
(57, 36, 3, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:51:58', NULL, 0),
(58, 36, 3, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:51:58', NULL, 0),
(59, 36, 3, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:51:58', NULL, 0),
(60, 36, 3, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:51:58', NULL, 0),
(61, 36, 3, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:51:58', NULL, 0),
(62, 36, 3, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:51:58', NULL, 0),
(63, 37, 4, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:58:44', NULL, 0),
(64, 37, 4, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:58:44', NULL, 1),
(65, 37, 4, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-09-28 18:58:44', NULL, 0),
(66, 19, 0, 1, 26, 0, 'Order', 0, 'Order Ready Status', 'Your Order is ready', 0, '2017-09-29 13:47:03', NULL, 1),
(67, 18, 0, 1, 26, 0, 'Order', 0, 'Order Ready Status', 'Your Order is ready', 0, '2017-09-29 15:35:05', NULL, 1),
(68, 17, 0, 1, 26, 0, 'Order', 0, 'Order Ready Status', 'Your Order is ready', 0, '2017-10-04 11:30:07', NULL, 1),
(69, 17, 0, 1, 26, 0, 'Wallet', 0, 'Reward Points', 'Earned 200 points on order 17', 0, '2017-10-04 11:33:07', NULL, 1),
(70, 38, 5, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 0),
(71, 38, 5, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 0),
(72, 38, 5, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 0),
(73, 38, 5, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 0),
(74, 38, 5, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 0),
(75, 38, 5, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 0),
(76, 38, 5, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 0),
(77, 38, 5, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 0),
(78, 38, 5, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 1),
(79, 38, 5, 1, 33, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 16:45:49', NULL, 1),
(80, 39, 6, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 0),
(81, 39, 6, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 0),
(82, 39, 6, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 0),
(83, 39, 6, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 0),
(84, 39, 6, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 0),
(85, 39, 6, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 0),
(86, 39, 6, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 0),
(87, 39, 6, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 0),
(88, 39, 6, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 1),
(89, 39, 6, 1, 33, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:13:41', NULL, 1),
(90, 40, 7, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 0),
(91, 40, 7, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 0),
(92, 40, 7, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 0),
(93, 40, 7, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 0),
(94, 40, 7, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 0),
(95, 40, 7, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 0),
(96, 40, 7, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 0),
(97, 40, 7, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 0),
(98, 40, 7, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 1),
(99, 40, 7, 1, 33, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-04 17:57:02', NULL, 1),
(100, 0, 0, 1, 3, 0, 'Membership', 0, 'Membership Renewal', 'Your membership has been expired on', 0, '2017-10-04 12:57:00', NULL, 0),
(101, 0, 0, 1, 3, 0, 'Membership', 0, 'Membership Renewal', 'Your membership has been expired on', 0, '2017-10-04 13:01:58', NULL, 0),
(102, 0, 0, 1, 3, 0, 'Membership', 0, 'Membership Renewal', 'Your membership has been expired on', 0, '2017-10-04 13:06:19', NULL, 0),
(103, 0, 0, 1, 2, 0, 'Membership', 0, 'Membership Renewal', 'Your membership has been expired on', 0, '2017-10-04 13:06:19', NULL, 0),
(104, 0, 0, 1, 3, 0, 'Membership', 0, 'Membership Renewal', 'Your membership has been expired on', 0, '2017-10-04 13:08:02', NULL, 0),
(105, 0, 0, 1, 2, 0, 'Membership', 0, 'Membership Renewal', 'Your membership has been expired on', 0, '2017-10-04 13:08:02', NULL, 0),
(106, 11, 0, 1, 26, 0, 'Wallet', 0, 'Reward Points', 'Earned 200 points on order 11', 0, '2017-10-05 14:36:57', NULL, 1),
(107, 18, 0, 1, 26, 0, 'Wallet', 0, 'Reward Points', 'Earned 200 points on order 18', 0, '2017-10-05 15:51:13', NULL, 1),
(108, 19, 0, 1, 26, 0, 'Wallet', 0, 'Reward Points', 'Earned 200 points on order 19', 0, '2017-10-05 16:15:51', NULL, 1),
(109, 10, 0, 1, 26, 0, 'Wallet', 0, 'Reward Points', 'Earned 200 points on order 10', 0, '2017-10-05 16:20:55', NULL, 1),
(110, 9, 0, 1, 26, 0, 'Wallet', 0, 'Reward Points', 'Earned 200 points on order 9', 0, '2017-10-05 16:25:14', NULL, 1),
(111, 8, 0, 1, 26, 0, 'Wallet', 0, 'Reward Points', 'Earned 10 points on order 8', 0, '2017-10-05 16:25:57', NULL, 1),
(112, 6, 0, 1, 26, 0, 'Wallet', 0, 'Reward Points', 'Earned 200 points on order 6', 0, '2017-10-05 16:27:19', NULL, 1),
(113, 20, 0, 1, 26, 0, 'Order', 0, 'Order Ready Status', 'Your Order is ready', 0, '2017-10-06 13:38:58', NULL, 1),
(114, 20, 0, 1, 26, 0, 'Wallet', 0, 'Reward Points', 'Earned 10 Points on Order No.20', 0, '2017-10-06 13:39:22', NULL, 1),
(115, 41, 8, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(116, 41, 8, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(117, 41, 8, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(118, 41, 8, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(119, 41, 8, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(120, 41, 8, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(121, 41, 8, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(122, 41, 8, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(123, 41, 8, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(124, 41, 8, 1, 33, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(125, 41, 8, 1, 34, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:51:29', NULL, 0),
(126, 42, 9, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(127, 42, 9, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(128, 42, 9, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(129, 42, 9, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(130, 42, 9, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(131, 42, 9, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(132, 42, 9, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(133, 42, 9, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(134, 42, 9, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(135, 42, 9, 1, 33, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(136, 42, 9, 1, 34, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 11:54:24', NULL, 0),
(137, 43, 10, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(138, 43, 10, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(139, 43, 10, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(140, 43, 10, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(141, 43, 10, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(142, 43, 10, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(143, 43, 10, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(144, 43, 10, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(145, 43, 10, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(146, 43, 10, 1, 33, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(147, 43, 10, 1, 34, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:11:18', NULL, 0),
(148, 44, 11, 1, 2, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(149, 44, 11, 1, 3, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(150, 44, 11, 1, 26, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(151, 44, 11, 1, 27, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(152, 44, 11, 1, 28, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(153, 44, 11, 1, 29, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(154, 44, 11, 1, 30, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(155, 44, 11, 1, 31, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(156, 44, 11, 1, 32, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(157, 44, 11, 1, 33, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(158, 44, 11, 1, 34, 0, 'Product', 0, 'New Product', 'New product has been launched', 0, '2017-10-07 12:43:40', NULL, 0),
(168, 1, 15, 1, 35, 0, 'Offer', 0, 'Offer', '10% OFF Yout Al-a Cart Bill', 0, '2017-12-29 15:46:11', NULL, 0),
(169, 1, 15, 1, 42, 0, 'Offer', 0, 'Offer', '10% OFF Yout Al-a Cart Bill', 0, '2017-12-29 15:46:11', NULL, 1),
(170, 1, 15, 1, 49, 0, 'Offer', 0, 'Offer', '10% OFF Yout Al-a Cart Bill', 0, '2017-12-29 15:46:11', NULL, 0),
(171, 34, 16, 1, 35, 0, 'Offer', 0, 'Offer', 'New Offer arrived on specific quantity', 0, '2018-01-02 18:59:52', NULL, 0),
(172, 34, 16, 1, 42, 0, 'Offer', 0, 'Offer', 'New Offer arrived on specific quantity', 0, '2018-01-02 18:59:52', NULL, 0),
(173, 34, 16, 1, 49, 0, 'Offer', 0, 'Offer', 'New Offer arrived on specific quantity', 0, '2018-01-02 18:59:52', NULL, 0),
(174, 35, 17, 1, 35, 0, 'Best Offer', 1, 'Best Offer', 'New Offer arrived on specific quantity', 0, '2018-01-02 19:15:38', NULL, 0),
(175, 35, 17, 1, 42, 0, 'Best Offer', 1, 'Best Offer', 'New Offer arrived on specific quantity', 0, '2018-01-02 19:15:38', NULL, 0),
(176, 35, 17, 1, 49, 0, 'Best Offer', 1, 'Best Offer', 'New Offer arrived on specific quantity', 0, '2018-01-02 19:15:38', NULL, 0),
(177, 4, 0, 1, 42, 0, 'Order', 1, 'Order', 'Your status is cancelled', 0, '2018-01-02 20:14:41', NULL, 0),
(178, 4, 0, 1, 42, 0, 'Order', 1, 'Order', 'Your status is cancelled', 0, '2018-01-02 20:18:24', NULL, 0),
(179, 36, 18, 1, 35, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-06 19:45:33', NULL, 0),
(180, 36, 18, 1, 42, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-06 19:45:33', NULL, 0),
(181, 36, 18, 1, 49, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-06 19:45:33', NULL, 0),
(182, 36, 18, 1, 50, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-06 19:45:33', NULL, 0),
(183, 36, 18, 1, 51, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-06 19:45:33', NULL, 0),
(184, 36, 18, 1, 52, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-06 19:45:33', NULL, 0),
(185, 37, 19, 1, 35, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:27:51', NULL, 0),
(186, 37, 19, 1, 42, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:27:51', NULL, 0),
(187, 37, 19, 1, 49, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:27:51', NULL, 0),
(188, 37, 19, 1, 50, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:27:51', NULL, 0),
(189, 37, 19, 1, 51, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:27:51', NULL, 0),
(190, 37, 19, 1, 52, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:27:51', NULL, 0),
(191, 38, 20, 1, 35, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:33:09', NULL, 0),
(192, 38, 20, 1, 42, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:33:09', NULL, 0),
(193, 38, 20, 1, 49, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:33:09', NULL, 0),
(194, 38, 20, 1, 50, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:33:09', NULL, 0),
(195, 38, 20, 1, 51, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:33:09', NULL, 0),
(196, 38, 20, 1, 52, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:33:09', NULL, 0),
(197, 39, 21, 1, 35, 0, 'Best Offer', 3, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:39:39', NULL, 0),
(198, 39, 21, 1, 42, 0, 'Best Offer', 3, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:39:39', NULL, 0),
(199, 39, 21, 1, 49, 0, 'Best Offer', 3, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:39:39', NULL, 0),
(200, 39, 21, 1, 50, 0, 'Best Offer', 3, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:39:39', NULL, 0),
(201, 39, 21, 1, 51, 0, 'Best Offer', 3, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:39:39', NULL, 0),
(202, 39, 21, 1, 52, 0, 'Best Offer', 3, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-10 11:39:39', NULL, 0),
(203, 2, 22, 1, 35, 0, 'Offer', 1, 'Offer', '3% OFF on Al-a Cart Bill', 0, '2018-01-10 15:40:14', NULL, 0),
(204, 2, 22, 1, 42, 0, 'Offer', 1, 'Offer', '3% OFF on Al-a Cart Bill', 0, '2018-01-10 15:40:14', NULL, 0),
(205, 2, 22, 1, 49, 0, 'Offer', 1, 'Offer', '3% OFF on Al-a Cart Bill', 0, '2018-01-10 15:40:14', NULL, 0),
(206, 2, 22, 1, 50, 0, 'Offer', 1, 'Offer', '3% OFF on Al-a Cart Bill', 0, '2018-01-10 15:40:14', NULL, 0),
(207, 2, 22, 1, 51, 0, 'Offer', 1, 'Offer', '3% OFF on Al-a Cart Bill', 0, '2018-01-10 15:40:14', NULL, 0),
(208, 2, 22, 1, 52, 0, 'Offer', 1, 'Offer', '3% OFF on Al-a Cart Bill', 0, '2018-01-10 15:40:14', NULL, 0),
(209, 3, 23, 1, 35, 0, 'Offer', 1, 'Offer', '12% OFF on Al-a Cart Bill', 0, '2018-01-10 16:20:04', NULL, 0),
(210, 3, 23, 1, 42, 0, 'Offer', 1, 'Offer', '12% OFF on Al-a Cart Bill', 0, '2018-01-10 16:20:04', NULL, 0),
(211, 3, 23, 1, 49, 0, 'Offer', 1, 'Offer', '12% OFF on Al-a Cart Bill', 0, '2018-01-10 16:20:04', NULL, 0),
(212, 3, 23, 1, 50, 0, 'Offer', 1, 'Offer', '12% OFF on Al-a Cart Bill', 0, '2018-01-10 16:20:04', NULL, 0),
(213, 3, 23, 1, 51, 0, 'Offer', 1, 'Offer', '12% OFF on Al-a Cart Bill', 0, '2018-01-10 16:20:04', NULL, 0),
(214, 3, 23, 1, 52, 0, 'Offer', 1, 'Offer', '12% OFF on Al-a Cart Bill', 0, '2018-01-10 16:20:04', NULL, 0),
(215, 30, 0, 1, 42, 0, 'Order', 1, 'Order', 'Your order is cancelled', 0, '2018-01-10 16:58:25', NULL, 0),
(216, 30, 0, 1, 42, 0, 'Wallet', 1, 'Credit', '323.4 is credit in wallet', 0, '2018-01-10 16:58:27', NULL, 0),
(217, 4, 24, 1, 35, 0, 'Offer', 1, 'Offer', '5% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:10', NULL, 0),
(218, 4, 24, 1, 42, 0, 'Offer', 1, 'Offer', '5% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:10', NULL, 0),
(219, 4, 24, 1, 49, 0, 'Offer', 1, 'Offer', '5% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:10', NULL, 0),
(220, 4, 24, 1, 50, 0, 'Offer', 1, 'Offer', '5% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:10', NULL, 0),
(221, 4, 24, 1, 51, 0, 'Offer', 1, 'Offer', '5% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:10', NULL, 0),
(222, 4, 24, 1, 52, 0, 'Offer', 1, 'Offer', '5% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:10', NULL, 0),
(223, 5, 25, 1, 35, 0, 'Offer', 1, 'Offer', '7% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:29', NULL, 0),
(224, 5, 25, 1, 42, 0, 'Offer', 1, 'Offer', '7% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:29', NULL, 0),
(225, 5, 25, 1, 49, 0, 'Offer', 1, 'Offer', '7% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:29', NULL, 0),
(226, 5, 25, 1, 50, 0, 'Offer', 1, 'Offer', '7% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:29', NULL, 0),
(227, 5, 25, 1, 51, 0, 'Offer', 1, 'Offer', '7% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:29', NULL, 0),
(228, 5, 25, 1, 52, 0, 'Offer', 1, 'Offer', '7% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:29', NULL, 0),
(229, 6, 26, 1, 35, 0, 'Offer', 1, 'Offer', '900% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:48', NULL, 0),
(230, 6, 26, 1, 42, 0, 'Offer', 1, 'Offer', '900% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:48', NULL, 0),
(231, 6, 26, 1, 49, 0, 'Offer', 1, 'Offer', '900% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:48', NULL, 0),
(232, 6, 26, 1, 50, 0, 'Offer', 1, 'Offer', '900% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:48', NULL, 0),
(233, 6, 26, 1, 51, 0, 'Offer', 1, 'Offer', '900% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:48', NULL, 0),
(234, 6, 26, 1, 52, 0, 'Offer', 1, 'Offer', '900% OFF on Al-a Cart Bill', 0, '2018-01-11 13:02:48', NULL, 0),
(235, 7, 27, 1, 35, 0, 'Offer', 1, 'Offer', '11% OFF on Al-a Cart Bill', 0, '2018-01-11 13:03:08', NULL, 0),
(236, 7, 27, 1, 42, 0, 'Offer', 1, 'Offer', '11% OFF on Al-a Cart Bill', 0, '2018-01-11 13:03:08', NULL, 0),
(237, 7, 27, 1, 49, 0, 'Offer', 1, 'Offer', '11% OFF on Al-a Cart Bill', 0, '2018-01-11 13:03:08', NULL, 0),
(238, 7, 27, 1, 50, 0, 'Offer', 1, 'Offer', '11% OFF on Al-a Cart Bill', 0, '2018-01-11 13:03:08', NULL, 0),
(239, 7, 27, 1, 51, 0, 'Offer', 1, 'Offer', '11% OFF on Al-a Cart Bill', 0, '2018-01-11 13:03:08', NULL, 0),
(240, 7, 27, 1, 52, 0, 'Offer', 1, 'Offer', '11% OFF on Al-a Cart Bill', 0, '2018-01-11 13:03:08', NULL, 0),
(241, 8, 28, 1, 35, 0, 'Offer', 2, 'Offer', '11% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:05', NULL, 0),
(242, 8, 28, 1, 42, 0, 'Offer', 2, 'Offer', '11% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:05', NULL, 0),
(243, 8, 28, 1, 49, 0, 'Offer', 2, 'Offer', '11% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:05', NULL, 0),
(244, 8, 28, 1, 50, 0, 'Offer', 2, 'Offer', '11% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:05', NULL, 0),
(245, 8, 28, 1, 51, 0, 'Offer', 2, 'Offer', '11% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:05', NULL, 0),
(246, 8, 28, 1, 52, 0, 'Offer', 2, 'Offer', '11% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:05', NULL, 0),
(247, 9, 29, 1, 35, 0, 'Offer', 2, 'Offer', '15% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:36', NULL, 0),
(248, 9, 29, 1, 42, 0, 'Offer', 2, 'Offer', '15% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:36', NULL, 0),
(249, 9, 29, 1, 49, 0, 'Offer', 2, 'Offer', '15% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:36', NULL, 0),
(250, 9, 29, 1, 50, 0, 'Offer', 2, 'Offer', '15% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:36', NULL, 0),
(251, 9, 29, 1, 51, 0, 'Offer', 2, 'Offer', '15% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:36', NULL, 0),
(252, 9, 29, 1, 52, 0, 'Offer', 2, 'Offer', '15% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:36', NULL, 0),
(253, 10, 30, 1, 35, 0, 'Offer', 2, 'Offer', '19% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:56', NULL, 0),
(254, 10, 30, 1, 42, 0, 'Offer', 2, 'Offer', '19% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:56', NULL, 0),
(255, 10, 30, 1, 49, 0, 'Offer', 2, 'Offer', '19% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:56', NULL, 0),
(256, 10, 30, 1, 50, 0, 'Offer', 2, 'Offer', '19% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:56', NULL, 0),
(257, 10, 30, 1, 51, 0, 'Offer', 2, 'Offer', '19% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:56', NULL, 0),
(258, 10, 30, 1, 52, 0, 'Offer', 2, 'Offer', '19% OFF on Foodparcel Bill', 0, '2018-01-11 13:13:56', NULL, 0),
(259, 11, 31, 1, 35, 0, 'Offer', 3, 'Offer', '10% OFF on Partypackage Bill', 0, '2018-01-11 13:18:45', NULL, 0),
(260, 11, 31, 1, 42, 0, 'Offer', 3, 'Offer', '10% OFF on Partypackage Bill', 0, '2018-01-11 13:18:45', NULL, 0),
(261, 11, 31, 1, 49, 0, 'Offer', 3, 'Offer', '10% OFF on Partypackage Bill', 0, '2018-01-11 13:18:45', NULL, 0),
(262, 11, 31, 1, 50, 0, 'Offer', 3, 'Offer', '10% OFF on Partypackage Bill', 0, '2018-01-11 13:18:45', NULL, 0),
(263, 11, 31, 1, 51, 0, 'Offer', 3, 'Offer', '10% OFF on Partypackage Bill', 0, '2018-01-11 13:18:45', NULL, 0),
(264, 11, 31, 1, 52, 0, 'Offer', 3, 'Offer', '10% OFF on Partypackage Bill', 0, '2018-01-11 13:18:45', NULL, 0),
(265, 12, 32, 1, 35, 0, 'Offer', 3, 'Offer', '5% OFF on Partypackage Bill', 0, '2018-01-11 13:19:14', NULL, 0),
(266, 12, 32, 1, 42, 0, 'Offer', 3, 'Offer', '5% OFF on Partypackage Bill', 0, '2018-01-11 13:19:14', NULL, 0),
(267, 12, 32, 1, 49, 0, 'Offer', 3, 'Offer', '5% OFF on Partypackage Bill', 0, '2018-01-11 13:19:14', NULL, 0),
(268, 12, 32, 1, 50, 0, 'Offer', 3, 'Offer', '5% OFF on Partypackage Bill', 0, '2018-01-11 13:19:14', NULL, 0),
(269, 12, 32, 1, 51, 0, 'Offer', 3, 'Offer', '5% OFF on Partypackage Bill', 0, '2018-01-11 13:19:14', NULL, 0),
(270, 12, 32, 1, 52, 0, 'Offer', 3, 'Offer', '5% OFF on Partypackage Bill', 0, '2018-01-11 13:19:14', NULL, 0),
(271, 13, 33, 1, 35, 0, 'Offer', 3, 'Offer', '13% OFF on Partypackage Bill', 0, '2018-01-11 13:20:10', NULL, 0),
(272, 13, 33, 1, 42, 0, 'Offer', 3, 'Offer', '13% OFF on Partypackage Bill', 0, '2018-01-11 13:20:10', NULL, 0),
(273, 13, 33, 1, 49, 0, 'Offer', 3, 'Offer', '13% OFF on Partypackage Bill', 0, '2018-01-11 13:20:10', NULL, 0),
(274, 13, 33, 1, 50, 0, 'Offer', 3, 'Offer', '13% OFF on Partypackage Bill', 0, '2018-01-11 13:20:10', NULL, 0),
(275, 13, 33, 1, 51, 0, 'Offer', 3, 'Offer', '13% OFF on Partypackage Bill', 0, '2018-01-11 13:20:10', NULL, 0),
(276, 13, 33, 1, 52, 0, 'Offer', 3, 'Offer', '13% OFF on Partypackage Bill', 0, '2018-01-11 13:20:10', NULL, 0),
(277, 40, 34, 1, 35, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 3', 0, '2018-01-11 14:48:09', NULL, 0),
(278, 40, 34, 1, 42, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 3', 0, '2018-01-11 14:48:09', NULL, 0),
(279, 40, 34, 1, 49, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 3', 0, '2018-01-11 14:48:09', NULL, 0),
(280, 40, 34, 1, 50, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 3', 0, '2018-01-11 14:48:09', NULL, 0),
(281, 40, 34, 1, 51, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 3', 0, '2018-01-11 14:48:09', NULL, 0),
(282, 40, 34, 1, 52, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 3', 0, '2018-01-11 14:48:09', NULL, 0),
(283, 32, 0, 1, 42, 0, 'Wallet', 1, 'Debit', '29.50 is debit in wallet', 0, '2018-01-11 15:12:34', NULL, 0),
(284, 33, 0, 1, 42, 0, 'Wallet', 1, 'Debit', '10.30 is debit in wallet', 0, '2018-01-11 15:15:56', NULL, 0),
(285, 33, 0, 1, 42, 0, 'Order', 1, 'Order', 'Your order is cancelled', 0, '2018-01-11 15:20:46', NULL, 0),
(286, 33, 0, 1, 42, 0, 'Wallet', 1, 'Credit', '33.00 is credit in wallet', 0, '2018-01-11 15:20:47', NULL, 0),
(287, 32, 0, 1, 42, 0, 'Order', 1, 'Order', 'Your order is cancelled', 0, '2018-01-11 15:22:52', NULL, 0),
(288, 32, 0, 1, 42, 0, 'Wallet', 1, 'Credit', '32.34 is credit in wallet', 0, '2018-01-11 15:22:52', NULL, 0),
(289, 14, 35, 1, 35, 0, 'Offer', 2, 'Offer', '45435% OFF on Foodparcel Bill', 0, '2018-01-12 14:17:09', NULL, 0),
(290, 14, 35, 1, 42, 0, 'Offer', 2, 'Offer', '45435% OFF on Foodparcel Bill', 0, '2018-01-12 14:17:09', NULL, 0),
(291, 14, 35, 1, 49, 0, 'Offer', 2, 'Offer', '45435% OFF on Foodparcel Bill', 0, '2018-01-12 14:17:09', NULL, 0),
(292, 14, 35, 1, 50, 0, 'Offer', 2, 'Offer', '45435% OFF on Foodparcel Bill', 0, '2018-01-12 14:17:09', NULL, 0),
(293, 14, 35, 1, 51, 0, 'Offer', 2, 'Offer', '45435% OFF on Foodparcel Bill', 0, '2018-01-12 14:17:09', NULL, 0),
(294, 14, 35, 1, 52, 0, 'Offer', 2, 'Offer', '45435% OFF on Foodparcel Bill', 0, '2018-01-12 14:17:09', NULL, 0),
(295, 41, 36, 1, 35, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 34', 0, '2018-01-12 16:30:56', NULL, 0),
(296, 41, 36, 1, 42, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 34', 0, '2018-01-12 16:30:56', NULL, 0),
(297, 41, 36, 1, 49, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 34', 0, '2018-01-12 16:30:56', NULL, 0),
(298, 41, 36, 1, 50, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 34', 0, '2018-01-12 16:30:56', NULL, 0),
(299, 41, 36, 1, 51, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 34', 0, '2018-01-12 16:30:56', NULL, 0),
(300, 41, 36, 1, 52, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 34', 0, '2018-01-12 16:30:56', NULL, 0),
(301, 42, 37, 1, 35, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 434', 0, '2018-01-12 16:57:05', NULL, 0),
(302, 42, 37, 1, 42, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 434', 0, '2018-01-12 16:57:05', NULL, 0),
(303, 42, 37, 1, 49, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 434', 0, '2018-01-12 16:57:05', NULL, 0),
(304, 42, 37, 1, 50, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 434', 0, '2018-01-12 16:57:05', NULL, 0),
(305, 42, 37, 1, 51, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 434', 0, '2018-01-12 16:57:05', NULL, 0),
(306, 42, 37, 1, 52, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 434', 0, '2018-01-12 16:57:05', NULL, 0),
(307, 43, 38, 1, 35, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 45', 0, '2018-01-12 18:01:53', NULL, 0),
(308, 43, 38, 1, 42, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 45', 0, '2018-01-12 18:01:53', NULL, 0),
(309, 43, 38, 1, 49, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 45', 0, '2018-01-12 18:01:53', NULL, 0),
(310, 43, 38, 1, 50, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 45', 0, '2018-01-12 18:01:53', NULL, 0),
(311, 43, 38, 1, 51, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 45', 0, '2018-01-12 18:01:53', NULL, 0),
(312, 43, 38, 1, 52, 0, 'Best Offer', 2, 'Best Offer', 'New Offer on order of quantity 45', 0, '2018-01-12 18:01:53', NULL, 0),
(313, 44, 39, 1, 35, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 1', 0, '2018-01-17 15:52:50', NULL, 0),
(314, 44, 39, 1, 42, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 1', 0, '2018-01-17 15:52:50', NULL, 0),
(315, 44, 39, 1, 49, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 1', 0, '2018-01-17 15:52:50', NULL, 0),
(316, 44, 39, 1, 50, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 1', 0, '2018-01-17 15:52:50', NULL, 0),
(317, 44, 39, 1, 51, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 1', 0, '2018-01-17 15:52:50', NULL, 0),
(318, 44, 39, 1, 52, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 1', 0, '2018-01-17 15:52:50', NULL, 0),
(319, 45, 40, 1, 35, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 5476', 0, '2018-01-19 15:17:03', NULL, 0),
(320, 45, 40, 1, 42, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 5476', 0, '2018-01-19 15:17:03', NULL, 0),
(321, 45, 40, 1, 49, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 5476', 0, '2018-01-19 15:17:03', NULL, 0),
(322, 45, 40, 1, 50, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 5476', 0, '2018-01-19 15:17:03', NULL, 0),
(323, 45, 40, 1, 51, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 5476', 0, '2018-01-19 15:17:03', NULL, 0),
(324, 45, 40, 1, 52, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 5476', 0, '2018-01-19 15:17:03', NULL, 0),
(325, 46, 41, 1, 35, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-20 11:23:23', NULL, 0),
(326, 46, 41, 1, 42, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-20 11:23:23', NULL, 0),
(327, 46, 41, 1, 49, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-20 11:23:23', NULL, 0),
(328, 46, 41, 1, 50, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-20 11:23:23', NULL, 0),
(329, 46, 41, 1, 51, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-20 11:23:23', NULL, 0),
(330, 46, 41, 1, 52, 0, 'Best Offer', 1, 'Best Offer', 'New Offer on order of quantity 10', 0, '2018-01-20 11:23:23', NULL, 0),
(331, 0, 0, 1, 66, 0, 'Installation Reward', 0, 'Installation Reward', 'Congratulations! You have successfully registered . Please verify your mobile no. to get 250rs in wallet', 0, '2018-01-24 14:29:18', NULL, 0),
(332, 0, 0, 1, 70, 0, 'Installation Reward', 0, 'Installation Reward', 'Congratulations! You have successfully registered . Please verify your mobile no. to get 250rs in wallet', 1, '2018-01-24 15:17:15', NULL, 0),
(333, 0, 0, 1, 71, 0, 'Installation Reward', 0, 'Installation Reward', 'Congratulations! You have successfully registered . Please verify your mobile no. to get 250rs in wallet', 0, '2018-01-25 12:59:33', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `address` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_address`
--

INSERT INTO `user_address` (`id`, `user_id`, `address`) VALUES
(1, '42', 'new palasia'),
(2, '42', 'old palasia'),
(4, '42', 'magnet tower indore');

-- --------------------------------------------------------

--
-- Table structure for table `user_membership`
--

CREATE TABLE `user_membership` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `membership_type` varchar(100) NOT NULL,
  `membership_subscription_date` date NOT NULL,
  `subscription_expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_membership`
--

INSERT INTO `user_membership` (`id`, `user_id`, `membership_type`, `membership_subscription_date`, `subscription_expiry_date`) VALUES
(1, 26, 'PREMIUM', '2017-10-07', '2017-10-17'),
(2, 3, 'PREMIUM', '2017-10-11', '2017-10-21'),
(3, 2, 'PREMIUM', '2017-09-20', '2017-10-05'),
(4, 34, 'PREMIUM', '2017-10-11', '2017-10-21');

-- --------------------------------------------------------

--
-- Table structure for table `user_offers`
--

CREATE TABLE `user_offers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_offers`
--

INSERT INTO `user_offers` (`id`, `user_id`, `offer_id`, `created_date`) VALUES
(1, 1, 7, '2017-09-26 17:44:22'),
(2, 2, 7, '2017-09-26 17:44:22'),
(3, 3, 7, '2017-09-26 17:44:22'),
(4, 4, 7, '2017-09-26 17:44:22'),
(5, 5, 7, '2017-09-26 17:44:22'),
(6, 6, 7, '2017-09-26 17:44:22'),
(7, 26, 7, '2017-09-26 17:44:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_wallet`
--

CREATE TABLE `user_wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` float(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_wallet`
--

INSERT INTO `user_wallet` (`id`, `user_id`, `amount`) VALUES
(1, 42, 1012.34),
(4, 61, 250.00),
(8, 66, 250.00),
(9, 70, 250.00),
(10, 71, 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `transaction_type` enum('DEBIT','CREDIT') NOT NULL,
  `amount` float(8,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `transcation_user_type` varchar(100) NOT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id`, `user_id`, `order_id`, `transaction_type`, `amount`, `description`, `transcation_user_type`, `date`) VALUES
(1, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(2, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(3, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(4, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(5, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(6, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(7, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(8, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(9, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(10, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(11, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(12, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(13, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(14, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(15, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(16, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(17, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(18, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(19, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(20, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(21, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(22, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(23, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(24, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(25, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(26, 42, 4, 'CREDIT', 323.00, NULL, '1', '2018-01-02 00:00:00'),
(27, 42, 4, 'CREDIT', 323.00, 'Used 10 Points on Order No.21', '1', '2018-01-02 00:00:00'),
(28, 42, 4, 'CREDIT', 323.00, 'Used 10 Points on Upgrade Membership', '1', '2018-01-02 00:00:00'),
(29, 42, 4, 'CREDIT', 323.00, 'Earned 10 Points on Order No. 20', '1', '2018-01-02 00:00:00'),
(30, 42, 4, 'CREDIT', 323.00, 'Earned 10 Points on Credit wallet Balance', '1', '2018-01-02 00:00:00'),
(31, 42, 4, 'CREDIT', 323.00, 'Used 10 Points on Order No. 22', '1', '2018-01-02 00:00:00'),
(32, 42, 4, 'CREDIT', 323.00, 'Used 10 Points on Order No. 23', '1', '2018-01-02 00:00:00'),
(33, 42, 4, 'CREDIT', 323.00, 'Earned 10 Points on Credit wallet Balance', '1', '2018-01-02 00:00:00'),
(34, 42, 4, 'CREDIT', 323.00, 'Earned 10 Points on Credit wallet Balance', '1', '2018-01-02 00:00:00'),
(35, 42, 4, 'CREDIT', 330.00, NULL, '2', '2018-01-02 00:00:00'),
(36, 42, 30, 'CREDIT', 323.00, 'User wants to cancel his order', '1', '2018-01-10 16:58:25'),
(37, 42, 32, 'DEBIT', 29.50, 'User use 29.50 for payment.', '1', '2018-01-11 15:12:34'),
(38, 42, 33, 'DEBIT', 10.30, 'User use 10.30 for payment.', '1', '2018-01-11 15:15:56'),
(39, 42, 33, 'CREDIT', 33.00, 'Admin cancel order due to some reasons', '2', '2018-01-11 15:20:46'),
(40, 42, 32, 'CREDIT', 32.34, 'User wants to cancel his order', '1', '2018-01-11 15:22:52'),
(41, 70, 0, 'CREDIT', 250.00, 'Congratulations! You have got 250rs', '1', '2018-01-25 07:21:29'),
(42, 71, 0, 'CREDIT', 250.00, 'Congratulations! You have got250rs on Calypso App installation', '1', '2018-01-25 12:59:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alla_cart`
--
ALTER TABLE `alla_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing_offer`
--
ALTER TABLE `billing_offer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_product`
--
ALTER TABLE `cart_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_management`
--
ALTER TABLE `category_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms`
--
ALTER TABLE `cms`
  ADD PRIMARY KEY (`cms_id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `configure_products`
--
ALTER TABLE `configure_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_support`
--
ALTER TABLE `contact_support`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`countries_id`);

--
-- Indexes for table `device_history`
--
ALTER TABLE `device_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_parcel`
--
ALTER TABLE `food_parcel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_dates_day`
--
ALTER TABLE `items_dates_day`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_dates_days_price`
--
ALTER TABLE `items_dates_days_price`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_dates`
--
ALTER TABLE `item_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `key_configuration`
--
ALTER TABLE `key_configuration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_token`
--
ALTER TABLE `membership_token`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_meta`
--
ALTER TABLE `order_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_category`
--
ALTER TABLE `package_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parcel_items`
--
ALTER TABLE `parcel_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `party_package`
--
ALTER TABLE `party_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `priceOffers`
--
ALTER TABLE `priceOffers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_subcategory`
--
ALTER TABLE `product_subcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suggestion_feedback`
--
ALTER TABLE `suggestion_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_device_history`
--
ALTER TABLE `users_device_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `user_id_3` (`user_id`),
  ADD KEY `user_id_4` (`user_id`),
  ADD KEY `user_id_5` (`user_id`),
  ADD KEY `user_id_6` (`user_id`);

--
-- Indexes for table `users_notifications`
--
ALTER TABLE `users_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_membership`
--
ALTER TABLE `user_membership`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_offers`
--
ALTER TABLE `user_offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_wallet`
--
ALTER TABLE `user_wallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `alla_cart`
--
ALTER TABLE `alla_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `billing_offer`
--
ALTER TABLE `billing_offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `cart_product`
--
ALTER TABLE `cart_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `category_management`
--
ALTER TABLE `category_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `cms`
--
ALTER TABLE `cms`
  MODIFY `cms_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `configure_products`
--
ALTER TABLE `configure_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contact_support`
--
ALTER TABLE `contact_support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `countries_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;
--
-- AUTO_INCREMENT for table `device_history`
--
ALTER TABLE `device_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `food_parcel`
--
ALTER TABLE `food_parcel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `items_dates_day`
--
ALTER TABLE `items_dates_day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `items_dates_days_price`
--
ALTER TABLE `items_dates_days_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;
--
-- AUTO_INCREMENT for table `item_dates`
--
ALTER TABLE `item_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `key_configuration`
--
ALTER TABLE `key_configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `order_meta`
--
ALTER TABLE `order_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `package_category`
--
ALTER TABLE `package_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `parcel_items`
--
ALTER TABLE `parcel_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `party_package`
--
ALTER TABLE `party_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `priceOffers`
--
ALTER TABLE `priceOffers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `product_subcategory`
--
ALTER TABLE `product_subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suggestion_feedback`
--
ALTER TABLE `suggestion_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT for table `users_device_history`
--
ALTER TABLE `users_device_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;
--
-- AUTO_INCREMENT for table `users_notifications`
--
ALTER TABLE `users_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=334;
--
-- AUTO_INCREMENT for table `user_address`
--
ALTER TABLE `user_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user_membership`
--
ALTER TABLE `user_membership`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user_offers`
--
ALTER TABLE `user_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `user_wallet`
--
ALTER TABLE `user_wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
