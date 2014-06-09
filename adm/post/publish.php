<?php
define('IN_BLOG', true);
define('IN_ADMIN', true);
// define('PATH', '');

include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

if(isset($_REQUEST['postid']) && isset($_REQUEST['published']))
{
    $post_id = $_REQUEST['postid'];
    $published = $_REQUEST['published'];
}
else
{
    die("postid and published are required for this endpoint");
}

if($post_id)
{
    // Flip dat bit
    $new_publish = $published ? 0 : 1;
    $message = "new publish will be: $new_publish, published is $published";
    $sql = "UPDATE `miniblog` SET `published` = {$new_publish} WHERE `post_id` = $post_id";
    $result = mb_query($sql, $database);

    header('Content-Type: application/json');
    if($result)
    {
        $return = array(
            "published" => $new_publish,
            "message" => $message
        );
        echo json_encode($return);
    }
}
