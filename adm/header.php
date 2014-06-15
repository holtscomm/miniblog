<?php
session_start();
require_once('class.user.php');
$user = null;
if(isset($_SESSION["user"]))
{
    $user = unserialize($_SESSION["user"]);
}
else
{
    header("Location: login.php");
    exit;
}

include('../includes/config.php');
include('../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

if(!$database)
{
    die("Could not connect to MySQL database, check the settings in config.php");
}
$config = mb_config($database);
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
<script type="text/javascript" src="../includes/js/MB.js"></script>
<script type="text/javascript" src="../includes/js/MB.postmodel.js"></script>
<script type="text/javascript" src="../includes/js/MB.postslist.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    width: 1000,
    height: 400,
    browser_spellcheck: true,
    plugins: ["image", "link"],
    target_list: [
        {title: 'None', value: ''},
        {title: 'New page', value: '_blank'},
        {title: 'Same page', value: '_self'}
    ]
 });
</script>
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
        <li><a href="index.php">Posts</a></li>
        <li><a href="edit.php?mode=add">New post</a></li>
        <li><a href="options.php">Options</a></li>
        <li><a href="password.php">Change password</a></li>
        <li><a href="login.php?logout=true">Logout</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->

    </div><!-- /.container-fluid -->
</nav>
