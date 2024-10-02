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


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

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
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `available` tinyint(1) NOT NULL,
  `numberOfBook` int NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `img`, `description`, `createdAt`, `updatedAt`, `available`, `numberOfBook`, `user_id`) VALUES
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `profilePicture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `role` enum('user','admin','moderator') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `profilePicture`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'johndoe', 'john.doe@example.com', '482c811da5d5b4bc6d497ffa98491e38', '/assets/img/users/johndoe.jpg', 'user', 1, '2024-08-13 16:01:14', '2024-08-21 11:16:00'),
(2, 'janedoe', 'jane.doe@example.com', '96b33694c4bb7dbd07391e0be54745fb', '/assets/img/users/janedoe.png', 'admin', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(3, 'marksmith', 'mark.smith@example.com', '7d347cf0ee68174a3588f6cba31b8a67', '/assets/img/users/marksmith.png', 'moderator', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(4, 'emilyjones', 'emily.jones@example.com', '34819d7beeabb9260a5c854bc85b3e44', '/assets/img/users/emilyjones.png', 'user', 1, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(5, 'davidsmith', 'david.smith@example.com', 'b0439fae31f8cbba6294af86234d5a28', '/assets/img/users/davidsmith.png', 'user', 0, '2024-08-13 16:01:14', '2024-08-20 14:30:00'),
(6, 'Emilie', 'Emilie@free.fr', '$2y$10$F7Hpb2Jvhuey4waGsAgUbeoSY3TXTO.Pne2RP9oovkAQwMbJqPYbO', '/assets/img/users/david-lezcano-1xzSAlZUVSc-unsplash 1.png', 'user', 1, '2024-08-23 11:54:56', '2024-09-09 12:43:33'),
(7, 'anocr', '123123@free.fr', '$2y$10$jnamIM7rji2QyBxPywFr.e/vQ74JLVol5xrCDoV1kvID83B/r4abK', '', 'user', 1, '2024-09-16 17:27:12', '2024-09-16 17:27:12'),
(8, 'anocr2', '123@free.fr', '$2y$10$uddN1ejXWzmrNdCTFIbAuuL4G2y36MhP6x7i6enu0PzJStionFG7m', '', 'user', 1, '2024-09-16 17:28:10', '2024-09-16 17:28:10'),
(9, 'anocr4', '4567@free.fr', '$2y$10$.8siHQ.qUGURJuhZFwaVkOM/uY2JelojYqtsau7iSgFEcU4u83n9q', '', 'user', 1, '2024-09-16 17:33:05', '2024-09-16 17:33:05'),
(10, 'nnnn', '789@free.fr', '$2y$10$9VqYQBBXDTujbMH0LnCFAO31y8IqcXGibEZXnzncrBo4zC5AHotTW', '', 'user', 1, '2024-09-16 17:36:29', '2024-09-16 17:36:29'),
(11, 'zzznnn', '123456@gmail.fr', '$2y$10$zaVJ5kfUnrkaG256oN7NX.BpQd/epZl7z0S7qz2Ce9CtVWuAc6z3y', '/assets/img/users/profile_66f584de281ad1.48103351.jpeg', 'user', 1, '2024-09-16 17:47:35', '2024-09-26 17:59:26'),
(12, 'jjjjj', '987@free.fr', '$2y$10$j7gUpv0LEhHgGpGiMVPP1.8PEko6SCILCPF0sloThltHeTncUZZhG', '/assets/img/users/johndoe.jpg', 'user', 1, '2024-09-16 17:51:40', '2024-09-16 18:10:30'),
(14, 'matggg', 'mat123@free.fr', '$2y$10$3nFVh7dkNx5D8JePXshGsuM0TZg3JFcwQAQNvnyzdRrGFF4c2ZlOK', '', 'user', 1, '2024-09-16 18:38:22', '2024-09-16 18:38:22'),
(15, 'matggg', 'mat123@free.fr', '$2y$10$3nFVh7dkNx5D8JePXshGsuM0TZg3JFcwQAQNvnyzdRrGFF4c2ZlOK', '', 'user', 1, '2024-09-16 18:38:22', '2024-09-16 18:38:22'),
(16, 'mattta', 'matt123@free.fr', '$2y$10$aZ0k/bQXdoz6nJs8y5Ie2urfCexWnWconWaNNoy5mCt8x8d/oVbTu', '', 'user', 1, '2024-09-16 18:51:16', '2024-09-16 18:51:16'),
(17, 'mattta', 'matt123@free.fr', '$2y$10$aZ0k/bQXdoz6nJs8y5Ie2urfCexWnWconWaNNoy5mCt8x8d/oVbTu', '', 'user', 1, '2024-09-16 18:51:16', '2024-09-16 18:51:16');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
