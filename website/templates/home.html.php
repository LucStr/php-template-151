<h1>Browsergame</h1>
<div>Dieses Spiel wurde von Luca Strebel programmiert :)</div>
<?php 
	if(isset($_SESSION["username"])){
		?>
		Hallo <?= $_SESSION["username"] ?>,</br>
		Du bist eingeloggt, möchtest du die Welt <a href="/enter">hier</a> betreten?
		<?php 
	} else{
		?>
		Du musst dich <a href="/login">einloggen</a>
		<?php
	}
?>