<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<!-- Jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Jquery Ende-->
<!-- CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
<?php 
include "layout.css";
?>
</style>
<!-- CSS Ende-->
<title>Browsergame</title>
</head>
<body>
    <div id="middle">
	    <div id="navigation">
			<a href="/">Home</a>
			<a href="/Village">Overview</a>
			<a href="/Ranking">Ranking</a>
		</div>
		<?php 
			LucStr\MessageHandler::renderMessages();
		?>
        <div id="maincontent">
        	<?php include $view ?>
        </div>
        <?php if(isset($_SESSION['username'])) { ?>
          <div id="currentlyLoggedIn">
            Hello, you are logged in as <b><?=htmlentities($_SESSION['username'])?></b> <br>
            <a id="logoutButton" href="/Login/Logout">LOGOUT</a>
          </div>
        <?php } ?>
    </div>
</body>
</html>