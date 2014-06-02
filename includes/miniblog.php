<?php
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

$post	= (string) $database->real_escape_string($_GET['post']);
$page	= (int) $database->real_escape_string(intval($_GET['page']));
$ppp	= (int) intval($config['posts-per-page']);
$from	= (int) intval($ppp * $page);
$category_name = (string) $database->real_escape_string($_GET['category']);
$category_id = $category_name != "" ? get_category_id($category_name, $database) : null;
$preview = (string) $database->real_escape_string($_GET['preview']);

$sql = "SELECT * FROM `miniblog` WHERE";

$sql .= $preview == "" ? "`published` = 1" :
						 "`published` is not null";

if($category_id != null)
{
	$sql .= " AND `post_category` = {$category_id}";
}
if($post != '')
{
	$sql .= " AND `post_slug` = '{$post}'";
}
else
{
	$sql .= " ORDER BY `date` DESC LIMIT $from, $ppp";
}

$result = mb_query($sql, $database);

$total_post_sql = "SELECT COUNT(*) FROM `miniblog` WHERE `published` = 1";
$total_result = mb_query($total_post_sql, $database);
$total = $total_result->fetch_array()[0];

$miniblog_posts = "";

if($result->num_rows > 0)
{
	while($posts = $result->fetch_assoc())
	{
		$post_category_name = get_category_name_for_id($posts['post_category'], $database);
		$vars = array(
			'$postid$' => $posts['post_id'],
			'$posturl$' => ($config['use-modrewrite'] == 1) ? $posts['post_slug'] : $config['miniblog-filename'] . '?post=' . $posts['post_slug'],
			'$posttitle$' => stripslashes($posts['post_title']),
			'$postdate$' => date($config['date-format'], $posts['date']),
			'$postcontent$' => stripslashes($posts['post_content']),
			'$postcategoryname$' => $post_category_name
		);

		$template_vars = array_keys($vars);
		$template_values = array_values($vars);

		$output = file_get_contents(PATH . 'includes/template.html');
		$output = str_replace($template_vars, $template_values, $output);

		$miniblog_posts .= $output;
	}
}

$single = $post != '';
$category_link = $category_id != null ? "&category={$category_name}" : "";
if($total > ($from + $ppp))
{
	$miniblog_previous = '<a href="' . $config['miniblog-filename'] .
						 '?page=' . ($page + 1) . $category_link . '">&laquo; Older posts</a>';
}
if($from > 0)
{
	$miniblog_next = '<a href="' . $config['miniblog-filename'] .
	 				'?page=' . ($page - 1) . $category_link . '">Recent posts &raquo;</a>';
}
?>
