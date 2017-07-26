<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/print.css"); ?>">
</head>
<body>
    <div class="uk-panel uk-panel-header">
        <h3 style="text-align:center;"><?php echo $this->session->userdata("profilKlinik") !== FALSE ? $this->session->userdata("profilKlinik") : "Klinik"; ?></h3>
        <h3 class="uk-panel-title" style="text-align:center;">Transaksi Periode <?php echo date("F",strtotime($this->input->post("opt_bulan"))); ?></h3>
        <table class="uk-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Transaksi</th>
                    <th>Nama Pasien</th>
                    <th>Dokter</th>
                    <th>Jenis Obat</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total (Qty x Harga)</th>
                </tr>
            </thead>
                <?php
                $total=0;
                foreach ($qry_tp as $row_tp)
                {
                    // $tipe_pasien = $row_tp->pasien_tipe === "23" ? "Umum" : "Subsidi";
                    $sub_total = $row_tp->td_qty*$row_tp->td_harga;
                    $total = $total + $sub_total;
                    ?>
                    <tr>
                        <td><?php echo date("d-F-Y",strtotime($row_tp->th_insert_date)); ?></td>
                        <td><?php echo strtoupper($row_tp->jasa_ket === NULL ? $row_tp->barang_nama : $row_tp->jasa_ket); ?></td>
                        <td><?php echo strtoupper($row_tp->pasien_nama)." [".$row_tp->pasien_tipe."]"; ?></td>
                        <td><?php echo strtoupper($row_tp->th_dokter); ?></td>
                        <td><?php echo strtoupper($row_tp->jo_ket); ?></td>
                        <td><?php echo $row_tp->td_qty; ?></td>
                        <td><?php echo number_format($row_tp->td_harga,0,',','.'); ?></td>
                        <td><?php echo number_format($sub_total,0,',','.'); ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="7">TOTAL</td>
                    <td><?php echo number_format($total,0,',','.'); ?></td>
                </tr>
        </table>
    </div>
</body>
</html>
