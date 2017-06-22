<h1>Registration</h1>
<form action="/Registration/Register" method="POST">
	<?= $html->renderCSRF() ?>
	<label>Email:</label>
	<input id="email" type="text" name="email" value="<?= (isset($email)) ? $email : "" ?>"/>
	<label>Username:</label>
	<input id="username" type="text" name="username" value="<?= (isset($username)) ? htmlentities($username) : "" ?>"/>
	<p style="display: none;color: red;" id="usernametaken">This username is already taken</p>
	<label>Password:</label>
	<input type="password" name="password" />
	<input value="Register" type="submit" />
</form>

<script>
$("#username").focusout(function() {
	console.log("entered");
    $.ajax({
        url: "/Registration/CheckUsername",
        data: {
            username: $("#username").val()
        },
        success: function(data) {
            console.log(data);
            if (data == 1) {
                $("#usernametaken").show();
            } else{
            	$("#usernametaken").hide();
            }
        },
    });
});
</script>