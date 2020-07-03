<div class="admin_content">
    <h1>View all reviews</h1>
    <hr>
    <p style="float:left;margin-bottom:10px;">
        Total <?php echo count($count);?> records found
    </p>

    <form style="float:right;margin-top:10px;" method="post"  action="<?php echo base_url();?>admin/reviews/all">
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
            <th style="width:3%;">Id</th>
            <th style="width:10%;">SKU</th>
            <th style="width:5%;">Name</th>
            <th style="width:10%;">Headline</th>
            <th style="width:15%;">Comments</th>
            <th style="width:3%;">Rating</th>
            <th style="width:5%;">Date Submitted</th>
            <th style="width:3%;">Admin Id</th>
            <th style="width:5%;">Date Approved</th>
            <th style="width:4%;">Approved</th>
            <th style="width:4%;">Active</th>
            <th style="width:4%;">Edit</th>
            <th style="width:4%;">Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($reviews)) {
            $x = 0;
            foreach ($reviews as $review){
                $style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
                ?>
                <tr style="<?php echo $style_row;?>">
                    <td style="text-align:center;">
                        <?php echo $review['id'];?>
                    </td>
                    <td style="text-align:center;width:200px;">
                        <?php echo $review['sku'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $review['name'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $review['headline'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $review['comments'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo $review['rating'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo date("H:i:s  d/m/Y", strtotime($review['date_submitted']));?>
                    </td>
                    <td style="text-align:center;width:10px;">
                        <?php echo $review['admin_id'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo date("H:i:s  d/m/Y", strtotime($review['date_approved']));?>
                    </td>
                    <td style="text-align:center;width:10px;">
                        <?php echo $approved = (!empty($review['approved'])) ? "Yes" : "No";;?>
                    </td>
                    <td style="text-align:center;width:20px">
                        <span style="display:none;"><?php echo $review['active'];?></span>
                        <?php $active = (!empty($review['active']) && $review['active'] == 1) ? "active" : "inactive";?>
                        <img src="<?php echo base_url('assets/images/'.$active.'.png');?>">

                    </td>
                    <td style="text-align:center;width:20px;">
                        <a href="<?php echo base_url('admin/reviews/edit/'.$review['id']);?>">
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
                <td colspan="13" style="text-align: center;">
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