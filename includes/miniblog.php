<?php
/*
	miniblog v 1.0.0
	copyright 2009
*/
if(!defined('IN_BLOG'))
{
	exit;
}

include(PATH . 'includes/config.php');
include(PATH . 'includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

if(!$database)
{
	die("Could not connect to MySQL database, check the settings in config.php");
}

$config = mb_config($database);

$post	= (string) mysql_real_escape_string($_GET['post']);
$page	= (int) mysql_real_escape_string(intval($_GET['page']));
$ppp	= (int) intval($config['posts-per-page']);
$from	= (int) intval($ppp * $page);


$sql = ($post == '') 
            ? 'SELECT * FROM `miniblog` WHERE `published` = 1 ORDER BY `date` DESC LIMIT ' . $from . ', ' . $ppp 
            : "SELECT * FROM `miniblog` WHERE `post_slug` = '{$post}' AND `published` = 1";

$result = mb_query($sql, $database);

$total_post_sql = "SELECT COUNT(*) FROM `miniblog` WHERE `published` = 1";
$total_result = mb_query($total_post_sql, $database);
$total = $total_result->field_seek(0);

if($result->num_rows > 0)
{ 
	while($posts = $result->fetch_assoc())
	{
		
		$vars = array(
			'$postid$'		=> $posts['post_id'],
			'$posturl$'		=> ($config['use-modrewrite'] == 1) ? $posts['post_slug'] : $config['miniblog-filename'] . '?post=' . $posts['post_slug'],
			'$posttitle$'	=> stripslashes($posts['post_title']),
			'$postdate$'	=> date($config['date-format'], $posts['date']),
			'$postcontent$'	=> stripslashes($posts['post_content']),
		);
		
		$template_vars		= array_keys($vars);
		$template_values	= array_values($vars);
		
		$output = file_get_contents(PATH . 'includes/template.html');
		$output = str_replace($template_vars, $template_values, $output);
		
		$miniblog_posts .= $output;
	}
}

$single = ($post == '') ? false : true;

if($total > ($from + $ppp))
{
	$miniblog_previous = '<a href="' . $config['miniblog-filename'] . '?page=' . ($page + 1)  . '">&laquo; Older posts</a>';
}
if($from > 0)
{
	$miniblog_next = '<a href="' . $config['miniblog-filename'] . '?page=' . ($page - 1)  . '">Recent posts &raquo;</a>';
}
?>