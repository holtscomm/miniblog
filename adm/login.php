<div class="page">
	<form role="form" action="admin.php?mode=login" method="post">
		<h1 class="login">Login</h1>
		<span class="error-text"><?=$error_text?></span>
		<div class="form-group">
			<label for="password">Control panel password:</label>
			<input class="form-control" id="password" size="30" type="password" name="password" />
		</div>
		<p><input class="btn btn-primary" type="submit" name="SimplePoll_Login" value="Login" /></p>
	</form>
</div>
