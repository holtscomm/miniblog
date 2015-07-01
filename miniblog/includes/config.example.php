<?php

if(strstr($_SERVER['SERVER_NAME'], "localhost"))
{
	// localhost config options
	$sqlconfig = array();
	$sqlconfig['username'] = ''; // database username
	$sqlconfig['password'] = ''; // database password
	$sqlconfig['host'] = ''; // database host (usually localhost)
	$sqlconfig['port'] = 1; // database port
	$sqlconfig['dbname'] = ''; // database name
}
else if(strstr($_SERVER['SERVER_NAME'], "prod"))
{
	// sandbox config options
	$sqlconfig = array();
	$sqlconfig['username'] = ''; // database username
	$sqlconfig['password'] = '!'; // database password
	$sqlconfig['host'] = ''; // database host (usually localhost)
	$sqlconfig['port'] = 1; // database port
	$sqlconfig['dbname'] = ''; // database name
}

?>
