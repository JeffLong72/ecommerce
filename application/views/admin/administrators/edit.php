<div class="admin_content">
    <h1>Edit administrator</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/administrators/edit/'.$administrators['id']); ?>
    <input type="hidden" name="id" value="<?php echo $administrators['id'];?>">
    <input type="hidden" name="option" value="<?php echo $administrators['username'];?>">
	<input type="hidden" name="admin_created_by" id="admin_created_by" value="<?php $_SESSION['admin']['id'];?>" />
    <div class="tab-container">

            <h2>Edit details</h2>
			<p>
				Enter the users details below.
			</p>
            <p>
                <label for="username">Username:<span class="required">*</span></label>
                <input type="username" name="username" id="username" value="<?php echo $administrators['username'];?>" /><br />
            </p>
            <p>
                <label for="password">Password:<span class="required">*</span></label>
                <input type="password" name="password" id="password" value="" /><br />
            </p>
            <p>
                <label for="confirm_password">Password Confirm:<span class="required">*</span></label>
                <input type="password" name="confirm_password" id="confirm_password" value="" /><br />
            </p>
            <p>
                <label for="email">Email:<span class="required">*</span></label>
                <input type="text" name="email" id="email" value="<?php echo $administrators['email'];?>" /><br />
            </p>
			<p>
				For login security, choose a phrase at least 7 characters long that contains both letters and numbers.<br />
			</p>
            <p>
                <label for="security_pass">Security Pass:<span class="required">*</span></label>
                <input type="text" name="security_pass" id="security_pass" value="" />
				<br />
            </p>
			<p>
				Activate this user account?
			</p>
            <p>
                <label for="title">Active:</label>
                <select name="active">
                    <?php $selected = (!empty($administrators['active'])) ? " selected='selected' " : "";?>
                    <option value="0">Inactive</option>
                    <option value="1" <?php echo $selected;?>>Active</option>
                </select>
                <br />
            </p>
    </div>

    <p>
        <label for="submit">&nbsp;</label>
        <input class="submit_button" type="submit" name="submit" value="Update Admin User" />
    </p>
    </form>

</div>

<style>
.admin_content label {
	width: 145px;
}
</style>