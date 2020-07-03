<div class="admin_content">
    <h1>View all vouchers</h1>
    <hr>
    <p style="float:left;margin-bottom:10px;">
        Total <?php echo count($count);?> records found
    </p>

    <form style="float:right;margin-top:10px;" method="post"  action="<?php echo base_url();?>admin/vouchers/all">
        <strong>Search:</strong>
        <input type="text" name="keyword" placeholder="" value="<?php echo (!empty($keyword)) ? $keyword : "";?>">
        <input type="submit" name="submit" value=">>" style="width:50px;">
    </form>

    <div style="clear:both;"></div>

    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>

    <table id="voucher_table" class="all_table display">
        <thead>
        <tr>
            <th style="width:3%;">Id</th>
            <th style="width:8%;">Code</th>
            <th style="width:10%;">Description</th>
            <th style="width:4%;">Money Off</th>
            <th style="width:5%;">Percent Off</th>
            <th style="width:7%;">Category</th>
            <th style="width:15%;">Product</th>
            <th style="width:7%;">Brand</th>
            <th style="width:4%;">Used</th>
            <th style="width:4%;">Max Uses</th>
            <th style="width:8%;">Expires Date</th>
            <th style="width:3%;">Admin Id</th>
            <th style="width:8%;">Date Created</th>
            <th style="width:3%;">Active</th>
            <th style="width:5%;">Edit</th>
            <th style="width:3%;">Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($vouchers)) {
            $x = 0;
            foreach ($vouchers as $voucher){
                $style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
                ?>
                <tr style="<?php echo $style_row;?>">
                    <td style="text-align:center;">
                        <?php echo $voucher['id'];?>
                    </td>
                    <td style="text-align:center;width:200px;">
                        <?php echo $voucher['voucher_code'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $voucher['voucher_description'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
						<?php echo ( $voucher['voucher_money_off'] != "0.00" )  ? $voucher['voucher_money_off'] : "-";?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo ( ! empty ( $voucher['voucher_percent_off'] ) )  ? $voucher['voucher_percent_off'] : "-";?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $voucher['voucher_category'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $voucher['voucher_product'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $voucher['voucher_brand'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $voucher['voucher_used'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $voucher['voucher_max_uses'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo ( ! empty ( $voucher['voucher_expires_date'] ) ) ? date("d/m/Y", strtotime($voucher['voucher_expires_date'])) : "";?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $voucher['admin_id'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo date("H:i:s d/m/Y", strtotime($voucher['date_created']));?>
                    </td>
                    <td style="text-align:center;width:20px">
                        <span style="display:none;"><?php echo $voucher['active'];?></span>
                        <?php $active = (!empty($voucher['active']) && $voucher['active'] == 1) ? "active" : "inactive";?>
                        <img src="<?php echo base_url('assets/images/'.$active.'.png');?>">

                    </td>
                    <td style="text-align:center;width:20px;">
                        <a href="<?php echo base_url('admin/vouchers/edit/'.$voucher['id']);?>">
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
                <td colspan="16" style="text-align: center;">
                    No vouchers has been set ...
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>

    <p class="pagination">
        <?php echo $pagination;?>
    </p>

</div>
