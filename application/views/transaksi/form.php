<?php
$tdt_id = $qry_tdt->tdt_id;
$tdt_jasa = $qry_tdt->tdt_jasa;
$tdt_barang = $qry_tdt->tdt_barang;
$tdt_qty = $qry_tdt->tdt_qty;
$tdt_harga = $qry_tdt->tdt_harga;
?>

<form action="<?php echo base_url("transaksi/update_transaksi"); ?>" method="post" class="uk-form uk-form-horizontal">
    <input type="hidden" name="txt_id" value="<?php echo $tdt_id; ?>">
    <div class="uk-form-row">
        <label class="uk-form-label" for="opt_jasa">Jasa</label>
        <div class="uk-form-controls">
            <select name="opt_jasa" autofocus="autofocus" readonly="readonly">
                <option value="0">Pilih Jasa</option>
                <?php
                foreach ($qry_jasa as $row_jasa)
                {
                    $sel_jasa = $tdt_jasa === $row_jasa->jasa_id ? "selected=\"selected\"" : "";
                    ?>
                    <option value="<?php echo $row_jasa->jasa_id; ?>" <?php echo $sel_jasa; ?>><?php echo $row_jasa->jasa_ket; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for="opt_barang">Barang</label>
        <div class="uk-form-controls">
            <select name="opt_barang" readonly="readonly">
                <?php
                foreach ($qry_barang as $row_barang)
                {
                    $sel_barang = $tdt_barang === $row_barang->barang_id ? "selected=\"selected\"" : "";
                    ?>
                    <option value="<?php echo $row_barang->barang_id; ?>" <?php echo $sel_barang; ?>><?php echo $row_barang->barang_nama; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for="txt_qty">Qty</label>
        <div class="uk-form-controls">
            <input type="text" name="txt_qty" value="<?php echo $tdt_qty; ?>" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for=""></label>
        <div class="uk-form-controls">
            <button type="submit" name="btn_update" value="btn_update" class="uk-button uk-button-primary">Update</button>
        </div>
    </div>
</form>
