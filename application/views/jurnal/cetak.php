<?php
$date_tgl_1 = new DateTime($tgl_1);
$date_tgl_2 = new DateTime($tgl_2);
?>
<div class="uk-grid">
    <div class="uk-width-2-3">
        <span class="uk-text-bold">Periode <?php echo $date_tgl_1->format("d F Y")." s/d ".$date_tgl_2->format("d F Y"); ?></span>
    </div>
    <div class="uk-width-1-3">
        <form method="post" action="<?php echo base_url("jurnal/cetak"); ?>" class="uk-form uk-align-right">
            <input type="hidden" name="txt_tgl_1" value="<?php echo $tgl_1; ?>">
            <input type="hidden" name="txt_tgl_2" value="<?php echo $tgl_2; ?>">
            <button type="submit" name="btn_cetak" value="cetak_pdf" class="uk-button uk-button-success">Cetak PDF</button>
        </form>
    </div>
</div>
<div class="uk-panel uk-panel-box uk-margin-top">
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
                    <td><span class="uk-align-right"><?php echo $row_jurnal->ka_kode === "P-001" ? number_format($row_jurnal->harga,0,',','.') : '-';?></span></td>
                    <td><span class="uk-align-right"><?php echo substr($row_jurnal->ka_kode,0,1) === "B" || $row_jurnal->ka_kode === "P-002" ? number_format($row_jurnal->harga,0,',','.') : '-';?></span></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
