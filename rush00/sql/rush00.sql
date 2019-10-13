-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.99.104
-- Generation Time: Oct 13, 2019 at 07:16 PM
-- Server version: 10.4.8-MariaDB-1:10.4.8+maria~bionic
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rush00`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `short` varchar(16) NOT NULL COMMENT 'Ключ',
  `name` text NOT NULL COMMENT 'Имя',
  `description` text NOT NULL COMMENT 'Описание',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Дата создания',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Дата изменения',
  `active` int(1) DEFAULT 0 COMMENT 'Статус'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `short`, `name`, `description`, `created_at`, `updated_at`, `active`) VALUES
(1, 'python', 'Python', 'Python (МФА: [ˈpʌɪθ(ə)n]; в русском языке распространено название пито́н) — высокоуровневый язык программирования общего назначения, ориентированный на повышение производительности разработчика и читаемости кода. Синтаксис ядра Python минималистичен. В то же время стандартная библиотека включает большой объём полезных функций.', '2019-10-13 14:48:33', '2019-10-13 15:18:38', 1),
(2, 'cpp', 'C/C++', 'Си (англ. C) — компилируемый статически типизированный язык программирования общего назначения, разработанный в 1969—1973 годах сотрудником Bell Labs Деннисом Ритчи как развитие языка Би. Первоначально был разработан для реализации операционной системы UNIX, но впоследствии был перенесён на множество других платформ. Согласно дизайну языка, его конструкции близко сопоставляются типичным машинным инструкциям, благодаря чему он нашёл применение в проектах, для которых был свойственен язык ассемблера, в том числе как в операционных системах, так и в различном прикладном программном обеспечении для множества устройств — от суперкомпьютеров до встраиваемых систем. Язык программирования Си оказал существенное влияние на развитие индустрии программного обеспечения, а его синтаксис стал основой для таких языков программирования, как C++, C#, Java и Objective-C.', '2019-10-13 14:48:33', '2019-10-13 15:18:38', 1),
(3, 'java', 'Java', 'Java — строго типизированный объектно-ориентированный язык программирования, разработанный компанией Sun Microsystems (в последующем приобретённой компанией Oracle). Разработка ведётся сообществом, организованным через Java Community Process, язык и основные реализующие его технологии распространяются по лицензии GPL. Права на торговую марку принадлежат корпорации Oracle.', '2019-10-13 14:48:46', '2019-10-13 15:18:46', 1),
(4, 'all', 'Все книги', 'Здесь вы можете увидеть все книги, что представлены в нашем магазине!', '2019-10-13 15:44:55', '2019-10-13 15:45:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `category_item`
--

CREATE TABLE `category_item` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `category_id` int(11) NOT NULL COMMENT 'ID категории',
  `item_id` int(11) NOT NULL COMMENT 'ID книги'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category_item`
--

INSERT INTO `category_item` (`id`, `category_id`, `item_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `short` varchar(16) NOT NULL COMMENT 'Ключ',
  `name` tinytext NOT NULL COMMENT 'Название',
  `price` float NOT NULL DEFAULT 0 COMMENT 'Цена',
  `description` text NOT NULL COMMENT 'Описание',
  `image` tinytext NOT NULL COMMENT 'Ссылка на картинку',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Дата создания',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Дата изменения',
  `active` int(1) DEFAULT 0 COMMENT 'Статус'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `short`, `name`, `price`, `description`, `image`, `created_at`, `updated_at`, `active`) VALUES
(1, 'python_1', 'Изучаем программирование на Python', 1056, 'Надоело продираться через дебри малопонятных самоучителей по программированию? С этой книгой вы без труда усвоите азы Python и научитесь работать со структурами и функциями. В ходе обучения вы создадите свое собственное веб-приложение и узнаете, как управлять базами данных, обрабатывать исключения, пользоваться контекстными менеджерами, декораторами и генераторами. Все это и многое другое - во втором издании \"Изучаем программирование на Python\".', 'https://i.ibb.co/C2zCX4K/1019869028.jpg', '2019-10-13 15:12:39', '2019-10-13 15:12:39', 1),
(2, 'python_2', 'Изучаем Python. Том 1', 2125, 'С помощью этой практической книги вы получите всестороннее и глубокое введение в основы языка Python. Будучи основанным на популярном учебном курсе Марка Лутца, обновленное 5-е издание книги поможет вам быстро научиться писать эффективный высококачественный код на Python. Она является идеальным способом начать изучение Python, будь вы новичок в программировании или профессиональный разработчик программного обеспечения на других языках.Это простое и понятное учебное пособие, укомплектованное контрольными вопросами, упражнениями и полезными иллюстрациями, позволит вам освоить основы линеек Python 3.X и 2.X. Вы также ознакомитесь с расширенными возможностями языка, получившими широкое распространение в коде Python.Благодаря книге вы:\" Исследуете основные встроенные типы объектов Python, такие как числа, списки и словари\" Научитесь создавать и обрабатывать объекты с помощью операторов Python и освоите общую синтаксическую модель Python\" Сможете применять функции для устранения избыточности кода и упаковки кода с целью многократного использования\" Узнаете, как организовывать операторы, функции и прочие инструменты в более крупные компоненты посредством модулей\" Погрузитесь глубже в классы - инструмент объектно-ориентированного программирования Python для структурирования кода\" Научитесь писать крупные программы с применением модели обработки исключений и инструментов разработки Python\" Освоите более сложные инструменты Python, включая декораторы, дескрипторы, метаклассы и обработку Unicode\"Книга Learning Python находится в начале моего списка рекомендованной литературы для любого, кто желает научиться программировать на Python.\"Дуг Хеллманнстарший инженер-программист,Racemi, Inc.Марк Лутц является мировым лидером в обучении языку Python, автором самых ранних и ставших бестселлерами книг по Python, а также первопроходцем в сообществе Python, начиная с 1992 года. Обладая более чем 30-летним опытом разработки, Марк был автором книг Programming Python, 4th Edition и Python Pocket Reference, 4th Edition издательства O\'Reilly.', 'https://i.ibb.co/s5hwkcV/1037901189.jpg', '2019-10-13 15:13:16', '2019-10-13 15:13:16', 1),
(3, 'python_3', 'Изучаем Python. Программирование игр, визуализация данных, веб-приложения', 1106, 'Книга \"Изучаем Python\" - это ускоренный курс, который позволит вам сэкономить время и сразу начать писать работоспособные программы (игры, визуализации данных, веб-приложения и многое другое).\r\nХотите стать программистом? В первой части книги вам предстоит узнать о базовых принципах программирования, познакомиться со списками, словарями, классами и циклами, вы научитесь создавать программы и тестировать код. Во второй части книги вы начнете использовать знания на практике, работая над тремя крупными проектами: создадите собственную \"стрелялку\" с нарастающей сложностью уровней, займетесь работой с большими наборами данных и освоите их визуализацию, и, наконец, создадите полноценное веб-приложение на базе Django, гарантирующее конфиденциальность пользовательской информации.\r\nЕсли вы решились разобраться в том что такое программирование, не нужно ждать. Ключ на старт и вперед!', 'https://i.ibb.co/FBVYkv2/1037901646.jpg', '2019-10-13 15:13:47', '2019-10-13 15:13:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'ID пользователя',
  `status` varchar(16) NOT NULL DEFAULT 'placed' COMMENT 'Статус заказа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`) VALUES
(1, 7, 'placed'),
(2, 7, 'placed'),
(3, 7, 'placed'),
(4, 7, 'placed'),
(5, 7, 'placed');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `order_id` int(11) NOT NULL COMMENT 'ID заказа',
  `item_id` int(11) NOT NULL COMMENT 'ID книги',
  `item_qty` int(11) NOT NULL DEFAULT 1 COMMENT 'Количество книг'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `item_id`, `item_qty`) VALUES
(1, 1, 1, 8),
(2, 4, 1, 4),
(3, 5, 2, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `type` text NOT NULL DEFAULT 'user' COMMENT 'Тип пользователя',
  `name` text NOT NULL COMMENT 'Имя пользователя',
  `email` text NOT NULL COMMENT 'Электронная почта',
  `password` varchar(128) NOT NULL COMMENT 'Хэш пароля',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Дата создания',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Дата изменения',
  `active` int(1) NOT NULL DEFAULT 0 COMMENT 'Статус'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `name`, `email`, `password`, `created_at`, `updated_at`, `active`) VALUES
(7, 'admin', 'test', 'test@test', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff', '2019-10-13 16:13:17', '2019-10-13 18:36:40', 1),
(8, 'user', 'test', 'test@test2', '5e2c119747b29257037d21fac79bffb824873fe4d44843947d1f672aeb9bb42687c17c35c6bedbec4ceb8b1db77cf2588851d83abe6e111c5243be965e770704', '2019-10-13 16:13:39', '2019-10-13 16:13:39', 1),
(9, 'user', 'set', 'set@set', 'a702ceb437e84f953fb015c343a9ac457d3bf915b73ec4256aa9f6b348454e9c9d3393f377c2fee3067f5907561b24214beb46e8f9b6750cd24239f7b4216608', '2019-10-13 16:27:19', '2019-10-13 16:27:19', 1),
(10, 'user', 'admin', 'admin@admin.admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', '2019-10-13 18:05:46', '2019-10-13 18:05:46', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indexes for table `category_item`
--
ALTER TABLE `category_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category_item`
--
ALTER TABLE `category_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_item`
--
ALTER TABLE `category_item`
  ADD CONSTRAINT `category_item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `category_item_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
