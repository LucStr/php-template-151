Deine Dörfer:
<?php 
foreach ($villages as $village){
	?>
	<div>
		<a href="/Village/Overview?villageId=<?= $village["villageId"]?>"><?= $village["name"]?></a>	
	</div>
	<?php
}
?>