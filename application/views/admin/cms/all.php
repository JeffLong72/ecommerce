<div class="admin_content">

	<h1>View CMS pages</h1>
	<hr>
	
	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>
	
	<table id="cms_pages_table" class="all_table display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Date</th>				
                <th>Active</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
		<tbody>
	
		<?php
            $x = 0;
            foreach ($cms_pages as $cms_page){ $style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : ""; ?>

                    <tr  style="<?php echo $style_row;?>">
                        <td style="text-align:center;width:50px;"><?php echo $cms_page['id'];?></td>
                        <td>
                            <a href="<?php echo base_url().$cms_page['slug'];?>.html" target="_blank">
                                <?php echo $cms_page['title'];?>
                            </a>
                        </td>
                        <td style="text-align:center;width:50px;"><?php echo date("d/m/Y", strtotime($cms_page['last_updated']));?></td>
                        <td style="text-align:center;width:50px">
                            <span style="display:none;"><?php echo $cms_page['active'];?></span>
                            <?php $active = (!empty($cms_page['active'])) ? "active" : "inactive";?>
                            <img src="<?php echo base_url('assets/images/'.$active.'.png');?>">
                        </td>
                        <td style="text-align:center;width:50px;">
                            <a href="<?php echo base_url('admin/cms/edit/'.$cms_page['id']);?>">
                                <img src="<?php echo base_url('assets/images/edit.png');?>" border="0">
                            </a>
                        </td>
                        <td style="text-align:center;width:50px;"><img src="<?php echo base_url('assets/images/delete.png');?>"></td>
                    </tr>

            <?php }
                $x++;
        if(empty($products)) {?>
        <tr>
            <td colspan="7" style="text-align: center;">
                No CMS pages has been added yet...
            </td>
        </tr>
        <?php } ?>
		</tbody>
	</table>
	
</div>

<style>
table a {
	color: #000 !important;
}
#cms_pages_table_wrapper {
	background-color: #fff;
	padding: 10px;
	border: 1px solid #ccc;
}
</style>