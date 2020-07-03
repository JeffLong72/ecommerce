<div class="content">

		<?php if($this->session->flashdata('msg')): ?>
			<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
		<?php endif; ?>

		
		<?php if(!empty($dupes)): ?>
			<p class="checkout_required_field" style="margin-left:10px;">
				<?php echo $dupes;?>
			</p>
		<?php endif; ?>			
			

</div>