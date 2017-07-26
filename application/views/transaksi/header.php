<?php echo validation_errors(); ?>
<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("transaksi"); ?>">
    <div class="uk-form-row">
        <label for="txt_kode" class="uk-form-label">No Transaksi</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" readonly="readonly" name="txt_kode" value="<?php echo $kode_transaksi; ?>">
            <input class="uk-form-width-medium" type="hidden" id="txt_kode_transaksi" name="txt_kode_transaksi" value="<?php echo $kode_transaksi; ?>">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="opt_kode_akun" class="uk-form-label">Kode Akun</label>
        <div class="uk-form-controls">
            <select name="opt_kode_akun" id="opt_kode_akun" required="required" autofocus="autofocus">
                <option value="">PILIH KODE AKUN</option>
                <?php
                foreach ($qry_ka as $row_ka)
                {
                    ?>
                    <option value="<?php echo $row_ka->ka_id; ?>"><?php echo strtoupper($row_ka->ka_akun); ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row">
        <label for="opt_pasien" class="uk-form-label">Nama Pasien</label>
        <div class="uk-form-controls">
            <input type="hidden" name="opt_pasien" id="opt_pasien">
            <input type="text" name="txt_pasien" id="txt_pasien" placeholder="Nama Pasien" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_customer" class="uk-form-label">Nama Pembayar</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_customer" name="txt_customer" placeholder="Nama Pembayar" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_customer" class="uk-form-label">Nama Dokter</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_dokter" name="txt_dokter" placeholder="Nama Dokter" required="required">
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <label for="txt_customer" class="uk-form-label"></label>
        <button type="submit" name="btn_simpan_head" id="btn_simpan_head" value="btn_simpan_head" class="uk-button uk-button-primary">Transaksi</button>
    </div>
</form>

<script type="text/javascript">
$('#opt_kode_akun').change(function(){
    var id_ka = $("#opt_kode_akun").val();
    var options = {
        url: "<?php echo base_url("transaksi/pasien_json?i="); ?>"+id_ka,

        getValue: "pasien_nama",

        template: {
            type: "description",
            fields: {
                description: "pasien_tgl_lahir"
            }
        },

        list: {
            maxNumberOfElements: 20,
            match: {
                enabled: true
            },
            onSelectItemEvent: function() {
                var index = $("#txt_pasien").getSelectedItemData().pasien_id;
                $("#opt_pasien").val(index).trigger("change");
            }
        },

        theme: "plate-dark"
    };

    $("#txt_pasien").easyAutocomplete(options);
});

var options = {
    url: "<?php echo base_url("transaksi/pasien_json"); ?>",

    getValue: "pasien_nama",

    template: {
        type: "description",
        fields: {
            description: "pasien_tgl_lahir"
        }
    },

    list: {
        maxNumberOfElements: 20,
        match: {
            enabled: true
        },
        onSelectItemEvent: function() {
            var index = $("#txt_pasien").getSelectedItemData().pasien_id;
            $("#opt_pasien").val(index).trigger("change");
        }
    },

    theme: "plate-dark"
};

$("#txt_pasien").easyAutocomplete(options);
</script>
