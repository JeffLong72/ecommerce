<div class="admin_content">
    <h1>View all deliveries</h1>
    <hr>
    <p style="float:left;margin-bottom:10px;">
        Total <?php echo count($count);?> records found
    </p>

    <form style="float:right;margin-top:10px;" method="post"  action="<?php echo base_url();?>admin/delivery/all">
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
            <th style="width:50px;">Option</th>
            <th style="width:15%;">Cost</th>
            <th style="width:5%;">Active</th>
            <th style="width:5%;">Edit</th>
            <th style="width:5%;">Delete</th>
        </tr>
        </thead>
        <tbody>
            <?php
                if(!empty($delivery)) {
                    $x = 0;
                    foreach ($delivery as $deliver){
                    $style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
             ?>
                        <tr style="<?php echo $style_row;?>">
                            <td style="text-align:center;">
                                <?php echo $deliver['id'];?>
                            </td>
                            <td style="text-align:center;width:200px;">
                                <?php echo $deliver['option'];?>
                            </td>
                            <td style="text-align:center;width:20px;">
                                <?php echo $deliver['cost'];?>
                            </td>
                            <td style="text-align:center;width:20px">
                                <span style="display:none;"><?php echo $deliver['active'];?></span>
                                <?php $active = (!empty($deliver['active']) && $deliver['active'] == 1) ? "active" : "inactive";?>
                                <img src="<?php echo base_url('assets/images/'.$active.'.png');?>">

                            </td>
                            <td style="text-align:center;width:20px;">
                                <a href="<?php echo base_url('admin/delivery/edit/'.$deliver['id']);?>">
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
						No results found
					</td>
				</tr>
		<?php } ?>

		</tbody>
	</table>

	<p class="pagination">
		<?php echo $pagination;?>
	</p>

</div>