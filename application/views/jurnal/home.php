<form action="<?php echo base_url("jurnal/cetak"); ?>" method="post" class="uk-form ukform-stacked">
    <div class="uk-form-row">
        <label class="uk-form-label" for="txt_tgl_1">Tanggal</label>
        <div class="uk-form-controls">
            <input type="text" name="txt_tgl_1" data-uk-datepicker="{format:'YYYY-MM-DD'}" placeholder="Tanggal 1"> -
            <input type="text" name="txt_tgl_2" data-uk-datepicker="{format:'YYYY-MM-DD'}" placeholder="Tanggal 2">
            <button type="submit" name="btn_cetak" value="cetak" class="uk-button">Cetak</button>
        </div>
    </div>
</form>
