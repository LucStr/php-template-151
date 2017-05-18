<h1>Hauptgebäude</h1>
<p>Im Hauptgebäude können alle Gebäude ausgebaut werden.</p>
<table>
	<tr>
		<th>Gebäude</th>
		<th>Holz</th>
		<th>Stein</th>
		<th>Gold</th>
		<th>Zeit</th>		
	</tr>
	<?php 
	//print_r($village);
	//print("<br>");
	//print_r($buildings);
	foreach ($buildings as $key => $building){
	?>
	<tr>
		<td><b><?= $building["name"]?></b></td>
		<td><?= intval($building["woodcost"] * pow($building["costMultiplier"], $village[$key . "lvl"])) ?></td>
		<td><?= intval($building["stonecost"] * pow($building["costMultiplier"], $village[$key . "lvl"]))?></td>
		<td><?= intval($building["goldcost"] * pow($building["costMultiplier"], $village[$key . "lvl"]))?></td>		
		<td><?= intval($building["time"] * pow($building["timeMultiplier"], $village["mainlvl"]))?></td>
		<td><a href="/Village/Build?villageId=<?= $village["villageId"]?>&building=<?= $key?>">Stufe <?= ($village[$key . "lvl"] + 1)?></a></td>
	</tr>
	<?php 
	}
	?>
	<a href="/Village/Overview?villageId=<?= $village["villageId"]?>">Zurück zur Übersicht</a>
</table>