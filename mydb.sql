-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 06 2023 г., 14:34
-- Версия сервера: 10.6.7-MariaDB-log
-- Версия PHP: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mydb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `house` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entrance` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appartement` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'coffee'),
(2, 'tea'),
(3, 'bakery'),
(4, 'pizza'),
(5, 'burgers'),
(6, 'sandwiches'),
(7, 'rolls'),
(8, 'desserts'),
(9, 'drinks'),
(10, 'alcohol');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`products`)),
  `preliminary_cost` smallint(6) NOT NULL,
  `points_spent` smallint(6) NOT NULL,
  `total` smallint(6) NOT NULL,
  `points_gained` smallint(6) NOT NULL,
  `payment_method` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(19) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` smallint(6) NOT NULL,
  `price` int(6) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `weight`, `price`, `description`, `category_id`) VALUES
(1, 'latte', 200, 90, 'This is delicious!', 1),
(2, 'americano', 200, 90, 'This is delicious!', 1),
(3, 'espresso', 200, 90, 'This is delicious!', 1),
(4, 'black tea', 200, 55, 'This is delicious!', 2),
(5, 'green tea', 200, 55, 'This is delicious!', 2),
(6, 'matcha tea', 200, 75, 'This is delicious!', 2),
(7, 'meat pie', 190, 85, 'This is delicious!', 3),
(8, 'cheese pie', 190, 85, 'This is delicious!', 3),
(9, 'hot dog', 190, 100, 'This is delicious!', 3),
(10, '4 cheeses', 500, 390, 'This is delicious!', 4),
(11, 'meaty', 500, 450, 'This is delicious!', 4),
(12, 'pineapple', 500, 400, 'This is delicious!', 4),
(13, 'simple burger', 250, 90, 'This is delicious!', 5),
(14, 'double burger', 500, 150, 'This is delicious!', 5),
(15, 'vegetarian burger', 250, 80, 'This is delicious!', 5),
(16, 'chicken sandwich', 150, 60, 'This is delicious!', 6),
(17, 'pork sandwich', 150, 70, 'This is delicious!', 6),
(18, 'sweet sandwich', 150, 65, 'This is delicious!', 6),
(19, 'california', 900, 700, 'This is delicious!', 7),
(20, 'filadelfia', 900, 700, 'This is delicious!', 7),
(21, 'sushi', 700, 800, 'This is delicious!', 7),
(22, 'cupcake', 50, 120, 'This is delicious!', 8),
(23, 'brownie', 50, 120, 'This is delicious!', 8),
(24, 'ice cream', 50, 110, 'This is delicious!', 8),
(25, 'apple juice', 80, 60, 'This is delicious!', 9),
(26, 'milk shake', 60, 75, 'This is delicious!', 9),
(27, 'fruit juice', 80, 60, 'This is delicious!', 9),
(28, 'beer', 450, 90, 'This is delicious!', 10),
(29, 'wine', 300, 115, 'This is delicious!', 10),
(30, 'coctail', 200, 140, 'This is delicious!', 10);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` mediumint(9) NOT NULL,
  `login` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points` smallint(6) DEFAULT NULL,
  `phone` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday_date` date NOT NULL,
  `registration_date` date NOT NULL,
  `img` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
