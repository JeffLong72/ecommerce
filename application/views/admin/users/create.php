<div class="admin_content">
    <h1>Add new customer</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/users/create'); ?>
    <div class="tab-container">

        <h2>Customer name</h2>
        <p>
            <label for="first_name">First Name:<span class="required">*</span></label>
            <input type="first_name" name="first_name" id="first_name" placeholder="" value="<?php echo (!empty($users['first_name'])) ? $users['first_name'] : "";?>" /><br />
        </p>
        <p>
            <label for="last_name">Last Name:<span class="required">*</span></label>
            <input type="last_name" name="last_name" id="last_name" placeholder="" value="<?php echo (!empty($users['last_name'])) ? $users['last_name'] : "";?>" /><br />
        </p><br /><br />
        <h2>Customer login details</h2>
        <p>
            <label for="email">Email:<span class="required">*</span></label>
            <input type="email" name="email" id="email" placeholder="" value="<?php echo (!empty($users['email'])) ? $users['email'] : "";?>" /><br />
        </p>
        <p>
            <label for="password">Password:<span class="required">*</span></label>
            <input type="password" name="password" id="password" placeholder="" value="<?php echo (!empty($users['password'])) ? $users['password'] : "";?>" /><br />
        </p>
        <p>
            <label for="confirm_password">Password Confirm:<span class="required">*</span></label>
            <input type="confirm_password" name="confirm_password" id="" placeholder="" value="<?php echo (!empty($users['confirm_password'])) ? $users['confirm_password'] : "";?>" /><br />
        </p><br /><br />
        <h2>Customer address details</h2>
        <p>
            <label for="house_number">House number:<span class="required"></span></label>
            <input type="house_number" name="house_number" id="house_number" placeholder="" value="<?php echo (!empty($users['house_number'])) ? $users['house_number'] : "";?>" /><br />
        </p>
        <p>
            <label for="street_name">Street name:<span class="required"></span></label>
            <input type="street_name" name="street_name" id="street_name" placeholder="" value="<?php echo (!empty($users['street_name'])) ? $users['street_name'] : "";?>" /><br />
        </p>
        <p>
            <label for="town_city">Town city:<span class="required"></span></label>
            <input type="town_city" name="town_city" id="town_city" placeholder="" value="<?php echo (!empty($users['town_city'])) ? $users['town_city'] : "";?>" /><br />
        </p>
        <p>
            <label for="county">County:<span class="required"></span></label>
            <input type="county" name="county" id="county" placeholder="" value="<?php echo (!empty($users['county'])) ? $users['county'] : "";?>" /><br />
        </p>
        <p>
            <label for="country">Country:<span class="required"></span></label>
            <input type="country" name="country" id="country" placeholder="" value="<?php echo (!empty($users['country'])) ? $users['country'] : "";?>" /><br />
        </p>
        <p>
            <label for="postcode">Postcode:<span class="required"></span></label>
            <input type="postcode" name="postcode" id="postcode" placeholder="" value="<?php echo (!empty($users['postcode'])) ? $users['postcode'] : "";?>" /><br />
        </p>
        <p>
            <label for="telephone">Telephone:<span class="required"></span></label>
            <input type="telephone" name="telephone" id="telephone" placeholder="" value="<?php echo (!empty($users['telephone'])) ? $users['telephone'] : "";?>" /><br />
        </p>
        <p>
            <label for="store_credit">Store Credit:<span class="required"></span></label>
            <input type="store_credit" name="store_credit" id="store_credit" placeholder="0.00" value="<?php echo (!empty($users['store_credit'])) ? $users['store_credit'] : "";?>" /><br />
        </p>
        <p>
            <label for="title">Active:</label>
            <select name="active">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <br />
        </p>

    </div>

    <p>
        <label for="submit">&nbsp;</label>
        <input class="submit_button" type="submit" name="submit" value="Create customer" />
    </p>
    </form>

</div>
