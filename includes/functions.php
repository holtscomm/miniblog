<?php

function mb_connect($sqlconfig)
{
    $database = new mysqli($sqlconfig['host'], $sqlconfig['username'], $sqlconfig['password']);
	$database->select_db($sqlconfig['dbname']);
	return $database;
}

function mb_query($query, $database)
{
    if(!$database)
    {
        die("Database connection is required");
    }
    return $database->query($query);
}

function mb_config($database)
{
	$result = mb_query("SELECT * FROM `miniblog_config`", $database);
	$config = array();
	while($row = $result->fetch_assoc())
	{
		$config[$row['config_name']] = $row['config_value'];
	}
	$result->close();
	return $config;
}

function mb_slug($string, $database)
{
	$string = strtolower(trim($string));
	$string = str_replace(' ', '-', $string);
	$slug = preg_replace('/[^a-z0-9-]/', '', $string);

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

function generate_option_list($list_items = array(), $selected)
{
	$html = "";
    foreach($list_items as $value => $label)
    {

        $html .= ($selected == $value) ? "<option value=\"{$value}\" selected=\"selected\">{$label}</option>" : "<option value=\"{$value}\">{$label}</option>";

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
 * This function takes a category name and will return the category id if it exists,
 * or false if it does not.
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
 * This function takes no parameters (besides a database connection) and returns an
 *  associative array of the details about the featured post (including content).
 */
function get_featured_post($database)
{
	// Don't show a non-published post, ever!
	$sql = "SELECT * FROM `miniblog` WHERE `featured` = 1 AND `published` = 1";

	$result = mb_query($sql, $database);

	$assoc_result = $result->fetch_assoc();

	// Can't do this all in one step anymore, some PHP installations don't like it very much.
	return $assoc_result;
}

/**
 * fill_post_template
 *
 * This function takes the associative array of a post and converts it into the templated
 *  HTML for that post.
 * Parameters (along with database connection):
 *  $post - associative array of post data
 */
function fill_post_template($post, $database)
{
	$config = mb_config($database);
	$post_category_name = get_category_name_for_id($post['post_category'], $database);
	$vars = array(
		'$postid$' => $post['post_id'],
		'$posturl$' => ($config['use-modrewrite'] == 1) ? $post['post_slug'] : $config['miniblog-filename'] . '?post=' . $post['post_slug'],
		'$posttitle$' => stripslashes($post['post_title']),
		'$postdate$' => date($config['date-format'], $post['date']),
		'$postcontent$' => stripslashes($post['post_content']),
		'$postcategoryname$' => $post_category_name
	);

	$template_vars = array_keys($vars);
	$template_values = array_values($vars);

	$output = file_get_contents(PATH . 'includes/template.html');
	$output = str_replace($template_vars, $template_values, $output);

	return $output;
}

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
