Your Villages:
<?php 
if(count($villages) > 0){
	foreach ($villages as $village){
		?>
		<div>
			<a href="/Village/Overview?villageId=<?= $village["villageId"]?>"><?= htmlentities($village["name"])?></a>	
		</div>
		<?php
	}
} else{
	?>
	<div>
		<a href="/Village/Create">Create Village</a>	
	</div>
	<?php
}
?>