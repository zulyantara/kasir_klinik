<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/print.css"); ?>">
</head>
<body>
    <div class="uk-panel uk-panel-header">
        <h3 style="text-align:center;"><?php echo $this->session->userdata("profilKlinik") !== FALSE ? $this->session->userdata("profilKlinik") : "Klinik"; ?></h3>
        <h3 class="uk-panel-title" style="text-align:center;">Laporan Stok Obat</h3>
        <table class="uk-table">
            <thead>
                <tr>
                    <th>Kode Obat</th>
                    <th>Nama Obat</th>
                    <th>Jenis Obat</th>
                    <th>Kelompok Obat</th>
                    <th>Stok</th>
                    <th>Limit</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($qry_barang as $row_barang)
                {
                    ?>
                    <tr>
                        <td><?php echo $row_barang->barang_kode; ?></td>
                        <td><?php echo strtoupper($row_barang->barang_nama); ?></td>
                        <td><?php echo strtoupper($row_barang->jo_ket); ?></td>
                        <td><?php echo strtoupper($row_barang->kb_ket); ?></td>
                        <td><?php echo $row_barang->barang_jumlah; ?></td>
                        <td><?php echo $row_barang->barang_limit; ?></td>
                        <td><?php echo $row_barang->barang_harga; ?></td>
                        <td><?php echo $row_barang->barang_harga_beli; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
