<?php
$sp_id = isset($qry_sp) ? $qry_sp->sp_id : "";
$jasa = isset($qry_sp) ? $qry_sp->sp_jasa : "";
$barang = isset($qry_sp) ? $qry_sp->sp_barang : "";
$qty = isset($qry_sp) ? $qry_sp->sp_qty : "";

$btn_value = $sp_id === "" ? "btn_simpan" : "btn_ubah";

echo validation_errors();
$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <input type="hidden" name="txt_id" id="txt_id" value="<?php echo $sp_id; ?>">
    <div class="uk-form-row">
        <label for="txt_jasa" class="uk-form-label">Jasa</label>
        <div class="uk-form-controls">
            <input type="hidden" name="txt_id_jasa" id="txt_id_jasa">
            <input class="uk-form-width-medium" type="text" id="txt_jasa" name="txt_jasa" value="<?php echo $jasa; ?>" placeholder="Jasa" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_barang" class="uk-form-label">Barang</label>
        <div class="uk-form-controls">
            <input type="hidden" name="txt_id_barang" id="txt_id_barang">
            <input class="uk-form-width-medium" type="text" min="0" id="txt_barang" name="txt_barang" value="<?php echo $barang; ?>" placeholder="Barang" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_qty" class="uk-form-label">Qty</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-small" type="number" min="0" id="txt_qty" name="txt_qty" value="<?php echo $qty; ?>" required="required">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="uk-button uk-button-primary" <?php echo $konfirmasi;?>>Simpan</button>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>

<script type="text/javascript">
var option_jasa = {
    url: "<?php echo base_url("stok_paket/jasa_json"); ?>",

    getValue: "jasa_ket",

    list: {
        match: {
            enabled: true
        },
        onSelectItemEvent: function() {
            var index = $("#txt_jasa").getSelectedItemData().jasa_id;
            $("#txt_id_jasa").val(index).trigger("change");
        }
    },

    theme: "plate-dark"
};

$("#txt_jasa").easyAutocomplete(option_jasa);

var option_barang = {
    url: "<?php echo base_url("stok_paket/barang_json"); ?>",

    getValue: "barang_nama",

    template: {
		type: "custom",
		method: function(value, item) {
			return value + " | " + item.jo_ket + " | " + item.barang_jumlah;
		}
	},

    list: {
        match: {
            enabled: true
        },
        onSelectItemEvent: function() {
            var index = $("#txt_barang").getSelectedItemData().barang_id;
            $("#txt_id_barang").val(index).trigger("change");
        }
    },

    theme: "plate-dark"
};

$("#txt_barang").easyAutocomplete(option_barang);
</script>
