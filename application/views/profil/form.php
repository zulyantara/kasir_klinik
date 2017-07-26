<?php
$profil_id = isset($qry_profil) ? $qry_profil->profil_id : "";
$profil_nama = isset($qry_profil) ? ucwords($qry_profil->profil_nama) : "";
$profil_alamat = isset($qry_profil) ? $qry_profil->profil_alamat : "";
$profil_telp = isset($qry_profil) ? $qry_profil->profil_telp : "";
$profil_kota = isset($qry_profil) ? $qry_profil->profil_kota : "";

$btn_value = $profil_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_profil_id" id="txt_profil_id" value="<?php echo $profil_id; ?>">
    <div class="uk-form-row">
        <label for="txt_profil_nama" class="uk-form-label">Nama</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_profil_nama" name="txt_profil_nama" value="<?php echo $profil_nama; ?>" placeholder="Nama" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_profil_alamat" class="uk-form-label">Alamat</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-large" type="text" id="txt_profil_alamat" name="txt_profil_alamat" value="<?php echo $profil_alamat; ?>" placeholder="Alamat" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_profil_telp" class="uk-form-label">No Telp</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_profil_telp" name="txt_profil_telp" value="<?php echo $profil_telp; ?>" placeholder="No. Telp" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_profil_kota" class="uk-form-label">Kota</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_profil_kota" name="txt_profil_kota" value="<?php echo $profil_kota; ?>" placeholder="Kota" required="required">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>
