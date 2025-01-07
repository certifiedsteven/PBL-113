-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 02:38 PM
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
-- Database: `evote`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `katasandi` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nim` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `Email`, `katasandi`, `nama`, `nim`) VALUES
(1, 'hafizxd24@gmail.com', '47e77cc38d81bd94463daf1764a1f829', 'Hafiz', '4342401069'),
(2, 'steven.btm2016@gmail.com', 'a36574edb703c814e3ad78273983d3e2', 'steven', '4342401068');

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

CREATE TABLE `hasil` (
  `id_hasil` int(11) NOT NULL,
  `id_kandidat` int(11) NOT NULL,
  `id_pemilihan` int(11) NOT NULL,
  `jumlah_suara` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hasil`
--

INSERT INTO `hasil` (`id_hasil`, `id_kandidat`, `id_pemilihan`, `jumlah_suara`) VALUES
(3, 1, 1, 6),
(4, 2, 1, 5),
(5, 1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(10) NOT NULL,
  `nama_jurusan` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`) VALUES
(1, 'informatika'),
(2, 'elektronika');

-- --------------------------------------------------------

--
-- Table structure for table `kandidat`
--

CREATE TABLE `kandidat` (
  `id_kandidat` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `foto_kandidat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kandidat`
--

INSERT INTO `kandidat` (`id_kandidat`, `nama`, `foto_kandidat`) VALUES
(1, 'Supardianto', '113105_1733636881.jpg'),
(2, 'Muhammad Sahrul Nizan', 'nizan_1733636988.png'),
(4, 'Bagus Harimukti', 'Screenshot 2024-08-30 153503_1734497674.png'),
(5, 'nizam', 'nizan_1733636988_1734498024.png'),
(6, 'alena', 'AL_1733736005_1734498042.jpg'),
(7, 'Hafiz Atama Romadhoni', 'nizan_1733636988_1734498496.png');

-- --------------------------------------------------------

--
-- Table structure for table `mengambil_data`
--

CREATE TABLE `mengambil_data` (
  `id_akses` int(11) NOT NULL,
  `id_pemilihan` int(11) NOT NULL,
  `kandidat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mengambil_data`
--

INSERT INTO `mengambil_data` (`id_akses`, `id_pemilihan`, `kandidat_id`) VALUES
(5, 1, 1),
(6, 1, 2),
(8, 3, 1),
(9, 3, 2),
(10, 4, 1),
(17, 7, 1),
(18, 7, 4),
(19, 7, 6),
(20, 8, 1),
(21, 8, 2),
(22, 8, 4),
(23, 8, 5),
(24, 8, 6),
(25, 8, 7);

-- --------------------------------------------------------

--
-- Table structure for table `pemilih`
--

CREATE TABLE `pemilih` (
  `Id_pemilih` int(11) NOT NULL,
  `nim` varchar(12) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `katasandi` varchar(250) NOT NULL,
  `email` varchar(50) NOT NULL,
  `jurusan` int(10) NOT NULL,
  `prodi` int(11) DEFAULT NULL,
  `angkatan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemilih`
--

INSERT INTO `pemilih` (`Id_pemilih`, `nim`, `nama`, `katasandi`, `email`, `jurusan`, `prodi`, `angkatan`) VALUES
(1, '4342401062', 'Putri', '6357dd873f3189c0dfb5d9dc46d863b1', 'putri@olympvotes.web.id', 1, 1, 0),
(2, '4342401069', 'Hafiz Atama Romadhoni', '47e77cc38d81bd94463daf1764a1f829', 'hafizxd24@gmail.com', 1, 1, 0),
(3, '12345678', 'John Doe', '7c6a180b36896a0a8c02787eeafb0e4c', 'john@example.com', 1, 1, 0),
(4, '87654321', 'Jane Smith', '6cb75f652a9b52798eb6cf2201057c73', 'jane@example.com', 1, 1, 0),
(5, '11223344', 'Alice Brown', '819b0643d6b89dc9b579fdfc9094f28e', 'alice@example.com', 1, 1, 0),
(17, '44332211', 'Bob White', '34cc93ece0ba9e3f6f235d4af979b16c', 'bob@example.com', 1, 1, 0),
(19, '4342401089', 'Bagus Harimukti', '03cd2e926d41251797a35ba0512f2730', 'bagus@olympvote.web.id', 1, 1, 0),
(20, '4342401001', 'Rizka Fatikarani', 'e10adc3949ba59abbe56e057f20f883e', 'rizka fatikarani@gmail.com', 1, 1, 0),
(21, '4342401002', 'Agung Ramadhan', 'e10adc3949ba59abbe56e057f20f883e', 'agung ramadhan@gmail.com', 1, 1, 0),
(22, '4342401003', 'Dhominica Riskana Laurensa ', 'e10adc3949ba59abbe56e057f20f883e', 'dhominica riskana laurensa @gmail.com', 1, 1, 0),
(23, '4342401004', 'Keisha Asri Dellvia', 'e10adc3949ba59abbe56e057f20f883e', 'keisha asri dellvia@gmail.com', 1, 1, 0),
(24, '4342401005', 'Alendea Resta Amaira', 'e10adc3949ba59abbe56e057f20f883e', 'alendea resta amaira@gmail.com', 1, 1, 0),
(25, '4342401006', 'Rahayu Suci Ramadhani', 'e10adc3949ba59abbe56e057f20f883e', 'rahayu suci ramadhani@gmail.com', 1, 1, 0),
(26, '4342401007', 'Elisabet Simamora', 'e10adc3949ba59abbe56e057f20f883e', 'elisabet simamora@gmail.com', 1, 1, 0),
(27, '4342401008', 'Muhammad Farhan', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad farhan@gmail.com', 1, 1, 0),
(28, '4342401009', 'Shandy Sapta Wirayudha', 'e10adc3949ba59abbe56e057f20f883e', 'shandy sapta wirayudha@gmail.com', 1, 1, 0),
(30, '4342401011', 'Putri Balqis', 'e10adc3949ba59abbe56e057f20f883e', 'putri balqis@gmail.com', 1, 1, 0),
(31, '4342401012', 'Vioni Az-zahra', 'e10adc3949ba59abbe56e057f20f883e', 'vioni az-zahra@gmail.com', 1, 1, 0),
(33, '4342401014', 'Muhammad Syarif Quthub', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad syarif quthub@gmail.com', 1, 1, 0),
(34, '4342401015', 'Muhammad Syifa Abdurrasyid', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad syifa abdurrasyid@gmail.com', 1, 1, 0),
(35, '4342401016', 'Andri Yani Maurexa ', 'e10adc3949ba59abbe56e057f20f883e', 'andri yani maurexa @gmail.com', 1, 1, 0),
(36, '4342401017', 'Muna Sry Wulan Nasution', 'e10adc3949ba59abbe56e057f20f883e', 'muna sry wulan nasution@gmail.com', 1, 1, 0),
(37, '4342401018', 'Rofika Adi Amanda', 'e10adc3949ba59abbe56e057f20f883e', 'rofika adi amanda@gmail.com', 1, 1, 0),
(38, '4342401019', 'Berlian Puti Istana Jingga', 'e10adc3949ba59abbe56e057f20f883e', 'berlian puti istana jingga@gmail.com', 1, 1, 0),
(39, '4342401020', 'Gabriel Vivaldi Panjaitan', 'e10adc3949ba59abbe56e057f20f883e', 'gabriel vivaldi panjaitan@gmail.com', 1, 1, 0),
(40, '4342401021', 'Ahmad Hafizh Habibie Purba', 'e10adc3949ba59abbe56e057f20f883e', 'ahmad hafizh habibie purba@gmail.com', 1, 1, 0),
(41, '4342401022', 'Megahnanda Sucimuliani Pasolo', 'e10adc3949ba59abbe56e057f20f883e', 'megahnanda sucimuliani pasolo@gmail.com', 1, 1, 0),
(42, '4342401023', 'Miftahur Rahmah', 'e10adc3949ba59abbe56e057f20f883e', 'miftahur rahmah@gmail.com', 1, 1, 0),
(43, '4342401024', 'Raihan Putra Atmaja', 'e10adc3949ba59abbe56e057f20f883e', 'raihan putra atmaja@gmail.com', 1, 1, 0),
(44, '4342401025', 'Hanif Wicaksono Suryohusodo', 'e10adc3949ba59abbe56e057f20f883e', 'hanif wicaksono suryohusodo@gmail.com', 1, 1, 0),
(45, '4342401026', 'Rafi Ikhwansyah', 'e10adc3949ba59abbe56e057f20f883e', 'rafi ikhwansyah@gmail.com', 1, 1, 0),
(46, '4342401027', 'Ardiila Dwi Putri', 'e10adc3949ba59abbe56e057f20f883e', 'ardiila dwi putri@gmail.com', 1, 1, 0),
(47, '4342401028', 'Muhammad Haqqi Ghafur', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad haqqi ghafur@gmail.com', 1, 1, 0),
(49, '4342401030', 'Alya Putri Ramadhani', 'e10adc3949ba59abbe56e057f20f883e', 'alya putri ramadhani@gmail.com', 1, 1, 0),
(50, '4342401031', 'Muhammad Ariq Akbari Ashar', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad ariq akbari ashar@gmail.com', 1, 1, 0),
(51, '4342401032', 'Christian Jeremiatur Hamonangan S', 'e10adc3949ba59abbe56e057f20f883e', 'christian jeremiatur hamonangan s@gmail.com', 1, 1, 0),
(52, '4342401033', 'Adib Zahran', 'e10adc3949ba59abbe56e057f20f883e', 'adib zahran@gmail.com', 1, 1, 0),
(53, '4342401034', 'Wasyn Sulaiman Siregar', 'e10adc3949ba59abbe56e057f20f883e', 'wasyn sulaiman siregar@gmail.com', 1, 1, 0),
(54, '4342401035', 'Muhammad Hasan Firdaus', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad hasan firdaus@gmail.com', 1, 1, 0),
(55, '4342401036', 'Andri Putra Desyandra Siregar', 'e10adc3949ba59abbe56e057f20f883e', 'andri putra desyandra siregar@gmail.com', 1, 1, 0),
(56, '4342401037', 'Hasna Fadhilah Ramadhan', 'e10adc3949ba59abbe56e057f20f883e', 'hasna fadhilah ramadhan@gmail.com', 1, 1, 0),
(57, '4342401038', 'Veny Isnaini Prastia', 'e10adc3949ba59abbe56e057f20f883e', 'veny isnaini prastia@gmail.com', 1, 1, 0),
(58, '4342401039', 'Sheila Rifqi', 'e10adc3949ba59abbe56e057f20f883e', 'sheila rifqi@gmail.com', 1, 1, 0),
(59, '4342401040', 'Juan Immanuel Tinambunan', 'e10adc3949ba59abbe56e057f20f883e', 'juan immanuel tinambunan@gmail.com', 1, 1, 0),
(60, '4342401041', 'Dony Tata Fahreza', 'e10adc3949ba59abbe56e057f20f883e', 'dony tata fahreza@gmail.com', 1, 1, 0),
(61, '4342401042', 'Muhammad Aidil Jupriadi Saleh', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad aidil jupriadi saleh@gmail.com', 1, 1, 0),
(62, '4342401043', 'Rizky Julfiandi', 'e10adc3949ba59abbe56e057f20f883e', 'rizky julfiandi@gmail.com', 1, 1, 0),
(65, '4342401046', 'Rohani Jesicca Siringo Ringo', 'e10adc3949ba59abbe56e057f20f883e', 'rohani jesicca siringo ringo@gmail.com', 1, 1, 0),
(66, '4342401047', 'Indria Bintani Aiska', 'e10adc3949ba59abbe56e057f20f883e', 'indria bintani aiska@gmail.com', 1, 1, 0),
(67, '4342401048', 'Muhammad Jauharil Musthofa', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad jauharil musthofa@gmail.com', 1, 1, 0),
(68, '4342401049', 'Nanda Prasetyani', 'e10adc3949ba59abbe56e057f20f883e', 'nanda prasetyani@gmail.com', 1, 1, 0),
(69, '4342401050', 'Fito Desta Fabiansah', 'e10adc3949ba59abbe56e057f20f883e', 'fito desta fabiansah@gmail.com', 1, 1, 0),
(70, '4342401051', 'Rivana Alwarid', 'e10adc3949ba59abbe56e057f20f883e', 'rivana alwarid@gmail.com', 1, 1, 0),
(71, '4342401052', 'Dhiyaa Ahmad Azzhariif', 'e10adc3949ba59abbe56e057f20f883e', 'dhiyaa ahmad azzhariif@gmail.com', 1, 1, 0),
(72, '4342401053', 'Hasan Abdurrahman', 'e10adc3949ba59abbe56e057f20f883e', 'hasan abdurrahman@gmail.com', 1, 1, 0),
(73, '4342401054', 'Syifa Dwitya Wulandari', 'e10adc3949ba59abbe56e057f20f883e', 'syifa dwitya wulandari@gmail.com', 1, 1, 0),
(74, '4342401055', 'Naomi Anisa Yulia Siahaan', 'e10adc3949ba59abbe56e057f20f883e', 'naomi anisa yulia siahaan@gmail.com', 1, 1, 0),
(76, '4342401057', 'Muhammad Maulana Yusuf', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad maulana yusuf@gmail.com', 1, 1, 0),
(77, '4342401058', 'Dinny Mardin', 'e10adc3949ba59abbe56e057f20f883e', 'dinny mardin@gmail.com', 1, 1, 0),
(78, '4342401059', 'Alldreno Hosea Perangin Angin', 'e10adc3949ba59abbe56e057f20f883e', 'alldreno hosea perangin angin@gmail.com', 1, 1, 0),
(79, '4342401060', 'Eric Marchelino Hutabarat', 'e10adc3949ba59abbe56e057f20f883e', 'eric marchelino hutabarat@gmail.com', 1, 1, 0),
(80, '4342401061', 'Fajar Mirza Hanif', 'e10adc3949ba59abbe56e057f20f883e', 'fajar mirza hanif@gmail.com', 1, 1, 0),
(81, '4342401062', 'Putri', 'e10adc3949ba59abbe56e057f20f883e', 'putri@gmail.com', 1, 1, 0),
(82, '4342401063', 'Afifah Luthfi Fathonah', 'e10adc3949ba59abbe56e057f20f883e', 'afifah luthfi fathonah@gmail.com', 1, 1, 0),
(83, '4342401064', 'Navita Damayanti Syarif', 'e10adc3949ba59abbe56e057f20f883e', 'navita damayanti syarif@gmail.com', 1, 1, 0),
(84, '4342401065', 'Muhammad Fadhil Osman', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad fadhil osman@gmail.com', 1, 1, 0),
(85, '4342401066', 'Thalita Aurelia Marsim', 'e10adc3949ba59abbe56e057f20f883e', 'thalita aurelia marsim@gmail.com', 1, 1, 0),
(86, '4342401067', 'Muhammad Thariq Syafruddin', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad thariq syafruddin@gmail.com', 1, 1, 0),
(87, '4342401068', 'Steven Kumala', 'e10adc3949ba59abbe56e057f20f883e', 'steven kumala@gmail.com', 1, 1, 0),
(88, '4342401069', 'Hafiz Atama Romadhoni', 'e10adc3949ba59abbe56e057f20f883e', 'hafiz atama romadhoni@gmail.com', 1, 1, 0),
(89, '4342401070', 'Muhamad Ariffadhlullah', 'e10adc3949ba59abbe56e057f20f883e', 'muhamad ariffadhlullah@gmail.com', 1, 1, 0),
(90, '4342401071', 'Ibra Marioka', 'e10adc3949ba59abbe56e057f20f883e', 'ibra marioka@gmail.com', 1, 1, 0),
(91, '4342401072', 'Diva Satria', 'e10adc3949ba59abbe56e057f20f883e', 'diva satria@gmail.com', 1, 1, 0),
(92, '4342401073', 'Fahri Andrean Saputra', 'e10adc3949ba59abbe56e057f20f883e', 'fahri andrean saputra@gmail.com', 1, 1, 0),
(93, '4342401074', 'Surya Nur Aini', 'e10adc3949ba59abbe56e057f20f883e', 'surya nur aini@gmail.com', 1, 1, 0),
(94, '4342401075', 'Arshafin Alfisyahrin', 'e10adc3949ba59abbe56e057f20f883e', 'arshafin alfisyahrin@gmail.com', 1, 1, 0),
(95, '4342401076', 'Muhammad Addin', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad addin@gmail.com', 1, 1, 0),
(96, '4342401077', 'Jerimy Steven Robert Monangin', 'e10adc3949ba59abbe56e057f20f883e', 'jerimy steven robert monangin@gmail.com', 1, 1, 0),
(97, '4342401078', 'Muhammad Ali Asrory', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad ali asrory@gmail.com', 1, 1, 0),
(98, '4342401079', 'Muhammad Faldy Rizaldi', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad faldy rizaldi@gmail.com', 1, 1, 0),
(99, '4342401080', 'Adhyca Hafeez Wibowo', 'e10adc3949ba59abbe56e057f20f883e', 'adhyca hafeez wibowo@gmail.com', 1, 1, 0),
(100, '4342401081', 'Lathifah Nasywa Kesumaputri', 'e10adc3949ba59abbe56e057f20f883e', 'lathifah nasywa kesumaputri@gmail.com', 1, 1, 0),
(101, '4342401082', 'Agnes Natalia', 'e10adc3949ba59abbe56e057f20f883e', 'agnes natalia@gmail.com', 1, 1, 0),
(102, '4342401083', 'Nayla Nur Nabila', 'e10adc3949ba59abbe56e057f20f883e', 'nayla nur nabila@gmail.com', 1, 1, 0),
(103, '4342401084', 'Hermansa', 'e10adc3949ba59abbe56e057f20f883e', 'hermansa@gmail.com', 1, 1, 0),
(104, '4342401085', 'Berkat Tua Siallagan', 'e10adc3949ba59abbe56e057f20f883e', 'berkat tua siallagan@gmail.com', 1, 1, 0),
(105, '4342401086', 'Ananda Meliana Sembiring', 'e10adc3949ba59abbe56e057f20f883e', 'ananda meliana sembiring@gmail.com', 1, 1, 0),
(106, '4342401087', 'Suci Aqilah Nst', 'e10adc3949ba59abbe56e057f20f883e', 'suci aqilah nst@gmail.com', 1, 1, 0),
(107, '4342401088', 'Ray Refaldo', 'e10adc3949ba59abbe56e057f20f883e', 'ray refaldo@gmail.com', 1, 1, 0),
(108, '4342401089', 'Bagus Harimukti', 'e10adc3949ba59abbe56e057f20f883e', 'bagus harimukti@gmail.com', 1, 1, 0),
(109, '4342401090', 'Hady Wiranata', 'e10adc3949ba59abbe56e057f20f883e', 'hady wiranata@gmail.com', 1, 1, 0),
(111, '4342411002', 'Pandu Tenaya', 'e10adc3949ba59abbe56e057f20f883e', 'pandu tenaya@gmail.com', 1, 1, 0),
(112, '4342411003', 'Putri Salsa Nabila', 'e10adc3949ba59abbe56e057f20f883e', 'putri salsa nabila@gmail.com', 1, 1, 0),
(113, '4342411004', 'Lucky Abdillah', 'e10adc3949ba59abbe56e057f20f883e', 'lucky abdillah@gmail.com', 1, 1, 0),
(114, '4342411005', 'Keysya Arghinaya', 'e10adc3949ba59abbe56e057f20f883e', 'keysya arghinaya@gmail.com', 1, 1, 0),
(115, '4342411006', 'Kharisman Anggelo Lamaindi', 'e10adc3949ba59abbe56e057f20f883e', 'kharisman anggelo lamaindi@gmail.com', 1, 1, 0),
(116, '4342411007', 'Nabil Naufaldo', 'e10adc3949ba59abbe56e057f20f883e', 'nabil naufaldo@gmail.com', 1, 1, 0),
(117, '4342411008', 'Muhammad Abi Nubli Rosyadi', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad abi nubli rosyadi@gmail.com', 1, 1, 0),
(118, '4342411009', 'Habib Burrahman', 'e10adc3949ba59abbe56e057f20f883e', 'habib burrahman@gmail.com', 1, 1, 0),
(119, '4342411010', 'Novia Lasmauli Hutabarat', 'e10adc3949ba59abbe56e057f20f883e', 'novia lasmauli hutabarat@gmail.com', 1, 1, 0),
(120, '4342411011', 'Muhammad Rafi Rachim Ramadhan', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad rafi rachim ramadhan@gmail.com', 1, 1, 0),
(121, '4342411012', 'Kezya Himawari', 'e10adc3949ba59abbe56e057f20f883e', 'kezya himawari@gmail.com', 1, 1, 0),
(122, '4342411013', 'Albert Simangunsong', 'e10adc3949ba59abbe56e057f20f883e', 'albert simangunsong@gmail.com', 1, 1, 0),
(123, '4342411014', 'Sandrian Hafizhul Pratama', 'e10adc3949ba59abbe56e057f20f883e', 'sandrian hafizhul pratama@gmail.com', 1, 1, 0),
(124, '4342411015', 'Djibril Titian Zidanku', 'e10adc3949ba59abbe56e057f20f883e', 'djibril titian zidanku@gmail.com', 1, 1, 0),
(125, '4342411016', 'Erwin Oktodinata Hutabarat', 'e10adc3949ba59abbe56e057f20f883e', 'erwin oktodinata hutabarat@gmail.com', 1, 1, 0),
(126, '4342411017', 'Anjely Juniarti', 'e10adc3949ba59abbe56e057f20f883e', 'anjely juniarti@gmail.com', 1, 1, 0),
(127, '4342411018', 'Angelina Maria Angwarmase', 'e10adc3949ba59abbe56e057f20f883e', 'angelina maria angwarmase@gmail.com', 1, 1, 0),
(128, '4342411019', 'Jainal A Sibuea', 'e10adc3949ba59abbe56e057f20f883e', 'jainal a sibuea@gmail.com', 1, 1, 0),
(129, '4342411020', 'Aipti Gusti Pratiwi', 'e10adc3949ba59abbe56e057f20f883e', 'aipti gusti pratiwi@gmail.com', 1, 1, 0),
(130, '4342411021', 'Ruth Sartika Sibarani', 'e10adc3949ba59abbe56e057f20f883e', 'ruth sartika sibarani@gmail.com', 1, 1, 0),
(131, '4342411022', 'Rizky Alfiansyah', 'e10adc3949ba59abbe56e057f20f883e', 'rizky alfiansyah@gmail.com', 1, 1, 0),
(132, '4342411023', 'Annisa Nabila Andrint', 'e10adc3949ba59abbe56e057f20f883e', 'annisa nabila andrint@gmail.com', 1, 1, 0),
(133, '4342411024', 'Nauval Putra Widaya', 'e10adc3949ba59abbe56e057f20f883e', 'nauval putra widaya@gmail.com', 1, 1, 0),
(134, '4342411025', 'Nicholas Jonah Winata', 'e10adc3949ba59abbe56e057f20f883e', 'nicholas jonah winata@gmail.com', 1, 1, 0),
(135, '4342411026', 'Shanessa Julytavia Br Siahaan', 'e10adc3949ba59abbe56e057f20f883e', 'shanessa julytavia br siahaan@gmail.com', 1, 1, 0),
(136, '4342411027', 'Aurellia Azzahra Putri Huda', 'e10adc3949ba59abbe56e057f20f883e', 'aurellia azzahra putri huda@gmail.com', 1, 1, 0),
(137, '4342411028', 'Ridho Ihsan Fauzan', 'e10adc3949ba59abbe56e057f20f883e', 'ridho ihsan fauzan@gmail.com', 1, 1, 0),
(140, '4342411031', 'Muhammad Raihan Hanafi', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad raihan hanafi@gmail.com', 1, 1, 0),
(141, '4342411032', 'Destia', 'e10adc3949ba59abbe56e057f20f883e', 'destia@gmail.com', 1, 1, 0),
(142, '4342411033', 'Rizki Yehezkiel Sigalingging', 'e10adc3949ba59abbe56e057f20f883e', 'rizki yehezkiel sigalingging@gmail.com', 1, 1, 0),
(143, '4342411034', 'Tomingse Lingga', 'e10adc3949ba59abbe56e057f20f883e', 'tomingse lingga@gmail.com', 1, 1, 0),
(144, '4342411035', 'Jhonatan Herwalt Simbolon', 'e10adc3949ba59abbe56e057f20f883e', 'jhonatan herwalt simbolon@gmail.com', 1, 1, 0),
(145, '4342411036', 'Muhammad Dimas Fajar Efendi', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad dimas fajar efendi@gmail.com', 1, 1, 0),
(146, '4342411037', 'Ivander Benjamin Valentino Sitanggang', 'e10adc3949ba59abbe56e057f20f883e', 'ivander benjamin valentino sitanggang@gmail.com', 1, 1, 0),
(147, '4342411038', 'Julyawan Caesar Ragil', 'e10adc3949ba59abbe56e057f20f883e', 'julyawan caesar ragil@gmail.com', 1, 1, 0),
(148, '4342411039', 'Avyz Yudistira', 'e10adc3949ba59abbe56e057f20f883e', 'avyz yudistira@gmail.com', 1, 1, 0),
(149, '4342411040', 'Aulia Putri', 'e10adc3949ba59abbe56e057f20f883e', 'aulia putri@gmail.com', 1, 1, 0),
(150, '4342411041', 'Revan Dwi Cahyadi', 'e10adc3949ba59abbe56e057f20f883e', 'revan dwi cahyadi@gmail.com', 1, 1, 0),
(151, '4342411042', 'Terovini Maria Saleky', 'e10adc3949ba59abbe56e057f20f883e', 'terovini maria saleky@gmail.com', 1, 1, 0),
(152, '4342411043', 'Terovina Elisabeth Saleky', 'e10adc3949ba59abbe56e057f20f883e', 'terovina elisabeth saleky@gmail.com', 1, 1, 0),
(153, '4342411044', 'Dava Revaldo Saputra', 'e10adc3949ba59abbe56e057f20f883e', 'dava revaldo saputra@gmail.com', 1, 1, 0),
(154, '4342411045', 'Randy Nugraha Saputra', 'e10adc3949ba59abbe56e057f20f883e', 'randy nugraha saputra@gmail.com', 1, 1, 0),
(155, '4342411046', 'Ghifarry Ramadhan Mahendra', 'e10adc3949ba59abbe56e057f20f883e', 'ghifarry ramadhan mahendra@gmail.com', 1, 1, 0),
(156, '4342411047', 'Rolan Shino Gido Gultom', 'e10adc3949ba59abbe56e057f20f883e', 'rolan shino gido gultom@gmail.com', 1, 1, 0),
(157, '4342411048', 'Danu Yudistia', 'e10adc3949ba59abbe56e057f20f883e', 'danu yudistia@gmail.com', 1, 1, 0),
(158, '4342411049', 'Ellsa Santoso', 'e10adc3949ba59abbe56e057f20f883e', 'ellsa santoso@gmail.com', 1, 1, 0),
(159, '4342411050', 'Muhammad Sharaheel Azhar Alfath', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad sharaheel azhar alfath@gmail.com', 1, 1, 0),
(160, '4342411051', 'Shafwah Khansa Meizura', 'e10adc3949ba59abbe56e057f20f883e', 'shafwah khansa meizura@gmail.com', 1, 1, 0),
(161, '4342411052', 'Muhamad Farhan Saz', 'e10adc3949ba59abbe56e057f20f883e', 'muhamad farhan saz@gmail.com', 1, 1, 0),
(162, '4342411053', 'Muhammad Rafly Sabani', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad rafly sabani@gmail.com', 1, 1, 0),
(163, '4342411054', 'Salsabila Zahra Fitria', 'e10adc3949ba59abbe56e057f20f883e', 'salsabila zahra fitria@gmail.com', 1, 1, 0),
(164, '4342411055', 'Veron Manasse Situmorang', 'e10adc3949ba59abbe56e057f20f883e', 'veron manasse situmorang@gmail.com', 1, 1, 0),
(165, '4342411056', 'M. Farrel Adelio A', 'e10adc3949ba59abbe56e057f20f883e', 'm. farrel adelio a@gmail.com', 1, 1, 0),
(166, '4342411057', 'Muhammad Ghiyats', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad ghiyats@gmail.com', 1, 1, 0),
(167, '4342411058', 'Rafael Setya Ramadhan', 'e10adc3949ba59abbe56e057f20f883e', 'rafael setya ramadhan@gmail.com', 1, 1, 0),
(168, '4342411059', 'Putri Amalia', 'e10adc3949ba59abbe56e057f20f883e', 'putri amalia@gmail.com', 1, 1, 0),
(169, '4342411060', 'Muhammad Nasyith Aditya Putera', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad nasyith aditya putera@gmail.com', 1, 1, 0),
(170, '4342411061', 'Ivander Justine Savero', 'e10adc3949ba59abbe56e057f20f883e', 'ivander justine savero@gmail.com', 1, 1, 0),
(171, '4342411062', 'Muhammad Nabil Aditya Putera', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad nabil aditya putera@gmail.com', 1, 1, 0),
(172, '4342411063', 'Aulia Cahya Lamira', 'e10adc3949ba59abbe56e057f20f883e', 'aulia cahya lamira@gmail.com', 1, 1, 0),
(174, '4342411065', 'Imel Valentina Parapat', 'e10adc3949ba59abbe56e057f20f883e', 'imel valentina parapat@gmail.com', 1, 1, 0),
(175, '4342411066', 'Nurul Khotimah', 'e10adc3949ba59abbe56e057f20f883e', 'nurul khotimah@gmail.com', 1, 1, 0),
(177, '4342411068', 'Andi Hardiansya Permana', 'e10adc3949ba59abbe56e057f20f883e', 'andi hardiansya permana@gmail.com', 1, 1, 0),
(178, '4342411069', 'Fadilah Amelia', 'e10adc3949ba59abbe56e057f20f883e', 'fadilah amelia@gmail.com', 1, 1, 0),
(179, '4342411070', 'Aditiya Arsandi Sulaeman', 'e10adc3949ba59abbe56e057f20f883e', 'aditiya arsandi sulaeman@gmail.com', 1, 1, 0),
(180, '4342411071', 'Fasha Ar-rafly', 'e10adc3949ba59abbe56e057f20f883e', 'fasha ar-rafly@gmail.com', 1, 1, 0),
(181, '4342411072', 'Rahel Tarigan', 'e10adc3949ba59abbe56e057f20f883e', 'rahel tarigan@gmail.com', 1, 1, 0),
(182, '4342411073', 'Kharlos Daylo Saut Silaban', 'e10adc3949ba59abbe56e057f20f883e', 'kharlos daylo saut silaban@gmail.com', 1, 1, 0),
(183, '4342411074', 'Tengku Radhi Ammar Athallah', 'e10adc3949ba59abbe56e057f20f883e', 'tengku radhi ammar athallah@gmail.com', 1, 1, 0),
(184, '4342411075', 'Nabil Albarru Yasmed', 'e10adc3949ba59abbe56e057f20f883e', 'nabil albarru yasmed@gmail.com', 1, 1, 0),
(185, '4342411076', 'Meisya Novita Sabrina', 'e10adc3949ba59abbe56e057f20f883e', 'meisya novita sabrina@gmail.com', 1, 1, 0),
(186, '4342411077', 'Jose Juneo Brahmana Girsang', 'e10adc3949ba59abbe56e057f20f883e', 'jose juneo brahmana girsang@gmail.com', 1, 1, 0),
(187, '4342411078', 'Fadhal Rahman', 'e10adc3949ba59abbe56e057f20f883e', 'fadhal rahman@gmail.com', 1, 1, 0),
(188, '4342411079', 'Rahel Hadasa Friskila Ginting', 'e10adc3949ba59abbe56e057f20f883e', 'rahel hadasa friskila ginting@gmail.com', 1, 1, 0),
(189, '4342411080', 'Andryan Marcellino Simarmata', 'e10adc3949ba59abbe56e057f20f883e', 'andryan marcellino simarmata@gmail.com', 1, 1, 0),
(190, '4342411081', 'Ramanda Syawal Rafaelliano', 'e10adc3949ba59abbe56e057f20f883e', 'ramanda syawal rafaelliano@gmail.com', 1, 1, 0),
(191, '4342411082', 'Jhon Martin Gabriel Sirait', 'e10adc3949ba59abbe56e057f20f883e', 'jhon martin gabriel sirait@gmail.com', 1, 1, 0),
(192, '4342411083', 'Sitiacika Mustamin', 'e10adc3949ba59abbe56e057f20f883e', 'sitiacika mustamin@gmail.com', 1, 1, 0),
(193, '4342411084', 'Chris Jericho Sembiring', 'e10adc3949ba59abbe56e057f20f883e', 'chris jericho sembiring@gmail.com', 1, 1, 0),
(194, '4342411085', 'Muhammad Zidane Gensopa', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad zidane gensopa@gmail.com', 1, 1, 0),
(195, '4342411086', 'Ignasius Pandego Simbolon', 'e10adc3949ba59abbe56e057f20f883e', 'ignasius pandego simbolon@gmail.com', 1, 1, 0),
(196, '4342411087', 'Dicky Dwi Hardana Putra', 'e10adc3949ba59abbe56e057f20f883e', 'dicky dwi hardana putra@gmail.com', 1, 1, 0),
(197, '4342411088', 'Muhammad Ridho Syahputra', 'e10adc3949ba59abbe56e057f20f883e', 'muhammad ridho syahputra@gmail.com', 1, 1, 0),
(198, '4342411089', 'Adhitya Abrar', 'e10adc3949ba59abbe56e057f20f883e', 'adhitya abrar@gmail.com', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pemilihan`
--

CREATE TABLE `pemilihan` (
  `id_pemilihan` int(11) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `waktu_selesai` time NOT NULL,
  `jurusan` int(10) NOT NULL,
  `prodi` int(11) DEFAULT NULL,
  `angkatan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemilihan`
--

INSERT INTO `pemilihan` (`id_pemilihan`, `judul`, `deskripsi`, `tanggal_mulai`, `waktu_mulai`, `tanggal_selesai`, `waktu_selesai`, `jurusan`, `prodi`, `angkatan`) VALUES
(1, 'LECTURER OF THE MONTH DECEMBE', 'Pemilihan pengajar terbaik bulan desember 2024 untuk kategori pengajar prodi trpl angkatan 2024', '2024-12-08', '13:00:00', '2024-12-18', '12:00:00', 1, 1, 2024),
(3, 'IOTM DECEMBERS', 'Tes saja', '2024-12-16', '13:20:00', '2025-01-01', '13:40:00', 1, 1, 2024),
(4, 'intructure of the mont desember', 'intrukture tebaik', '2024-12-18', '00:53:00', '2024-12-19', '03:56:00', 1, 1, 2024),
(7, 'LECTURER OF THE MONH DECEMB', 'a', '2024-12-21', '22:02:00', '2024-12-23', '22:05:00', 1, 1, 2024),
(8, 'a', 'a', '2024-12-23', '21:30:00', '2024-12-24', '21:27:00', 1, 1, 2024);

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id_prodi` int(10) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `id_jurusan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id_prodi`, `prodi`, `id_jurusan`) VALUES
(1, 'trpl', 1),
(2, 'trm', 1);

-- --------------------------------------------------------

--
-- Table structure for table `suara`
--

CREATE TABLE `suara` (
  `id_suara` int(11) NOT NULL,
  `Id_pemilih` int(16) NOT NULL,
  `Id_kandidat` int(16) NOT NULL,
  `Id_pemilihan` int(16) NOT NULL,
  `suara` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suara`
--

INSERT INTO `suara` (`id_suara`, `Id_pemilih`, `Id_kandidat`, `Id_pemilihan`, `suara`) VALUES
(7, 3, 1, 1, 1),
(8, 4, 1, 1, 1),
(9, 17, 1, 1, 1),
(10, 1, 1, 1, 1),
(11, 50, 2, 1, 1),
(12, 51, 2, 1, 1),
(13, 52, 1, 1, 1),
(14, 53, 1, 1, 1),
(15, 54, 2, 1, 1),
(16, 55, 2, 1, 1),
(20, 2, 2, 1, 1),
(21, 2, 1, 3, 1),
(22, 19, 1, 4, 1),
(23, 19, 1, 1, 1),
(24, 87, 1, 3, 1),
(25, 87, 4, 7, 1),
(26, 87, 1, 8, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_suara` (`id_kandidat`),
  ADD KEY `id_voting` (`id_pemilihan`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `kandidat`
--
ALTER TABLE `kandidat`
  ADD PRIMARY KEY (`id_kandidat`);

--
-- Indexes for table `mengambil_data`
--
ALTER TABLE `mengambil_data`
  ADD PRIMARY KEY (`id_akses`),
  ADD KEY `voting_id` (`id_pemilihan`),
  ADD KEY `kandidat_id` (`kandidat_id`);

--
-- Indexes for table `pemilih`
--
ALTER TABLE `pemilih`
  ADD PRIMARY KEY (`Id_pemilih`),
  ADD KEY `fk_prodi` (`prodi`),
  ADD KEY `jurusan` (`jurusan`);

--
-- Indexes for table `pemilihan`
--
ALTER TABLE `pemilihan`
  ADD PRIMARY KEY (`id_pemilihan`),
  ADD KEY `fk_voting_prodi` (`prodi`),
  ADD KEY `jurusan` (`jurusan`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id_prodi`),
  ADD KEY `fk_prodi_jurusan` (`id_jurusan`);

--
-- Indexes for table `suara`
--
ALTER TABLE `suara`
  ADD PRIMARY KEY (`id_suara`),
  ADD KEY `Id_kandidat` (`Id_kandidat`),
  ADD KEY `Id_pemilih` (`Id_pemilih`),
  ADD KEY `Id_voting` (`Id_pemilihan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kandidat`
--
ALTER TABLE `kandidat`
  MODIFY `id_kandidat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mengambil_data`
--
ALTER TABLE `mengambil_data`
  MODIFY `id_akses` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `pemilih`
--
ALTER TABLE `pemilih`
  MODIFY `Id_pemilih` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `pemilihan`
--
ALTER TABLE `pemilihan`
  MODIFY `id_pemilihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id_prodi` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `suara`
--
ALTER TABLE `suara`
  MODIFY `id_suara` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil`
--
ALTER TABLE `hasil`
  ADD CONSTRAINT `hasil_ibfk_1` FOREIGN KEY (`id_kandidat`) REFERENCES `kandidat` (`id_kandidat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hasil_ibfk_2` FOREIGN KEY (`id_pemilihan`) REFERENCES `pemilihan` (`id_pemilihan`);

--
-- Constraints for table `mengambil_data`
--
ALTER TABLE `mengambil_data`
  ADD CONSTRAINT `mengambil_data_ibfk_1` FOREIGN KEY (`id_pemilihan`) REFERENCES `pemilihan` (`id_pemilihan`) ON DELETE CASCADE,
  ADD CONSTRAINT `mengambil_data_ibfk_2` FOREIGN KEY (`kandidat_id`) REFERENCES `kandidat` (`id_kandidat`) ON DELETE CASCADE;

--
-- Constraints for table `pemilih`
--
ALTER TABLE `pemilih`
  ADD CONSTRAINT `fk_prodi` FOREIGN KEY (`prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE,
  ADD CONSTRAINT `jurusan` FOREIGN KEY (`jurusan`) REFERENCES `jurusan` (`id_jurusan`);

--
-- Constraints for table `pemilihan`
--
ALTER TABLE `pemilihan`
  ADD CONSTRAINT `fk_voting_prodi` FOREIGN KEY (`prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemilihan_ibfk_1` FOREIGN KEY (`jurusan`) REFERENCES `jurusan` (`id_jurusan`);

--
-- Constraints for table `prodi`
--
ALTER TABLE `prodi`
  ADD CONSTRAINT `fk_prodi_jurusan` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `suara`
--
ALTER TABLE `suara`
  ADD CONSTRAINT `suara_ibfk_1` FOREIGN KEY (`Id_kandidat`) REFERENCES `kandidat` (`id_kandidat`),
  ADD CONSTRAINT `suara_ibfk_2` FOREIGN KEY (`Id_pemilih`) REFERENCES `pemilih` (`Id_pemilih`),
  ADD CONSTRAINT `suara_ibfk_3` FOREIGN KEY (`Id_pemilihan`) REFERENCES `pemilihan` (`id_pemilihan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
