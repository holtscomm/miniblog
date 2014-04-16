<?php
define('IN_BLOG', true);
define('PATH', '');
include('includes/miniblog.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Miniblog Index</title>
</head>

<body>
<div class="container">
	<div class="page-header">
	    <h1>Campaign Updates</h1>
	</div>
	<div>
    	<?= $miniblog_posts ?>
	</div>
	<div class="navigation">
		<?php if(!$single) { ?>
			<?php if($miniblog_previous) {	?> <p class="previous-link"><?=$miniblog_previous?></p>	<?php } ?>
			<?php if($miniblog_next) {	?>	<p class="next-link"><?=$miniblog_next?></p> <?php } ?>
		<?php } ?>
		<?php if($single) { ?>
			<p class="previous-link"><a href="<?=$config['miniblog-filename']?>">&laquo; return to posts</a></p>
		<?php } ?>
		<div class="clear"></div>
	</div>
</div>
</body>
</html>