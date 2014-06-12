<?php
include('header.php');

if(isset($_POST['miniblog_PostBack']))
{

	if($_POST['current_password'] != '' && $_POST['new_password'] != '' && $_POST['confirm_password'] != '')
	{
		$current_password = password_hash($_POST['current_password'], PASSWORD_DEFAULT);
		$new_password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
		$new_password = $database->real_escape_string($_POST['new_password']);
		$confirm_password = $database->real_escape_string($_POST['confirm_password']);

		$sql = "SELECT `password` FROM `miniblog_user` WHERE `username` = 'admin'";
		$result = mb_query($sql, $database);
		$real_current_pass = $result->field_seek(0);

		if($current_password == $real_current_pass)
		{
			if($new_password == $confirm_password)
			{
				$sql = "UPDATE `miniblog_user` SET `password` = '{$new_password_hash}' WHERE `username` = 'admin'";
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
?>

<div class="page">
	<h1 class="edit">Edit password</h1>
	<p>Update the admin panel password using the form below. Once the password is updated you'll be required to login to the admin panel again</p>

	<span class="error-text"><?=$response_text?></span>
	<form action="password.php" method="post" role="form">

	<div class="form-group">
		<label for="current-password">Current password:</label><br />
		<input class="form-control" type="password" name="current_password" id="current-password" />
	</p>

	<div class="form-group">
		<label for="new-password">New password:</label><br />
		<input class="form-control" type="password" name="new_password" id="new-password" />
	</p>

	<div class="form-group">
		<label for="confirm-password">Confirm new password:</label><br />
		<input class="form-control" type="password" name="confirm_password" id="confirm-password" />
	</p>

	<div class="form-group">
		<input class="btn btn-primary" type="submit" name="miniblog_PostBack" value="Change password" />
	</p>



</div>

<?php
include('footer');
?>
