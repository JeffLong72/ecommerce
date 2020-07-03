<div class="admin_content">
    <h1>View all currencies</h1>
    <hr>
    <p style="float:left;margin-bottom:10px;">
        Total <?php echo count($count);?> records found
    </p>

    <form style="float:right;margin-top:10px;" method="post"  action="<?php echo base_url();?>admin/currency/all">
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
            <th style="width:15px;">Currency Code</th>
            <th style="width:15%;">Currency Symbol</th>
            <th style="width:15%;">Currency Rate</th>
            <th style="width:20%;">Last Updated</th>			
            <th style="width:5%;">Active</th>
            <th style="width:5%;">Edit</th>
            <th style="width:5%;">Delete</th>
        </tr>
        </thead>
        <tbody>
            <?php
                if(!empty($currency)) {
                    $x = 0;
                    foreach ($currency as $currency_key){
                    $style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : ""; ?>
                        <tr style="<?php echo $style_row;?>">
                            <td style="text-align:center;">
                                <?php echo $currency_key['id'];?>
                            </td>
                            <td style="text-align:center;width:200px;">
                                <?php echo $currency_key['currency_text'];?>
                            </td>
                            <td style="text-align:center;width:200px;">
                                <?php echo $currency_key['currency_html'];?>
                            </td>
                            <td style="text-align:center;width:20px;">
                                <?php echo $currency_key['currency_rate'];?>
                            </td>
                            <td style="text-align:center;width:20px;">
                                <?php echo date("H:i:s d/m/Y", strtotime($currency_key['last_updated']));?>
                            </td>							
                            <td style="text-align:center;width:20px">
                                <span style="display:none;"><?php echo $currency_key['active'];?></span>
                                <?php $active = (!empty($currency_key['active']) && $currency_key['active'] == 1) ? "active" : "inactive";?>
                                <img src="<?php echo base_url('assets/images/'.$active.'.png');?>">

                            </td>
                            <td style="text-align:center;width:20px;">
                                <a href="<?php echo base_url('admin/currency/edit/'.$currency_key['id']);?>">
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
                        <td colspan="9" style="text-align: center;">
                            No currencies has been set ...
                        </td>
                    </tr>
            <?php } ?>

		</tbody>
	</table>

	<p class="pagination">
		<?php echo $pagination;?>
	</p>

</div>