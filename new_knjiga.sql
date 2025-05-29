-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 29. maj 2025 ob 20.05
-- Različica strežnika: 10.4.32-MariaDB
-- Različica PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Zbirka podatkov: `new_knjiga`
--

-- --------------------------------------------------------

--
-- Struktura tabele `authors`
--

CREATE TABLE `authors` (
  `authors_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `authors`
--

INSERT INTO `authors` (`authors_id`, `first_name`, `last_name`) VALUES
(1, 'George', 'Orwell'),
(2, 'Jane', 'Austen'),
(3, 'J.K.', 'Rowling'),
(4, 'Haruki', 'Murakami'),
(5, 'Gabriel', 'García Márquez');

-- --------------------------------------------------------

--
-- Struktura tabele `books`
--

CREATE TABLE `books` (
  `books_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `language` varchar(50) DEFAULT NULL,
  `publication_year` int(11) DEFAULT NULL,
  `edition_number` varchar(20) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `authors_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `books`
--

INSERT INTO `books` (`books_id`, `title`, `language`, `publication_year`, `edition_number`, `added_by`, `genre_id`, `authors_id`) VALUES
(1, '1984', 'Slovenščina', 1949, '1. izdaja', 2, 1, 1),
(2, 'Prevzetnost in pristranost', 'Slovenščina', 1813, '3. izdaja', 2, 2, 2),
(3, 'Harry Potter in kamen modrosti', 'Slovenščina', 1997, '1. izdaja', 2, 3, 3),
(4, 'Norveški gozd', 'Slovenščina', 1987, '2. izdaja', 2, 5, 4),
(5, 'Sto let samote', 'Slovenščina', 1967, '1. izdaja', 2, 4, 5),
(6, 'yep', 'p', 1454, '3', 4, 1, 2);

-- --------------------------------------------------------

--
-- Struktura tabele `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `genres`
--

INSERT INTO `genres` (`genre_id`, `name`, `description`) VALUES
(1, 'Dystopian', 'Fictional society that is undesirable or frightening.'),
(2, 'Romance', 'Stories focused on romantic relationships.'),
(3, 'Fantasy', 'Fiction with magical or supernatural elements.'),
(4, 'Magical Realism', 'Blends magical elements with the real world.'),
(5, 'Literary Fiction', 'Serious, character-driven narratives.');

-- --------------------------------------------------------

--
-- Struktura tabele `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Odloži podatke za tabelo `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `first_name`, `last_name`, `is_admin`) VALUES
(4, 'a', '$2y$10$zRMm4SIPAlpwF2WiVD3M6uc7BVsg3UhieA/1.Kz9zbqBLpFvrvoau', 'mionvid@gmail.com', 'michle', 'brown', NULL),
(5, 'admin', '$2y$10$ADXI0evDHLXs7IKCliuAOOg4Wqg3o6PCuHttNpuz2cG9ET9x62RIi', 'admin@gmail.com', 'admin', '1', 1);

-- --------------------------------------------------------

--
-- Struktura tabele `users_books_status`
--

CREATE TABLE `users_books_status` (
  `status_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `books_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksi zavrženih tabel
--

--
-- Indeksi tabele `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`authors_id`);

--
-- Indeksi tabele `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`books_id`),
  ADD KEY `IX_Relationship3` (`genre_id`),
  ADD KEY `IX_Relationship4` (`authors_id`);

--
-- Indeksi tabele `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

--
-- Indeksi tabele `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeksi tabele `users_books_status`
--
ALTER TABLE `users_books_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `IX_Relationship1` (`user_id`),
  ADD KEY `IX_Relationship2` (`books_id`);

--
-- AUTO_INCREMENT zavrženih tabel
--

--
-- AUTO_INCREMENT tabele `authors`
--
ALTER TABLE `authors`
  MODIFY `authors_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT tabele `books`
--
ALTER TABLE `books`
  MODIFY `books_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT tabele `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT tabele `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT tabele `users_books_status`
--
ALTER TABLE `users_books_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Omejitve tabel za povzetek stanja
--

--
-- Omejitve za tabelo `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `Relationship3` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`),
  ADD CONSTRAINT `Relationship4` FOREIGN KEY (`authors_id`) REFERENCES `authors` (`authors_id`);

--
-- Omejitve za tabelo `users_books_status`
--
ALTER TABLE `users_books_status`
  ADD CONSTRAINT `Relationship1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `Relationship2` FOREIGN KEY (`books_id`) REFERENCES `books` (`books_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
