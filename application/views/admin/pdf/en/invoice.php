
<!-- logo and address //-->
<table style="width:100%;">
<tr>
	<td>
		<img style="width:200px;" src="<?php echo base_url().$site['main_logo'];?>">
	</td>
	<td style="text-align:right;">
		<?php echo $site['address'];?>
	</td>
</tr>
</table>

<br /><br />

<!-- order details //-->
<table style="border: 1px solid #f5f5f5;">
<tr style="background-color: #f5f5f5;">
	<td>
		<strong>Invoice # </strong>1000000<?php echo $order['id'];?>
	</td>
</tr>
<tr style="background-color: #f5f5f5;">
	<td>
		<strong>Order #</strong> <?php echo $order['order_id'];?>
	</td>
</tr>
<tr style="background-color: #f5f5f5;">
	<td>
		<strong>Order date:</strong> <?php echo date("H:ia d/m/Y", strtotime($order['order_date']));?>
	</td>
</tr>
</table>

<br /><br />

<!-- sold to & shipped too //-->
<table style="border: 1px solid #f5f5f5;">
<tr style="background-color: #f5f5f5;">
	<td>
		<strong>Sold to:</strong>
	</td>
	<td>
		<strong>Ship to:</strong>
	</td>
</tr>
<tr>
	<td>
		<?php echo $order['billing_first_name'];?> <?php echo $order['billing_last_name'];?><br />
		<?php echo $order['billing_house_number'];?> <?php echo $order['billing_street_name'];?>,<br /> 
		<?php echo $order['billing_town_city'];?>, <br /> 
		<?php echo $order['billing_county'];?>, <br /> 
		<?php echo $order['billing_country'];?>, <br /> 
		<?php echo $order['billing_postcode'];?><br /> 	
		T: <?php echo $order['billing_telephone'];?>
	</td>
	<td>
		<?php echo ( ! empty ( $order['shipping_first_name'] ) ) ? $order['shipping_first_name'] : $order['billing_first_name'];?> <?php echo ( ! empty ( $order['shipping_last_name'] ) ) ? $order['shipping_last_name'] : $order['billing_last_name'];?>, <br />
		<?php echo ( ! empty ( $order['shipping_house_number'] ) ) ? $order['shipping_house_number'] : $order['billing_house_number'];?> <?php echo ( ! empty ( $order['shipping_street_name'] ) ) ? $order['shipping_street_name'] : $order['billing_street_name'];?>, <br />
		<?php echo ( ! empty ( $order['shipping_town_city'] ) ) ? $order['shipping_town_city'] : $order['billing_town_city'];?>, <br />
		<?php echo ( ! empty ( $order['shipping_county'] ) ) ? $order['shipping_county'] : $order['billing_county'];?>, <br />
		<?php echo ( ! empty ( $order['shipping_country'] ) ) ? $order['shipping_country'] : $order['billing_country'];?>, <br />
		<?php echo ( ! empty ( $order['shipping_postcode'] ) ) ? $order['shipping_postcode'] : $order['billing_postcode'];?> <br />
		T: <?php echo ( ! empty ( $order['shipping_telephone'] ) ) ? $order['shipping_telephone'] : $order['billing_telephone'];?>
	</td>
</tr>
</table>

<br /><br />

<!-- payment & shipping //-->
<table style="border: 1px solid #f5f5f5;">
<tr style="background-color: #f5f5f5;">
	<td>
		<strong>Payment:</strong>
	</td>
	<td>
		<strong>Shipping Method:</strong>
	</td>
</tr>
<tr>
	<td>
		<p>
		Alipay
		</p>
	</td>
	<td>
		<p>
		1-3 days
		</p>
		<p>
		(Total Shipping Charges <?php echo $currency['currency_html'];?><?php echo number_format((float)( $order['order_delivery_cost'] * $order['order_exchange_rate'] ), 2, '.', '');?>)
		</p>
	</td>
</tr>
</table>

<br /><br />

<!-- products //-->
<table style="border: 1px solid #f5f5f5;">
<tr style="background-color: #f5f5f5;">
	<td style="width: 50%">
		<strong>Product</strong>
	</td>
	<td style="width: 15%">
		<strong>Price</strong>
	</td>
	<td style="width: 15%">
		<strong>QTY</strong>
	</td>
	<td style="width: 20%">
		<strong>Total</strong>
	</td>	
</tr>

<?php 
$subtotal = 0;
foreach( $products as $product ) {
?>
	<tr>
		<td style="width: 50%">
			<p>
			<?php echo $product['product_title'];?><br />
			SKU: <?php echo $product['sku'];?>
			</p>		
		</td>
		<td style="width: 15%">
			<p>
			<?php 
			$cost = ( $product['cost'] * $product['exchange_rate'] );
			$cost = number_format((float)( $cost ), 2, '.', '')
			?>
			<?php echo $currency['currency_html'];?><?php echo $cost;?>
			</p>
		</td>
		<td style="width: 15%">
			<p>
			<?php echo $product['qty'];?>
			</p>
		</td>
		<td style="width: 20%">
			<p>
			<?php $total = number_format((float)( $cost * $product['qty'] ), 2, '.', '');?>
			<?php echo $currency['currency_html'];?><?php echo $total;?>
			<?php $subtotal += $total;?>
			</p>		
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<p>&nbsp;</p>
		</td>
	</tr>	
<?php }?>

<tr>
	<td colspan="3" style="text-align:right;">
		<p>
		<strong>Sub-total:</strong>
		</p>
	</td>
	<td>
		<p>
		<?php echo $currency['currency_html'];?><?php echo number_format((float)( $subtotal ), 2, '.', '');?>
		</p>
	</td>
</tr>
<tr>
	<td colspan="3" style="text-align:right;">
		<p>
			<strong>Discount<?php echo ( ! empty ( $order['order_voucher_code'] ) ) ? " (".$order['order_voucher_code'].")" : "" ;?>:</strong>
		</p>
	</td>
	<td>
		<p>
			<?php 
			$discount = ( ! empty ( $order['order_voucher_discount'] ) ) ? ( $order['order_voucher_discount'] * $order['order_exchange_rate'] ) : 0 ;
			echo $currency['currency_html']."".number_format((float)( $discount ), 2, '.', '');
			?>
		</p>
	</td>
</tr>
<tr>
	<td colspan="3" style="text-align:right;">
		<p>
		<strong>Delivery:</strong>
		</p>
	</td>
	<td>
		<p>
		<?php $delivery_cost =  ( $order['order_delivery_cost'] * $order['order_exchange_rate'] );?>
		<?php echo $currency['currency_html'];?><?php echo number_format((float)( $delivery_cost ), 2, '.', '');?>
		</p>
	</td>
</tr>
<tr>
	<td colspan="3" style="text-align:right;">
		<p>
		<strong>Total:</strong>
		</p>
	</td>
	<td>
		<p>
		<?php echo $currency['currency_html'];?><?php echo number_format((float)( ( $subtotal - $discount ) + $delivery_cost ), 2, '.', '');?>
		</p>
	</td>
</tr>
</table>