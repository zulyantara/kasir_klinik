-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2016 at 03:13 PM
-- Server version: 5.7.12-0ubuntu1
-- PHP Version: 7.0.4-7ubuntu2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir_db`
--

-- --------------------------------------------------------

--
-- Structure for view `v_ph`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ph`  AS  select `pembelian_head`.`ph_id` AS `ph_id`,`pembelian_head`.`ph_nama` AS `ph_nama`,`pembelian_head`.`ph_kode_akun` AS `ph_kode_akun`,`pembelian_head`.`ph_insert_date` AS `ph_insert_date`,`pembelian_head`.`ph_insert_user` AS `ph_insert_user`,sum(`pembelian_detail`.`pd_harga_beli`) AS `total`,`kode_akun`.`ka_akun` AS `ka_akun`,`user`.`user_name` AS `user_name` from (((`pembelian_head` left join `pembelian_detail` on((`pembelian_head`.`ph_id` = `pembelian_detail`.`pd_head`))) left join `kode_akun` on((`pembelian_head`.`ph_kode_akun` = `kode_akun`.`ka_id`))) left join `user` on((`pembelian_head`.`ph_insert_user` = `user`.`user_id`))) group by `pembelian_head`.`ph_id`,`pembelian_head`.`ph_nama`,`pembelian_head`.`ph_kode_akun`,`pembelian_head`.`ph_insert_date`,`pembelian_head`.`ph_insert_user`,`kode_akun`.`ka_akun`,`user`.`user_name` ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
