Deine Dörfer:
<?php 
if(count($villages) > 0){
	foreach ($villages as $village){
		?>
		<div>
			<a href="/Village/Overview?villageId=<?= $village["villageId"]?>"><?= $village["name"]?></a>	
		</div>
		<?php
	}
} else{
	?>
	<div>
		<a href="/Village/Create">Dorf erzeugen</a>	
	</div>
	<?php
}
?>