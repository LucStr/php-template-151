<h1>Forgot Password</h1>
<form action="/Registration/RequestPasswordResetEmail" method="POST">
	<?= $html->renderCSRF() ?>
	<label>Username:</label>
	<input type="text" name="username"/><br>
	<input value="Request Mail" type="submit" />
</form>