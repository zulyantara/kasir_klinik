-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2016 at 12:39 PM
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
-- Structure for view `v_th`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_th` AS select `transaksi_head`.`th_id` AS `th_id`,`transaksi_head`.`th_kode` AS `th_kode`,`transaksi_head`.`th_pasien` AS `th_pasien`,`transaksi_head`.`th_kode_akuntansi` AS `th_kode_akuntansi`,`transaksi_head`.`th_customer` AS `th_customer`,`transaksi_head`.`th_dokter` AS `th_dokter`,`transaksi_head`.`th_insert_date` AS `th_insert_date`,`transaksi_head`.`th_insert_user` AS `th_insert_user`,`pasien`.`pasien_id` AS `pasien_id`,`pasien`.`pasien_kode` AS `pasien_kode`,`pasien`.`pasien_nama` AS `pasien_nama`,`pasien`.`pasien_tgl_lahir` AS `pasien_tgl_lahir`,`pasien`.`pasien_alamat` AS `pasien_alamat`,`pasien`.`pasien_sex` AS `pasien_sex`,`pasien`.`pasien_telp` AS `pasien_telp`,`pasien`.`pasien_tipe` AS `pasien_tipe`,`pasien`.`pasien_af` AS `pasien_af`,`pasien`.`pasien_pekerjaan` AS `pasien_pekerjaan`,`pasien`.`pasien_insert_date` AS `pasien_insert_date`,`pasien`.`pasien_insert_user` AS `pasien_insert_user`,`pasien`.`pasien_update_date` AS `pasien_update_date`,`pasien`.`pasien_update_user` AS `pasien_update_user`,`kode_akun`.`ka_id` AS `ka_id`,`kode_akun`.`ka_kode` AS `ka_kode`,`kode_akun`.`ka_akun` AS `ka_akun`,`kode_akun`.`ka_parent` AS `ka_parent`,`kode_akun`.`ka_jenis_akun` AS `ka_jenis_akun`,`kode_akun`.`ka_ket` AS `ka_ket`,`kode_akun`.`ka_insert_date` AS `ka_insert_date`,`kode_akun`.`ka_insert_user` AS `ka_insert_user`,`kode_akun`.`ka_update_date` AS `ka_update_date`,`kode_akun`.`ka_update_user` AS `ka_update_user`,(select sum((`transaksi_detail`.`td_qty` * `transaksi_detail`.`td_harga`)) from `transaksi_detail` where (`transaksi_detail`.`td_head` = `transaksi_head`.`th_kode`)) AS `total` from ((`transaksi_head` left join `pasien` on((`transaksi_head`.`th_pasien` = `pasien`.`pasien_id`))) left join `kode_akun` on((`transaksi_head`.`th_kode_akuntansi` = `kode_akun`.`ka_id`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
