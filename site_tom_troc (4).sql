-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 26 sep. 2024 à 17:10
-- Version du serveur : 8.3.0
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 
-- Base de données : `site_tom_troc`
--

-- --------------------------------------------------------

-- 
-- Structure de la table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `available` tinyint(1) NOT NULL,
  `number_of_book` int NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_user_book` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `img`, `description`, `created_at`, `updated_at`, `available`, `number_of_book`, `user_id`) VALUES
(1, 'Esther', 'Gabriel Garcia Marquez', '/assets/img/books/Esther.png', 'A story that chronicles several generations of the Buendía family.', '2024-08-05 10:00:00', NULL, 0, 0, 1),
(2, 'The Kinfolk Table', 'Jane Austen', '/assets/img/books/The Kinfolk Table.png', 'A romantic novel that charts the emotional development of the protagonist Elizabeth Bennet.', '2024-08-05 10:00:00', NULL, 1, 0, 2),
(3, 'Wabi Sabi', 'Mark Twain', '/assets/img/books/Wabi Sabi.png', 'A novel about a young boy who travels down the Mississippi River on a raft with a runaway slave.', '2024-08-05 10:00:00', NULL, 1, 0, 3),
(4, 'Milk & honey', 'Haruki Murakami', '/assets/img/books/Milk & honey.png', 'A metaphysical novel that blends reality and fantasy, featuring two distinct, yet interrelated plots.', '2024-08-05 10:00:00', NULL, 1, 0, 4),
(5, 'Delight!', 'Chimamanda Ngozi Adichie', '/assets/img/books/Delight!.png', 'A story about a young Nigerian woman who moves to the United States for university.', '2024-08-05 10:00:00', NULL, 1, 0, 11);

-- --------------------------------------------------------

-- 
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_sender_message` FOREIGN KEY (`sender_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_receiver_message` FOREIGN KEY (`receiver_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

-- 
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `profile_picture`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'johndoe', 'john.doe@example.com', '482c811da5d5b4bc6d497ffa98491e38', '/assets/img/users/johndoe.jpg', 'user', 1, '2024-08-13 16:01:14', '2024-08-21 11:16:00'),
(2, 'janedoe', 'jane.doe@example.com', '96b33694c4bb7dbd07391e0be54745fb', '/assets/img/users/janedoe.png', 'admin', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(3, 'marksmith', 'mark.smith@example.com', '7d347cf0ee68174a3588f6cba31b8a67', '/assets/img/users/marksmith.png', 'user', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(4, 'emilyjones', 'emily.jones@example.com', '34819d7beeabb9260a5c854bc85b3e44', '/assets/img/users/emilyjones.png', 'user', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(5, 'davidsmith', 'david.smith@example.com', 'b0439fae31f8cbba6294af86234d5a28', '/assets/img/users/davidsmith.png', 'user', 0, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(6, 'Emilie', 'Emilie@free.fr', '$2y$10$F7Hpb2Jvhuey4waGsAgUbeoSY3TXTO.Pne2RP9oovkAQwMbJqPYbO', '/assets/img/users/david-lezcano-1xzSAlZUVSc-unsplash 1.png', 'user', 1, '2024-08-23 11:54:56', '2024-09-09 12:43:33'),
(7, 'anocr', '123123@free.fr', '$2y$10$jnamIM7rji2QyBxPywFr.e/vQ74JLVol5xrCDoV1kvID83B/r4abK', '', 'user', 1, '2024-09-16 17:27:12', '2024-09-16 17:27:12'),
(8, 'anocr2', '123@free.fr', '$2y$10$uddN1ejXWzmrNdCTFIbAuuL4G2y36MhP6x7i6enu0PzJStionFG7m', '', 'user', 1, '2024-09-16 17:28:10', '2024-09-16 17:28:10'),
(9, 'anocr4', '4567@free.fr', '$2y$10$.8siHQ.qUGURJuhZFwaVkOM/uY2JelojYqtsauuVnmksrBOVLwQDu', '', 'user', 1, '2024-09-17 13:18:12', '2024-09-17 13:18:12');

COMMIT;
