<div class="admin_content">
    <h1>View all Administrators</h1>
    <hr>
    <p style="float:left;margin-bottom:10px;">
        Total <?php echo count($count);?> records found
    </p>

    <form style="float:right;margin-top:10px;" method="post"  action="<?php echo base_url();?>admin/administrators/all">
        <strong>Search:</strong>
        <input type="text" name="keyword" placeholder="" value="<?php echo (!empty($keyword)) ? $keyword : "";?>">
        <input type="submit" name="submit" value=">>" style="width:50px;">
    </form>

    <div style="clear:both;"></div>

    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>

    <table id="delivery_table" class="all_table display">
        <thead>
        <tr>
            <th style="width:5%;">Id</th>
            <th style="width:15%;">Username</th>
            <th style="width:20%;">Last Login</th>
            <th style="width:5%;">Login Attempts</th>
            <th style="width:5%;">Last Login Attempt</th>
            <th style="width:5%;">Created By Admin ID</th>
            <th style="width:5%;">Active</th>
            <th style="width:5%;">Edit</th>
            <th style="width:5%;">Assign Permissions</th>
            <th style="width:5%;">Delete</th>
        </tr>
        </thead>
        <tbody>
            <?php
                if(!empty($administrators)) {
                    $x = 0;
                    foreach ($administrators as $administrator){
                    $style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
             ?>
                        <tr style="<?php echo $style_row;?>">
                            <td style="text-align:center;">
                                <?php echo $administrator['id'];?>
                            </td>
                            <td style="text-align:center;width:200px;">
                                <?php echo $administrator['username'];?>
                            </td>
                            <td style="text-align:center;width:20px;">
                                <?php echo (! empty ($administrator['last_login'])) ? date("H:i:s d/m/Y", strtotime($administrator['last_login'])) : "Never";?>
                            </td>
                            <td style="text-align:center;width:20px;">
                                <?php echo $administrator['login_attempts'];?>
                            </td>
                            <td style="text-align:center;width:20px;">
                                <?php echo (! empty ($administrator['last_login_attempt'])) ? date("H:i:s d/m/Y", strtotime($administrator['last_login_attempt'])) : "Never";?>
                            </td>
                            <td style="text-align:center;width:20px;">
                                <?php echo $_SESSION['admin']['id'];?>
                            </td>
                            <td style="text-align:center;width:20px">
                                <span style="display:none;"><?php echo $administrator['active'];?></span>
                                <?php $active = (!empty($administrator['active']) && $administrator['active'] == 1) ? "active" : "inactive";?>
                                <img src="<?php echo base_url('assets/images/'.$active.'.png');?>">

                            </td>
                            <td style="text-align:center;width:20px;">
                                <a href="<?php echo base_url('admin/administrators/edit/'.$administrator['id']);?>">
                                    <img src="<?php echo base_url('assets/images/edit.png');?>" border="0">
                                </a>
                            </td>
                            <td style="text-align:center;width:20px;">
                                <a href="<?php echo base_url('admin/administrators/admin_permissions/'.$administrator['id']);?>">
                                    <img src="<?php echo base_url('assets/images/edit.png');?>" border="0">
                                </a>
                            </td>
                            <td style="text-align:center;"><img src="<?php echo base_url('assets/images/delete.png');?>"></td>
                        </tr>

			<?php
				$x++;
			}
		} else { ?>
				<tr>
					<td colspan="15" style="text-align: center;">
						No administrators found ...
					</td>
				</tr>
		<?php } ?>

		</tbody>
	</table>

	<p class="pagination">
		<?php echo $pagination;?>
	</p>

</div>