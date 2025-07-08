-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2025 at 07:15 PM
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
-- Database: `grocers`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_total_amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_status`, `order_date`, `order_total_amount`) VALUES
(1, 'Pending', '2025-06-18 16:57:52', 13.45),
(2, 'Pending', '2025-06-18 17:04:35', 33.25),
(3, 'Pending', '2025-06-18 17:04:56', 20.70),
(4, 'Pending', '2025-06-18 17:06:24', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_stock` int(11) DEFAULT 0,
  `product_image` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_stock`, `product_image`) VALUES
(1, 'Joya Apple 8s', 12.00, 50, 'https://publish-p35803-e190640.adobeaemcloud.com/content/dam/aem-cplotusonlinecommerce-project/my/images/magento/catalog/product/7/5/750945401694502415.jpg/jcr:content/renditions/medium.png'),
(2, 'Banana', 4.59, 75, 'https://www.krinstitute.org/assets/contentMS/img/template/shutterstock_518328943.jpg'),
(3, 'Dutch Lady Fresh Milk', 7.45, 27, 'https://publish-p35803-e190640.adobeaemcloud.com/content/dam/aem-cplotusonlinecommerce-project/my/images/magento/catalog/product/1/2/124161745564250.jpg/jcr:content/renditions/medium.png'),
(4, 'Massimo White Sandwich Loaf 400G', 3.00, 23, 'https://publish-p35803-e190640.adobeaemcloud.com/content/dam/aem-cplotusonlinecommerce-project/my/images/magento/catalog/product/m/a/massimo_white_sandwich_loaf_400g_1692327254.jpg/jcr:content/renditions/medium.png'),
(5, 'Chicken Fillet', 16.80, 19, 'https://publish-p35803-e190640.adobeaemcloud.com/content/dam/aem-cplotusonlinecommerce-project/my/images/magento/catalog/product/l/o/lotus_s_3_mar_with_shadow_191647880136.png/jcr:content/renditions/medium.png'),
(6, 'Tomato 6s', 5.80, 39, 'https://publish-p35803-e190640.adobeaemcloud.com/content/dam/aem-cplotusonlinecommerce-project/my/images/magento/catalog/product/1/7/17653101706606335.jpg/jcr:content/renditions/plp-large.jpeg'),
(7, 'Anchor Cheese 200GM', 9.25, 34, 'https://publish-p35803-e190640.adobeaemcloud.com/content/dam/aem-cplotusonlinecommerce-project/my/images/magento/catalog/product/908/9415007016908/ShotType1_540x540.jpg/jcr:content/renditions/plp-large.jpeg'),
(8, 'Happy Egg Grade D 30s', 7.20, 59, 'https://publish-p35803-e190640.adobeaemcloud.com/content/dam/aem-cplotusonlinecommerce-project/my/images/magento/catalog/product/7/5/750813341697440051.jpg/jcr:content/renditions/plp-large.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
