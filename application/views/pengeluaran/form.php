<?php
echo validation_errors();
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <div class="uk-form-row">
        <label for="opt_pengeluaran_ka" class="uk-form-label">Kode Akun</label>
        <div class="uk-form-controls">
            <select name="opt_pengeluaran_ka" id="opt_pengeluaran_ka" required="required">
                <?php
                foreach ($qry_ka as $row_ka)
                {
                    ?>
                    <option value="<?php echo $row_ka->ka_id; ?>"><?php echo $row_ka->ka_kode." | ".$row_ka->ka_akun; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_pengeluaran_ket" class="uk-form-label">Keterangan</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_pengeluaran_ket" name="txt_pengeluaran_ket" placeholder="Keterangan" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_pengeluaran_qty" class="uk-form-label">Qty</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="number" min="0" id="txt_pengeluaran_qty" name="txt_pengeluaran_qty" placeholder="qty" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_pengeluaran_harga" class="uk-form-label">Harga</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="number" min="1" id="txt_pengeluaran_harga" name="txt_pengeluaran_harga" placeholder="Harga" required="required">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="btn_simpan" class="uk-button uk-button-primary">Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>
