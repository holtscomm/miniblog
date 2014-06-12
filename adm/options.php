<?php
include('header.php');

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
?>

<div class="page">
	<h1 class="edit">Edit options</h1>

	<p>From this page you can update the options held in the configuration table. This section is only recommended for advanced users, for most setups the default settings will be fine.</p>
	<p>To update the admin password use the dedicated password page <a href="password.php">here</a></p>


	<span class="error-text"><?=$response_text?></span>

	<form role="form" action="options.php" method="post">

	<?=$option_list?>

	<div>
		<input type="submit" class="btn btn-primary" name="miniblog_PostBack" value="Update options" />
	</div>
</div>

<?php
include('footer.php');
?>
