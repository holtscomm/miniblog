<?php
define('IN_BLOG', true);
define('IN_ADMIN', true);
// define('PATH', '');

include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

// Optional 'limit' parameter
$limit = get_value($_REQUEST, 'limit', 1000);
$post_id = get_value($_REQUEST, 'postId', null);
$order_by = get_value($_REQUEST, 'orderBy', null);

$posts = get_posts($post_id, $limit, $order_by, $database);

// Return it via an "api" (so fancy)
header('Content-Type: application/json');
echo json_encode($posts);
