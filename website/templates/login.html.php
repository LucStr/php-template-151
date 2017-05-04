<h1>Login</h1>
<form action="/login" method="POST">
	<a href="/register">Kein Account?</a>
	<label>Username:</label>
	<input type="text" name="username" value="<?= (isset($username)) ? $username : "" ?>"/>
	<label>Passoword:</label>
	<input type="password" name="password" />	
	<input value="Login" type="submit" />
</form>