<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/print.css"); ?>">
</head>
<body>
    <div class="uk-panel uk-panel-header">
        <h3 style="text-align:center;"><?php echo $this->session->userdata("profilKlinik") !== FALSE ? $this->session->userdata("profilKlinik") : "Klinik"; ?></h3>
        <h3 class="uk-panel-title" style="text-align:center;">Jurnal Transaksi Periode <?php echo $tgl_1." s/d ".$tgl_2; ?></h3>
        <table class="uk-table uk-table-condensed">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Transaksi</th>
                    <th>Jumlah</th>
                    <th>Akun</th>
                    <th>Nama Akun</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // transaksi
                foreach ($qry_jurnal as $row_jurnal)
                {
                    ?>
                    <tr>
                        <td><?php echo $row_jurnal->tgl; ?></td>
                        <td><?php echo $row_jurnal->transaksi; ?></td>
                        <td><span class="uk-align-right"><?php echo number_format($row_jurnal->harga,0,',','.'); ?></span></td>
                        <td><?php echo $row_jurnal->ka_kode; ?></td>
                        <td><?php echo $row_jurnal->ka_akun; ?></td>
                        <td><span class="uk-align-right"><?php echo substr($row_jurnal->ka_kode,0,1) !== "B" ? number_format($row_jurnal->harga,0,',','.') : '-';?></span></td>
                        <td><span class="uk-align-right"><?php echo substr($row_jurnal->ka_kode,0,1) === "B" ? number_format($row_jurnal->harga,0,',','.') : '-';?></span></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
