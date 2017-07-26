<?php
$sb_id = isset($qry_sb) ? $qry_sb->sb_id : "";
$sb_ket = isset($qry_sb) ? ucwords($qry_sb->sb_ket) : "";

$btn_value = $sb_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_sb_id" id="txt_sb_id" value="<?php echo $sb_id; ?>">
    <div class="uk-form-row">
        <label for="txt_sb_ket" class="uk-form-label">Satuan Barang</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_sb_ket" name="txt_sb_ket" value="<?php echo $sb_ket; ?>" placeholder="Satuan Barang" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>
