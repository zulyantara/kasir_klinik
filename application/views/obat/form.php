<?php
$barang_id = isset($qry_barang) ? $qry_barang->barang_id : "";
$barang_jo = isset($qry_barang) ? $qry_barang->barang_jenis : "";
$barang_kb = isset($qry_barang) ? $qry_barang->barang_kelompok : "";
$barang_nama = isset($qry_barang) ? ucwords($qry_barang->barang_nama) : "";
$barang_ket = isset($qry_barang) ? $qry_barang->barang_ket : "";
$barang_sb = isset($qry_barang) ? $qry_barang->barang_satuan : "";
$barang_jumlah = isset($qry_barang) ? ucwords($qry_barang->barang_jumlah) : "";
$barang_limit = isset($qry_barang) ? ucwords($qry_barang->barang_limit) : "";
$barang_harga = isset($qry_barang) ? ucwords($qry_barang->barang_harga) : "";
$barang_harga_beli = isset($qry_barang) ? ucwords($qry_barang->barang_harga_beli) : "";

$btn_value = $barang_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_barang_id" id="txt_barang_id" value="<?php echo $barang_id; ?>">
    <div class="uk-form-row">
        <label for="opt_barang_jo" class="uk-form-label">Jenis Obat</label>
        <div class="uk-form-controls">
            <select id="opt_barang_jo" name="opt_barang_jo">
                <?php
                foreach ($qry_jo as $row_jo)
                {
                    $sel_jo = $barang_jo === $row_jo->jo_id ? "selected=\"selected\"" : "";
                    ?>
                    <option value="<?php echo $row_jo->jo_id; ?>" <?php echo $sel_jo; ?>><?php echo $row_jo->jo_ket; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="opt_barang_kb" class="uk-form-label">Kelompok Obat</label>
        <div class="uk-form-controls">
            <select id="opt_barang_kb" name="opt_barang_kb">
                <?php
                foreach ($qry_kb as $row_kb)
                {
                    $sel_kb = $barang_kb === $row_kb->kb_id ? "selected=\"selected\"" : "";
                    ?>
                    <option value="<?php echo $row_kb->kb_id; ?>" <?php echo $sel_kb; ?>><?php echo $row_kb->kb_ket; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_barang_nama" class="uk-form-label">Nama</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_barang_nama" name="txt_barang_nama" value="<?php echo $barang_nama; ?>" placeholder="Nama" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_barang_ket" class="uk-form-label">Keterangan</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_barang_ket" name="txt_barang_ket" value="<?php echo $barang_ket; ?>" placeholder="Keterangan">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="opt_barang_sb" class="uk-form-label">Satuan Barang</label>
        <div class="uk-form-controls">
            <select id="opt_barang_sb" name="opt_barang_sb">
                <?php
                foreach ($qry_sb as $row_sb)
                {
                    $sel_sb = $barang_sb === $row_sb->sb_id ? "selected=\"selected\"" : "";
                    ?>
                    <option value="<?php echo $row_sb->sb_id; ?>" <?php echo $sel_sb; ?>><?php echo $row_sb->sb_ket; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_barang_jumlah" class="uk-form-label">Jumlah</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="number" min="0" id="txt_barang_jumlah" name="txt_barang_jumlah" value="<?php echo $barang_jumlah; ?>" placeholder="Jumlah" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_barang_limit" class="uk-form-label">Limit</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="number" min="0" id="txt_barang_limit" name="txt_barang_limit" value="<?php echo $barang_limit; ?>" placeholder="Limit" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_barang_harga" class="uk-form-label">Harga Jual</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" min="1" id="txt_barang_harga" name="txt_barang_harga" value="<?php echo $barang_harga; ?>" placeholder="Harga Jual" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_barang_harga_beli" class="uk-form-label">Harga Beli</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" min="1" id="txt_barang_harga_beli" name="txt_barang_harga_beli" value="<?php echo $barang_harga_beli; ?>" placeholder="Harga Beli" required="required">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>

<script type="text/javascript">
$('#txt_barang_harga').number( true, 0 );
$('#txt_barang_harga_beli').number( true, 0 );
</script>
