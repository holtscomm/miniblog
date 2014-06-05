<?php
if(!defined('IN_BLOG'))
{
	exit;
}

if(strstr($_SERVER['SERVER_NAME'], "localhost"))
{
	// localhost config options
	$sqlconfig = array();
	$sqlconfig['username'] = 'root'; // database username
	$sqlconfig['password'] = 'root'; // database password
	$sqlconfig['host'] = 'localhost'; // database host (usually localhost)
	$sqlconfig['port'] = 8889; // database port
	$sqlconfig['dbname'] = 'miniblog'; // database name
}
else if(strstr($_SERVER['SERVER_NAME'], "sandbox"))
{
	// sandbox config options
	$sqlconfig = array();
	$sqlconfig['username'] = 'sandbox_miniblog'; // database username
	$sqlconfig['password'] = 'Supers3cure!'; // database password
	$sqlconfig['host'] = 'localhost'; // database host (usually localhost)
	$sqlconfig['port'] = 8889; // database port
	$sqlconfig['dbname'] = 'sandbox_miniblog'; // database name
}

?>
