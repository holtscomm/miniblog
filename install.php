<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Miniblog Installer</title>
<style type="text/css">

body {
	margin:0 auto;
	padding:10px;
	font-family:Geneva, Arial, Helvetica, sans-serif;
	font-size:1.0em;
}
div#wrapper {
	width:50%;
	margin:0 auto;
	padding:0;
}
span.success {
	font-weight:bold;
	color:#339900;
}
span.error {
	font-weight:bold;
	color:#ff0000;
}
span.sqlerror strong, span.tip strong {
	display:block;
	padding:5px;
	background-color:#ccc;
}
span.sqlerror, span.tip {
	border:1px solid #ccc;
	margin-top:10px;
	display:block;
	font-size:0.9em;
}
span.sqlerror span, span.tip span {
	display:block;
	padding:5px;
}
span.sqlerror strong {
	background-color:#3399CC;
}
span.sqlerror {
	border:1px solid #3399CC;
}
em {
	background-color:#ddd;
	font-family:"Courier New", Courier, monospace;
}
h1 {
	border-bottom:1px solid #ddd;
	font-size:1.4em;
	color:#333;
}
a {
	font-size:0.9em;
	color:#ffffff;
	background-color:#333333;
	padding:3px;
	text-decoration:none;
}

</style>
</head>

<body>
<div id="wrapper">
<h1>Installing</h1>
<?php

	define('IN_BLOG', true);
	define('PATH', '');

	include(PATH . 'includes/config.php');
	include(PATH . 'includes/functions.php');

	$database = mb_connect($sqlconfig);

	/* installer vars */
	$install_step = (int) $_GET['step'];

	$dbl = $database->select_db($sqlconfig['dbname']);

	$success = '<span class="success">Success!</span><br />';
	$fail    = '<span class="error">Failed!</span><br />';

	$sql_error = '<br /><span class="sqlerror"><strong>MySQL said:</strong><span>%s</span></span><br />';

	$tip  = '<span class="tip"><strong>Tip:</strong><span>%s</span></span>';
	$code = '<span class="tip"><strong>Code:</strong><span>%s</span></span>';

	$continue = '<br /><a href="install.php?step=%d">Continue &raquo;</a>';

	if($install_step == 1 || $install_step == 0)
	{

		echo 'Testing connection...';

		if(!$database)
		{
			echo $fail;
			echo(sprintf($sql_error, $database->error));
			echo(sprintf($tip, 'Check your <em>$sqlconfig</em> settings in <em>config.php</em><br />Refresh this page to try again'));

		}
		else
		{
			echo $success;
			echo 'Testing database...';


			if(!$dbl)
			{
				echo $fail;
				echo(sprintf($sql_error, $database->error));
				echo(sprintf($tip, 'Check your <em>$sqlconfig</em> settings in <em>config.php</em><br />Make sure the database exists - this installer will <em>not</em> create it for you. See your web hositng control panel.<br />Refresh this page to try again'));
			}
			else
			{
				echo $success;
				echo(sprintf($continue, 2));

			}
		}

	}


	if($install_step == 2)
	{
		if(!$database || !$dbl)
		{
			header("Location: install.php");
			exit;
		}

		echo 'Creating tables <em>miniblog</em>, <em>miniblog_config</em>, <em>miniblog_category</em>, <em>miniblog_user</em>...';

		$sql = "CREATE TABLE `miniblog` (
				  `post_id` int(20) NOT NULL auto_increment,
				  `post_slug` varchar(255) NOT NULL default '',
				  `post_title` varchar(255) NOT NULL default '',
				  `post_content` longtext NOT NULL,
				  `post_category` int(3) NULL default NULL,
				  `date` int(20) NOT NULL default '0',
				  `published` int(1) NOT NULL default '0',
				  PRIMARY KEY  (`post_id`)
				)";

		$result = mb_query($sql, $database);

		$sql = "CREATE TABLE `miniblog_config` (
				  `config_name` varchar(255) NOT NULL default '',
				  `config_value` varchar(255) NOT NULL default '',
				  `config_explain` longtext NOT NULL
				)";

		$result2 = mb_query($sql, $database);

		$sql = "CREATE TABLE `miniblog_category` (
				  `cat_id` int(3) NOT NULL auto_increment,
				  `name` varchar(50) NOT NULL,
				  PRIMARY KEY (`cat_id`)
				)";

		$result3 = mb_query($sql, $database);

		$sql = "CREATE TABLE `miniblog_user` (
				`user_id` int(3) NOT NULL auto_increment,
				`username` varchar(100) NOT NULL,
				`password` varchar(500) NOT NULL,
				PRIMARY KEY (`user_id`)
				)";

		$result4 = mb_query($sql, $database);

		if(!$result || !$result2 || !$result3 || !$result4)
		{
			echo $fail;
			echo(sprintf($sql_error, $database->error));
			echo(sprintf($tip, 'Check your <em>$sqlconfig</em> settings in <em>config.php</em><br />Make sure the table doesn\'t already exist. Refresh this page to try again'));
		}
		else
		{
			echo $success;
			echo 'Inserting record data...';

			$sql = "INSERT INTO `miniblog_config` (`config_name`, `config_value`, `config_explain`) VALUES
					('posts-per-page', '5', 'Posts displayed each page'),
					('date-format', 'F d, Y', 'Date format as per the PHP date function <a href=\"http://www.php.net/date\">here</a>'),
					('password', '5f4dcc3b5aa765d61d8327deb882cf99', 'Admin password'),
					('miniblog-filename', 'index.php', 'Name of the file which miniblog.php is included into'),
					('use-modrewrite', 1, 'Use modrewrite for post URLs - use 1 for yes, 0 for no.')";

			$result = mb_query($sql, $database);

			$sql = "INSERT INTO `miniblog` (`post_slug`, `post_title`, `post_content`, `date`, `published`) VALUES
('welcome-to-miniblog', 'Welcome to miniblog!', '<p>Welcome to your new installation of miniblog. To remove or edit this post, add new posts and change options login to your admin panel.</p>', " . time() . ", 1)";

			$result2 = mb_query($sql, $database);

			$sql = 'INSERT INTO `miniblog_user` (`username`, `password`) VALUES
					("admin", "$2y$10$DTfDCO1a9wX1XhwWWFzIfOe9YyShBBLD0yMtQfDCImOL4BPSUBUsG")';

			$result3 = mb_query($sql, $database);

			if(!$result || !$result2 || !$result3)
			{
					echo $fail;
					echo(sprintf($sql_error, $database->error));
					echo(sprintf($tip, 'Check your <em>$db</em> settings in <em>online.php</em><br />Refresh this page to try again'));
			}
			else
			{
				echo $success;
				echo(sprintf($continue, 3));
			}

		}

	}

	if($install_step == 3)
	{

		?>
			<p>Installation is now complete!<br /><br />
				View your miniblog here: <a href="index.php">miniblog</a><br /><br />
				Login to your miniblog admin panel, with password: <strong>password</strong> here: <a href="adm/login.php">admin</a><br /><br /></p>
				<p><strong>Installation complete!</strong></p>
				<p><strong>Please delete this file (install.php) from your server</strong></p>
			</code>

		<?php

	}


?>
</div>
</body>
</html>
<?php
ob_end_flush();
?>
