<div class="footer">
	<div class="footer_inner">
		<div class="footer_section">
			<ul>
				<li><a class="data-tag" data-tag="footer-blog-link" href="<?php echo base_url();?>blog.html">Blog</a></li>
				<li><a class="data-tag" data-tag="footer-about-us-link" href="<?php echo base_url();?>about-us.html">About us</a></li>
				<li><a class="data-tag" data-tag="footer-privacy-policy-link" href="<?php echo base_url();?>privacy-policy.html">Privacy policy</a></li>
				<li><a class="data-tag" data-tag="footer-terms-and-conditions-link" href="<?php echo base_url();?>terms-and-conditions.html">Terms &amp conditions</a></li>
				<li><a class="data-tag" data-tag="footer-refound-policy-link" href="<?php echo base_url();?>refund-policy.html">Refund policy</a></li>
			</ul>
		</div>
		<div class="footer_section">
			<ul>
				<li><a class="data-tag" data-tag="footer-contact-us-link" href="<?php echo base_url();?>contact-us.html">Contact us</a></li>
				<li><a class="data-tag" data-tag="TODO" href="<?php echo current_url();?>">Menu item</a></li>
				<li><a class="data-tag" data-tag="TODO" href="<?php echo current_url();?>">Menu item</a></li>
				<li><a class="data-tag" data-tag="TODO" href="<?php echo current_url();?>">Menu item</a></li>
				<li><a class="data-tag" data-tag="TODO" href="<?php echo current_url();?>">Menu item</a></li>
			</ul>
		</div>
		<div class="footer_section">
			<p>
				CompanyName<br />
				Address line 1<br />
				Address line 2<br />
				Town/City<br />
				Country<br />
				Postcode<br />
				Tel: 01234 567890
			</p>
		</div>
		<div class="footer_section subscribe">
			<form class="subscribe_footer" style="width: 320px;">
				<label for="subscribe_input">Subscribe to newsletter</label><br /><br />
				<input class="subscribe_input" type="text" id="subscribe_input" ame="subscribe_input" value="" placeholder="Enter your email address">
				<input class="subscribe_button data-tag" data-tag="footer-subscribe-button"  type="submit" name="" value="Subscribe">
			</form>
			<br /><br />
			<img class="credit_cards_image" style="margin-left:15px;" src="<?php echo base_url('/assets/images/credit-cards.png'); ?>" alt="Secure online payments" title="Secure online payments" border="0">
		</div>
		<div class="clr_float"></div>
	</div>
</div>
<div class="clr_float"></div>
<div class="copyright">
	&copy;<?php echo date("Y");?> <a href="<?php echo base_url();?>">SkueCommerce.com</a>
</div>
<script src="<?php echo base_url('/assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/js/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo base_url('third_party/lightslider/js/lightslider.js');?>"></script>								            
<script src="<?php echo base_url('/assets/js/script.js'); ?>"></script> 
</body>
</html>