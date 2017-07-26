
<div class="uk-grid">
    <!-- <div class="uk-width-1-2">
        <div class="uk-panel uk-panel-header uk-panel-box uk-panel-box-primary">
            <h3 class="uk-panel-title">Cek Pasien</h3>
            <input type="text" id="txt_pasien">
        </div>
    </div> -->
    <div class="uk-width-1-1">
        <div class="uk-panel uk-panel-header uk-panel-box uk-panel-box-primary">
            <h3 class="uk-panel-title">Jumlah Transaksi</h3>
            Jumlah transaksi hari ini = <?php echo $qry_sum_transaksi !== 0 ? number_format($qry_sum_transaksi->total_transaksi,0,',','.') : $qry_sum_transaksi; ?>
        </div>
    </div>
</div>
<div class="uk-grid">
    <div class="uk-width-1-2">
        <div class="uk-panel uk-panel-header uk-panel-box uk-panel-box-primary">
            <h3 class="uk-panel-title">Stok Obat/Alkes Habis</h3>
            <ul class="uk-list">
                <?php
                if ($qry_barang_habis !== FALSE)
                {
                    foreach ($qry_barang_habis as $row_brg_habis)
                    {
                        ?>
                        <li><?php echo $row_brg_habis->barang_nama.", Limit = ".$row_brg_habis->barang_limit; ?></li>
                        <?php
                    }
                }
                else
                {
                    ?>
                    Tidak ada data
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="uk-width-1-2">
        <div class="uk-panel uk-panel-header uk-panel-box uk-panel-box-primary">
            <h3 class="uk-panel-title">Stok Obat/Alkes Limit</h3>
            <ul class="uk-list">
                <?php
                if ($qry_barang_limit !== FALSE)
                {
                    foreach ($qry_barang_limit as $row_brg_limit)
                    {
                        ?>
                        <li><?php echo $row_brg_limit->barang_nama.", Stok = ".$row_brg_limit->barang_jumlah.", Limit = ".$row_brg_limit->barang_limit; ?></li>
                        <?php
                    }
                }
                else
                {
                    ?>
                    Tidak ada data
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</div>
