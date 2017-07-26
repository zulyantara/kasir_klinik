<?php
$pasien_id = isset($qry_pasien) ? $qry_pasien->pasien_id : "";
$pasien_nama = isset($qry_pasien) ? ucwords($qry_pasien->pasien_nama) : "";
$pasien_tgl_lahir = isset($qry_pasien) ? ucwords($qry_pasien->pasien_tgl_lahir) : "";
$pasien_alamat = isset($qry_pasien) ? ucwords($qry_pasien->pasien_alamat) : "";
$pasien_sex = isset($qry_pasien) ? ucwords($qry_pasien->pasien_sex) : "";
$pasien_telp = isset($qry_pasien) ? ucwords($qry_pasien->pasien_telp) : "";
$pasien_tipe = isset($qry_pasien) ? ucwords($qry_pasien->pasien_tipe) : "";
$pasien_af = isset($qry_pasien) ? ucwords($qry_pasien->pasien_af) : "";
$pasien_pekerjaan = isset($qry_pasien) ? ucwords($qry_pasien->pasien_pekerjaan) : "";

$btn_value = $pasien_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_pasien_id" id="txt_pasien_id" value="<?php echo $pasien_id; ?>">
    <div class="uk-form-row">
        <label for="txt_pasien_nama" class="uk-form-label">Nama</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_pasien_nama" name="txt_pasien_nama" value="<?php echo $pasien_nama; ?>" placeholder="Nama" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_pasien_tgl_lahir" class="uk-form-label">Tanggal Lahir</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_pasien_tgl_lahir" name="txt_pasien_tgl_lahir" value="<?php echo $pasien_tgl_lahir; ?>" placeholder="Tanggal Lahir" required="required" data-uk-datepicker="{format:'YYYY-MM-DD'}">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_pasien_alamat" class="uk-form-label">Alamat</label>
        <div class="uk-form-controls">
            <textarea name="txt_pasien_alamat" id="txt_pasien_alamat" placeholder="Alamat" required="required"><?php echo $pasien_alamat; ?></textarea>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="opt_pasien_sex" class="uk-form-label">Jenis Kelamin</label>
        <div class="uk-form-controls">
            <select name="opt_pasien_sex" id="opt_pasien_sex" required="required">
                <?php
                $sel_l = $pasien_sex === "l" ? "selected=\"selected\"" : "";
                $sel_p = $pasien_sex === "p" ? "selected=\"selected\"" : "";
                ?>
                <option value="01" <?php echo $sel_l; ?>>Laki-Laki</option>
                <option value="02" <?php echo $sel_p; ?>>Perempuan</option>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_pasien_telp" class="uk-form-label">No. Telepon</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_pasien_telp" name="txt_pasien_telp" value="<?php echo $pasien_telp; ?>" placeholder="No. Telp">
        </div>
    </div>
    <?php
    if ($this->session->userdata("userLevel") !== "2")
    {
        ?>
        <div class="uk-form-row">
            <label for="opt_pasien_tipe" class="uk-form-label">Tipe Pasien</label>
            <div class="uk-form-controls">
                <select name="opt_pasien_tipe" id="opt_pasien_tipe">
                    <option value="">PILIH TIPE</option>
                    <?php
                    $arr_tipe = array(56=>"foc",23=>"umum");
                    foreach ($arr_tipe as $key_tipe => $val_tipe)
                    {
                        $sel_tipe = $pasien_tipe == $key_tipe ? "selected=\"selected\"" : "";
                        ?>
                        <option value="<?php echo $key_tipe; ?>" <?php echo $sel_tipe; ?>><?php echo strtoupper($val_tipe); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="uk-form-row">
            <label for="opt_pasien_af" class="uk-form-label">Asal FOC</label>
            <div class="uk-form-controls">
                <select name="opt_pasien_af" id="opt_pasien_af">
                    <option value="">PILIH ASAL FOC</option>
                    <?php
                    foreach ($qry_af as $row_af)
                    {
                        $sel_af = $pasien_af == $row_af->af_id ? "selected=\"selected\"" : "";
                        ?>
                        <option value="<?php echo $row_af->af_id; ?>" <?php echo $sel_af; ?>><?php echo $row_af->af_kode." | ".strtoupper($row_af->af_ket); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="uk-form-row">
        <label for="txt_pasien_pekerjaan" class="uk-form-label">Pekerjaan</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-large" type="text" id="txt_pasien_pekerjaan" name="txt_pasien_pekerjaan" value="<?php echo $pasien_pekerjaan; ?>" placeholder="Pekerjaan">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>
