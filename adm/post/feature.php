<?php
// API for getting or setting the featured post
//  Pass in postId as a GET or POST param to set it as the featured post.
//  Don't pass in postId to get the currently featured post.

include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

$post_id = get_value($_REQUEST, 'postId', false);
$id_only = get_value($_REQUEST, 'idOnly', false);

header('Content-Type: application/json');
// Set featured post
if($post_id)
{
    $featured = set_featured_post($post_id, $database);
    if($featured)
    {
        echo json_encode(array("featured" => (int)$post_id));
    }
}
// Get featured post
else if($post_id == null)
{
    if($id_only)
    {
        $featured_post_id = get_featured_post($database, $id_only);
        echo json_encode(array("featured" => (int)$featured_post_id));
    }
    else
    {
        $featured_post = get_featured_post($database);
        echo json_encode(array("featured" => $featured_post));
    }
}
else
{
    http_response_code(400);
    echo json_encode(array("message" => "postId was " . $post_id));
}
