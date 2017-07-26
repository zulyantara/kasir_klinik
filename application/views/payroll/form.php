<?php
echo validation_errors();
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url($panel_title."/form"); ?>">
    <div class="uk-form-row">
        <label for="opt_payroll_staff" class="uk-form-label">Staff</label>
        <div class="uk-form-controls">
            <select name="opt_payroll_staff" id="opt_payroll_staff" required="required">
                <?php
                foreach ($qry_staff as $row_staff)
                {
                    ?>
                    <option value="<?php echo $row_staff->staff_id; ?>"><?php echo $row_staff->staff_nama; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <?php
        if ($qry_staff !== FALSE)
        {
            ?>
            <button type="submit" name="btn_simpan" id="btn_simpan" value="btn_simpan" class="uk-button uk-button-primary">Simpan</button>
            <?php
        }
        ?>
        <a href="<?php echo base_url($panel_title); ?>" class="uk-button uk-button-primary">List <?php echo humanize($panel_title); ?></a>
    </div>
</form>
