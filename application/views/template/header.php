<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->session->userdata("profilKlinik") !== FALSE ? $this->session->userdata("profilKlinik") : "Klinik"; ?></title>
        <link rel="stylesheet" href="<?php echo site_url("assets/uikit/css/uikit.almost-flat.min.css"); ?>" />
        <link rel="stylesheet" href="<?php echo site_url("assets/uikit/css/components/datepicker.almost-flat.min.css"); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo site_url("assets/datatables/css/dataTables.uikit.min.css"); ?>">
        <link rel="stylesheet" href="<?php echo site_url("assets/jquery/easy-autocomplete.min.css"); ?>" />
        <link rel="stylesheet" href="<?php echo site_url("assets/jquery/easy-autocomplete.themes.min.css"); ?>" />
        <link rel="stylesheet" href="<?php echo site_url("assets/css/tara.css"); ?>" />

        <script src="<?php echo site_url("assets/jquery/jquery-2.2.1.min.js"); ?>"></script>
        <script src="<?php echo site_url("assets/uikit/js/uikit.min.js"); ?>"></script>
        <script src="<?php echo site_url("assets/uikit/js/components/datepicker.min.js"); ?>"></script>
        <script src="<?php echo site_url("assets/uikit/js/components/form-select.min.js"); ?>"></script>

        <script type="text/javascript" language="javascript" src="<?php echo site_url("assets/datatables/js/jquery.dataTables.min.js"); ?>"></script>
        <script type="text/javascript" charset="utf8" src="<?php echo site_url("assets/datatables/js/dataTables.uikit.min.js"); ?>"></script>

        <script type="text/javascript" src="<?php echo site_url("assets/jquery/jquery.number.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url("assets/jquery/jquery.easy-autocomplete.min.js"); ?>"></script>
    </head>
    <body>
		<div class="uk-container uk-container-center">
			<nav class="uk-navbar uk-margin-bottom">
				<a href="<?php echo site_url(); ?>" class="uk-navbar-brand uk-hidden-small"><?php echo $this->session->userdata("profilKlinik") !== FALSE ? $this->session->userdata("profilKlinik") : "Klinik"; ?></a>
				<ul class="uk-navbar-nav uk-hidden-small">
                    <li><a href="<?php echo site_url("pasien"); ?>"><i class="uk-icon uk-icon-wheelchair"></i> Pasien</a></li>
					<li class="uk-parent" data-uk-dropdown>
                        <a href="<?php echo site_url("transaksi"); ?>"><i class="uk-icon uk-icon-cart-plus"></i> Transaksi</a>
                        <div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom">
							<ul class="uk-nav uk-navbar">
								<li><a href="<?php echo site_url("transaksi"); ?>">Transaksi</a></li>
                                <li><a href="<?php echo site_url("pembelian"); ?>">Pembelian Obat</a></li>
                                <li><a href="<?php echo site_url("stok_paket"); ?>">Stok Paket</a></li>
                                <li><a href="<?php echo site_url('transaksi/list_transaksi'); ?>">List Transaksi</a></li>
							</ul>
						</div>
                    </li>
					<li class="uk-parent" data-uk-dropdown>
						<a href="<?php echo site_url("jurnal"); ?>"><i class="uk-icon uk-icon-bar-chart"></i> Laporan</a>
						<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom">
							<ul class="uk-nav uk-navbar">
								<li><a href="<?php echo site_url("jurnal"); ?>">Jurnal</a></li>
                                <!-- <li><a href="<?php echo site_url("neraca_lajur"); ?>">Neraca Lajur</a></li> -->
								<!-- <li><a href="<?php echo site_url("neraca"); ?>">Neraca</a></li> -->
								<li><a href="<?php echo site_url("laba_rugi"); ?>">Laba Rugi</a></li>
                                <!-- <li><a href="<?php echo site_url("nilai_stok_barang"); ?>">Nilai Stok Barang</a></li> -->
                                <!-- <li><a href="<?php echo site_url("stok_barang"); ?>">Stok Barang</a></li> -->
                                <li><a href="<?php echo site_url("transaksi_penjualan"); ?>">Transaksi Penjualan</a></li>
							</ul>
						</div>
					</li>
				</ul>
                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav">
                        <li><a href="#offcanvas-1" data-uk-offcanvas><i class="uk-icon-bars"></i> Menu</a></li>
                        <?php
                        if($this->session->userdata('isLoggedIn') OR $this->session->userdata('isLoggedIn') === TRUE)
                        {
                            ?>
                            <li class="uk-parent" data-uk-dropdown>
                                <a href="<?php echo site_url("auth/logout"); ?>"><i class="uk-icon uk-icon-user"></i> <?php echo humanize($this->session->userdata("userName")); ?></a>
                                <div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom">
        							<ul class="uk-nav uk-navbar">
                                        <li><a href="<?php echo site_url("auth/logout"); ?>">Logout</a></li>
                                        <li><a href="<?php echo site_url("auth/change_password"); ?>">Ubah Password</a></li>
                                        <?php
                                        if($this->session->userdata("userLevel")==="0")
                                        {
                                            ?>
                                            <li><a href="<?php echo site_url("auth/list_user"); ?>">User</a></li>
                                            <?php
                                        }
                                        ?>
        							</ul>
        						</div>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <a href="#tara-offcanvas" data-uk-offcanvas="" class="uk-navbar-toggle uk-visible-small"></a>
				<div class="uk-navbar-brand uk-navbar-center uk-visible-small">Klinik</div>
			</nav>

            <div class="uk-offcanvas" id="offcanvas-1">
                <div class="uk-offcanvas-bar">
                    <ul class="uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav="">
                        <li class="uk-nav-header">Menu Utama</li>
                        <li><a href="<?php echo site_url("pengeluaran"); ?>">Pengeluaran</a></li>
                        <li><a href="<?php echo site_url("payroll"); ?>">Penggajian</a></li>
                        <li aria-expanded="false" class="uk-parent">
                            <a href="#">Master</a>
                            <ul class="uk-nav-sub">
                                <li><a href="<?php echo site_url("profil"); ?>">Profil</a></li>
                                <li><a href="<?php echo site_url("staff"); ?>">Staff</a></li>
                                <li><a href="<?php echo site_url("satuan_barang"); ?>">Satuan Barang</a></li>
                                <li><a href="<?php echo site_url("jenis_obat"); ?>">Jenis Obat</a></li>
                                <li><a href="<?php echo site_url("kelompok_barang"); ?>">Kelompok Obat</a></li>
                                <li><a href="<?php echo site_url("jenis_akun"); ?>">Jenis Akun</a></li>
                                <li><a href="<?php echo site_url("kode_akun"); ?>">Kode Akun</a></li>
                                <li><a href="<?php echo site_url("asal_foc"); ?>">Asal FOC</a></li>
                            </ul>
                        </li>
                        <li aria-expanded="false" class="uk-parent">
                            <a href="#">Jasa/Obat</a>
                            <ul class="uk-nav-sub">
                                <li><a href="<?php echo site_url("jasa"); ?>">Jasa</a></li>
								<li><a href="<?php echo site_url("obat"); ?>">Obat</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

			<div class="uk-grid" data-uk-grid-margin>
				<div class="uk-width-1-1">
					<div class="uk-panel uk-panel-box uk-panel-header">
						<h3 class="uk-panel-title"><?php echo humanize(isset($panel_title) ? $panel_title : "home"); ?></h3>
