<div class="admin_content">
	<h1>Create a new blog page</h1>
	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>	
	<p>Create a new blog by entering the details below. If you set the blog status to active, the blog will automatically be published on the date you set.</p>
	<hr>
	
	<span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

	<?php echo form_open_multipart('admin/blogs/create'); ?>
		<h2>Blog details</h2>
		<p style="float:right;">
			<span class="required">*</span> Required field
		</p>
		<p>
			<label for="title">Title:<span class="required">*</span></label>
			<input type="input" name="title" id="title" /><br />
		</p>
		<p>
			<label for="title">Url:<span class="required">*</span></label>
			<input type="input" name="slug" id="slug"/><br />
		</p>
		<p>
			<label for="title">Date:<span class="required">*</span></label>
			<input type="input" name="blog_date" id="blog_date" /><br />
		</p>
		<p>
			<label for="text">Content:</label><br />
			<textarea id="textarea" name="text"></textarea><br />
		</p>
		<?php /* ?>
	<hr>			
		<h2>Related Products</h2>
		<p>Select the related products you would like to display on this blog page</p>
		<p>
			<select multiple="multiple" id="selected_products" name="selected_products[]">
				<?php 
				if (!empty($blog['products'])) {
					foreach($blog['products'] as $product) {
						$selected = "";
						if(!empty($blog['related_products'])) {
							foreach($blog['related_products'] as $related_products) {
								if( empty($selected) && $related_products['sku'] == $product['sku']) {
									$selected = " selected='selected' ";
								}
							}
						}
						?>
						<option value='<?php echo $product['sku'];?>' <?php echo $selected;?>><?php echo $product['title'];?></option>
						<?php
					}
				}?>
			</select>
		</p>
		<?php */ ?>
	<hr>		
		<h2>Blog Image</h2>				
		<p>
			<label for="text">Image:</label>
			<input type="file" name="image" size="20" /><br />
		</p>
		<p>
			<label for="image_alt">Image alt:</label>
			<input type="text" name="image_alt" id="image_alt" value="" /><br />
		</p>	
		<p>
			<label for="image_title">Image title:</label>
			<input type="text" name="image_title" id="image_title" value="" /><br />
		</p>
	<hr>	
		<h2>SEO Details</h2>
		<p>
			<label for="meta_title">Meta title (70 chars):</label>
			<input type="text" name="meta_title" id="meta_title" value="" maxlength="70"/><br />
		</p>			
		<p>
			<label for="title">Meta description (160 chars):</label>
			<textarea name="meta_description" id="meta_description" maxlength="160"></textarea><br />
		</p>
		<p>
		This is what your page would look like in the Search Engine results,
		</p>
		<div class="search_engine">
			<div class="search_engine_title"><span id="seo_title">Blog title</span></div>
			<div class="search_engine_url"><?php echo base_url();?>blog/<span id="seo_url">blog-url.html</span></div>
			<div class="search_engine_desc"><span id="seo_text">The text you enter into the Meta description field ...</span></div>			
		</div>
		<p>
			<label for="redirect">301 Redirect:</label>
			<input type="text" name="redirect" id="redirect" value="" placeholder="https://" /><br />
		</p>	
		<p>
			<label for="canonical">Canonical:</label>
			<input type="text" name="canonical" id="canonical" value="" placeholder="https://" /><br />
		</p>		
		<script>
		$('#slug').keyup(function() {
			var seo_slug = $('#slug').val();
			$('#seo_url').text(seo_slug);
		});
		$('#meta_title').keyup(function() {
			var seo_title = $('#meta_title').val();
			$('#seo_title').text(seo_title);
		});
		$('#meta_description').keyup(function() {
			var seo_text = $('#meta_description').val();
				seo_text = seo_text.replace(/^(.{160}[^\s]*).*/, "$1"); 
			$('#seo_text').text(seo_text + "...");
		});
		$('#meta_description').focusout(function() {
			var seo_text = $('#meta_description').val();
				seo_text = seo_text.replace(/^(.{160}[^\s]*).*/, "$1"); 
			$('#seo_text').text(seo_text + "...");
		});		
		</script>
	<hr>		
		<h2>Blog Status</h2>
		<p>
			<label for="title">Status:</label>
			<select name="status">
				<option value="1">Active</option>
				<option value="0">Inactive</option>
			</select>	
			<br />
		</p>	
		<p>
			<label for="submit">&nbsp;</label>
			<input class="submit_button" type="submit" name="submit" value="Create blog page" />
		</p>
	</form>
	
</div>

<style>
.search_engine {
	margin-left:80px;
	border:1px solid #ccc;
	background-color: #fff;
	height: 80px;
	width: 600px;
	padding: 10px;
	border-radius: 10px 10px 10px 10px;
}
.search_engine_title {
	font-size: 18px;
	color: #1a0dab;
    font-family: arial,sans-serif;
}
.search_engine_url {
	color: #006621;
    font-style: normal;
	font-family: arial,sans-serif;
}
.search_engine_desc {
	color: #545454;
	font-family: arial,sans-serif;
	line-height: 1.4;
    word-wrap: break-word;
}
label {
	width:80px;
	float: left;
	font-weight: bold;
}
input {
	height: 24px;
    width: 275px;
	border: 1px solid #ccc;
	padding-left: 5px;
}
textarea {
	height: 75px;
    width: 375px;
	border: 1px solid #ccc;
	padding-left: 5px;
	padding-right: 5px;
}
select {
	height: 24px;
    width: 275px;
	border: 1px solid #ccc;
	padding-left: 5px;
}
.validation_errors {
	color: red;
}
.required {
	color: red;
}
.submit_button {
	background-color: #006BB4 !important;
    height: 40px;
    width: 240px;
    color: #ffffff !important;
    font-weight: bold !important;
	font-size: 20px;
	font-family: Quicksand, Helvetica, Arial, sans-serif;
    border: none;
	border-radius: 5px 5px 5px 5px;
	padding: 5px;
	padding-left: 10px;
	padding-right: 10px;
	text-decoration: none;
	cursor:pointer;
}
</style>

<script>
// set datepicker field
$( "#blog_date" ).datepicker({dateFormat: 'dd/mm/yy'});

// SEO urls
$( "#title" ).focusout(function() {
	// get the title
	var str = $( "#title" ).val();
	// replace any apostrophes
	str = str.replace(/'/g,"");	
	// replace any non-alphanumeric characters with spaces
	str = str.replace(/[\W_]+/g," ");
	// replace any spaces with hyphens
	str = str.replace(/ /g, "-", str);
	$( "#slug" ).val(str);
	$( "#seo_url" ).text(str + ".html");
});

// set ckeditor
var editor = CKEDITOR.replace( 'textarea', {
        height: 400
    } );
CKFinder.setupCKEditor( editor );

// multi-select
$('#selected_products').multiSelect({
  selectableHeader: "<input type='text' class='search-input' style='width: 90%;border-radius: 5px;margin-bottom: 5px;' autocomplete='off' placeholder='Search ...'>",
  selectionHeader: "<input type='text' class='search-input' style='width: 90%;border-radius: 5px;margin-bottom: 5px;' autocomplete='off' placeholder='Search ...'>",
  afterInit: function(ms){
    var that = this,
        $selectableSearch = that.$selectableUl.prev(),
        $selectionSearch = that.$selectionUl.prev(),
        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
    .on('keydown', function(e){
      if (e.which === 40){
        that.$selectableUl.focus();
        return false;
      }
    });

    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
    .on('keydown', function(e){
      if (e.which == 40){
        that.$selectionUl.focus();
        return false;
      }
    });
  },
  afterSelect: function(){
    this.qs1.cache();
    this.qs2.cache();
  },
  afterDeselect: function(){
    this.qs1.cache();
    this.qs2.cache();
  }
});
</script>