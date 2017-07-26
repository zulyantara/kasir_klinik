<?php
$random = rand();
$rand_tk = base64_encode($random."-".$k."-".$s);
?>
<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("pembelian/form_detail?k=".$rand_tk); ?>">
    <input type="hidden" name="txt_head" id="txt_head" value="<?php echo $k; ?>">
    <input type="hidden" name="txt_supplier" id="txt_supplier" value="<?php echo $s; ?>">
    <!-- <div class="uk-form-row">
        <label class="uk-form-label" for="opt_kb">Kelompok Barang</label>
        <div class="uk-form-controls">
            <select name="opt_kb" id="opt_kb">
                <option value="">PILIH KELOMPOK BARANG</option>
                <?php
                foreach ($qry_kb as $row_kb)
                {
                    ?>
                    <option value="<?php echo $row_kb->kb_id; ?>"><?php echo $row_kb->kb_ket; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div> -->
    <div class="uk-form-row">
        <label class="uk-form-label" for="txt_barang">Nama Obat / Alkes</label>
        <div class="uk-form-controls">
            <input type="hidden" name="txt_id_barang" id="txt_id_barang" value="">
            <input type="text" name="txt_barang" id="txt_barang" placeholder="Nama Obat / Alkes" required="required" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for="txt_qty">Qty</label>
        <div class="uk-form-controls">
            <input type="text" name="txt_qty" id="txt_qty" required="required" class="uk-form-width-small">
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for="txt_harga">Total Harga</label>
        <div class="uk-form-controls">
            <input type="text" name="txt_harga" id="txt_harga" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for=""></label>
        <div class="uk-form-controls">
            <button type="submit" name="btn_simpan" id="btn_simpan" value="simpan" class="uk-button uk-button-primary">Simpan</button>
        </div>
    </div>
</form>

<table class="uk-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Obat / Alkes</th>
            <th>Qty</th>
            <th class="uk-text-center">Harga</th>
            <!-- <th class="uk-text-center">Total</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        $g_total = 0;
        $no = 1;
        if ($qry_pdt !== FALSE)
        {
            foreach ($qry_pdt as $row_pdt)
            {
                //$total = $row_pdt->pdt_qty*$row_pdt->pdt_harga_beli;
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $row_pdt->barang_nama; ?></td>
                    <td><?php echo $row_pdt->pdt_qty; ?></td>
                    <td><span class="uk-align-left">Rp</span><span class="uk-align-right"><?php echo number_format($row_pdt->pdt_harga_beli,0,',','.'); ?></span></td>
                    <!-- <td><span class="uk-align-left">Rp</span><span class="uk-align-right"><?php echo number_format($total,0,',','.'); ?></span></td> -->
                </tr>
                <?php
                $no++;
                $g_total = $g_total + $row_pdt->pdt_harga_beli;
            }
        }
        else
        {
            ?>
            <tr>
                <td colspan="4">Tidak ada pembelian</td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="3"><span class="uk-text-bold uk-align-right">TOTAL</span></td>
            <td><span class="uk-align-left">Rp</span><span class="uk-text-bold uk-align-right"><?php echo number_format($g_total,0,',','.'); ?></span></td>
        </tr>
    </tbody>
</table>
<form class="uk-form uk-align-right" method="post" action="<?php echo base_url("pembelian/simpan_pembelian"); ?>">
    <input type="hidden" name="txt_head" id="txt_head" value="<?php echo $k; ?>">
    <button type="submit" name="btn_simpan" id="btn_simpan" value="simpan_detail" class="uk-button uk-button-success">Selesai Transaksi</button>
</form>
<script>
$(document).ready(function(){
    $('#txt_qty').number( true, 0 );
    $('#txt_harga').number( true, 0 );

    $('#opt_kb').change(function(){
        var id_kb = $("#opt_kb").val();
        var options = {
            url: "<?php echo base_url("transaksi/barang_json?i="); ?>"+id_kb,

            getValue: "barang_nama",

            template: {
                type: "custom",
                method: function(value, item){
                    return value+" | "+item.jo_ket+" | "+item.barang_jumlah;
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

        $("#txt_barang").easyAutocomplete(options);
    });
});

var options = {
    url: "<?php echo base_url("transaksi/barang_json"); ?>",

    getValue: "barang_nama",

    template: {
        type: "custom",
        method: function(value, item){
            return value+" | "+item.jo_ket+" | "+item.barang_jumlah;
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

$("#txt_barang").easyAutocomplete(options);
</script>
