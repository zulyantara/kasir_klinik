<form method="post" action="<?php echo base_url($panel_title."/cetak"); ?>" class="uk-form">
    <input type="hidden" name="opt_bulan" value="<?php echo $bulan; ?>">
    <button type="submit" name="btn_cetak" value="cetak_pdf" class="uk-button uk-button-success">Cetak PDF</button>
</form>
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
            <th class="uk-text-right">Harga</th>
            <th class="uk-text-right">Total (Qty x Harga)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        foreach ($qry_tp as $row_tp)
        {
            $tipe_pasien = $row_tp->pasien_tipe === "23" ? "Umum" : "Subsidi";
            $sub_total = $row_tp->td_qty*$row_tp->td_harga;
            $total = $total + $sub_total;
            ?>
            <tr>
                <td><?php echo date("d-F-Y",strtotime($row_tp->th_insert_date)); ?></td>
                <td><?php echo strtoupper($row_tp->th_kode); ?></td>
                <td><?php echo strtoupper($row_tp->jasa_ket === NULL ? $row_tp->barang_nama : $row_tp->jasa_ket); ?></td>
                <td><?php echo strtoupper($row_tp->pasien_nama)." [".$tipe_pasien."]"; ?></td>
                <td><?php echo strtoupper($row_tp->th_dokter); ?></td>
                <td><?php echo strtoupper($row_tp->jo_ket); ?></td>
                <td><?php echo $row_tp->td_qty; ?></td>
                <td class="uk-text-right"><?php echo number_format($row_tp->td_harga,0,',','.'); ?></td>
                <td class="uk-text-right"><?php echo number_format($sub_total,0,',','.'); ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td class="uk-text-right uk-text-bold" colspan="8">TOTAL</td>
            <td class="uk-text-right"><?php echo number_format($total, 0, ',', '.'); ?></td>
        </tr>
    </tbody>
</table>
