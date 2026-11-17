-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2026 at 05:58 PM
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
-- Database: `ulasanweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `balasan`
--

CREATE TABLE `balasan` (
  `BalasanID` int(11) NOT NULL,
  `UlasanID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ParentID` int(11) DEFAULT NULL,
  `Isi` text NOT NULL,
  `Dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `balasan`
--

INSERT INTO `balasan` (`BalasanID`, `UlasanID`, `UserID`, `ParentID`, `Isi`, `Dibuat`) VALUES
(3, 33, 20, NULL, 'Menurut gw lu terlalu muji deh, bagian tengahnya biasa aja wkwk', '2026-11-17 15:10:45'),
(4, 33, 19, 3, 'Lah selera orang beda bang, gw enjoy masa harus izin lu dulu 😭', '2026-11-17 15:10:45'),
(5, 33, 22, 4, 'WKWK udah jangan ribut, yang satu cari cerita yang satu cari gebetan. Beda tujuan nontonnya.', '2026-11-17 15:10:45'),
(6, 34, 20, NULL, 'Menurut gw lu terlalu muji deh, bagian tengahnya biasa aja wkwk', '2026-11-17 15:10:45'),
(7, 34, 19, 6, 'Lah selera orang beda bang, gw enjoy masa harus izin lu dulu 😭', '2026-11-17 15:10:45'),
(8, 34, 22, 7, 'WKWK udah jangan ribut, yang satu cari cerita yang satu cari gebetan. Beda tujuan nontonnya.', '2026-11-17 15:10:45'),
(9, 35, 20, NULL, 'Ngecrush boleh tapi kalau udah serem gw duluan kabur ya 😭', '2026-11-17 15:10:45'),
(10, 35, 19, 9, 'Lah selera orang beda bang, gw enjoy masa harus izin lu dulu 😭', '2026-11-17 15:10:45'),
(11, 35, 22, 10, 'WKWK udah jangan ribut, yang satu cari cerita yang satu cari gebetan. Beda tujuan nontonnya.', '2026-11-17 15:10:45'),
(12, 36, 20, NULL, 'Menurut gw lu terlalu muji deh, bagian tengahnya biasa aja wkwk', '2026-11-17 15:10:45'),
(13, 36, 19, 12, 'Lah selera orang beda bang, gw enjoy masa harus izin lu dulu 😭', '2026-11-17 15:10:45'),
(14, 36, 22, 13, 'WKWK udah jangan ribut, yang satu cari cerita yang satu cari gebetan. Beda tujuan nontonnya.', '2026-11-17 15:10:45'),
(15, 37, 20, NULL, 'Menurut gw lu terlalu muji deh, bagian tengahnya biasa aja wkwk', '2026-11-17 15:10:45'),
(16, 37, 19, 15, 'Lah selera orang beda bang, gw enjoy masa harus izin lu dulu 😭', '2026-11-17 15:10:45'),
(17, 37, 22, 16, 'WKWK udah jangan ribut, yang satu cari cerita yang satu cari gebetan. Beda tujuan nontonnya.', '2026-11-17 15:10:45'),
(18, 42, 21, NULL, 'Menurut gw lu terlalu muji deh, bagian tengahnya biasa aja wkwk', '2026-11-17 15:10:45'),
(19, 42, 20, 18, 'Lah selera orang beda bang, gw enjoy masa harus izin lu dulu 😭', '2026-11-17 15:10:45'),
(20, 42, 23, 19, 'WKWK udah jangan ribut, yang satu cari cerita yang satu cari gebetan. Beda tujuan nontonnya.', '2026-11-17 15:10:45'),
(21, 47, 22, NULL, 'Menurut gw lu terlalu muji deh, bagian tengahnya biasa aja wkwk', '2026-11-17 15:10:45'),
(22, 47, 21, 21, 'Lah selera orang beda bang, gw enjoy masa harus izin lu dulu 😭', '2026-11-17 15:10:45'),
(23, 47, 24, 22, 'WKWK udah jangan ribut, yang satu cari cerita yang satu cari gebetan. Beda tujuan nontonnya.', '2026-11-17 15:10:45'),
(24, 52, 23, NULL, 'Menurut gw lu terlalu muji deh, bagian tengahnya biasa aja wkwk', '2026-11-17 15:10:45'),
(25, 52, 22, 24, 'Lah selera orang beda bang, gw enjoy masa harus izin lu dulu 😭', '2026-11-17 15:10:45'),
(26, 52, 25, 25, 'WKWK udah jangan ribut, yang satu cari cerita yang satu cari gebetan. Beda tujuan nontonnya.', '2026-11-17 15:10:45'),
(27, 57, 24, NULL, 'Menurut gw lu terlalu muji deh, bagian tengahnya biasa aja wkwk', '2026-11-17 15:10:45'),
(28, 57, 23, 27, 'Lah selera orang beda bang, gw enjoy masa harus izin lu dulu 😭', '2026-11-17 15:10:45'),
(29, 57, 26, 28, 'WKWK udah jangan ribut, yang satu cari cerita yang satu cari gebetan. Beda tujuan nontonnya.', '2026-11-17 15:10:45');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `UlasanID` int(11) NOT NULL,
  `Ulasan_UserID` int(11) NOT NULL,
  `Movie_Api_ID` varchar(50) NOT NULL,
  `Rating` tinyint(4) NOT NULL,
  `UlasanUsers` text NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `ulasan`
--

INSERT INTO `ulasan` (`UlasanID`, `Ulasan_UserID`, `Movie_Api_ID`, `Rating`, `UlasanUsers`, `create_at`) VALUES
(33, 19, '1028506', 2, 'The Sound of 007 bikin gw baru sadar musik Bond sepenting itu', '2026-11-17 15:06:38'),
(34, 19, '1263337', 3, 'Heart of the Beast bikin gw mikir kalau nyasar di alam gw kuat berapa jam ya', '2026-11-17 12:06:38'),
(35, 19, '1339713', 4, 'Gila ni film Obsession jujur gw ngecrush ama Nikki tapi serem tapi suka huaaa', '2026-11-17 09:06:38'),
(36, 19, '1368337', 5, 'Odysseus cuma pengen pulang tapi rintangannya ga kelar-kelar anjir', '2026-11-17 06:06:38'),
(37, 19, '1400940', 2, 'Clayface seremnya bukan cuma bentuknya, identitasnya itu loh bikin ga nyaman', '2026-11-17 03:06:38'),
(38, 20, '1263337', 3, 'Gw suka tema bertahan hidup, tiap keputusan kecil kerasa penting', '2026-11-17 00:06:38'),
(39, 20, '1339713', 4, 'Niatnya pengen dicintai malah bikin masalah, makanya jangan asal minta wkwk', '2026-11-16 21:06:38'),
(40, 20, '1368337', 5, 'The Odyssey bikin perjalanan mudik gw keliatan gampang banget', '2026-11-16 18:06:38'),
(41, 20, '1400940', 2, 'Gw liat konsep badan berubah gitu langsung merinding sendiri', '2026-11-16 15:06:38'),
(42, 20, '299534', 3, 'Pas Avengers ngumpul gw merinding parah, berasa ikut maju lawan Thanos', '2026-11-16 12:06:38'),
(43, 21, '1339713', 4, 'Nikki bikin gw bingung mau salting apa takut, dua-duanya jalan bareng 😭', '2026-11-16 09:06:38'),
(44, 21, '1368337', 5, 'Gw suka nuansa mitologinya, berasa denger dongeng gede versi serius', '2026-11-16 06:06:38'),
(45, 21, '1400940', 2, 'Clayface tuh bikin gw mikir muka sendiri aja kadang ga kenal wkwk', '2026-11-16 03:06:38'),
(46, 21, '299534', 3, 'Tony Stark lu bikin gw nangis anjir, padahal udah sok kuat dari awal', '2026-11-16 00:06:38'),
(47, 21, '372058', 4, 'Kimi no Na wa bikin gw pengen teriak NAMA LU SIAPA juga 😭', '2026-11-15 21:06:38'),
(48, 22, '1368337', 5, 'Cyclops bikin gw mikir mending puter balik aja bang 😭', '2026-11-15 18:06:38'),
(49, 22, '1400940', 2, 'Dateng karena penasaran karakter DC, malah kebawa suasana suramnya', '2026-11-15 15:06:38'),
(50, 22, '299534', 3, 'Endgame rame sih tapi bagian perjalanan waktunya bikin gw mikir keras wkwk', '2026-11-15 12:06:38'),
(51, 22, '372058', 4, 'Visual langitnya cakep banget, wallpaper hidup anjir', '2026-11-15 09:06:38'),
(52, 22, '687163', 5, 'Rocky lucu bgt anjir, gw malah pengen punya temen kayak dia', '2026-11-15 06:06:38'),
(53, 23, '1400940', 2, 'Gw suka horor yang mainin identitas begini, bukan cuma bikin kaget', '2026-11-15 03:06:38'),
(54, 23, '299534', 3, 'Captain America pegang palu itu kepuasan batin banget asli', '2026-11-15 00:06:38'),
(55, 23, '372058', 4, 'Taki sama Mitsuha bikin gw senyum terus tiba-tiba nyesek', '2026-11-14 21:06:38'),
(56, 23, '687163', 5, 'Grace sama Rocky tuh duo yang bikin gw betah ngikutin ceritanya', '2026-11-14 18:06:38'),
(57, 23, '969681', 2, 'Peter Parker ini hidupnya kapan santai sih, sini gw beliin seblak dulu', '2026-11-14 15:06:38'),
(58, 24, '299534', 3, 'Gw masih kepikiran Natasha, sakit banget bagian itu 😭', '2026-11-14 12:06:38'),
(59, 24, '372058', 4, 'Gw masuk cuma mau liat anime bagus, keluar malah mikirin takdir', '2026-11-14 09:06:38'),
(60, 24, '687163', 5, 'Project Hail Mary bikin gw merasa pinter lima menit terus bingung lagi wkwk', '2026-11-14 06:06:38'),
(61, 24, '969681', 2, 'Brand New Day bikin gw balik pengen jadi Spider-Man, besok inget takut tinggi', '2026-11-14 03:06:38'),
(62, 24, '1028506', 3, 'Gw kira dokumenter musik bakal bikin ngantuk, ternyata asik juga', '2026-11-14 00:06:38'),
(63, 25, '372058', 4, 'Lagunya masuk langsung merinding, ga kuat gw', '2026-11-13 21:06:38'),
(64, 25, '687163', 5, 'Dateng buat luar angkasa, pulang sayang Rocky 😭', '2026-11-13 18:06:38'),
(65, 25, '969681', 2, 'Gw suka Spider-Man pas ngurus masalah jalanan New York, kerasa deket aja', '2026-11-13 15:06:38'),
(66, 25, '1028506', 3, 'Begitu nuansa musik Bond masuk langsung kebayang jas sama mobilnya wkwk', '2026-11-13 12:06:38'),
(67, 25, '1263337', 4, 'Nonton beginian sambil rebahan aman tuh rasanya bersyukur banget wkwk', '2026-11-13 09:06:38'),
(68, 26, '687163', 5, 'Bagian sainsnya kadang berat buat gw tapi hubungan karakternya ngena', '2026-11-13 06:06:38'),
(69, 26, '969681', 2, 'Peter lu kebanyakan mikir sendiri, curhat kek sekali-sekali 😭', '2026-11-13 03:06:38'),
(70, 26, '1028506', 3, 'Suka liat proses kreatif di balik musik film, ga cuma hasil jadinya', '2026-11-13 00:06:38'),
(71, 26, '1263337', 4, 'Suasana alamnya bikin penasaran sekaligus pengen cepet pulang', '2026-11-12 21:06:38'),
(72, 26, '1339713', 5, 'Gw doang apa konsep permintaannya lebih serem daripada penampakannya?', '2026-11-12 18:06:38'),
(73, 27, '969681', 2, 'Jaringnya ke mana-mana, masalah hidup gw juga sama sih wkwk', '2026-11-12 15:06:38'),
(74, 27, '1028506', 3, 'Kalau ga suka Bond mungkin biasa aja, tapi gw enjoy bahas musiknya', '2026-11-12 12:06:38'),
(75, 27, '1263337', 4, 'Jujur gw lebih tertarik sisi manusianya daripada sekadar situasi bahayanya', '2026-11-12 09:06:38'),
(76, 27, '1339713', 5, 'Obsession tuh definisi hati bilang gas otak bilang KABUR', '2026-11-12 06:06:38'),
(77, 27, '1368337', 2, 'Perjalanan pulangnya menarik tapi gw lebih penasaran sama yang nunggu di rumah', '2026-11-12 03:06:38'),
(78, 28, '1028506', 3, 'Abis ini pengen dengerin lagu-lagu Bond lagi dari awal.', '2026-11-12 00:06:38'),
(79, 28, '1263337', 4, 'Film bertema survival tuh bikin gw ngomel sendiri: jangan ke situ bang 😭', '2026-11-11 21:06:38'),
(80, 28, '1339713', 5, 'Menurut gw bagian romantisnya malah bikin horornya makin ga nyaman.', '2026-11-11 18:06:38'),
(81, 28, '1368337', 2, 'Buat gw bagian paling ngena tuh keinginan pulangnya, sederhana tapi berat.', '2026-11-11 15:06:38'),
(82, 28, '1400940', 3, 'Jujur konsepnya lebih nempel di kepala gw daripada horor biasa.', '2026-11-11 12:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UsersID` int(11) NOT NULL,
  `Name` varchar(16) NOT NULL,
  `Username` varchar(16) NOT NULL,
  `Password` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UsersID`, `Name`, `Username`, `Password`) VALUES
(1, 'Budi', 'admin', '$2y$10$dGiKKss7E'),
(2, 'asdasd', 'adminasd', 'asdasdasd'),
(3, 'AcidNiga', 'AcidGanteng', '12345678'),
(9, 'Acid gacor', 'acid', '$2y$10$eW5Qiy5wQ'),
(14, 'mrwent', 'rodriwent', '$2y$10$EWHaL00Cl'),
(17, 'ghalyniga', 'ghaly', 'prabowo'),
(18, 'azra', 'nigar', 'PqZ563MbHFMFGvc'),
(19, 'Prabowo', 'sim3d90aa4308', '$2y$10$OYmjXwB6i'),
(20, 'Teddy', 'sim8aca5d3844', '$2y$10$Mdlmtrbqf'),
(21, 'Hanip', 'simbe61fca354', '$2y$10$c0.o59FoB'),
(22, 'Grace', 'simcf05a29a2a', '$2y$10$nWDFQWB24'),
(23, 'Acid', 'sim3d6431a827', '$2y$10$vAD6XQxDY'),
(24, 'Azra', 'sim0443271308', '$2y$10$6.MG3roWw'),
(25, 'Naya', 'sim9ad6c044ea', '$2y$10$YMh8nOYCf'),
(26, 'Dimas', 'simc9926ab35b', '$2y$10$RHYrfp3Sv'),
(27, 'Raka', 'sim1a69ce12ca', '$2y$10$uZrCev1kL'),
(28, 'Putri', 'sime2906c2438', '$2y$10$x01WW8u4U'),
(29, 'steve', 'steve', '$2y$10$Vr0PRbpk3'),
(30, 'menkrep', 'menkrep', '$2y$10$ritguwNJD'),
(31, 'azra1', 'Azra1', '$2y$10$RUs.NJrDm'),
(32, 'Grace11', 'ghaly1', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balasan`
--
ALTER TABLE `balasan`
  ADD PRIMARY KEY (`BalasanID`),
  ADD KEY `UlasanID` (`UlasanID`),
  ADD KEY `ParentID` (`ParentID`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`UlasanID`),
  ADD UNIQUE KEY `uq_user_movie` (`Ulasan_UserID`,`Movie_Api_ID`),
  ADD KEY `idx_movie_api` (`Movie_Api_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UsersID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balasan`
--
ALTER TABLE `balasan`
  MODIFY `BalasanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `UlasanID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UsersID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `fk_ulasan_user` FOREIGN KEY (`Ulasan_UserID`) REFERENCES `users` (`UsersID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
