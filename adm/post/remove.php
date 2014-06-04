<?php
define('IN_BLOG', true);
define('IN_ADMIN', true);
// define('PATH', '');

include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

if(isset($_REQUEST['postid']))
{
    $post_id = (int)$_REQUEST['postid'];
}
else
{
    die("postid is required for this endpoint");
}

if($post_id)
{
    // Delete the post
    $sql = "DELETE FROM `miniblog` where `post_id` = $post_id";
    $result = mb_query($sql, $database);

    header('Content-Type: application/json');
    if($result)
    {
        echo json_encode(array("deleted"=>true));
    }
}
