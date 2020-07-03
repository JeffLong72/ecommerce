<div class="admin_content">
    <h1>Edit currency</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/currency/edit/'.$currency['id']); ?>
    <input type="hidden" name="id" value="<?php echo $currency['id'];?>">
    <input type="hidden" name="option" value="<?php echo $currency['currency_text'];?>">
    <div class="tab-container">

            <h2>Currency Page</h2>

            <p>
                <label for="title">Currency Code:<span class="required">*</span></label>
                <input type="currency_text" name="currency_text" id="currency_text" value="<?php echo $currency['currency_text'];?>" /><br />
            </p>
            <p>
                <label for="title">Currency Symbol:<span class="required">*</span></label>
                <input type="currency_html" name="currency_html" id="currency_html" value="<?php echo $currency['currency_html'];?>" /><br />
            </p>
            <p>
                <label for="title">Currency Rate:<span class="required">*</span></label>
                <input type="currency_rate" name="currency_rate" id="currency_rate" value="<?php echo $currency['currency_rate'];?>" /><br />
            </p>
            <p>
                <label for="title">Active:</label>
                <select name="active">
                    <?php $selected = (!empty($currency['active'])) ? " selected='selected' " : "";?>
                    <option value="0">Inactive</option>
                    <option value="1" <?php echo $selected;?>>Active</option>
                </select>
                <br />
            </p>
    </div>

    <p>
        <label for="submit">&nbsp;</label>
        <input class="submit_button" type="submit" name="submit" value="Update currency" />
    </p>
    </form>

</div>

