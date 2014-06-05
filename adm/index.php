<?php
if(!defined('IN_ADMIN') || !defined('IN_BLOG'))
{
	header('Location: admin.php');
	exit;
}

// $database should be instantiated by this point by ./admin.php
if(!$database)
{
    die("Database object must exist - something is very wrong");
}

switch($mode)
{
	default:

	case 'list':
		echo "<p>Please use <a href='list.php'>list.php</a> instead.</p>";
	break;


    /**
    * Edit post "view"
    */
	case 'edit':

		$id = $database->real_escape_string($_GET['id']);

		$post_sql = "SELECT * FROM `miniblog` WHERE `post_id` = '{$id}'";
		$result = mb_query($post_sql, $database);
		$post = $result->fetch_assoc();

		if($result->num_rows == 1)
		{

			if(isset($_POST['miniblog_PostBack']))
			{

				$data = $_POST['data'];

				$data['post_category'] = create_category($data['post_category'], $database);

				if($_POST['data']['post_title'] != $post['post_title'])
				{
					$data['post_slug'] = mb_slug($_POST['data']['post_title']);
				}

				$sql = '';
				$i = 1;
				foreach($data as $field => $value)
				{
					if($value == '')
					{
						$failed = true;
						break;
					}

					$sql .= "`" . $database->real_escape_string($field) . "` = '" . $database->real_escape_string($value) . "'";
					$sql .= ($i == sizeof($data)) ? '' : ', ';

					$i++;
				}

				if($failed)
				{
					$response_text = 'Error: You must fill out all fields';
				}
				else
				{
					$sql = "UPDATE `miniblog` SET {$sql} WHERE `post_id` = '{$id}'";
					// Update the post
					mb_query($sql, $database);
					// Get it back again
					$result = mb_query($post_sql, $database);
					$post = $result->fetch_assoc();

					$response_text = 'Post updated';
					if($result && $data['published'] == 1)
                    {
                        // If the post was successful and was published, add a link to it
                        $response_text .= " - <a href='../?post=".$post['post_slug']."'>view post</a>";
                    }
				}
			}


			include('edit.php');
		}
	break;

	/**
	 * Options "view"
	 */
	case 'options':

		if(isset($_POST['miniblog_PostBack']))
		{

				$data = $_POST['data'];

				foreach($data as $name => $value)
				{

					if($value == '')
					{
						$failed = true;
						break;
					}

					$name = $database->real_escape_string($name);
					$value = $database->real_escape_string($value);

					$sql = "UPDATE `miniblog_config` SET `config_value` = '{$value}' WHERE `config_name` = '{$name}'";
					mb_query($sql, $database);

				}

				if($failed)
				{
					$response_text = 'Error: You must fill out all fields';
				}
				else
				{
					$response_text = 'Options updated';
				}


		}

		$sql = "SELECT * FROM `miniblog_config` WHERE `config_name` <> 'password'";
		$result = mb_query($sql, $database);

		while($row = $result->fetch_assoc())
		{
			$option_list .= "<div class='form-group'>
								<label for=\"{$row['config_name']}\">" . str_replace('-', ' ', trim(ucfirst($row['config_name']))) . "</label><br />
								<input class='form-control' type=\"text\" name=\"data[{$row['config_name']}]\" value=\"" . stripslashes($row['config_value']) . "\" id=\"{$row['config_name']}\" /><br /><span class=\"form-text\">{$row['config_explain']}</span>
							</div>";
		}

		include('options.php');

	break;

	/**
    * Add post "view"
    */
	case 'add':

		if(isset($_POST['miniblog_PostBack']))
		{
				$data = $_POST['data'];
				$failed = false;
				$data['post_slug'] = mb_slug($_POST['data']['post_title'], $database);
				$data['date'] = time();
				$data['post_category'] = create_category($data['post_category'], $database);
				$sql = '';
				$i = 1;
				foreach($data as $field => $value)
				{
					if($value == '')
					{
						$failed = true;
						break;
					}
					$fields .= "`" . $database->real_escape_string($field) . "`";
					$values .= "'" . $database->real_escape_string($value) . "'";

					$values .= ($i == sizeof($data)) ? '' : ', ';
					$fields .= ($i == sizeof($data)) ? '' : ', ';

					$i++;
				}

				$post = $_POST['data'];

				if($failed)
				{
					$response_text = 'Error: You must fill out all fields';
				}
				else
				{
					$sql = "INSERT INTO `miniblog` ({$fields}) VALUES ({$values})";
					$result = mb_query($sql, $database);
					$response_text = ($result) ? 'Post added' : 'Post could not be added';
					if($result && $data['published'] == 1)
					{
					    // If the post was successful and was published, add a link to it
					    $response_text .= " - <a href='../?post=".$data['post_slug']."'>view post</a>";
					}
				}

		}

		include('edit.php');

	break;

    /**
    * Delete post "view"
    */
	case 'delete':

		$id = $database->real_escape_string($_GET['id']);

		$post_sql = "SELECT * FROM `miniblog` WHERE `post_id` = '{$id}'";
		$result = mb_query($post_sql, $database);

		if($result->num_rows == 1)
		{
		    $sql = "DELETE FROM `miniblog` WHERE `post_id` = '{$id}'";
			$result = mb_query($sql, $database);
			if($result)
			{
				header("Location: list.php");
			}
			else
			{
				die($database->error);
			}
		}
		else
		{
			header("Location: list.php");
		}
	break;

	/**
    * Login "view"
    */
	case 'login':

		if(isset($_POST['SimplePoll_Login']))
		{
			if(md5($_POST['password']) == PASSWORD)
			{
				session_start();
				$_SESSION['miniblog_Admin'] = true;
				$_SESSION['miniblog_AdminPass'] = PASSWORD;
				define('miniblog_ID', md5(time()));

				header('Location: list.php');
			}
			else
			{
				$error_text = 'Incorrect password';
			}
		}

		include('login.php');

	break;

	/**
    * Password "view"
    */
	case 'password':

		if(isset($_POST['miniblog_PostBack']))
		{

			if($_POST['current_password'] != '' && $_POST['new_password'] != '' && $_POST['confirm_password'] != '')
			{
				$current_password = md5($_POST['current_password']);
				$new_password	  = md5($_POST['new_password']);
				$confirm_password = md5($_POST['confirm_password']);

				$sql = "SELECT `config_value` FROM `miniblog_config` WHERE `config_name` = 'password'";
				$result = mb_query($sql, $database);
				$real_current_pass = $result->field_seek(0);

				if($current_password == $real_current_pass)
				{

					if($new_password == $confirm_password)
					{
					    $sql = "UPDATE `miniblog_config` SET `config_value` = '{$new_password}' WHERE `config_name` = 'password'";
						$result = mb_query($sql, $database);
						if($result)
						{
							$response_text = 'Password updated';
						}
						else
						{
							$response_text = 'Could not update password';
						}
					}
					else
					{
						$response_text = 'Both passwords must match';
					}

				}
				else
				{
					$response_text = 'Current password incorrect';
				}
			}
			else
			{
				$response_text = 'You must fill out all fields';
			}
		}

		include('password.php');

	break;

    /**
    * Logout "view"
    */
	case 'logout':
		$_SESSION['miniblog_Admin'] = false;
		unset($_SESSION['miniblog_Admin']);
		unset($_SESSION['miniblog_AdminPass']);
		session_destroy();
		header('Location: admin.php?mode=login');
	break;

}
?>
