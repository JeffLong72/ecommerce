<div class="admin_content">
    <h1>Edit user</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/users/edit/'.$users[0]['id']); ?>
    <input type="hidden" name="id" value="<?php echo $users[0]['id'];?>">
    <input type="hidden" name="option" value="<?php echo $users[0]['first_name'];?>">
    <div class="tab-container">

            <h2>Customer name</h2>

            <p>
                <label for="first_name">First Name:</label>
                <input type="first_name" name="first_name" id="first_name" value="<?php echo $users[0]['first_name'];?>" /><br />
            </p>
            <p>
                <label for="last_name">Last Name:</label>
                <input type="last_name" name="last_name" id="last_name" value="<?php echo $users[0]['last_name'];?>" /><br />
            </p><br /><br />
            <h2>Customer login details</h2>
            <p>
                <label for="email">Email:<span class="required">*</span></label>
                <input type="email" name="email" id="email" value="<?php echo $users[0]['email'];?>" /><br />
            </p>
            <p>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value="" /><br />
            </p>
            <p>
                <label for="confirm_password">Password Confirm:</label>
                <input type="password" name="confirm_password" id="confirm_password" value="" /><br />
            </p><br /><br />
            <h2>Customer address details</h2>
            <p>
                <label for="house_number">House number:<span class="required"></span></label>
                <input type="house_number" name="house_number" id="house_number" value="" /><br />
            </p>
            <p>
                <label for="street_name">Street name:<span class="required"></span></label>
                <input type="street_name" name="street_name" id="street_name" value="" /><br />
            </p>
            <p>
                <label for="town_city">Town city:<span class="required"></span></label>
                <input type="town_city" name="town_city" id="town_city" value="" /><br />
            </p>
            <p>
                <label for="county">County:<span class="required"></span></label>
                <input type="county" name="county" id="county" value="" /><br />
            </p>
            <p>
                <label for="country">Country:<span class="required"></span></label>
                <input type="country" name="country" id="country" value="" /><br />
            </p>
            <p>
                <label for="postcode">Postcode:<span class="required"></span></label>
                <input type="postcode" name="postcode" id="postcode" value="" /><br />
            </p>
            <p>
                <label for="telephone">Telephone:<span class="required"></span></label>
                <input type="telephone" name="telephone" id="telephone" value="" /><br />
            </p>
            <p>
                <label for="store_credit">Store Credit:<span class="required"></span></label>
                <input type="store_credit" name="store_credit" id="store_credit" value="" placeholder="0.00"/><br />
            </p>
            <p>
                <label for="title">Active:</label>
                <select name="active">
                    <?php $selected = (!empty($users['active'])) ? " selected='selected' " : "";?>
                    <option value="0">Inactive</option>
                    <option value="1" <?php echo $selected;?>>Active</option>
                </select>
                <br />
            </p>
    </div>

    <p>
        <label for="submit">&nbsp;</label>
        <input class="submit_button" type="submit" name="submit" value="Update user" />
    </p>
    </form>

</div>

