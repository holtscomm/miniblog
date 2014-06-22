<?php
include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

$post_id = (int) get_value($_REQUEST, 'postId', 0);

if($post_id)
{
    // Delete the post
    $result = delete_post($post_id, $database);

    header('Content-Type: application/json');
    if($result)
    {
        echo json_encode(array("deleted"=>true));
    }
}
else
{
    die('postId is required for this endpoint');
}
