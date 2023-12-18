-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Sun 17.Dec 2023, 14:13
-- Verzia serveru: 10.4.28-MariaDB
-- Verzia PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `eshop_db`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `login` varchar(80) NOT NULL,
  `password` varchar(60) NOT NULL,
  `profile_pic_url` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `brand` varchar(45) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `type` varchar(10) NOT NULL,
  `material` varchar(45) NOT NULL,
  `weight_in_grams` double NOT NULL,
  `description` mediumtext NOT NULL,
  `selling_price` double NOT NULL,
  `units_in_stock` int(11) NOT NULL,
  `image_url` varchar(2048) DEFAULT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `sale`
--

CREATE TABLE `sale` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `sale_has_product`
--

CREATE TABLE `sale_has_product` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_ibfk_1` (`account_id`);

--
-- Indexy pre tabuľku `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sale_account1_idx` (`account_id`);

--
-- Indexy pre tabuľku `sale_has_product`
--
ALTER TABLE `sale_has_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sale_has_product_product1_idx` (`product_id`),
  ADD KEY `fk_sale_has_product_sale1_idx` (`sale_id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pre tabuľku `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pre tabuľku `sale`
--
ALTER TABLE `sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT pre tabuľku `sale_has_product`
--
ALTER TABLE `sale_has_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `fk_sale_account1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Obmedzenie pre tabuľku `sale_has_product`
--
ALTER TABLE `sale_has_product`
  ADD CONSTRAINT `fk_sale_has_product_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sale_has_product_sale1` FOREIGN KEY (`sale_id`) REFERENCES `sale` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
