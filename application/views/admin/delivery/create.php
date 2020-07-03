<div class="admin_content">
    <h1>Add new delivery</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/delivery/create'); ?>
    <div class="tab-container">

            <h2>Delivery Page</h2>
            <p>
                <label for="title">Option:<span class="required">*</span></label>
                <input type="option" name="option" id="option" placeholder="Describe delivery option, eg. Next day" value="<?php echo (!empty($delivery['option'])) ? $delivery['option'] : "";?>" /><br />
            </p>
            <p>
                <label for="title">Cost:<span class="required">*</span></label>
                <input type="cost" name="cost" id="cost" placeholder="0.00" value="<?php echo (!empty($delivery['cost'])) ? $delivery['cost'] : "";?>" /><br />
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
        <input class="submit_button" type="submit" name="submit" value="Create delivery option" />
    </p>
    </form>

</div>
