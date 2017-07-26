-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 18, 2016 at 01:51 AM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kasir_sorong`
--

-- --------------------------------------------------------

--
-- Structure for view `v_pengeluaran`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pengeluaran` AS select `pengeluaran`.`pengeluaran_id` AS `pengeluaran_id`,`pengeluaran`.`pengeluaran_kode_akun` AS `pengeluaran_kode_akun`,`pengeluaran`.`pengeluaran_ket` AS `pengeluaran_ket`,`pengeluaran`.`pengeluaran_qty` AS `pengeluaran_qty`,`pengeluaran`.`pengeluaran_harga` AS `pengeluaran_harga`,`pengeluaran`.`pengeluaran_insert_date` AS `pengeluaran_insert_date`,`pengeluaran`.`pengeluaran_insert_user` AS `pengeluaran_insert_user`,`kode_akun`.`ka_kode` AS `ka_kode`,`kode_akun`.`ka_akun` AS `ka_akun`,`kode_akun`.`ka_ket` AS `ka_ket`,sum((`pengeluaran`.`pengeluaran_qty` * `pengeluaran`.`pengeluaran_harga`)) AS `total` from (`pengeluaran` left join `kode_akun` on((`pengeluaran`.`pengeluaran_kode_akun` = `kode_akun`.`ka_id`))) group by `pengeluaran`.`pengeluaran_id`,`pengeluaran`.`pengeluaran_kode_akun`,`pengeluaran`.`pengeluaran_ket`,`pengeluaran`.`pengeluaran_qty`,`pengeluaran`.`pengeluaran_harga`,`pengeluaran`.`pengeluaran_insert_date`,`pengeluaran`.`pengeluaran_insert_user`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
