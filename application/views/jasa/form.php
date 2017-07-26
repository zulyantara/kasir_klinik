<?php
$jasa_id = isset($qry_jasa) ? $qry_jasa->jasa_id : "";
$jasa_ket = isset($qry_jasa) ? ucwords($qry_jasa->jasa_ket) : "";
$jasa_harga = isset($qry_jasa) ? ucwords($qry_jasa->jasa_harga) : "";

$btn_value = $jasa_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_jasa_id" id="txt_jasa_id" value="<?php echo $jasa_id; ?>">
    <div class="uk-form-row">
        <label for="txt_jasa_ket" class="uk-form-label">Keterangan</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_jasa_ket" name="txt_jasa_ket" value="<?php echo $jasa_ket; ?>" placeholder="Keterangan" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_jasa_harga" class="uk-form-label">Harga</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_jasa_harga" name="txt_jasa_harga" value="<?php echo $jasa_harga; ?>" placeholder="Harga" required="required">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>

<script type="text/javascript">
$('#txt_jasa_harga').number( true, 0 );
</script>
