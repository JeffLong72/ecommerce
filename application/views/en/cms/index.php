<div class="content">
	<?php 
		if(!empty($rows)){
			foreach ($rows as $row)
			{
				echo "<h1>".$row['title']."</h1>";
				echo "<p>".$row['text']."</p>";
			}
		}
	?>
</div>