
<div class="page">
	<h1 class="edit"><?=ucfirst($mode)?> post</h1>
	<span class="error-text"><?=$response_text?></span>
	<form role="form" action="admin.php?mode=<?=$mode?>&id=<?=$post['post_id']?>" method="post">
	<div class="form-group">
		<label for="title">Post title:</label><br />
		<input class="form-control" type="text" size="80" id="title" name="data[post_title]" value="<?=htmlspecialchars(stripslashes($post['post_title']))?>" />
	</div>

	<div class="form-group">
		<label for="content">Post content:</label><br />
		<textarea cols="77" rows="10" id="content" name="data[post_content]"><?=htmlspecialchars(stripslashes($post['post_content']))?></textarea><br />
	</div>

	<div class="form-group">
	    <label for="categories">Post category:</label><br />
	    <input class="form-control" type="text" size="80" id="categories" name="data[post_category]" value="<?=get_category_name_for_id(htmlspecialchars(stripslashes($post['post_category'])), $database)?>"/><br />
	</div>

	<div class="form-group">
		<label for="status">Post status:</label><br />
		<select id="status" name="data[published]" class="form-control">
			<?=generate_option_list(array('0' => 'Unpublished', '1' => 'Published'), $post['published'])?>
		</select>
	</div>

	<div>
		<input class="btn btn-primary" type="submit" name="miniblog_PostBack" value="<?=ucfirst($mode)?>" />
	</div>
</div>
