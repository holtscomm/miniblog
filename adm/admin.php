<?php
ob_start();
session_start();

define('VERSION', '1.0.0');

define('PATH', '../');
define('IN_BLOG', true);
define('IN_ADMIN', true);

include(PATH . 'includes/config.php');
include(PATH . 'includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

if(!$database)
{
	die("Could not connect to MySQL database, check the settings in config.php");
}

$config = mb_config($database);

define('PASSWORD', $config['password']);

$mode = $database->real_escape_string($_GET['mode']);

if(isset($_SESSION['miniblog_Admin']))
{
	if($_SESSION['miniblog_AdminPass'] == PASSWORD)
	{
		define('miniblog_ID', md5(time()));
	}
}
if(!defined('miniblog_ID') && $mode != 'login')
{
	header('Location: admin.php?mode=login');
}

$header = ($mode == 'login') ? 'simple-header.php' : 'header.php';
include($header);
include('index.php');
include('footer.php');
ob_end_flush();
