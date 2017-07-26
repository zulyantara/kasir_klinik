<?php
$random = rand();
$rand_tk = base64_encode($random."-".$k."-".$c."-".$p."-".$d."-".$ka);
?>
<div class="uk-grid">
    <div class="uk-width-1-1 uk-margin-small-bottom">
        <span class="uk-text-bold">Pasien: <?php echo strtoupper($qry_pasien !== FALSE ? $qry_pasien->pasien_nama : $p); ?> | Customer: <?php echo strtoupper($qry_pasien !== FALSE ? strtoupper($qry_pasien->tdt_customer) : $c); ?></span>
    </div>
    <div class="uk-width-2-3">
        <?php echo validation_errors(); ?>
        <form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("transaksi/form_detail?k=".$rand_tk); ?>">
            <input type="hidden" name="txt_kode_transaksi" value="<?php echo $k; ?>">
            <input type="hidden" name="txt_customer" value="<?php echo $c; ?>">
            <input type="hidden" name="txt_pasien" value="<?php echo $p; ?>">
            <input type="hidden" name="txt_dokter" value="<?php echo $d; ?>">
            <input type="hidden" name="txt_kode_akun" value="<?php echo $ka; ?>">
            <div class="uk-form-row">
                <label class="uk-form-label" for="">Jasa</label>
                <div class="uk-form-controls">
                    <select name="opt_jasa" id="opt_jasa" autofocus="autofocus">
                        <option value="0">Pilih Jasa</option>
                        <?php
                        foreach ($qry_jasa as $row_jasa)
                        {
                            ?>
                            <option value="<?php echo $row_jasa->jasa_id; ?>"><?php echo $row_jasa->jasa_ket; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <!-- <div class="uk-form-row">
                <label class="uk-form-label" for="">Kelompok Barang</label>
                <div class="uk-form-controls">
                    <select name="opt_kb" id="opt_kb">
                        <option value="">Pilih Kelompok Barang</option>
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
                <label class="uk-form-label" for="">Nama Obat/Alkes</label>
                <div class="uk-form-controls">
                    <input type="hidden" name="opt_barang" id="opt_barang" value="0">
                    <input type="text" name="txt_barang" id="txt_barang" placeholder="Nama Obat/Alkes">
                </div>
            </div>
            <div class="uk-form-row">
                <label for="txt_qty" class="uk-form-label">Qty</label>
                <div class="uk-form-controls">
                    <input class="uk-form-width-small" type="text" id="txt_qty" name="txt_qty" required="required" autocomplete="off">
                    <button type="submit" name="btn_simpan_detail" id="btn_simpan_detail" value="btn_simpan_detail" class="uk-button uk-button-primary">Tambah</button>
                </div>
            </div>
        </form>

        <table class="uk-table uk-table-hover uk-table-striped uk-table-condensed" id="transaksi_detail">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Obat/Alkes</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <?php
                    echo $this->session->userdata("userLevel") !== "2" ? "<th>Option</th>" : "";
                    ?>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td>No</td>
                    <td>Obat/Alkes</td>
                    <td>Qty</td>
                    <td>Harga</td>
                    <td>Total</td>
                    <?php
                    echo $this->session->userdata("userLevel") !== "2" ? "<td>Option</td>" : "";
                    ?>
                </tr>
            </tfoot>
            <tbody>
                <?php
                $grand_total = 0;
                $no = 1;
                if($qry_tdt !== FALSE)
                {
                    foreach ($qry_tdt as $row_tdt)
                    {
                        $random = rand();
                        $rand_tdt = base64_encode($random."-".$row_tdt->tdt_id);

                        $total = $row_tdt->tdt_qty * $row_tdt->tdt_harga;
                        $grand_total = $grand_total + $total;
                        ?>
                        <tr class="uk-text-primary">
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row_tdt->barang_nama === NULL ? $row_tdt->jasa_ket : $row_tdt->barang_nama; ?></td>
                            <td><?php echo $row_tdt->tdt_qty; ?></td>
                            <td><?php echo number_format($row_tdt->tdt_harga,0,',','.'); ?></td>
                            <td><?php echo number_format($total,0,',','.'); ?></td>
                            <?php
                            echo $this->session->userdata("userLevel") !== "2" ? "<td>".anchor("transaksi/form_edit?k=".$rand_tdt,"<i class=\"uk-icon uk-icon-edit\"></i>","class=\"uk-button uk-button-primary\"")." " : "";
                            echo $this->session->userdata("userLevel") !== "2" ? anchor("transaksi/delete?k=".$rand_tdt,"<i class=\"uk-icon uk-icon-trash\"></i>","class=\"uk-button uk-button-danger\"")."</td>" : "";
                            ?>
                        </tr>
                        <?php
                        $no++;
                    }
                }
                else
                {
                    ?>
                    <tr>
                        <td colspan="6">Belum ada transaksi</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="uk-width-1-3">
        <h1 class="uk-text-bold uk-text-primary">Rp.<span class="uk-align-right"><?php echo number_format($grand_total,0,',','.'); ?></span></h1><hr>
        <h2 class="uk-text-bold uk-text-primary">Rp.<span class="uk-align-right" id="kembalian"></span></h2><hr>
        <form action="<?php echo base_url("transaksi/simpan_transaksi"); ?>" method="post" class="uk-form uk-form-stacked">
            <input type="hidden" name="txt_kode_transaksi" value="<?php echo $k; ?>">
            <div class="uk-form-row">
                <label for="txt_total">Total Yang Harus Diabayar</label>
                <div class="uk-form-controls">
                    <input type="text" name="txt_total" id="txt_total" value="<?php echo $grand_total; ?>">
                </div>
            </div>
            <div class="uk-form-row">
                <label for="txt_dibayar">Uang Yang Diberikan</label>
                <div class="uk-form-controls">
                    <input type="text" name="txt_dibayar" id="txt_dibayar" onblur="RecalcTotal(<?php echo $grand_total; ?>)" autocomplete="off">
                </div>
            </div>
            <div class="uk-form-row">
                <label for="txt_kembalian">Uang Kembalian</label>
                <div class="uk-form-controls">
                    <input type="text" name="txt_kembalian" id="txt_kembalian">
                </div>
            </div>
            <div class="uk-form-row">
                <label for="txt_total"></label>
                <div class="uk-form-controls">
                    <button type="submit" value="btn_simpan_transaksi_detail" name="btn_simpan_detail" class="uk-button uk-button-primary">Simpan</button>
                    <button type="submit" value="btn_cetak_transaksi_detail" name="btn_simpan_detail" class="uk-button uk-button-primary">Cetak</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function number_format(a, b, c, d) {
   // credit: http://www.krisnanda.web.id/2009/06/09/javascript-number-format/

   a = Math.round(a * Math.pow(10, b)) / Math.pow(10, b);

   e = a + '';
   f = e.split('.');
   if (!f[0]) {
      f[0] = '0';
   }
   if (!f[1]) {
      f[1] = '';
   }

   if (f[1].length < b) {
      g = f[1];
      for (i = f[1].length + 1; i <= b; i++) {
         g += '0';
      }
      f[1] = g;
   }

   if (d != '' && f[0].length > 3) {
      h = f[0];
      f[0] = '';
      for (j = 3; j < h.length; j += 3) {
         i = h.slice(h.length - j, h.length - j + 3);
         f[0] = d + i + f[0] + '';
      }
      j = h.substr(0, (h.length % 3 == 0) ? 3 : (h.length % 3));
      f[0] = j + f[0];
   }

   c = (b <= 0) ? '' : c;
   return f[0] + c + f[1];
}

function RecalcTotal(tot_pembelian) {
    var Kembali = 0;
    var uangDibayar = $("#txt_dibayar").val();
    // var uangDibayar = parseInt(document.getElementById("txt_dibayar").value);

    Kembali = uangDibayar - tot_pembelian;

    document.getElementById("txt_kembalian").value = number_format(Kembali,0,',','.');
    document.getElementById("kembalian").innerHTML = '<span>' + number_format(Kembali,0,',','.') + '</span>';
}

$(document).ready(function(){
    $('#txt_dibayar').number( true, 0 );
    $('#txt_total').number( true, 0 );

    $('#opt_kb').change(function(){
        var id_kb = $("#opt_kb").val();
        var options = {
            url: "<?php echo base_url("transaksi/barang_json?i="); ?>"+id_kb,

            getValue: "barang_nama",

            template: {
                type: "custom",
                method: function(value, item){
                    item.jo_ket+" | "+item.barang_jumlah+value;
                }
            },

            list: {
                match: {
                    enabled: true
                },
                onSelectItemEvent: function() {
                    var index = $("#txt_barang").getSelectedItemData().barang_id;
                    $("#opt_barang").val(index).trigger("change");
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
            $("#opt_barang").val(index).trigger("change");
        }
    },

    theme: "plate-dark"
};

$("#txt_barang").easyAutocomplete(options);
</script>
