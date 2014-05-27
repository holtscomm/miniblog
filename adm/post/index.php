<?php
define('IN_BLOG', true);
define('IN_ADMIN', true);
// define('PATH', '');

include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

// Should be calling this without parameters
$sql = "SELECT * FROM `miniblog`";

$posts = mb_query($sql, $database);

$return = array();

while($row = $posts->fetch_assoc())
{
    $return[] = $row;
}

// Return it via an "api" (so fancy)
header('Content-Type: application/json');
echo json_encode($return);
