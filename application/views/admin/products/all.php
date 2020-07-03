<div class="admin_content">
	<h1>View all products</h1>
	<hr>
	<p style="float:left;margin-bottom:10px;">
		Total <?php echo count($count);?> records found
	</p>	
	
	<form style="float:right;margin-top:10px;" method="post"  action="<?php echo base_url();?>admin/products/all">
		<strong>Search:</strong> 
		<input type="text" name="keyword" placeholder="" value="<?php echo (!empty($keyword)) ? $keyword : "";?>">
		<input type="submit" name="submit" value=">>" style="width:50px;">
	</form>
	
	<div style="clear:both;"></div>

	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>
	
	<table id="products_table" class="all_table display">
        <thead>
            <tr>
				<th style="width:250px;">Sku</th>
                <th>Name</th>
                <th style="width:45px;">Stock</th>
                <th style="width:70px;">Stock on hold</th>
                <th style="width:45px;">Active</th>
                <th style="width:45px;">Edit</th>
                <th style="width:45px;">Delete</th>
            </tr>
        </thead>
		<tbody>
	
		<?php 
		if(!empty($products)) {
			$x = 0;
			foreach ($products as $product){
				$style_row = ($x % 2 != 0) ? "background: #f5f5f5;" : "";
			?>
					<tr style="<?php echo $style_row;?>">
						<td>
							<?php echo $product['sku'];?>					
						</td>
						<td>
							<a href="//<?php echo $domain.'/'.$product['slug'];?>.html" target="_blank">
								<?php echo $product['title'];?>
							</a>
						</td>
						<td style="text-align:center;width:20px;">
							<?php echo $product['stock'];?>
						</td>
						<td style="text-align:center;width:20px;">
							<?php echo $product['stock_on_hold'];?>
						</td>
						<td style="text-align:center;width:20px">
							<span style="display:none;"><?php echo $product['active'];?></span>
							<?php $active = (!empty($product['active']) && $product['active'] == 1) ? "active" : "inactive";?>
							<img src="<?php echo base_url('assets/images/'.$active.'.png');?>">
						</td>
						<td style="text-align:center;width:20px;">
							<a href="<?php echo base_url('admin/products/edit/'.$product['id']);?>">
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
						No product has been added yet...
					</td>
				</tr>
		<?php } ?>
	
		</tbody>
	</table>
	
	<p class="pagination">
		<?php echo $pagination;?>
	</p>	
	
</div>