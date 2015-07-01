<?php
/**
 * Functions for miniblog.
 *  The majority of these functions require a $database arg, which is the return value
 *  from mb_connect().
 */

/**
 * mb_connect
 *
 * Connect to the database using the configs from config.php
 *
 * Args:
 *    argA
 */
function mb_connect($sqlconfig)
{
    $database = new mysqli($sqlconfig['host'], $sqlconfig['username'], $sqlconfig['password']);
    $database->select_db($sqlconfig['dbname']);
    return $database;
}

/**
 * mb_query
 *
 * Submit a query to the database and return the result.
 *    Read http://www.php.net//manual/en/class.mysqli-result.php for more info
 *
 * Args:
 *    $query - an SQL string to execute on the database
 */
function mb_query($query, $database)
{
    if(!$database)
    {
        die("Database connection is required");
    }
    return $database->query($query);
}

/**
 * mb_slug
 *
 * Finds a unique slug for a string passed in
 *
 * Args:
 *    $post_title - will be used to make a slug, spaces replaced by dashes, etc.
 */
function mb_slug($post_title, $database)
{
    $post_title = strtolower(trim($post_title));
    $post_title = str_replace(' ', '-', $post_title);
    $slug = preg_replace('/[^a-z0-9-]/', '', $post_title);

    $i = 0;
    if(mb_slug_exists($slug, $database))
    {
        $i++;
        while(mb_slug_exists($slug . '-' . $i, $database))
        {
            $i++;
        }

        $slug = ($i == 0) ? $slug : $slug . '-' . $i;
    }

    return $slug;
}

/**
 * mb_slug_exists
 *
 * Check if a slug already exists in the database
 *
 * Args:
 *    $slug - the slug to check against in the database
 */
function mb_slug_exists($slug, $database)
{
    $slug = $database->real_escape_string($slug);
    $sql = "SELECT `post_id` FROM `miniblog` WHERE `post_slug` = '{$slug}' LIMIT 0, 1";
    $results = mb_query($sql, $database);

    $slug_exists = null;
    if($results->num_rows == 1)
    {
        $result->close();
        $slug_exists = true;
    }
    else
    {
        $slug_exists = false;
    }

    return $slug_exists;
}

/**
 * generate_option_list
 *
 * Generates HTML for a select input
 *
 * Args:
 *    $list_items - The items in the select box
 *    $selected - the value of the item that is selected
 */
function generate_option_list($list_items = array(), $selected)
{
    $html = "";
    foreach($list_items as $value => $label)
    {

        $html .= ($selected == $value) ? "<option value=\"{$value}\" selected=\"selected\">{$label}</option>" :
                                         "<option value=\"{$value}\">{$label}</option>";

    }
    return $html;
}

/**
 * get_category_names_for_ids
 *
 * This function takes a comma-separated list of category_ids and returns the ids and names
 * in an array of arrays like:
 * $category_names = {
 *     {
 *         "cat_id" => 2,
 *         "name" => "pizza"
 *     },
 *     {
 *         "cat_id" => 65,
 *         "name" => "hamburgers"
 *     }
 * }
 *
 * Args
 *    $category_ids - array of category ids
 */
function get_category_names_for_ids($category_ids, $database)
{
    $category_names = array();

    foreach(explode($category_ids, ",") as $category_id)
    {
        $category_names[] = array("cat_id" => $category_id,
                                  "name" => get_category_name_for_id($category_id, $database));
    }

    $result->close();

    return $category_names;
}

/**
 * get_category_name_for_id
 *
 * Returns a category name for an id
 *
 * Args:
 *    $category_id - category id in the database
 */
function get_category_name_for_id($category_id, $database)
{
    $category_name = null;
    $category_id_clean = $database->real_escape_string($category_id);
    $sql = "SELECT * FROM miniblog_category WHERE cat_id = {$category_id_clean}";
    $result = mb_query($sql, $database);
    if($result)
    {
        $name = $result->fetch_assoc();
        $category_name = $name["name"];
    }
    return $category_name;
}

/**
 * category_exists
 *
 * Takes a category name and will return the category id if it exists,
 * or false if it does not.
 *
 * Args
 *    $category_name - name of the category
 */
function get_category_id($category_name, $database)
{
    $category_name_clean = $database->real_escape_string($category_name);

    $sql = "SELECT `cat_id` FROM `miniblog_category` WHERE `name` = '{$category_name_clean}'";
    $result = mb_query($sql, $database);

    $category_exists = false;

    if($result && $result->num_rows >= 1)
    {
        $category = $result->fetch_assoc();
        $category_exists = $category["cat_id"];
    }

    $result->close();
    return $category_exists;
}

/**
 * create_category
 *
 * This function takes a category name and will try to create it in the database.
 * This function will recursively call itself until it succeeds.
 *
 * Args
 *    $category_name - name of the category to be created
 */
function create_category($category_name, $database)
{
    $category_name_clean = $database->real_escape_string($category_name);

    $category_exists = get_category_id($category_name_clean, $database);

    if(!$category_exists)
    {
        $sql = "INSERT INTO `miniblog_category` SET `name` = '{$category_name_clean}'";
        mb_query($sql, $database);
        $category_exists = create_category($category_name_clean, $database);
    }

    return $category_exists;
}

/**
 * get_featured_post
 *
 * This function takes no parameters and returns an associative array of the details
 * about the featured post (including content). If $id_only is true, only the post_id
 * will be returned.
 *
 * Args
 *    $id_only - Whether this function should return the post_id or the full post item
 */
function get_featured_post($database, $id_only=false)
{
    // Don't show a non-published post, ever!
    $sql = "SELECT * FROM `miniblog` WHERE `featured` = 1 AND `published` = 1";

    $result = mb_query($sql, $database);

    $assoc_result = $result->fetch_assoc();
    if($id_only)
    {
        $assoc_result = $assoc_result["post_id"];
    }
    // Can't do this all in one step anymore, some PHP installations don't like it very much.
    return $assoc_result;
}

/**
 * set_featured_post
 *
 * Takes a post id and sets the featured value on it to 1 in the database. Sets
 * all other posts featured value to be 0.
 *
 * Args:
 *    $post_id - post to set the featured value on
 */
function set_featured_post($post_id, $database)
{
    $return = false;

    if(!$post_id)
    {
        return false;
    }
    else
    {
        $post_id = $database->real_escape_string($post_id);
    }
    // Set every other post's featured flag to false
    $sql = "UPDATE `miniblog` SET `featured` = 0 WHERE `post_id` != $post_id";
    $result1 = mb_query($sql, $database);

    // Then set the desired post to be the featured one!
    $sql2 = "UPDATE `miniblog` SET `featured` = 1 WHERE `post_id` = $post_id";
    $result2 = mb_query($sql2, $database);


    if($result1 && $result2)
    {
        $return = true;
    }

    return $return;
}

/**
 * fill_post_template
 *
 * This function takes the associative array of a post and converts it into the templated
 * HTML for that post.
 *
 * Args:
 *  $post - associative array of post data
 */
function fill_post_template($post, $database)
{
    $config = get_options($database);
    $post_category_name = get_category_name_for_id($post['post_category'], $database);

    $vars = array(
        'postId' => $post['post_id'],
        'postUrl' => ($config['use-modrewrite'] == 1) ? $post['post_slug'] : $config['miniblog-filename'] . '?post=' . $post['post_slug'],
        'postTitle' => stripslashes($post['post_title']),
        'postDate' => date($config['date-format'], $post['date']),
        'postContent' => stripslashes($post['post_content']),
        'postCategoryName' => $post_category_name,
        'postCategoryLink' => $post['post_category'] != null ? "?category={$post_category_name}" : ""
    );

    return $vars;
}

/**
 * get_user_from_db
 *
 * Takes in a username and password and attempts to get a user from the database.
 * If it exists a new User object will be returned, otherwise false (so the login failed)
 *
 * Args:
 *    $username - Username of the user attempting login
 *    $password - Password associated with the username passed in
 */
function get_user_from_db($username, $password, $database)
{
    $user_object = false;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "SELECT * FROM `miniblog_user` WHERE `username` = '{$username}' AND `password` = '{$hashed_password}'";
    $id = $dbusername = $dbpassword = null;

    $result = mb_query($sql, $database);

    if($result)
    {
        $user_info = $result->fetch_assoc();
        $dbusername = $user_info['username'];
        $dbpassword = $user_info['password'];
        $id = $user_info['user_id'];
        $user_object = new User($dbusername, $dbpassword, $id);
    }

    return $user_object;
}

/**
 * get_value
 *
 * Takes an array, an index, and a default value to return if that index does not exist.
 * If the index exists in the array, the value at that index will be returned. Else, the default.
 *
 * Args:
 *   $array - an array to get a value from
 *   $expected_index - an index that you want to retrieve the value for
 *   $default - the value you want if the index doesn't exist
 */
function get_value($array, $expected_index, $default)
{
    if(isset($array[$expected_index]))
    {
        return $array[$expected_index];
    }
    else
    {
        return $default;
    }
}

/**
 * get_options
 *
 * Returns an indexed array of config names to config values for the miniblog installation.
 *
 * Args:
 *    $config_name - if you want just one config, pass in the config name from the database
 */
function get_options($database, $config_name=false)
{
    $sql = "SELECT * FROM `miniblog_config`";

    if($config_name)
    {
        $config_name = $database->real_escape_string($config_name);
        $sql .= " WHERE `config_name` = '{$config_name}'";
    }

    $result = mb_query($sql, $database);

    $result_array = array();
    while($row = $result->fetch_assoc())
    {
        $result_array[$row["config_name"]] = $row["config_value"];
    }

    return $result_array;
}

/**
 * get_posts
 *
 * Get posts by id or get all posts. Pass a limit or an order by column as well.
 *
 * Args:
 *    $post_id - The id of the post to retrieve, or null to get all posts
 *    $limit - Limit on how many posts to retrieve
 *    $orderby - Column to order by, e.g. date desc
 */
function get_posts($post_id, $limit=1000, $orderby="date", $database)
{
    if(!$post_id instanceof int)
    {
        $post_id = null;
    }
    $sql = "SELECT * FROM `miniblog`";

    if($post_id)
    {
        $post_id = $database->real_escape_string($post_id);
        $sql .= " WHERE `post_id` = {$post_id}";
    }

    if($orderby)
    {
        $orderby = $database->real_escape_string($orderby);
        $sql .= " ORDER BY {$orderby}";
    }

    if($limit)
    {
        $limit = $database->real_escape_string($limit);
        $sql .= " LIMIT 0, {$limit}";
    }

    $posts = mb_query($sql, $database);

    $return = array();

    while($row = $posts->fetch_assoc())
    {
        // Grab the post category name, to get rid of a race condition in the posts list
        $row["post_category_name"] = get_category_name_for_id($row["post_category"], $database);
        $return[] = $row;
    }

    return $return;
}

/**
 * publish_post
 *
 * Publish a post by post_id
 *
 * Args:
 *    $post_id - Id of the post to publish
 *    $published - 1 if the post is currently published, 0 if it is not
 */
function publish_post($post_id, $published, $database)
{
    $return = false;

    $post_id = $database->real_escape_string($post_id);
    $published = $database->real_escape_string($published);

    // Flip dat bit
    $new_publish = $published == 1 ? 0 : 1;
    $sql = "UPDATE `miniblog` SET `published` = {$new_publish} WHERE `post_id` = {$post_id}";
    $result = mb_query($sql, $database);
    if($result)
    {
        $return = $new_publish;
    }
    return $return;
}

/**
 * delete_post
 *
 * Delete a post by id
 *
 * Args:
 *    $post_id - Id of the post to delete
 */
function delete_post($post_id, $database)
{
    $post_id = $database->real_escape_string($post_id);
    // Delete the post
    $sql = "DELETE FROM `miniblog` where `post_id` = {$post_id}";
    $result = mb_query($sql, $database);

    return $result;
}
