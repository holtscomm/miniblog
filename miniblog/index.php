<?php
include('includes/miniblog.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Miniblog Index</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="includes/css/vendor/bootstrap.min.css">

<script id='postTemplate' type='text/html'>
<div class="post" data-bind="attr: { 'id': postId }">
	<h4><a data-bind="attr: { 'href': postUrl }, text: postTitle"></a></h4>
	<span class="date" data-bind="text: publishDate"></span>
	<div class="post-content" data-bind="html: postContent"></div>
	<a data-bind="href: postCategoryLink">View more posts from this category</a>
</div>
</script>
</head>

<body>
<div class="container">
	<div class="page-header">
	    <h1>Miniblog Default</h1>
	</div>
	<div class="col-md-8" data-bind="template: { name: 'postTemplate', foreach: posts }"></div>
	<?php
	if($featured_post && !$single)
	{
		?>
	<div class="col-md-4" data-bind="template: { name: 'postTemplate', data: featuredPost }"></div>
	<?php
	}
	?>
	<div class="col-md-8">
		<?php
		// If not viewing a single post, show some older/newer post links
		if(!$single)
		{
			if($miniblog_previous)
			{
				echo "<p class='previous-link'>{$miniblog_previous}</p>";
			}
			if($miniblog_next)
			{
				echo "<p class='next-link'>{$miniblog_next}</p>";
			}
		}
		// If viewing a single post, show the return to posts link
		else
		{
			echo "<p class='previous-link'><a href='" . $config['miniblog-filename'] . "'>&laquo; return to posts</a></p>";
		}
		?>
	</div>
</div>


<script src="includes/js/vendor/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="includes/js/vendor/moment.min.js"></script>
<script src="includes/js/vendor/bootstrap.min.js"></script>
<script type="text/javascript" src="includes/js/vendor/knockout-3.1.0.js"></script>
<script type="text/javascript" src="includes/js/MB.js"></script>
<script type="text/javascript" src="includes/js/MB.postmodel.js"></script>
<script type="text/javascript" src="includes/js/MB.posts.js"></script>

<script type='text/javascript'>
	$(function() {
		ko.applyBindings(new PostViewModel(<?= json_encode($miniblog_posts) ?>, <?= json_encode($featured_post) ?>, '<?= $single ?>'))
	});
</script>
</body>
</html>
