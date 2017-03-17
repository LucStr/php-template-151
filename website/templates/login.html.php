<h1>Login</h1>
<form action="/login" method="POST">
	<label>Email:</label>
	<input type="text" name="email" value="<?= (isset($email)) ? $email : "" ?>"/>
	<label>Passoword:</label>
	<input type="password" name="password" />	
	<input value="Login" type="submit" />
</form>