<?php
define('IN_BLOG', true);
define('IN_ADMIN', true);
// define('PATH', '');

include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

// Optional 'limit' parameter
$limit = 1000;
if(isset($_REQUEST['limit']))
{
    $limit = (int) intval($_REQUEST['limit']);
}

$sql = "SELECT * FROM `miniblog` ORDER BY date DESC LIMIT 0, $limit";

$posts = mb_query($sql, $database);

$return = array();

while($row = $posts->fetch_assoc())
{
    // Grab the post category name, to get rid of a race condition in the posts list
    $row["post_category_name"] = get_category_name_for_id($row["post_category"], $database);
    $return[] = $row;
}

// Return it via an "api" (so fancy)
header('Content-Type: application/json');
echo json_encode($return);
