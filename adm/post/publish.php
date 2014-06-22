<?php
define('IN_BLOG', true);
define('IN_ADMIN', true);
// define('PATH', '');

include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

$post_id = get_value($_REQUEST, 'postId', false);
$published = get_value($_REQUEST, 'published', false);

if($post_id)
{
    $post_is_published = publish_post($post_id, $published, $database);

    header('Content-Type: application/json');
    $return = array(
        "published" => $post_is_published
    );
    echo json_encode($return);
}
