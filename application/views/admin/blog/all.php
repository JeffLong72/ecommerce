<div class="admin_content">

	<h1>View all blogs</h1>
	<hr>
	
	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>
	
	<table id="blogs_table" class="all_table display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
				<th>Image</th>
                <th>Title</th>
                <th>Date</th>
                <th>Active</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
		<tbody>
	
		<?php
        if(!empty($blogs)) {
            $x = 0;
            foreach ($blogs as $blog){
                $style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
                ?>

                <tr style="<?php echo $style_row;?>">
                    <td style="text-align:center;width:50px;"><?php echo $blog['id'];?></td>
                    <td style="text-align:center;width:50px;">
                        <?php echo (!empty($blog['image'])) ? "<img style='width:100px;' src='".base_url('uploads/'.$_SESSION['admin']['site'].'/blogs/thumbs/'.$blog['image'])."'>" : "None";?>
                    </td>
                    <td>
                        <a href="<?php echo base_url().'blog/'.$blog['slug'];?>" target="_blank">
                            <?php echo $blog['title'];?>
                        </a>
                    </td>
                    <td style="text-align:center;width:100px;">
                        <?php echo date("d/m/Y", strtotime($blog['blog_date']));?>
                    </td>
                    <td style="text-align:center;width:50px">
                        <span style="display:none;"><?php echo $blog['active'];?></span>
                        <?php $active = (!empty($blog['active'])) ? "active" : "inactive";?>
                        <img src="<?php echo base_url('assets/images/'.$active.'.png');?>">
                    </td>
                    <td style="text-align:center;width:50px;">
                        <a href="<?php echo base_url('admin/blogs/edit/'.$blog['id']);?>">
                            <img src="<?php echo base_url('assets/images/edit.png');?>" border="0">
                        </a>
                    </td>
                    <td style="text-align:center;width:50px;"><img src="<?php echo base_url('assets/images/delete.png');?>"></td>
                </tr>

                <?php
            $x++;
        }
        } else { ?>
            <tr>
                <td colspan="7" style="text-align: center;">
                    No Blog entry found ...
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
#blogs_table_wrapper {
	background-color: #fff;
	padding: 10px;
	border: 1px solid #ccc;
}
</style>
