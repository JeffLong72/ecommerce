<div class="content">
	<div class="blog_page">
		<h1>Blog</h1>
	<?php
	if(!empty($rows)) {
		foreach($rows as $row) {
		?>
		<div class="blog_entry" style="margin-bottom: 50px;">
		
			<div style="max-width:100%;float:left;margin:0px 0px 0px 0px;text-align:center;"> 
				<a class="data-tag" data-tag="blog-index-image-<?php echo $row['slug'];?>" href="<?php echo base_url('blog/'.$row['slug'].'.html');?>">
					<img alt="<?php echo(!empty($row['title'])) ? $row['title'] : "";?>" style="max-width:450px;height:auto;float:left;" src="<?php echo(!empty($row['image'])) ? base_url("uploads/".$site."/blogs/".$row['image']) : base_url("uploads/nopic-150.png");?>" border="0">
				</a>
			</div>
			
			<div style="clear:both;"></div>
			
			<div style="float:left;">
				<a class="blog_title data-tag" data-tag="blog-index-title-<?php echo $row['slug'];?>" href="<?php echo base_url('blog/'.$row['slug'].'.html');?>">
					<h2 style="margin-top: 10px;"><?php echo $row['title'];?></h2>				
				</a>
				<p>
					<strong>
						<?php echo date("d M Y", strtotime($row['blog_date']));?>
					</strong>
				</p>
				<?php 
				$chars = 300;
				$string = strip_tags($row['text']);
				if (strlen($string) > $chars) {
					// truncate string
					$stringCut = substr($string, 0, $chars);
					$endPoint = strrpos($stringCut, ' ');
					//if the string doesn't contain any space then it will cut without word basis.
					$string = $endPoint? substr($stringCut, 0, $endPoint):substr($stringCut, 0);
					$string = (!empty($string)) ? $string.'... <br /><br /><a class="blog_button_read_more data-tag"  data-tag="blog-index-read-more-button-'.$row['slug'].'" href="'.base_url('blog/'.$row['slug'].'.html').'">Read more</a>' : "";
				}
				echo $string;
				?>
			</div>
			<div style="clear:both;"></div>
		</div>

		<?php	
		}
	}
	?>
	<p class="pagination">
		<?php echo $pagination;?>
	</p>
	</div>
	
	<div class="blog_options" style="float:left;margin-top:0px;">
		<h2>Recent blogs</h2>
		<ul class="blogs_recent">
			<?php if( ! empty( $latest_blogs ) ) {?>
				<?php foreach( $latest_blogs as $latest_blog) {?>
					<li>
						<a class="data-tag" data-tag="blog-view-side-menu-recent-blogs-<?php echo $latest_blog['slug'];?>" href="<?php echo base_url().'blog/'.$latest_blog['slug'];?>"><?php echo $latest_blog['title'];?></a>
					</li>				
				<?php }?>			
			<?php }?>
		</ul>
		
		<br /><br />
		
		<h2>All blogs</h2>
		<form method="post" action="<?php echo base_url();?>blog/search" name="blog_select_month_year_form">
			<label for="blog_select_month_year" class="hide">Select Blog</label>
			<select id="blog_select_month_year" name="blog_select_month_year" class="blog_select">
			<?php if( ! empty( $blogs_by_date ) ) {?>
				<?php foreach( $blogs_by_date as $blog) {?>
					<option value="<?php echo date("Y-m", strtotime($blog['blog_date']));?>" class="data-tag" data-tag="blog-view-side-menu-select-blogs-option-<?php echo date("m-Y", strtotime($blog['blog_date']));?>"><?php echo date("M Y", strtotime($blog['blog_date']));?></option>
				<?php }?>			
			<?php }?>			
			</select>
			<input type="submit" name="blog_select_month_year_submit"class="blog_button_search data-tag" data-tag="blog-view-side-menu-select-blogs-submit-button">
		</form>
	</div>
</div>

<div style="clear:both;"></div>