<div class="admin_content">
	<h1>Edit delivery</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/delivery/edit/'.$delivery['id']); ?>
        <input type="hidden" name="id" value="<?php echo $delivery['id'];?>">
        <input type="hidden" name="option" value="<?php echo $delivery['option'];?>">
        <div class="tab-container">

                <h2>Delivery Page</h2>
				
                <p>
                    <label for="title">Option:<span class="required">*</span></label>
                    <input type="option" name="option" id="option" value="<?php echo $delivery['option'];?>" /><br />
                </p>
                <p>
                    <label for="title">Cost:<span class="required">*</span></label>
                    <input type="cost" name="cost" id="cost" value="<?php echo $delivery['cost'];?>" /><br />
                </p>
                <p>
                    <label for="title">Active:</label>
                    <select name="active">
                        <?php $selected = (!empty($delivery['active'])) ? " selected='selected' " : "";?>
                        <option value="0">Inactive</option>
                        <option value="1" <?php echo $selected;?>>Active</option>
                    </select>
                    <br />
                </p>
				
        </div>

        <p>
            <label for="submit">&nbsp;</label>
            <input class="submit_button" type="submit" name="submit" value="Update delivery" />
        </p>
    </form>

</div>

