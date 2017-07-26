<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/print.css"); ?>">
</head>
<body>
    <div class="uk-panel uk-panel-header">
        <h3 style="text-align:center;"><?php echo  $this->session->userdata("profilKlinik") !== FALSE ? $this->session->userdata("profilKlinik") : "Klinik"; ?></h3>
        <h3 class="uk-panel-title" style="text-align:center;"> Dokter: <?php echo ucwords($qry_thead->th_dokter); ?> | Customer: <?php echo ucwords($qry_thead->th_customer); ?> | Pasien: <?php echo ucwords(strtolower($qry_thead->pasien_nama)); ?> | Tanggal: <?php echo date("d-m-Y",strtotime($qry_thead->th_insert_date)); ?> | Kasir: <?php echo $this->session->userdata("userName"); ?></h3>
        <p>Telah terima dari <?php echo ucwords($qry_thead->th_customer); ?></p>
        <p>Untuk pembayaran pengobatan pasien atas nama <?php echo ucwords(strtolower($qry_thead->pasien_nama)); ?>, dengan rincian:
        <table class="uk-table uk-table-hover uk-table-striped uk-table-condensed" id="transaksi_detail">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Obat/Alkes</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                $no = 1;
                foreach ($qry_transaksi as $row_transaksi)
                {
                    $total = $row_transaksi->td_qty * $row_transaksi->td_harga;
                    $grand_total = $grand_total + $total;
                    ?>
                    <tr class="uk-text-primary">
                        <td><?php echo $no; ?></td>
                        <td><?php echo strtoupper($row_transaksi->barang_nama === NULL ? $row_transaksi->jasa_ket : $row_transaksi->barang_nama); ?></td>
                        <td><?php echo $row_transaksi->td_qty; ?></td>
                        <td style="text-align: right;"><?php echo number_format($row_transaksi->td_harga,0,',','.'); ?></td>
                        <td style="text-align: right;"><?php echo number_format($total,0,',','.'); ?></td>
                    </tr>
                    <?php
                    $no++;
                }
                ?>
                <tr>
                    <td colspan="4" style="text-align: right;">Total</td>
                    <td style="text-align: right;"><?php echo number_format($grand_total,0,',','.'); ?></td>
                </tr>
                <tr>
                    <td colspan="5">Terbilang: <b><?php echo number_to_words($grand_total); ?></b></td>
                </tr>
            </tbody>
        </table>
        <p class="uk-margin-large-top">Sorong, <?php echo date("d-m-Y",strtotime($qry_thead->th_insert_date)); ?></p>
        <span class="uk-margin-large-top"></span>
        <span class="uk-margin-large-top"></span>
        <p class="uk-margin-large-top">dr. <?php echo ucwords($qry_thead->th_dokter); ?></p>
    </div>
    <hr>
</body>
</html>

<script type="text/javascript">
	try {
		this.print();
	}
	catch(e) {
		window.onload = window.print;
	}
</script>
