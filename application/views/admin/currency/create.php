<div class="admin_content">
    <h1>Add new currency</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/currency/create'); ?>
    <div class="tab-container">

            <h2>Delivery Page</h2>
            <p>
                <label for="title">Currency Code:<span class="required">*</span></label>
                <input type="currency_text" name="currency_text" id="currency_text" placeholder="example: GBP" value="<?php echo (!empty($currency['currency_text'])) ? $currency['currency_text'] : "";?>" /><br />
            </p>
            <p>
                <label for="title">Currency Symbolt:<span class="required">*</span></label>
                <input type="currency_html" name="currency_html" id="currency_html" placeholder="example: £" value="<?php echo (!empty($currency['currency_html'])) ? $currency['currency_html'] : "";?>" /><br />
            </p>
            <p>
                <label for="title">Currency Rate:<span class="required">*</span></label>
                <input type="currency_rate" name="currency_rate" id="currency_rate" placeholder="0.00" value="<?php echo (!empty($currency['currency_rate'])) ? $currency['currency_rate'] : "";?>" /><br />
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
        <input class="submit_button" type="submit" name="submit" value="Create currency option" />
    </p>
    </form>

</div>
