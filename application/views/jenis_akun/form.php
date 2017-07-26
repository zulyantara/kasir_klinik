<?php
$ja_id = isset($qry_ja) ? $qry_ja->ja_id : "";
$ja_kode = isset($qry_ja) ? ucwords($qry_ja->ja_kode) : "";
$ja_ket = isset($qry_ja) ? ucwords($qry_ja->ja_ket) : "";

$btn_value = $ja_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_ja_id" id="txt_ja_id" value="<?php echo $ja_id; ?>">
    <div class="uk-form-row">
        <label for="txt_ja_kode" class="uk-form-label">Kode</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_ja_kode" name="txt_ja_kode" value="<?php echo $ja_kode; ?>" placeholder="Kode" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_ja_ket" class="uk-form-label">Jenis Akun</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_ja_ket" name="txt_ja_ket" value="<?php echo $ja_ket; ?>" placeholder="Jenis Akun" required="required">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>
