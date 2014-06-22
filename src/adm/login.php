<?php
session_start();
require_once('class.user.php');
$user = null;
if(isset($_SESSION["user"]))
{
	$user = unserialize($_SESSION["user"]);
}
include('../includes/config.php');
include('../includes/functions.php');

require_once('class.user.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Miniblog Admin</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<script type="text/javascript" src="images/dialog.js"></script>
<script type="text/javascript" src="../includes/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../includes/js/knockout-3.1.0.js"></script>
<script type="text/javascript" src="../includes/js/moment.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="index.php">Miniblog Admin</a>
		</div>
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	<ul class="nav navbar-nav">
		<li><a href="login.php">Login</a></li>
	</ul>
	</div><!-- /.navbar-collapse -->

	</div><!-- /.container-fluid -->
</nav>
<div class="container">
<?php
if(isset($user) && !isset($_REQUEST['logout']))
{
	echo "<p>You are already logged in. Redirecting you to the list page. <a href='index.php'>Click here</a> if this takes more than a second.</p>";
	echo "<script type='text/javascript'>window.setTimeout('window.location.assign(\'index.php\')', 500);</script>";
}
else if(isset($_REQUEST['password']))
{
	// The only user we have for now
	$username = 'admin';
	$password = $database->real_escape_string($_REQUEST['password']);

	$user = get_user_from_db($username, $password, $database);

	if(!$user)
	{
		$error_text = "Password was incorrect!";
	}
	else
	{
		$_SESSION['user'] = serialize($user);
		echo "<p>You're logged in, redirecting you now... <a href='index.php'>Click here</a> if this takes more than a second.</p>";
        echo "<script type='text/javascript'>window.setTimeout('window.location.assign(\'index.php\')', 500);</script>";
	}
}
else if(isset($_REQUEST['logout']))
{
	unset($_SESSION["user"]);
    echo "<p>You have been logged out... redirecting to the login page. <a href='login.php'>Click here</a> if this takes more than a second.</p>";
    echo "<script type='text/javascript'>window.setTimeout('window.location.assign(\'login.php\')', 500);</script>";
}
else
{
?>

	<form role="form" action="login.php" method="post">
		<h1 class="login">Login</h1>
		<span class="error-text"><?=$error_text ? $error_text : ''?></span>
		<div class="form-group">
			<label for="password">Control panel password:</label>
			<input class="form-control" id="password" size="30" type="password" name="password" />
		</div>
		<p><input class="btn btn-primary" type="submit" name="SimplePoll_Login" value="Login" /></p>
	</form>

<?php
}
?>
</div>
<?php
include('footer.php');
?>
