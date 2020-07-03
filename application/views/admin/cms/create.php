<div class="admin_content">

	<h1>Create a new CMS page</h1>
	<?php if($this->session->flashdata('msg')): ?>
		<p class="flashdata"><?php echo $this->session->flashdata('msg'); ?></p>
	<?php endif; ?>	
	<p>Create a new CMS page by entering the details below. </p>
	<hr>
	
	<span class="validation_errors">
		<?php echo validation_errors(); ?>
	</span>

	<?php echo form_open('admin/cms/create'); ?>
		<h2>CMS page details</h2>
		<p style="float:right;">
			<span class="required">*</span> Required field
		</p>
		<p>
			<label for="title">Title:<span class="required">*</span></label>
			<input type="input" name="title" id="title" /><br />
		</p>
		<p>
			<label for="slug">Url:<span class="required">*</span></label>
			<input type="input" name="slug" id="slug"/><br />
		</p>
		<p>
			<label for="text">Content:</label><br />
			<textarea id="textarea" name="text"></textarea><br />
		</p>
	<hr>			
		<h2>SEO Details</h2>
		<p>
			<label for="meta_title">Meta title (70 chars):</label>
			<input type="text" name="meta_title" id="meta_title" maxlength="70"/><br />
		</p>		
		<p>
			<label for="meta_description">Meta description (160 chars):</label>
			<textarea name="meta_description" id="meta_description" maxlength="160"></textarea><br />
		</p>					
		<p>
		This is what your page would look like in the Search Engine results,
		</p>
		<div class="search_engine">
			<div class="search_engine_title"><span id="seo_title">CMS page title</span></div>
			<div class="search_engine_url"><?php echo base_url();?><span id="seo_url">cms-page-url.html</span></div>
			<div class="search_engine_desc"><span id="seo_text">The text you enter into the Meta description field ...</span></div>			
		</div>
		<p>
			<label for="redirect">301 Redirect:</label>
			<input type="text" name="redirect" id="redirect" placeholder="https://" /><br />
		</p>	
		<p>
			<label for="canonical">Canonical:</label>
			<input type="text" name="canonical" id="canonical" placeholder="https://" /><br />
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
		<h2>CMS Page Status</h2>
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
			<input class="submit_button" type="submit" name="submit" value="Create CMS page" />
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
</script>