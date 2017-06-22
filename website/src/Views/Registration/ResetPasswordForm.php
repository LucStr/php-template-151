<h1>Reset Password</h1>
<form action="/Registration/ResetPassword" method="POST">
	<?= $html->renderCSRF() ?>
	<label>Password:</label>
	<input type="password" name="password"/><br>
	<label>Confirm password:</label>
	<input type="password" name="passwordConfirm"/><br>
	<input type="hidden" name="userId" value="<?= $userId?>"/>
	<input type="hidden" name="confirmationUUID" value="<?= $confirmationUUID?>"/>
	
	<input value="Confirm" type="submit" />
</form>