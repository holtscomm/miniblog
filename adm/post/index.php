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
    $return[] = $row;
}

// Return it via an "api" (so fancy)
header('Content-Type: application/json');
echo json_encode($return);
