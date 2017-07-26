<?php
echo validation_errors();
?>

<form class="uk-form uk-form-horizontal" method="post" action="<?php echo base_url("auth/form_input"); ?>">
    <div class="uk-form-row">
        <label for="txt_user_name" class="uk-form-label">Username</label>
        <div class="uk-form-controls">
            <input class="uk-form-width-medium" type="text" id="txt_user_name" name="txt_user_name" placeholder="Nama" required="required">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_user_password" class="uk-form-label">Password</label>
        <div class="uk-form-controls">
            <input type="password" name="txt_user_password" id="txt_user_password" placeholder="Password">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_user_email" class="uk-form-label">Email</label>
        <div class="uk-form-controls">
            <input type="email" name="txt_user_email" id="txt_user_email" placeholder="Email">
        </div>
    </div>
    <div class="uk-form-row">
        <label for="txt_user_level" class="uk-form-label">Level</label>
        <div class="uk-form-controls">
            <select name="opt_user_level" id="opt_user_level">
                <?php
                foreach ($qry_ul as $row_ul)
                {
                    ?>
                    <option value="<?php echo $row_ul->ul_id; ?>"><?php echo strtoupper($row_ul->ul_ket); ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="uk-form-row uk-form-row-foot">
        <span class="uk-form-label"></span>
        <button type="submit" name="btn_simpan" id="btn_simpan" value="btn_simpan" class="uk-button uk-button-primary">Simpan</button>
        <a href="<?php echo base_url("auth/list_user"); ?>" class="uk-button uk-button-primary">List User</a>
    </div>
</form>
