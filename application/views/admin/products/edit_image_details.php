<div class="admin_content">
    <h1>Edit image details</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata">
			<?php echo $this->session->flashdata('msg'); ?>
		</p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/products/edit_image_details/'.$image['id']); ?>
    <input type="hidden" name="id" value="<?php echo $image['id'];?>">
    <div class="tab-container">

            <h2>Edit Details</h2>

            <p>
                <label for="title">Alt:<span class="required">*</span></label>
                <input type="alt" name="alt" id="alt" value="<?php echo $image['alt'];?>" /><br />
            </p>
            <p>
                <label for="title">Title:<span class="required">*</span></label>
                <input type="title" name="title" id="title" value="<?php echo $image['title'];?>" /><br />
            </p>
            <p>
                <label for="title">Active:</label>
                <select name="active">
                    <?php $selected = (!empty($image['active'])) ? " selected='selected' " : "";?>
                    <option value="0">Inactive</option>
                    <option value="1" <?php echo $selected;?>>Active</option>
                </select>
                <br />
            </p>

    </div>

    <p>
        <label for="submit">&nbsp;</label>
        <input class="submit_button" type="submit" name="submit" value="Update Image details" />
    </p>
    </form>

</div>
