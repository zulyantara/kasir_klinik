<?php
$staff_id = isset($qry_staff) ? $qry_staff->staff_id : "";
$staff_kode = isset($qry_staff) ? ucwords($qry_staff->staff_kode) : $kode_staff;
$staff_nama = isset($qry_staff) ? ucwords($qry_staff->staff_nama) : "";
$staff_tgl_lahir = isset($qry_staff) ? $qry_staff->staff_tgl_lahir : "";
$staff_no_telp = isset($qry_staff) ? $qry_staff->staff_no_telp : "";
$staff_alamat = isset($qry_staff) ? $qry_staff->staff_alamat : "";
$staff_gaji = isset($qry_staff) ? $qry_staff->staff_gaji : "";
$staff_jabatan = isset($qry_staff) ? ucwords($qry_staff->staff_jabatan) : "";
$staff_ka = isset($qry_staff) ? $qry_staff->staff_kode_akun : "";

$btn_value = $staff_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_staff_id" id="txt_staff_id" value="<?php echo $staff_id; ?>">
    <div class="uk-form-row">
        <label for="txt_staff_kode" class="uk-form-label">Kode</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_staff_kode" name="txt_staff_kode" value="<?php echo $staff_kode; ?>" placeholder="Kode" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_staff_nama" class="uk-form-label">Nama</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_staff_nama" name="txt_staff_nama" value="<?php echo $staff_nama; ?>" placeholder="Nama" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_staff_tgl_lahir" class="uk-form-label">Tanggal Lahir</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_staff_tgl_lahir" name="txt_staff_tgl_lahir" value="<?php echo $staff_tgl_lahir; ?>" placeholder="Tanggal Lahir" required="required" data-uk-datepicker="{format:'YYYY-MM-DD'}">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_staff_no_telp" class="uk-form-label">No Telp</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_staff_no_telp" name="txt_staff_no_telp" value="<?php echo $staff_no_telp; ?>" placeholder="No. Telp" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_staff_alamat" class="uk-form-label">Alamat</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-large" type="text" id="txt_staff_alamat" name="txt_staff_alamat" value="<?php echo $staff_alamat; ?>" placeholder="Alamat" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_staff_gaji" class="uk-form-label">Gaji</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_staff_gaji" name="txt_staff_gaji" value="<?php echo $staff_gaji; ?>" placeholder="Gaji" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_staff_jabatan" class="uk-form-label">Jabatan</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_staff_jabatan" name="txt_staff_jabatan" value="<?php echo $staff_jabatan; ?>" placeholder="Jabatan" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="opt_staff_ka" class="uk-form-label">Kode Akun</label>
        <div class="uk-form-controls">
            <select name="opt_staff_ka" id="opt_staff_ka">
                <?php
                foreach ($qry_ka as $row_ka)
                {
                    $sel_ka = $staff_ka === $row_ka->ka_id ? "selected=\"selected\"" : "";
                    ?>
                    <option value="<?php echo $row_ka->ka_id; ?>"><?php echo $row_ka->ka_ket; ?></option>
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

<script>
$('#txt_staff_gaji').number( true, 0 );
</script>
