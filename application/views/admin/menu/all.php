<div class="admin_content">
    <h1>View all menu promos</h1>
    <hr>
    <p style="float:left;margin-bottom:10px;">
        Total <?php echo count($count);?> records found
    </p>

    <form style="float:right;margin-top:10px;" method="post"  action="<?php echo base_url();?>admin/menu/all">
        <strong>Search:</strong>
        <input type="text" name="keyword" placeholder="" value="<?php echo (!empty($keyword)) ? $keyword : "";?>">
        <input type="submit" name="submit" value=">>" style="width:50px;">
    </form>

    <div style="clear:both;"></div>

    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>

    <table id="menu_table" class="all_table display">
        <thead>
        <tr>
            <th style="width:5%;">Id</th>
            <th style="width:20px;">Category</th>
            <th style="width:60%;">Menu HTML</th>
            <th style="width:5%;">Active</th>
            <th style="width:5%;">Edit</th>
            <th style="width:5%;">Delete</th>
        </tr>
        </thead>
        <tbody>
            <?php
                if(!empty($menus)) {
                    $x = 0;
                    foreach ($menus as $menu){
                    $style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
            ?>

                        <tr style="<?php echo $style_row;?>">
                            <td style="text-align:center;">
                                <?php echo $menu['id'];?>
                            </td>
                            <td style="text-align:center;width:200px;">
                                <?php echo $menu['category_name'];?>
                            </td>
                            <td style="text-align:center;width:20px;">
                                <?php echo $menu['menu_html'];?>
                            </td>
                            <td style="text-align:center;width:20px">
                                <span style="display:none;"><?php echo $menu['active'];?></span>
                                <?php $active = (!empty($menu['active']) && $menu['menu_html_active'] == 1) ? "active" : "inactive";?>
                                <img src="<?php echo base_url('assets/images/'.$active.'.png');?>">

                            </td>
                            <td style="text-align:center;width:20px;">
                                <a href="<?php echo base_url('admin/menu/edit/'.$menu['id']);?>">
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
					<td colspan="7" style="text-align: center;">
						No menu promos has been found ...
					</td>
				</tr>
		<?php } ?>

		</tbody>
	</table>

	<p class="pagination">
		<?php echo $pagination;?>
	</p>

</div>