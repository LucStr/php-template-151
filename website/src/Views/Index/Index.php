<h1>Browsergame</h1>
<div>This Browsergame was developped by Luca Strebel</div>
<?php
if(isset($_SESSION["username"])){
	?>
		Hello <?= htmlentities($_SESSION["username"]) ?>,<br>
		You are logged in, Do you want to enter <a href="/Village">here</a>?
		<?php 
	} else{
		?>
		You need to <a href="/Login">log in</a>
		<?php
	}
?>