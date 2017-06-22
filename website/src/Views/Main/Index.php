<h1>Mainbuilding</h1>
<p>In the Mainbuilding you can upgrade your buildings</p>
<?php 
	if(count($village["queue"]) > 0){
?>
<h2>Queue</h2>
<table id="queue">
	<tr>
		<th>Building</th>
		<th>End</th>
		<th>Time</th>	
	</tr>
	<?php 
		foreach ($village["queue"] as $build){
			?>
			<tr>
				<td><?= htmlentities($village["buildings"][$build["building"]]["name"]) . " Level " . $build["level"]?></td>
				<td><?= $build["endTime"] ?></td>
				<td class="timerCountDown"><?= strtotime($build["endTime"]) - time() ?></td>
			</tr>
			<?php 
		}
	}
?>
</table>
<h2>Buildings</h2>
<table>
	<tr>
		<th>Building</th>
		<th>Wood</th>
		<th>Stone</th>
		<th>Gold</th>
		<th>Time</th>	
		<th>Build</th>	
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
		<td><a href="/Main/Build?villageId=<?= $village["villageId"]?>&building=<?= $key?>">Level <?= $building["newlvl"]?></a></td>
	</tr>
	<?php 
	}
	?>	
</table>
<a href="/Village/Overview?villageId=<?= $village["villageId"]?>">Back to the overview</a>
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





