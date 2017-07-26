<?php
if(isset($message))
{
    echo "<div class=\"uk-alert uk-alert-danger\">".$message."</div>";
}
?>
<form class="uk-form uk-form-horizontal" action="<?php echo base_url("auth/change_password"); ?>" method="post">
    <div class="uk-form-row">
        <label class="uk-form-label" for="txt_old_password">Old Password</label>
        <div class="uk-form-controls">
            <input type="password" name="txt_old_password" id="txt_old_password" placeholder="Old Password" class="uk-form-width-large" autofocus="autofocus">
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for="txt_new_password">New Password</label>
        <div class="uk-form-controls">
            <input type="password" name="txt_new_password" id="txt_new_password" placeholder="New Password" class="uk-form-width-large">
        </div>
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for="txt_confirm_password">Confirm Password</label>
        <div class="uk-form-controls">
            <input type="password" name="txt_confirm_password" id="txt_confirm_password" placeholder="Confirm Password" class="uk-form-width-large">
        </div>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-controls">
            <button type="submit" name="btn_update" id="btn_update" value="btn_update" class="uk-button uk-button-primary">Update</button>
        </div>
    </div>
</form>