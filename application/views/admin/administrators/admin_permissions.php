<div class="admin_content">
    <h1>Edit permissions</h1>
    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>
    <hr>
    <span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

   <form action="<?php echo current_url();?>" method="POST" name="edit_permissions_form">
       <input type="hidden" hidden name="admin_id" value="<?php echo $admin_id;?>">
        <div class="tab-container">

            <div style="float:left;width:120px;font-weight: bold;">Administrators</div>
            <?php

            $current_section = "administrators";

            foreach($admin_permissions as $admin_permission) {

                $checked = "";
                foreach ( $admin_current_permissions as $admin_current_permission) {

                    if( empty( $checked ) && ! empty( $admin_current_permission['active'] ) && ( $admin_current_permission['section'] == $admin_permission['section']) && ($admin_current_permission['sub_section'] == $admin_permission['sub_section']) ) {
                        $checked = " checked='checked' ";
                    }
                }

                if( $admin_permission['section'] == $current_section ) {

                   ?>
                    <div style="float:left;width:170px;"><input class="checkbox" style="margin-right:10px;" type="checkbox" name="<?php echo $admin_permission['section'];?>[]" value="<?php echo $admin_permission['sub_section'];?>" <?php echo $checked;?> ><?php echo ucwords(str_replace("_", " ", $admin_permission['sub_section']));?></div>
                    <?php
                }
                else {

                    ?>

                    <div style="clear:both;"></div>
                    <div class="section_title"><?php echo ucfirst($admin_permission['section']);?></div>
                    <div style="float:left;width:170px;"><input class="checkbox" style="margin-right:10px;" type="checkbox" name="<?php echo $admin_permission['section'];?>[]" value="<?php echo $admin_permission['sub_section'];?>" <?php echo $checked;?> ><?php echo ucwords(str_replace("_", " ", $admin_permission['sub_section']));?></div>

                    <?php

                    $current_section = $admin_permission['section'];
                }
            }
            ?>

        </div><br /><br />

        <p>
            <label for="submit">&nbsp;</label>
            <input class="submit_button" type="submit" name="submit" value="Update Permissions" />
        </p>
    </form>

</div>

<style>
  .submit_button {
      text-align: center;
  }

.checkbox {
    width: 24px;
    margin-bottom: 7px;
    vertical-align: middle;
}

.section_title {
    float:left;
    width:120px;
    font-weight: bold;
}

</style>