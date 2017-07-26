<?php
$ka_id = isset($qry_ka) ? $qry_ka->ka_id : "";
$ka_kode = isset($qry_ka) ? ucwords($qry_ka->ka_kode) : "";
$ka_akun = isset($qry_ka) ? ucwords($qry_ka->ka_akun) : "";
$ka_ja = isset($qry_ka) ? ucwords($qry_ka->ka_jenis_akun) : "";

$btn_value = $ka_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_ka_id" id="txt_ka_id" value="<?php echo $ka_id; ?>">
    <div class="uk-form-row">
        <label for="txt_ka_kode" class="uk-form-label">Kode</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_ka_kode" name="txt_ka_kode" value="<?php echo $ka_kode; ?>" placeholder="Kode" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_ka_akun" class="uk-form-label">Keterangan</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_ka_akun" name="txt_ka_akun" value="<?php echo $ka_akun; ?>" placeholder="Keterangan" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="opt_ka_ja" class="uk-form-label">Jenis Akun</label>
        <div class="uk-form-controls">
            <select id="opt_ka_ja" name="opt_ka_ja">
                <?php
                foreach ($qry_ja as $row_ja)
                {
                    $sel_ja = $ka_ja === $row_ja->ja_id ? "selected=\"selected\"" : "";
                    ?>
                    <option value="<?php echo $row_ja->ja_id; ?>" <?php echo $sel_ja; ?>><?php echo $row_ja->ja_kode." | ".$row_ja->ja_ket; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>
