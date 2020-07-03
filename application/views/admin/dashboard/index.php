<div class="admin_content">

	<h1>Dashboard</h1>
	
	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>	
	
	<div class="dashboard_block">
		<h1>Welcome to the <?php echo $site;?> dashboard!</h1>
		<p>Heres some random text to start you off with...</p>
	</div>
	
</div>