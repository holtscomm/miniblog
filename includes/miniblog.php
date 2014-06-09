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
$total_array = $total_result->fetch_array();
$total = $total_array[0];

$miniblog_posts = "";

if($result->num_rows > 0)
{
	while($post = $result->fetch_assoc())
	{
		$output = fill_post_template($post, $database);

		$miniblog_posts .= $output;
	}
}

$featured_post_db_array = get_featured_post($database);
$featured_post = $featured_post_db_array ? fill_post_template($featured_post_db_array, $database) : null;

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
