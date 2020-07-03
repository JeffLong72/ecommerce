<div class="admin_content">
	<h1>Edit review</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/reviews/edit/'.$reviews['id']); ?>
        <input type="hidden" name="id" value="<?php echo $reviews['id'];?>">
        <input type="hidden" name="site" value="<?php echo $reviews['site'];?>">
        <input type="hidden" name="admin_id" value="<?php echo $reviews['admin_id'];?>">
        <input type="hidden" name="date_approved" value="<?php echo $reviews['date_approved'];?>">
        <div class="tab-container">

                <h2>Reviews Page</h2>
				
                <p>
                    <label for="title">Name:<span class="required">*</span></label>
                    <input type="name" name="name" id="name" value="<?php echo $reviews['name'];?>" /><br />
                </p>
                <p>
                    <label for="title">Comments:</label>
                    <textarea name="comments" id="comments" style="margin: 0px; height: 208px; width: 402px;"><?php echo $reviews['comments'];?></textarea><br />
                </p>
                <p>
                    <label for="approved">Approved:</label>
                    <select name="approved">
                        <option value="0" <?php echo (empty($reviews['approved'])) ? " selected='selected' " : "";?>>No</option>
                        <option value="1" <?php echo (!empty($reviews['approved'])) ? " selected='selected' " : "";?>>Yes</option>
                    </select>
                    <br />
                </p>
                <p>
                    <label for="active">Active:</label>
                    <select name="active">
                        <option value="0" <?php echo (empty($reviews['active'])) ? " selected='selected' " : "";?>>Inactive</option>
                        <option value="1" <?php echo (!empty($reviews['active'])) ? " selected='selected' " : "";?>>Active</option>
                    </select>
                    <br />
                </p>
				
        </div>

        <p>
            <label for="submit">&nbsp;</label>
            <input class="submit_button" type="submit" name="submit" value="Update reviews" />
        </p>
    </form>

</div>

