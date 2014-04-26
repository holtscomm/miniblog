<?php
define('IN_BLOG', true);
define('IN_ADMIN', true);
// define('PATH', '');

include('../../includes/config.php');
include('../../includes/functions.php');

$database = mb_connect($sqlconfig);
unset($sqlconfig);

$post_id = $_REQUEST['postid'];
$published = $_REQUEST['published'];

if($post_id)
{
    // Flip dat bit
    $new_publish = $published == 1 ? 0 : 1;
    $sql = "UPDATE miniblog SET published = {$new_publish} WHERE post_id = $post_id";
    $result = mb_query($sql, $database);
    
    if($result)
    {
        // Send them back to the list of posts for now...
        header('Location: ../admin.php?mode=list');
        exit;
    }
}