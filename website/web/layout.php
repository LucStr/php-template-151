<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<!-- Jquery -->
<script src="http://code.jquery.com/jquery-latest.js"></script>
<!-- Jquery Ende-->
<!-- CSS -->
<link href="css/layout.css" rel="stylesheet" type="text/css" />
<!-- CSS Ende-->
<title><?php echo $GLOBALS['title']; ?></title>
</head>
<body>
    <div id="middle">
		    <div id="navigation">
					<a>NAVIGATION</a>
				</div>

        <div class="maincontent">
        	<?php include $view ?>
        </div>

        <?php if(isset($_SESSION['username'])) { ?>
          <div id="currentlyLoggedIn">
            Guten Tag, Sie sind als <b><?=$_SESSION['username']?></b> eingeloggt.
          </div>
          <a id="logoutButton" href="/Login/Logout">AUSLOGGEN</a>
        <?php } ?>
    </div>
</body>
</html>