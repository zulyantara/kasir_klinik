<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php echo isset($message) ? "<div class=\"uk-alert uk-alert-".$alert."\"><span class=\"uk-text-warning uk-text-bold\">".$message."</span></div>" : ""; ?>

<div class="uk-grid uk-margin-bottom">
    <div class="uk-width-1-2">
        <button class="uk-button uk-button-primary" onClick="add_function();" type="button">Tambah Obat/Alkes</button>
    </div>
    <div class="uk-width-1-2">
        <div class="uk-grid uk-margin-bottom">
            <div class="uk-width-3-4">
                <button class="uk-button uk-button-success uk-float-right" onClick="print_stok();" type="button"><i class="uk-icon uk-icon-print"></i> Cetak Stok Obat</button>
            </div>
            <div class="uk-width-1-4">
                <button class="uk-button uk-float-right" onClick="reload_table();" type="button"><i class="uk-icon uk-icon-refresh"></i> Refresh</button>
            </div>
        </div>
    </div>
</div>

<table id="<?php echo $panel_title; ?>-grid" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
    <thead>
        <tr>
            <th style="width: 10%;">Kode</th>
            <th>Nama</th>
            <th style="width: 15%">Jenis Obat</th>
            <th>Stok</th>
            <th>Limit</th>
            <th>Harga</th>
            <th>Option</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Kode</th>
            <th>Keterangan</th>
            <th>Jenis Obat</th>
            <th>Stok</th>
            <th>Limit</th>
            <th>Harga</th>
            <th>Option</th>
        </tr>
    </tfoot>
</table>

<script type="text/javascript" language="javascript" >
    var dataTable
    $(document).ready(function() {
        dataTable = $('#<?php echo $panel_title; ?>-grid').DataTable( {
            "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            "pageLength": 25,
            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"<?php echo base_url($panel_title."/ajax_grid"); ?>", // json datasource
                type: "POST",  // method  , by default get
                error: function(){  // error handling
                    $("#<?php echo $panel_title; ?>-grid").append('<tbody class="<?php echo $panel_title; ?>-grid-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                    $("#<?php echo $panel_title; ?>-grid_processing").css("display","none");
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ -1 ],
                    "orderable": false,
                    "width": "13%",
                    "searchable": false
                }
            ]
        } );
    } );

    function reload_table()
    {
        dataTable.ajax.reload(null,false); //reload datatable ajax
    }

    function print_stok() {
        var base_url = "<?php echo $panel_title; ?>/print_stok";
        window.location.href = "<?php echo base_url(); ?>"+base_url;
    }

    function add_function() {
        var base_url = "<?php echo $panel_title; ?>/form";
        window.location.href = "<?php echo base_url(); ?>"+base_url;
    }

    function edit_function(m,id) {
    	var base_url = "<?php echo $panel_title; ?>/form?m="+m+"&id="+id;
    	window.location.href = "<?php echo base_url(); ?>"+base_url;
    }

    function del_function(m,id) {
        if (confirm('Apakah Anda Yakin?'))
        {
            var base_url = "<?php echo $panel_title; ?>/delete_data?m="+m+"&id="+id;
            window.location.href = "<?php echo base_url(); ?>"+base_url;
        }
        else
        {}
    }
</script>
