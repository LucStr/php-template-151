<h1>Hauptgebäude</h1>
<p>Im Hauptgebäude können alle Gebäude ausgebaut werden.</p>
<?php 
	if(count($village["queue"]) > 0){
?>
<h2>Warteschlange</h2>
<table id="queue">
	<tr>
		<th>Gebäude</th>
		<th>Ende</th>
		<th>Zeit</th>	
	</tr>
	<?php 
		foreach ($village["queue"] as $build){
			?>
			<tr>
				<td><?= htmlentities($village["buildings"][$build["building"]]["name"]) . " Stufe " . $build["level"]?></td>
				<td><?= $build["endTime"] ?></td>
				<td class="timerCountDown"><?= strtotime($build["endTime"]) - time() ?></td>
			</tr>
			<?php 
		}
	}
?>
</table>
<h2>Gebäude</h2>
<table>
	<tr>
		<th>Gebäude</th>
		<th>Holz</th>
		<th>Stein</th>
		<th>Gold</th>
		<th>Zeit</th>	
		<th>Bauen</th>	
	</tr>
	<?php 
	foreach ($village["buildings"] as $key => $building){
	?>
	<tr>
		<td><b><?= $building["name"]?></b></td>
		<td><?= $building["woodcost"] ?></td>
		<td><?= $building["stonecost"]?></td>
		<td><?= $building["goldcost"]?></td>		
		<td><?= $building["time"]?></td>
		<td><a href="/Main/Build?villageId=<?= $village["villageId"]?>&building=<?= $key?>">Stufe <?= $building["newlvl"]?></a></td>
	</tr>
	<?php 
	}
	?>	
</table>
<a href="/Village/Overview?villageId=<?= $village["villageId"]?>">Zurück zur Übersicht</a>
<script>
	setInterval(function(){		
		$(".timerCountDown").each(function(i, u){
				var newval = $(u).html() - 1;
				$(u).html($(u).html() - 1);
				if(newval == 0){
					location.reload();
				}
			});
	}, 1000)
</script>





