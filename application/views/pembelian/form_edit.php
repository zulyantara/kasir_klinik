<?php
$random = rand();
$rand_m = base64_encode($random."-".$m);
$rand_id = base64_encode($random."-".$id);
?>
<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("pembelian/simpan_edit?m=".$rand_m."&id=".$rand_id); ?>">
    <input type="hidden" name="txt_head" id="txt_head" value="<?php echo $id; ?>">
    <div class="uk-form-row">
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
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for="txt_barang">Nama Barang</label>
        <div class="uk-form-controls">
            <input type="hidden" name="txt_id_barang" id="txt_id_barang" value="">
            <input type="text" name="txt_barang" id="txt_barang" placeholder="Nama Barang" required="required" autofocus="autofocus">
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
            <th>Nama Barang</th>
            <th>Qty</th>
            <th class="uk-text-center">Total Harga</th>
            <th class="uk-text-center">Total (Qty x Total Harga)</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $g_total = 0;
        $no = 1;
        if ($qry_pd !== FALSE)
        {
            foreach ($qry_pd as $row_pd)
            {
                $total = $row_pd->pd_qty*$row_pd->pd_harga_beli;
                $random = rand();
                $rand_idpd = base64_encode($random."-".$row_pd->pd_id);
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $row_pd->barang_nama; ?></td>
                    <td><?php echo $row_pd->pd_qty; ?></td>
                    <td><span class="uk-align-left">Rp</span><span class="uk-align-right"><?php echo number_format($row_pd->pd_harga_beli,0,',','.'); ?></span></td>
                    <td><span class="uk-align-left">Rp</span><span class="uk-align-right"><?php echo number_format($total,0,',','.'); ?></span></td>
                    <td><a href="<?php echo base_url('pembelian/delete_pd?&idpd='.$rand_idpd); ?>" class="uk-button uk-button-danger"><i class="uk-icon uk-icon-trash"></i> </a></td>
                </tr>
                <?php
                $no++;
                $g_total = $g_total + $total;
            }
        }
        else
        {
            ?>
            <tr>
                <td colspan="5">Tidak ada pembelian</td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="4"><span class="uk-text-bold uk-align-right">TOTAL</span></td>
            <td><span class="uk-align-left">Rp</span><span class="uk-text-bold uk-align-right"><?php echo number_format($g_total,0,',','.'); ?></span></td>
            <td></td>
        </tr>
    </tbody>
</table>
<a href="<?php echo base_url('pembelian'); ?>" class="uk-button uk-button-success uk-align-right">Selesai Transaksi</a>
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
                type: "description",
                fields: {
                    description: "barang_jumlah"
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
        type: "description",
        fields: {
            description: "barang_jumlah"
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
