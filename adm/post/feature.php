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
    $post_id = $_REQUEST['postid'];
}
else if(!isset($_REQUEST['postid']))
{
    $post_id = null;
}
else
{
    die("postid is required for this endpoint");
}

header('Content-Type: application/json');
// Set featured post
if($post_id)
{
    // Set every other post's featured flag to false
    $sql = "UPDATE `miniblog` SET `featured` = 0 WHERE `post_id` != $post_id";
    $result1 = mb_query($sql, $database);

    // Then set the desired post to be the featured one!
    $sql = "UPDATE `miniblog` SET `featured` = 1 WHERE `post_id` = $post_id";
    $result2 = mb_query($sql, $database);

    if($result1 && $result2)
    {
        $return = array(
            "featured" => $post_id
        );
        echo json_encode($return);
    }
}
// Get featured post
else if($post_id == null)
{
    $sql = "SELECT `post_id` FROM `miniblog` WHERE `featured` = 1";
    $result = mb_query($sql, $database);

    if($result)
    {
        $post_id = $result->fetch_assoc();
        $return = array(
            "featured" => (int) $post_id['post_id']
        );
        echo json_encode($return);
    }
}
