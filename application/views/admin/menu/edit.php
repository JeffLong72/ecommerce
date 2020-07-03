<div class="admin_content">
    <h1>Edit menu promo item</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

    <?php echo form_open('admin/menu/edit/'.$menu_promo['id']); ?>
    <input type="hidden" name="id" value="<?php echo $menu_promo['id'];?>">
    <input type="hidden" name="site" value="<?php echo $menu_promo['site'];?>">
    <div class="tab-container">

        <div id="tab-1">
            <br>

            <p>
                <label for="title">Menu html:<span class="required">*</span></label><br />
                <textarea type="menu_html" name="menu_html" id="menu_html"><?php echo $menu_promo['menu_html'];?></textarea><br />
            </p>

            <p>
                <label for="title">Active:</label>
                <select name="active">
                    <?php $selected = (!empty($menu_promo['menu_html_active'])) ? " selected='selected' " : "";?>
                    <option value="0">Inactive</option>
                    <option value="1" <?php echo $selected;?>>Active</option>
                </select>
                <br />
            </p>
        </div>
    </div>

    <p>
        <label for="submit">&nbsp;</label>
        <input class="submit_button" type="submit" name="submit" value="Update menu promo" />
    </p>
    </form>

</div>

<script>
    // set ckeditor field(s)
    var editor = CKEDITOR.replace( 'menu_html', {
        height: 400
    } );
    CKFinder.setupCKEditor( editor );
</script>