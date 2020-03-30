-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 27. dub 2015, 12:19
-- Verze serveru: 5.6.17
-- Verze PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `crm`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `crm_client`
--

CREATE TABLE IF NOT EXISTS `crm_client` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Vypisuji data pro tabulku `crm_client`
--

INSERT INTO `crm_client` (`id`, `user_id`, `title`, `note`, `date_add`) VALUES
(1, 1, 'Penziony Mik', '3 wochesee', '2015-03-30 12:28:04'),
(2, 1, 'Vodari', '2 tydny9', '2015-03-30 12:28:50'),
(3, 2, 'Prodejce nabytku', '5 tydnu', '2015-03-30 12:38:48'),
(4, 2, 'Bazar ps3', 'masaricka', '2015-03-31 08:58:24'),
(7, 1, 'Archat', 'stavari', '2015-04-10 14:53:09');

-- --------------------------------------------------------

--
-- Struktura tabulky `crm_project`
--

CREATE TABLE IF NOT EXISTS `crm_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `sum_price` decimal(65,0) NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Vypisuji data pro tabulku `crm_project`
--

INSERT INTO `crm_project` (`id`, `client_id`, `title`, `note`, `sum_price`, `date_add`) VALUES
(1, 3, '6 stolu', 'dubových', '0', '2015-03-30 13:59:23'),
(2, 3, '4 zidle', 'buk', '0', '2015-03-30 14:07:53'),
(4, 3, '10 skriní', '1 tyden', '0', '2015-03-31 08:57:53'),
(5, 1, 'Objednat destinky', 'podtácky', '0', '2015-03-31 09:34:42'),
(6, 2, '10 trubek', 'plast', '0', '2015-03-31 09:48:08'),
(7, 7, 'Zámek', 'Losiny', '0', '2015-04-15 13:01:15');

-- --------------------------------------------------------

--
-- Struktura tabulky `crm_task`
--

CREATE TABLE IF NOT EXISTS `crm_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(65,0) NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=67 ;

--
-- Vypisuji data pro tabulku `crm_task`
--

INSERT INTO `crm_task` (`id`, `project_id`, `title`, `status`, `price`, `date_add`) VALUES
(1, 6, 'vyrobit trubky', 'z pvc', '0', '2015-03-31 14:46:22'),
(2, 5, 'Primontoval latku k destníku', 'fasrt', '6', '2015-03-31 14:49:46'),
(3, 2, 'stlouct zidle', 'hrebikem', '0', '2015-04-01 08:09:14'),
(4, 1, 'slepit', 'stoly', '0', '2015-04-02 09:28:33'),
(5, 5, 'natrit', 'trubky', '5', '2015-04-08 14:26:37'),
(6, 7, 'Opravit omitku', 'černá barva', '0', '2015-04-15 13:02:53'),
(40, 7, 'Kotelna', '200', '2000', '2015-04-20 09:31:13'),
(62, 5, 'natrit zabradli', 'do tydne', '1500', '2015-04-23 12:03:07'),
(63, 5, 'obrobit pist', '1 den', '10000', '2015-04-23 12:05:20');

-- --------------------------------------------------------

--
-- Struktura tabulky `crm_task_comment`
--

CREATE TABLE IF NOT EXISTS `crm_task_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Vypisuji data pro tabulku `crm_task_comment`
--

INSERT INTO `crm_task_comment` (`id`, `task_id`, `text`, `date_add`) VALUES
(1, 1, 'Vyhrane lepidlem na stoly', '2015-04-02 11:16:13'),
(2, 1, 'jedn', '2015-04-02 11:28:38'),
(3, 1, 'jde vam to dobre', '2015-04-15 13:04:10'),
(4, 1, 'rychle delejte', '2015-04-15 13:05:25'),
(5, 1, 'zamek pek', '2015-04-15 16:50:24'),
(13, 40, 'koupit trubky', '2015-04-21 12:46:15'),
(14, 2, 'smazat hi', '2015-04-23 12:05:48'),
(15, 2, 'ss', '2015-04-23 12:05:54');

-- --------------------------------------------------------

--
-- Struktura tabulky `crm_user`
--

CREATE TABLE IF NOT EXISTS `crm_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Vypisuji data pro tabulku `crm_user`
--

INSERT INTO `crm_user` (`id`, `name`, `surname`, `login`, `password`, `role`, `date_add`) VALUES
(1, 'Lukas', 'Komprs', 'Lukmi', '$2y$10$eVKIp8FVaDzjUt8zWhFreeCmJBOHKGfy0tLvCQZw08Am6U.GfVrLG', '', '2015-03-30 12:26:59'),
(2, 'Alan', 'Wake', 'Alan', '$2y$10$8EztslpoQoIVLnlMiBYTue19AOoyLcWPgJnYjMq92XTLuJsWeDiVq', '', '2015-03-30 12:37:39'),
(3, 'John', 'Terry', 'John', '$2y$10$fQukMHKkC772T4esS4oxqe2JlyeH6RiCYa56Jqbzv3vUcQgzP5zP6', '', '2015-04-22 09:39:07');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `crm_client`
--
ALTER TABLE `crm_client`
  ADD CONSTRAINT `crm_client_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `crm_user` (`id`);

--
-- Omezení pro tabulku `crm_project`
--
ALTER TABLE `crm_project`
  ADD CONSTRAINT `crm_project_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `crm_client` (`id`);

--
-- Omezení pro tabulku `crm_task`
--
ALTER TABLE `crm_task`
  ADD CONSTRAINT `crm_task_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `crm_project` (`id`);

--
-- Omezení pro tabulku `crm_task_comment`
--
ALTER TABLE `crm_task_comment`
  ADD CONSTRAINT `crm_task_comment_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `crm_task` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
