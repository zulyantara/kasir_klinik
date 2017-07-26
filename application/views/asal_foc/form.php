<?php
$af_id = isset($qry_af) ? $qry_af->af_id : "";
$af_ket = isset($qry_af) ? ucwords($qry_af->af_ket) : "";

$btn_value = $af_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_af_id" id="txt_af_id" value="<?php echo $af_id; ?>">
    <div class="uk-form-row">
        <label for="txt_af_ket" class="uk-form-label">Keterangan</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_af_ket" name="txt_af_ket" value="<?php echo $af_ket; ?>" placeholder="Keterangan" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>
