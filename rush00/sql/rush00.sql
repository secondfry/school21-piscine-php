-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.99.104
-- Generation Time: Oct 13, 2019 at 03:14 PM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Дата создания',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Дата изменения',
  `active` int(1) DEFAULT 0 COMMENT 'Статус'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `short`, `name`, `created_at`, `updated_at`, `active`) VALUES
(1, 'python', 'Python', '2019-10-13 14:48:33', '2019-10-13 14:48:33', 1),
(2, 'cpp', 'C/C++', '2019-10-13 14:48:33', '2019-10-13 14:48:33', 1),
(3, 'java', 'Java', '2019-10-13 14:48:46', '2019-10-13 14:48:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `short` varchar(16) NOT NULL COMMENT 'Ключ',
  `name` tinytext NOT NULL COMMENT 'Название',
  `category` int(11) NOT NULL COMMENT 'Категория',
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

INSERT INTO `items` (`id`, `short`, `name`, `category`, `price`, `description`, `image`, `created_at`, `updated_at`, `active`) VALUES
(1, 'python_1', 'Изучаем программирование на Python', 1, 1056, 'Надоело продираться через дебри малопонятных самоучителей по программированию? С этой книгой вы без труда усвоите азы Python и научитесь работать со структурами и функциями. В ходе обучения вы создадите свое собственное веб-приложение и узнаете, как управлять базами данных, обрабатывать исключения, пользоваться контекстными менеджерами, декораторами и генераторами. Все это и многое другое - во втором издании \"Изучаем программирование на Python\".', 'https://i.ibb.co/C2zCX4K/1019869028.jpg', '2019-10-13 15:12:39', '2019-10-13 15:12:39', 1),
(2, 'python_2', 'Изучаем Python. Том 1', 1, 2125, 'С помощью этой практической книги вы получите всестороннее и глубокое введение в основы языка Python. Будучи основанным на популярном учебном курсе Марка Лутца, обновленное 5-е издание книги поможет вам быстро научиться писать эффективный высококачественный код на Python. Она является идеальным способом начать изучение Python, будь вы новичок в программировании или профессиональный разработчик программного обеспечения на других языках.Это простое и понятное учебное пособие, укомплектованное контрольными вопросами, упражнениями и полезными иллюстрациями, позволит вам освоить основы линеек Python 3.X и 2.X. Вы также ознакомитесь с расширенными возможностями языка, получившими широкое распространение в коде Python.Благодаря книге вы:\" Исследуете основные встроенные типы объектов Python, такие как числа, списки и словари\" Научитесь создавать и обрабатывать объекты с помощью операторов Python и освоите общую синтаксическую модель Python\" Сможете применять функции для устранения избыточности кода и упаковки кода с целью многократного использования\" Узнаете, как организовывать операторы, функции и прочие инструменты в более крупные компоненты посредством модулей\" Погрузитесь глубже в классы - инструмент объектно-ориентированного программирования Python для структурирования кода\" Научитесь писать крупные программы с применением модели обработки исключений и инструментов разработки Python\" Освоите более сложные инструменты Python, включая декораторы, дескрипторы, метаклассы и обработку Unicode\"Книга Learning Python находится в начале моего списка рекомендованной литературы для любого, кто желает научиться программировать на Python.\"Дуг Хеллманнстарший инженер-программист,Racemi, Inc.Марк Лутц является мировым лидером в обучении языку Python, автором самых ранних и ставших бестселлерами книг по Python, а также первопроходцем в сообществе Python, начиная с 1992 года. Обладая более чем 30-летним опытом разработки, Марк был автором книг Programming Python, 4th Edition и Python Pocket Reference, 4th Edition издательства O\'Reilly.', 'https://i.ibb.co/s5hwkcV/1037901189.jpg', '2019-10-13 15:13:16', '2019-10-13 15:13:16', 1),
(3, 'python_3', 'Изучаем Python. Программирование игр, визуализация данных, веб-приложения', 1, 1106, 'Книга \"Изучаем Python\" - это ускоренный курс, который позволит вам сэкономить время и сразу начать писать работоспособные программы (игры, визуализации данных, веб-приложения и многое другое).\r\nХотите стать программистом? В первой части книги вам предстоит узнать о базовых принципах программирования, познакомиться со списками, словарями, классами и циклами, вы научитесь создавать программы и тестировать код. Во второй части книги вы начнете использовать знания на практике, работая над тремя крупными проектами: создадите собственную \"стрелялку\" с нарастающей сложностью уровней, займетесь работой с большими наборами данных и освоите их визуализацию, и, наконец, создадите полноценное веб-приложение на базе Django, гарантирующее конфиденциальность пользовательской информации.\r\nЕсли вы решились разобраться в том что такое программирование, не нужно ждать. Ключ на старт и вперед!', 'https://i.ibb.co/FBVYkv2/1037901646.jpg', '2019-10-13 15:13:47', '2019-10-13 15:13:47', 1);

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
  `active` int(1) NOT NULL DEFAULT 1 COMMENT 'Статус'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
