<div class="content">
	<div class="container">
	
		<h1>Items In Your Bag</h1>
		
		<?php echo (!empty($this->session->flashdata('msg'))) ? $this->session->flashdata('msg') : "";?>
		
		<?php 
		if(!empty($products)) {
		?>
		<form class="basket_form" action="<?php echo base_url();?>checkout/basket.html" method="post">
			<input type="hidden" name="update_basket" value="1">
			<?php
			foreach($products as $product) {
			?>
				<div class="basket_item">
					<hr>
					<div class="basket_item_image">
						<?php
						$this->load->model('checkout_model');
						$image = $this->checkout_model->get_product_images_in_basket($product['sku']);
						$image = (!empty($image)) ? $image[0]['category'] : "assets/images/10.jpg";
						?>
						<img src="<?php echo base_url();?><?php echo $image;?>" style="width:65px;float:left;" alt="<?php echo $product['product_title'];?>" title="<?php echo $product['product_title'];?>">
						<p style="float:left;margin-left: 20px;">
							<strong>
								<?php echo $product['product_title'];?>
							</strong>
						</p>
						<div style="clear:both;"></div>
					</div>
					<div class="basket_item_details">
						<p style="float:right;">
							<?php 
								$product_cost = ( $product['cost'] * $_SESSION['site']['currency_rate'] );
								$product_cost = number_format((float)($product_cost), 2, '.', '');
							?>							
							<?php echo $_SESSION['site']['currency_html'];?><?php echo number_format((float)( $product['qty'] * $product_cost), 2, '.', '');?>
						</p>
						<p>
							<label for="qty_<?php echo $product['product_id'];?>" class="hide">Quantity</label>
							<input type="text" id="qty_<?php echo $product['product_id'];?>" name="<?php echo $product['product_id'];?>" value="<?php echo $product['qty'];?>" class="basket_item_count">
						</p>
					</div>
				</div>
				<div style="clear:both;"></div>
			<?php 
			} 
			?>
			<div class="basket_item">
				<hr>
				
				<?php /* ?>
				<div class="basket_item_image">
					&nbsp;
					<p style="float:left;margin-left: 20px;">
						&nbsp;
					</p>
					<div style="clear:both;"></div>
				</div>
				<?php */ ?>
				
				<div class="" style="float:right;margin-top:20px;">
						<?php 
							if( !empty($_SESSION['customer']['voucher_result'])) {
							$style = ( ! empty ( $_SESSION['customer']['voucher_is_valid'] ) && $_SESSION['customer']['voucher_is_valid'] == TRUE ) ? " green " : " red ";
							?>
							<p style="color: <?php echo $style;?>;"><?php echo $_SESSION['customer']['voucher_result'];?></p>
						<?php }?>
						<strong>
							Apply Discount Code: 
						</strong>
						<?php $voucher_code = ( ! empty ( $_SESSION['customer']['voucher_code'] ) ) ? $_SESSION['customer']['voucher_code'] : "";?>
						<input class="discount_code_input" type="text" name="order_voucher_code" id="order_voucher_code" value="<?php echo $voucher_code;?>" placeholder="">	
						<input class="checkout_items_button data-tag" style="background-color: #ff9447;" data-tag="checkout-basket-apply-voucher-button"  type="submit" name="update_basket" value="Apply">
				</div>
				
				<div style="clear:both;"></div>
				
				<div class="basket_total_cost">
					<p style="margin-bottom: 10px;">
						<strong>
							Sub-total: <?php echo $_SESSION['site']['currency_html'];?><?php echo $sub_total;?>
						</strong>
						<br />
						<strong>
							Discount: <?php echo $_SESSION['site']['currency_html'];?><?php echo $less_discount;?>
						</strong>
						<div style="border-top: 1px solid #777;padding-top:10px;">
							<strong>
								Total cost: <?php echo $_SESSION['site']['currency_html'];?><?php echo $total_cost;?>
							</strong>								
						</div>
					</p>
				</div>
				
				<div style="clear:both;"></div>
			</div>	
		<?php	
		}
		else {?>
			<h3>Your bag is empty</h3>
			<p style="height:50px">&nbsp;</p>
		<?php }?>
		
			<hr>
		
			<a class="continue_button checkout_items_button data-tag" data-tag="checkout-basket-continue-shopping-button" href="<?php echo (!empty($_SESSION['redirect_continue_shopping'])) ? base64_decode($_SESSION['redirect_continue_shopping']) : base_url()."search";?>">Continue Shopping</a>
			<input class="update_button checkout_items_button data-tag" data-tag="checkout-basket-update-bag-button"  type="submit" name="update_basket" value="Update Bag">
			<a class="checkout_button checkout_items_button data-tag" data-tag="checkout-basket-checkout-securely-button" href="<?php echo base_url();?>checkout/details">Checkout Securely</a>
			<div style="clear:both;"></div>
		</form>
	</div>
</div>

<style>
.discount_code_input {
    border: 1px solid #ccc;
    border-radius: 5px 5px 5px 5px;
    padding: 5px;
    min-width: 280px;
	height:38px;
	font-size: 20px;
}
</style>