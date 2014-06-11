<?php
include('header.php');

$mode = $_GET['mode'];

if(isset($_GET['mode']) && $_GET['mode'] == 'add')
{
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
}
else if(isset($_GET['mode']) && $_GET['mode'] == 'edit')
{
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
	}
}
?>

<div class="page">
	<h1 class="edit"><?=ucfirst($mode)?> post</h1>
	<span class="error-text"><?=$response_text?></span>
	<form role="form" action="edit.php?mode=<?=$mode?>&id=<?=$post['post_id']?>" method="post">
	<div class="form-group">
		<label for="title">Post title:</label><br />
		<input class="form-control" type="text" size="80" id="title" name="data[post_title]" value="<?=htmlspecialchars(stripslashes($post['post_title']))?>" />
	</div>

	<div class="form-group">
		<label for="content">Post content:</label><br />
		<textarea cols="77" rows="10" id="content" name="data[post_content]"><?=htmlspecialchars(stripslashes($post['post_content']))?></textarea><br />
	</div>

	<div class="form-group">
	    <label for="categories">Post category:</label><br />
	    <input class="form-control" type="text" size="80" id="categories" name="data[post_category]" value="<?=get_category_name_for_id(htmlspecialchars(stripslashes($post['post_category'])), $database)?>"/><br />
	</div>

	<div class="form-group">
		<label for="status">Post status:</label><br />
		<select id="status" name="data[published]" class="form-control">
			<?=generate_option_list(array('0' => 'Unpublished', '1' => 'Published'), $post['published'])?>
		</select>
	</div>

	<div>
		<input class="btn btn-primary" type="submit" name="miniblog_PostBack" value="<?=ucfirst($mode)?>" />
	</div>
</div>

<?php
include('footer.php');
?>
