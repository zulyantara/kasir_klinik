<?php
$kb_id = isset($qry_kb) ? $qry_kb->kb_id : "";
$kb_ket = isset($qry_kb) ? ucwords($qry_kb->kb_ket) : "";

$btn_value = $kb_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_kb_id" id="txt_kb_id" value="<?php echo $kb_id; ?>">
    <div class="uk-form-row">
        <label for="txt_kb_ket" class="uk-form-label">Kelompok Obat</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_kb_ket" name="txt_kb_ket" value="<?php echo $kb_ket; ?>" placeholder="Kelompok Obat" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>
