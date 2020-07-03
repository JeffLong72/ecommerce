<div class="admin_content">
    <h1>Admin keywords</h1>
    <hr>

    <p style="float:left;margin-bottom:10px;">
        Total <?php echo count($count);?> records found
    </p>

    <form style="float:right;margin-top:10px;" method="post"  action="<?php echo base_url();?>admin/reports/keywords">
        <strong>Search:</strong>
        <input type="text" name="keyword" placeholder="" value="<?php echo (!empty($keyword)) ? $keyword : "";?>">
        <input type="submit" name="submit" value=">>" style="width:50px;">
    </form>
    <br />
    <br />

    <form style="margin-top:20px;" method="post"  action="<?php echo base_url();?>admin/reports/keywords">
        <strong>Date start:</strong>
        <input style="width: 239px" type="text" class="datepicker" name="txtdate1" value="<?php echo (!empty($start_date_value)) ? $start_date_value : "";?>" readonly>
        <strong>Date end:</strong>
        <input style="width: 239px; margin-bottom: 20px"  type="text" class="datepicker" name="txtdate2" value="<?php echo (!empty($end_date_value)) ? $end_date_value : "";?>" readonly>
        <input type="submit" name="submit" value="confirm" style="width:70px;">
    </form>

    <div style="clear:both;"></div>

    <?php if($this->session->flashdata('msg')): ?>
        <p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
    <?php endif; ?>

    <table id="keywords_table" class="all_table display">
        <thead>
            <tr>
                <th style="width:10%;">Id</th>
                <th style="width:10%;">User Id</th>
                <th style="width:50%;">Keyword</th>
                <th style="width:15%;">Date Added</th>
            </tr>
        </thead>
        <tbody>
        <?php

        if(!empty($keywords)) {
            $x = 0;
            foreach ($keywords as $keyword_item){
                $style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
                ?>
                <tr style="<?php echo $style_row;?>">
                    <td style="text-align:center;">
                        <?php echo $keyword_item['id'];?>
                    </td>
                    <td style="text-align:center;width:200px;">
                        <?php echo $keyword_item['userid'];?>
                    </td>
                    <td style="text-align:center;width:200px;">
                        <?php echo $keyword_item['keyword'];?>
                    </td>
                    <td style="text-align:center;width:20px;">
                        <?php echo date("H:i:s d/m/Y", strtotime($keyword_item['date_added']));?>

                    </td>
                </tr>

            <?php
                $x++;
            }
        } else { ?>
                <tr>
                    <td colspan="7" style="text-align: center;">
                        No admin keywords has been found ...
                    </td>
                </tr>
        <?php } ?>

        </tbody>
    </table>

    <p class="pagination">
        <?php echo $pagination;?>
    </p>

</div>

<script>
    $( function() {
        $( ".datepicker" ).datepicker({dateFormat: 'dd/mm/yy'});
    } );
</script>