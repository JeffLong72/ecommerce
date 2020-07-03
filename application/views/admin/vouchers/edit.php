<div class="admin_content">
    <h1>Edit voucher</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/vouchers/edit/'.$vouchers[0]['id']); ?>

    <input type="hidden" name="id" value="<?php echo $vouchers[0]['id'];?>">
    <div class="tab-container">

        <h2>Edit Voucher</h2>

        <p style="font-weight: bold">
            <label for="voucher_code">Voucher Code:</label>
            <?php echo $vouchers[0]['voucher_code'];?><br /><br />
        </p>

        <p>
            <label for="title">Active:</label>
            <select name="active">
                <?php $selected = (!empty($vouchers[0]['active'])) ? " selected='selected' " : "";?>
                <option value="0">Inactive</option>
                <option value="1" <?php echo $selected;?>>Active</option>
            </select>
            <br />
        </p>
    </div>

    <p>
        <label for="submit">&nbsp;</label>
        <input class="submit_button" type="submit" name="submit" value="Update voucher" />
    </p>
    </form>

</div>

