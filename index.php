<?php
define('IN_BLOG', true);
define('PATH', '');
include('includes/miniblog.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Miniblog Index</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</head>

<body>
<div class="container">
	<div class="page-header">
	    <h1>Miniblog Default</h1>
	</div>
	<?php
	if($featured_post)
	 {
		?>
	<div class="jumbotron">
		<?= $featured_post ?>
	</div>
	<?php
     }
	?>
	<div>
    	<?= $miniblog_posts ?>
	</div>
	<div class="navigation">
		<?php if(!$single) { ?>
			<?php if($miniblog_previous) {?> <p class="previous-link"><?=$miniblog_previous?></p><?php } ?>
			<?php if($miniblog_next) {?> <p class="next-link"><?=$miniblog_next?></p> <?php } ?>
		<?php } ?>
		<?php if($single) { ?>
			<p class="previous-link"><a href="<?=$config['miniblog-filename']?>">&laquo; return to posts</a></p>
		<?php } ?>
		<div class="clear"></div>
	</div>
</div>
</body>
</html>
