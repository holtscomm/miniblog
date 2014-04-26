<!DOCTYPE html>
<html lang="en">
<head>
<title>Miniblog Admin</title>
<link rel="stylesheet" href="images/styles.css" type="text/css" />
<script type="text/javascript" src="images/dialog.js"></script>
<script type="text/javascript" src="../includes/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    width: 1000,
    height: 400,
    browser_spellcheck: true,
    plugins: ["image", "link"],
    target_list: [
        {title: 'None', value: ''},
        {title: 'New page', value: '_blank'},
        {title: 'Same page', value: '_self'}
    ]
 });
</script>
</head>
<body>
<div class="navigation">
	<ul>
		<li><a href="admin.php?mode=list">Posts</a></li>
		<li><a href="admin.php?mode=add">New post</a></li>
		<li><a href="admin.php?mode=options">Options</a></li>
		<li><a href="admin.php?mode=password">Change password</a></li>
		<li><a href="admin.php?mode=logout" onclick="return confirm_dialog('admin.php?mode=logout', 'Are you sure you want to logout?');">Logout</a></li>
	</ul>
	<br class="clear" />
	
</div>
