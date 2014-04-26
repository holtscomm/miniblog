<?php
if(!defined('IN_BLOG'))
{
	exit;
}

function mb_connect($sqlconfig)
{
    $database = new mysqli($sqlconfig['host'], $sqlconfig['username'], $sqlconfig['password']);
	$database->select_db($sqlconfig['dbname']);
	return $database;
}

function mb_query($query, $database)
{
    if(!$database)
    {
        die("Database connection is required");
    }
    return $database->query($query);
}

function mb_config($database)
{
	$result = mb_query("SELECT * FROM `miniblog_config`", $database);
	$config = array();
	while($row = $result->fetch_assoc())
	{
		$config[$row['config_name']] = $row['config_value'];
	}
	return $config;
}

function mb_slug($string, $database)
{
	$string = strtolower(trim($string));
	$string = str_replace(' ', '-', $string);
	$slug = preg_replace('/[^a-z0-9-]/', '', $string);
	
	$i = 0;
	if(mb_slug_exists($slug, $database))
	{
		$i++;
		while(mb_slug_exists($slug . '-' . $i, $database))
		{
			$i++;
		}
	
		$slug = ($i == 0) ? $slug : $slug . '-' . $i;
	}
	
	return $slug;
}

function mb_slug_exists($slug, $database)
{
	$slug = mysql_real_escape_string($slug);
	$sql = "SELECT `post_id` FROM `miniblog` WHERE `post_slug` = '{$slug}' LIMIT 0, 1";
	$results = mb_query($sql, $database);
	
	if($results->num_rows == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function generate_option_list($list_items = array(), $selected)
{ 
    foreach($list_items as $value => $label)
    {
    
        $html .= ($selected == $value) ? "<option value=\"{$value}\" selected=\"selected\">{$label}</option>" : "<option value=\"{$value}\">{$label}</option>";
        
    }
    return $html;
}
?>