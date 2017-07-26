<form action="<?php echo base_url($panel_title."/cetak"); ?>" method="post" class="uk-form ukform-stacked">
    <div class="uk-form-row">
        <div class="uk-form-controls">
            <select name="opt_bulan" autofocus="autofocus" required="required">
                <?php
                $arr_bulan = array("01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agusuts","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember");
                foreach ($arr_bulan as $key => $value)
                {
                    ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php
                }
                ?>
            </select>
            <button type="submit" name="btn_cetak" value="cetak" class="uk-button">Cetak</button>
        </div>
    </div>
</form>
